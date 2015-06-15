<?php
/**
* 获取订单内容
*/
class mc_product_order extends ms_base
{

	public $goods_list = array();
	public $products = array(); //购买的产品
	public $express = array(); //可用物流列表
	public $integral = array(); //积分兑换信息
	public $subject = array(); //关联商户

	/**
	 * 填写一个订单
	 * @param  ms_product_cart $cart 购物车
	 * @param  array $products 准备购买的产品数据
	 * @return [type]       [description]
	 */
	public function write($products)
	{
		if(!_G('user')->isLogin) {
			return $this->add_error('member_op_not_login');
		}
		if(!$products || !is_array($products)) {
			return $this->add_error('product_empty');
		}

		foreach ($products as $product) {
			if($product['p_style'] == '1') $entity = true;
			if($product['p_style'] == '2') $virtual = true;
			if(!$product['sid']) {
				return $this->add_error('商品【'.$product['subject'].'】没有关联商家，无法购买。');
			}
			if($product['sid'] != $sid) {
				return $this->add_error('不能再一个订单内购买不同商户的商品，请分开下单。');
			}
			$goods = $this->cart->assemble_goods($product);
			if(!$goods) return false;
			$this->goods_list[] = $goods;
		}

		//总价计算
		$this->goods_amount = $this->goods_amount();
		if($this->goods_amount===false) return $this->add_error('商品无法计算总价。');

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
	 * 验证并把产品数组组合成一个商品下单时需要的数组
	 * @param  array $product 产品表数据
	 * @param  int $quantity 准备购买的数量
	 * @return array          商品信息数组
	 */
	public function assemble_goods($product, $quantity)
	{
		if(!$product) return $this->add_error('product_empty');

		$goods = array();
		$goods['pid'] 		= $product['pid'];
		$goods['sid'] 		= $product['sid'];
		$goods['p_image'] 	= $product['thumb'];
		$goods['pname'] 	= $product['subject'];
		$goods['price'] 	= mc_product_cart::myprice($product); //计算我的价格
		$goods['integral']	= $product['integral']; //可抵换积分
		$goods['quantity']	= $quantity; //购买数量

		//状态异常
		if(!$product['status']) return $this->add_error('product_empty');

		//是否上架商品
		if(!$product['is_on_sale']) return $this->add_error('抱歉，产品【'.$product['subject'].'】已经下架，进入此商家店铺挑选其他产品。');

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
	
}