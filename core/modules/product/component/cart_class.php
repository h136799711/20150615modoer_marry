<?php
/**
* 购物车
*/
!defined('IN_MUDDER') && exit('Access Denied');
class mc_product_cart extends ms_base
{
	//购物车cookie名字
    protected $cookie_name ='product_cart';
    protected $cookie_expire_name ='product_cart_expire';
    //默认购物车cookie信息保存12小时
    protected $cookie_expire_time = 43200;

    protected $goods = array();

    protected $cart_model;
    protected $product_model;
    protected $order_model;

    public $cartid = '';
    public $expire_time = 0;

	public static function instance()
	{
		static $instance = null;
		if(!$instance) $instance = new self();
		return $instance;
	}

	/**
	 * 计算我的购买价格
	 * @param  array $product 商品数据包含价格方案信息
	 * @return mixed          计算成功返回购买金额反之返回null或者false
	 */
	public static function calc_myprice($product)
	{
		return _G('loader')->model(':product')->myprice($product);
	}
	
	public function __construct()
	{
		parent::__construct();
		$this->init_cart_id();
        $this->product_model = $this->loader->model(':product');
        $this->cart_model = $this->loader->model('product:cart');
        $this->order_model = $this->loader->model('product:order');
	}

	/**
	 * 初始化一个购物车
	 */
	function init_cart_id()
	{
		$cartid_code = _cookie($this->cookie_name);
		if($cartid_code) {
			$cartid = $this->decrypt_cartid($cartid_code);
			$expire = _cookie($this->cookie_expire_name);
		}
		if(!$cartid) {
			$cartid = $this->create_cart_id(); //生成一个cartid
			$expire = _G('timestamp') + $this->cookie_expire_time;
            set_cookie($this->cookie_name, $this->encrypt_cartid($cartid), $expire);
            set_cookie($this->cookie_expire_name, $expire, $expire);
		}
		$this->cartid = $cartid;
		$this->expire_time = $expire;
	}

	/**
	 * 统计购物车商品数量
	 * @return integer
	 */
	public function goods_count()
	{
		return $this->cart_model->count_products($this->cartid);
	}

	/**
	 * 向购物车内增加一个产品
	 * @param integer  $id      商品ID	
	 * @param integer $quantity 购买数量
	 */
	public function add($pid, $quantity = 1, $buyattr = '')
	{
		$product = $this->product_model->read($pid);
		if(!$product) return $this->add_error('product_empty');
		
		//下单属性
		$format_buyattr = $this->format_buyattr($pid, $buyattr);
		if(!$format_buyattr && $this->has_error()) {
			return false;
		}

		//查询购物车内是否有同一个商品（可能存在不同购买属性）
		$r = $this->cart_model->find_all('*', array('cartid'=>$this->cartid, 'pid'=>$pid));
		$cart_goods = null;
		if($r) while ($v = $r->fetch_array()) {
			//购买属性对比
			if($v['buyattr'] == $format_buyattr) {
				$cart_goods = $v;
			}
		}

		//该商品购物车内已存在
		if($cart_goods) {
			$this->cart_model->_update_value($cart_goods['cid'],'quantity', $quantity);
			return $cart_goods['cid'];
		}

		//购物车商品
		if(!$goods = $this->assemble_goods($product, $quantity)) {
			return false;
		}
		//加入购物车数据表
        $post['cartid'] = $this->cartid;
        $post['overdue'] = $this->expire_time;
        $post['pid'] = $goods['pid'];
        $post['sid'] = $goods['sid'];
        $post['pname'] = $goods['pname'];
        $post['quantity'] = $goods['quantity'];
        $post['buyattr'] = $format_buyattr;
        $post['p_image'] = $goods['p_image'];
        $post['p_style'] = $goods['p_style'];
        $post['price'] = self::calc_myprice($product);
        if(_G('user')->uid) {
        	$post['uid'] = _G('user')->uid;
        }

        $cid = $this->cart_model->save($post);
        if(!$cid) return $this->add_error('添加到购物车失败。');

		return $cid;
	}

	public function update_buyattr($cid, $buyattr_exp)
	{
		$goods = $this->cart_model->read($cid);
		if(!$goods || $this->cartid != $goods['cartid']) {
			return $this->add_error('商品尚未加入购物车。');
		}

		$buyattr = $this->format_buyattr($goods['pid'], $buyattr_exp);
		if(!$buyattr) return $this->add_error('对不起，您提交的商品购买属性无效或未选择。');
		$this->cart_model->_update_value($cid, 'buyattr', $buyattr);
		return true;
	}

	/**
	 * 增加指定商品的购买数量
	 * 如果商品不存在购物车，则增加岛购物车
	 * @param integer  $cid 购物车表内商品记录ID
	 * @param integer $num 新增数量
	 */
	public function add_quantity($cid, $num = 1, $buyattr = '')
	{
		$goods = $this->cart_model->read($cid);
		if(!$goods || $this->cartid != $goods['cartid']) {
			return $this->add_error('商品尚未加入购物车。');
		}
		$this->cart_model->num_add($cid, $num);
		return true;
	}

	/**
	 * 减少指定商品的购买数量
	 * @param integer  $cid 购物车表内商品记录ID
	 * @param integer $num 减少数量
	 */
	public function dec_quantity($cid, $num = 1)
	{
		$goods = $this->cart_model->read($cid);
		if(!$goods || $this->cartid != $goods['cartid']) {
			return $this->add_error('商品尚未加入购物车。');
		}
		$num = min($goods['quantity'], $num); //确保不出现负数
		$this->cart_model->num_dec($cid, $num);
		return true;
	}

	/**
	 * 修改指定商品的购买数量
	 * 如果num参数小于0，则该商品从购物车中删除
	 * @param integer  $cid 购物车表内商品记录ID
	 * @param integer $num 修改数量
	 */
	public function change_quantity($cid, $num)
	{
		$goods = $this->cart_model->read($cid);
		if(!$goods || $this->cartid != $goods['cartid']) {
			return $this->add_error('商品尚未加入购物车。');
		}
		if($num > 0) {
			$this->cart_model->num_change($cid, $num);
		} else {
			$this->delete($goods['cid']);
		}
		return true;
	}

	/**
	 * 修改指定商品的购买数量和购买属性
	 * 如果num参数小于0，则该商品从购物车中删除
	 * @param integer  $cid 购物车表内商品记录ID
	 * @param integer $quantity 修改数量
	 * @param string $buyattr_exp 购买属性表达式
	 */
	public function update_change($cid, $quantity, $buyattr_exp)
	{
		$goods = $this->cart_model->read($cid);
		if(!$goods || $this->cartid != $goods['cartid']) {
			return $this->add_error('商品尚未加入购物车。');
		}
		if($quantity > 0) {
			$buyattr = $this->format_buyattr($goods['pid'], $buyattr_exp);
			if($this->has_error()) return;
			$this->cart_model->_update_value($cid, 'quantity', $quantity);
			$this->cart_model->_update_value($cid, 'buyattr', $buyattr);
		} else {
			return $this->add_error('未选择商品购买数量。');
		}
		return true;
	}

	/**
	 * 删除购物车内的一个商品
	 * @param  integer $cid 购物车商品记录ID，多个产品时位数组
	 * @return boolean
	 */
	public function delete($cid)
	{
		$this->cart_model->delete($this->cartid, $cid);
		return true;
	}

	/**
	 * 删除购物车内的一个商品
	 * @param  integer $pid 商品ID
	 * @return boolean
	 */
	public function delete_by_pid($pid)
	{
		$this->cart_model->delete_pid($this->cartid, $pid);
		return true;
	}

	/**
	 * 删除购物车内某个商户的商品
	 * @param  integer $id 商户ID
	 * @return boolean
	 */
	public function delete_by_sid($sid)
	{
		$this->cart_model->delete_sid($sid, $this->cartid);
		return true;
	}

	/**
	 * 删除购物车内的虚拟商品
	 * @return [type] [description]
	 */
	public function delete_virtual_goods()
	{
		$this->cart_model->delete_style($this->cartid, 2);
		return true;
	}

	/**
	 * 清空购物车
	 * @return boolean
	 */
	public function clear()
	{
		$this->cart_model->cart_clear($this->cartid);
		return true;
	}


	/**
	 * 获取购物车内的商品信息列表
	 * @return array
	 */
	public function get_goods_list()
	{
	    $where = array();
	    $where['c.cartid'] = $this->cartid;
	    //if($user->uid) $where['c.uid'] = $user->uid;
	    $offset = 100;
	    $start = get_start(1, $offset);
	    return $this->cart_model->find('c.*', $where, array('c.cid'=>'DESC'), $start, $offset, TRUE, 'p.*', 's.sid,s.name,s.subname');
	}

	/**
	 * 结算检验
	 * @param  array $cids 准备结算的购物车商品id,多个用数组标识
	 * @return [type]       [description]
	 */
	public function checkout($cids)
	{
		$products = $this->cart_model->get_products_by_cids($this->cartid, $cids);
		if(!$products) {
			return $this->add_error('product_empty');
		}

		$checkout = new mc_product_checkout();
		if(!$checkout->process($products)) {
			return $this->add_error($checkout);
		}

		return $checkout;
	}

	/**
	 * 验证并把产品数组组合成一个商品下单时需要的数组
	 * @param  array $product 产品表数据
	 * @param  int $quantity 准备购买的数量
	 * @return array          商品信息数组
	 */
	protected function assemble_goods($product, $quantity)
	{
		if(!$product) return $this->add_error('product_empty');

		$goods = array();
		$goods['pid'] 		= $product['pid'];
		$goods['sid'] 		= $product['sid'];
		$goods['p_image'] 	= $product['thumb'];
		$goods['pname'] 	= $product['subject'];
		$goods['price'] 	= self::calc_myprice($product); //计算我的价格
		$goods['integral']	= $product['integral']; //可抵换积分
		$goods['p_style']	= $product['p_style']; //可抵换积分
		$goods['quantity']	= $quantity; //购买数量
		$goods['buyattr']	= $product['buyattr']; //购买数量

		//状态异常
		if(!$product['status']) return $this->add_error('product_empty');

		//是否上架商品
		if(!$product['is_on_sale']) return $this->add_error('抱歉，产品【'.$product['subject'].'】已经下架，进入此商家店铺挑选其他产品。');

		//库存判断
		if($product['stock'] < $goods['quantity']) {
			return $this->add_error("当前商品【{$goods['pname']}】库存少于【{$goods['quantity']}】件。");
		}
		//价格判断
		if(!$goods['price'] || !is_numeric($goods['price'])) {
		    return $this->add_error("商品【{$goods['pname']}】，暂无价格！");
		}

		return $goods;
	}

	/**
	 * 格式化购买属性字符串
	 * @param  integer $pid         商品id
	 * @param  string $buyattr_exp 表单提交来的未经格式化的源数据
	 * @return string              
	 */
	protected function format_buyattr($pid, $buyattr_exp, $check_integrity=true)
	{
		$buyattr = msm_product_buyattr::buyattr_strtoarray($buyattr_exp);

		if(!$buyattr) return '';

		$buyattr_obj = $this->loader->model('product:buyattr');
		$r = $buyattr_obj->find_all('*', array('pid'=>$pid), 'listorder');
		if(!$r) return '';

		$result = array();
		while ($v = $r->fetch_array()) {
			if(isset($buyattr[$v['id']])) {
				$index = $buyattr[$v['id']];
				$values = explode(',', $v['value']);
				if(isset($values[$index])) {
					$result[$v['id']] = $index;
				}
			}
			if(!isset($result[$v['id']]) && $check_integrity) {
				return $this->add_error("您未选择商品的购买属性（{$v['name']}）。", 610001);
			}
		}

		if($result) asort($result);
		return msm_product_buyattr::buyattr_arraytostr($result);
	}

	/**
	 * 生成一个购物车唯一ID
	 * @return string
	 */
	protected function create_cart_id() {
        return time().'CN'.mt_rand(1000,9999);
    }

	/**
	 * 处理加密数据
	 * @param  string $txt 加密字符串
	 * @return string
	 */
	private function setkey($txt) {
		$encrypt_key = md5(strtolower($this->global['cfg']['authkey']));
		$ctr = 0;
		$tmp = '';
		for($i = 0; $i < strlen($txt); $i++) {
			$ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
			$tmp .= $txt[$i] ^ $encrypt_key[$ctr++];
		}
		return $tmp;
	}

    /**
     * 加密cookie字符串接口
     * @param  string $txt 加密字符串
     * @return string
     */
	private function encrypt_cartid($txt) {
		srand((double)microtime() * 1000000);
		$encrypt_key = md5(rand(0, 32000));
		$ctr = 0;
		$tmp = '';
		for($i = 0; $i < strlen($txt); $i++) {
			$ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
			$tmp .= $encrypt_key[$ctr].($txt[$i] ^ $encrypt_key[$ctr++]);
		}
		return base64_encode($this->setkey($tmp));
	}

	/**
	 * 解密cookie字符串接口
	 * @param  string $txt 解密字符串
	 * @return string
	 */
	private function decrypt_cartid($txt) {
		$txt = $this->setkey(base64_decode($txt));
		$tmp = '';
		for ($i = 0; $i < strlen($txt); $i++) {
			$tmp .= $txt[$i] ^ $txt[++$i];
		}
		return $tmp;
	}

}