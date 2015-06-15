<?php
/**
* 购物结算
*/
class mc_product_checkout extends ms_base
{
	public $sid = 0;
	public $pids = array();
	public $cids = array();
	
	public $goods = array();
	public $products = array(); //购买的产品
	public $express = array(); //可用物流列表
	public $integral = array(); //积分兑换信息
	public $subject = array(); //关联商户

	public $owner = array(); //商户管理员（店长）

	public $virtual = false; //是否虚拟产品

	public $product_model; //商品数据表模型
	public $cart_model; //购物车数据表模型

    public function __construct()
    {
        parent::__construct();
        $this->product_model = $this->loader->model(':product');
        $this->cart_model = $this->loader->model('product:cart');
    }

	/**
	 * 购物结算验证和统计
	 * @param  array $products 购物车准备下单的产品（必须包含购物车表字段内容）
	 * @return boolean         成功返回true反之false
	 */
	public function process($products)
	{
		if(!_G('user')->isLogin) {
			return $this->add_error('member_op_not_login');
		}

		if(!$products || !is_array($products)) {
			return $this->add_error('product_empty');
		}

		$this->sid = $products[0]['sid'];
		foreach ($products as $product) {
			$this->pids[] = $product['pid'];
			$this->cids[] = $product['cid'];
			if($product['p_style'] == '1') $entity = true;
			if($product['p_style'] == '2') $virtual = true;
			if($product['sid'] != $this->sid) {
				return $this->add_error('不能再一个订单内购买不同商户的商品，请分开下单。');
			}
			$goods = $this->assemble_goods($product, $product['quantity'], $product['buyattr']);
			if(!$goods) return false;
			$this->goods[] = $goods;
		}

		if(!$this->goods) return $this->add_error('购物车内没有商品。');
		if($entity===$virtual) return $this->add_error('虚拟商品和实物商品不能同时下单，请分开下单。');
		
		$this->virtual = $virtual;
		$this->products = $products;

		//总价计算
		$this->goods_amount = $this->goods_amount();
		if($this->goods_amount === false) return $this->add_error('商品无法计算总价。');

		//可抵积分
		$this->integral = $this->goods_integral();

		//物流方式
		$this->express = $this->goods_express($products);

		//关联商户
		$this->subject = $this->loader->model('item:subject')->read($this->sid);
		if(!$this->subject) return $this->add_error('product_subject_empty');

		//卖家信息
		$owners = $this->loader->model('item:subject')->owners($this->subject['sid']);
		//判断是否是自己在购买
		if($owners) {
			foreach($owners as $owner) {
				if(_G('user')->uid == $owner['uid']) return $this->add_error('抱歉，不能购买自己的产品！');
			}
			reset($owners);
			$this->owner = $owners[0];			
		}

		return true;
	}

	/**
	 * 计算商品 $this->goods 的总价
	 * @return float        总价
	 */
	public function goods_amount()
	{
		$amount = false;
		foreach ($this->goods as $gd) {
			$amount +=  $gd['price'] * $gd['quantity'];
		}
		return $amount;
	}

	/**
	 * 验证并把产品数组组合成一个商品下单时需要的数组
	 * @param  array $product 产品表数据
	 * @param  int $quantity 准备购买的数量
	 * @return array          商品信息数组
	 */
	protected function assemble_goods($product, $quantity, $buyattr)
	{
		if(!$product) return $this->add_error('product_empty');

		$buyattr_obj = $this->loader->model('product:buyattr');
		$r = $buyattr_obj->find_all('*', array('pid'=>$product['pid']), 'listorder');
		if(!$r) {
			$buyattr = '';
		} else {
			$buyattr = $buyattr?msm_product_buyattr::buyattr_strtoarray($buyattr):array();
			$buyattr_static = array();
			$buyattr_invalid = $buyattr?false:true;
			if($buyattr) {
				while ($v = $r->fetch_array()) {
					if(isset($buyattr[$v['id']])) {
						$index = $buyattr[$v['id']];
						$values = explode(',', $v['value']);
						if(isset($values[$index])) {
							$buyattr_static[] = array('name'=>$v['name'],'value' => $values[$index]);
						}
					} else {
						$buyattr_invalid = true;
					}
				}		
			}
			if($buyattr_invalid) return $this->add_error("您未选择商品\"{$product['subject']}\"的购买属性。", 610001);
		}

		$goods = array();
		$goods['pid'] 			= $product['pid'];
		$goods['sid'] 			= $product['sid'];
		$goods['goods_image'] 	= $product['thumb'];
		$goods['pname'] 		= $product['subject'];
		$goods['price'] 		= mc_product_cart::calc_myprice($product); //计算我的价格
		$goods['integral']		= $product['integral']; //可抵换积分
		$goods['quantity']		= $quantity; //购买数量
		$goods['buyattr']		= $buyattr_static ? $buyattr_static : array(); //购买商品规格属性

		if(!$product['sid']) {
			return $this->add_error('商品【'.$goods['pname'].'】没有关联商家，无法购买。');
		}

		//状态异常
		if(!$product['status']) return $this->add_error('product_empty');

		//是否上架商品
		if(!$product['is_on_sale']) return $this->add_error('抱歉，产品【'.$goods['pname'].'】已经下架，进入此商家店铺挑选其他产品。');

		//库存判断
		if($product['stock'] < $goods['quantity']) {
			return $this->add_error("当前商品【{$goods['subject']}】库存少于【{$goods['quantity']}】件。");
		}
		//价格判断
		if(!$goods['price'] || !is_numeric($goods['price'])) {
		    return $this->add_error("商品【{$goods['subject']}】，暂无价格！");
		}

		return $goods;
	}

	/**
	 * 计算商品 $this->goods 的可用积分
	 * @return float        总价
	 */
	protected function goods_integral()
	{
		$integral = array();
		$cash_rate = (int)S('product:cash_rate');
		$point = S('product:pointgroup');

		if($cash_rate > 0 && $point) {
			$integral['point'] = $point;
			$integral['point_name'] = display('member:point',"point/$point");
			$integral['rate'] = $cash_rate; //汇率 1:rate
			//计算所有产品的积分
			$total_integral = 0;
			foreach ($this->goods as $gd) {
				$total_integral += $gd['integral'] * $gd['quantity'];
			}
			// 可以积分
			$integral['integral'] = min(_G('user')->$point, $total_integral);
			// 折现的现金
			$integral['price'] = $integral['integral'] / $cash_rate;
			//可抵换现金
			$integral['ex_price'] = min($this->goods_amount, $integral['price']);
			//可用积分
			$integral['ex_integral'] = round($integral['ex_price'] * $cash_rate);
		}

		return $integral;
	}

	/**
	 * 获取商品的物流方式
	 * @param  array $products  多商品列表
	 * @param  boolean $is_single 上一个参数是否是独立商品
	 * @return array            获取的物流方式列表
	 */
	protected function goods_express($products, $is_single = false)
	{
		$express = array();

		//免运费(只要有1个产品需要运费，购物篮多商品就不能免运费)
		$is_free = true;
		$free = array(
			'shipid' => 'free',
			'shipname' => '卖家承担运费',
			'price' => 0,
		);

		//货到付款(只要有1个产品不支持货到付款，购物篮多商品就不支持货到付款)
		$is_cod = true;
		$cod = array(
			'shipid' => 'cod',
			'shipname' => '货到付款',
			'price' => 0,
			'des' => "同城商家上门送货，不支持外地送货",
		);

		//单一商品时
		if($is_single) {
			if($products['is_cod']&&$products['p_style']=='1') {
				$cod['price'] += $products['cod_price'];
			} else {
				$is_cod = false;
			}
			$is_free = !$product['freight'];
		} else {
			foreach ($products as $product) {
				//货到付款
				if($product['is_cod'] && $is_cod) {
					$cod['price'] += $product['cod_price']; //货到付款费用运费
				} elseif (!$product['is_cod']) {
					$is_cod = false;
				}
				//免运费
				if($product['freight']) {
					$is_free = false; //有商品不支持免运费
				}
			}
		}

		//所有商品都支持货到付款
		if($is_cod) $express[] = $cod;
		//所有商品都支持免运费
		if($is_free) $express[] = $free;

		//商品只有1种时，可以使用内置的物流方式
		if(count($products) === 1 && $products[0]['freight'] == 2) {
			$array = array('freight_price_snail', 'freight_price_exp', 'freight_price_ems');
			foreach ($array as $key) {
				$express[] = array(
					'shipid' => $key,
					'shipname' => lang('product_'.$key),
					'price' => $product[$key],
				);
			}
		} elseif(count($products) === 1 && $products[0]['freight'] == 0) {
			//TODO
		} else {
			//获取商户定义的物流管理列表内的物流方式
			$ship_obj = $this->loader->model('product:shipping');
			$ship_list = $ship_obj->find_by_subject($products[0]['sid'], true);
			if($ship_list) foreach ($ship_list as $ship) {
				$express[] = array  (
					'shipid' => $ship['shipid'],
					'shipname' => $ship['shipname'],
					'price' => $ship['price'],
					'des' => $ship['shipdesc'],
				);
			}
		}

		return $express;
	}

}