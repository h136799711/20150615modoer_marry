<?php
/**
* 订单生成业务逻辑
*/
class msm_product_order_create extends msm_product_order
{

	public $goods_amount = 0; //商品总价

	public $goods = array(); //商品列表
	public $express = array(); //可用物流列表
	public $integral = array(); //积分兑换信息
	public $subject = array(); //关联商户

	public $owner = array(); //商户管理员（店长）

	public $product_model; //商品数据模型
	public $cart_model; //购物车数据模型

    function __construct()
    {
        parent::__construct();
        $this->product_model = $this->loader->model(':product');
        $this->cart_model = $this->loader->model('product:cart');
    }

	//购物结算生成订单
	public function create()
	{
		$sid = _input('sid', 0, MF_INT_KEY);
		$cids = explode(',', _input('cids', 0, MF_TEXT));

		$cart_slg = mc_product_cart::instance();
		$checkout_obj = $cart_slg->checkout($cids);

		if(!$checkout_obj) {
			return $this->add_error($cart_slg);
		}

	    //地址监测
	    $address_id = _input('addressid', 0, MF_INT_KEY);
	    $address = $this->loader->model('member:address')->read($address_id);
	    if(!$address || $address['uid'] != _G('user')->uid) return $this->add_error('收货人地址不存在！');

	    //提交订单
	    $post = array();
	    $post['ordersn'] = $cart_slg->cartid; //购物车号
	    $post['orderstyle'] = $checkout_obj->virtual ? 2 : 1; //1：实物，2：虚拟商品
	    $post['sid'] = $checkout_obj->subject['sid']; //订单所属商家
	    $post['sellerid'] = (int)$checkout_obj->owner['uid']; //卖家
	    $post['sellername'] = _T($checkout_obj->owner['username']);
	    $post['buyerid'] = _G('user')->uid; //买家
	    $post['buyername'] = _G('user')->username;
	    $post['buyeremail'] = _G('user')->email;
	    $post['remark'] = _post('remark', '', MF_TEXT); //留言
		$post['truebuyer_id'] = 0;
		$post['truebuyer_name'] = "";
		
//	    if(_G('user')->groupid == 16){
//		    $post['truebuyer_id'] = _post('truebuyer_id', '', MF_TEXT);
//			
//		    $truebuyer = $this->loader->model('member:member')->read($post['truebuyer_id'],MEMBER_READ_USERNAME);
//			if(is_array($truebuyer)){
//				$post['truebuyer_name'] = $truebuyer['username'];
//			}else{			
//		   	 	return $this->add_error('新人ID错误！');
//			}
//		}

		if(_G('user')->groupid == 16){
		    $post['truebuyer_name'] = _post('truebuyer_name', '', MF_TEXT);
			
		    $truebuyer = $this->loader->model('member:member')->read($post['truebuyer_name'],MEMBER_READ_USERNAME);
			if(is_array($truebuyer)){
				$post['truebuyer_id'] = $truebuyer['uid'];
			}else{			
		   	 	return $this->add_error('新人登录用户名错误！');
			}
		}
		
	    //物流检测
	    if(!$checkout_obj->express) {
	    	return $this->add_error('卖家未设置物流信息，无法下单。');
	    }
		//物流监测
	    $shipid = _post('shipid', 0, MF_TEXT);
	    if(!$shipid) {
	    	return $this->add_error('未选择物流方式，请返回选择！');
	    }
	    $ship = false;
	    $ship_price = 0;
	    foreach ($checkout_obj->express as $express) {
	    	if($express['shipid'] == $shipid) {
	    		$ship = $express; //使用的物流信息
	    		$ship_price = $express['price'];
	    		$post['is_cod'] = $shipid == 'cod' ? 1 : 0; //货到付款
	    		break;
	    	}
	    }
	    if(!$ship) {
	    	return $this->add_error('对不起，您订单指定的物流类型无效或不支持。');
	    }

	    //积分抵换处理
	    if($checkout_obj->integral) {
	    	$post['integral'] = _post('integral', 0, MF_INT_KEY); //花费的积分
	    	if($post['integral'] > $checkout_obj->integral['ex_integral']) {
	    		return $this->add_error("抵换的 {$checkout_obj->integral['point_name']} 不能超过 {$checkout_obj->integral['ex_integral']} 个");
	    	}
	    	$pointtype = $checkout_obj->integral['point'];
	    	if(_G('user')->$pointtype < $post['integral']) {
	    		return $this->add_error("您的 {$checkout_obj->integral['point_name']} 不足，请返回重新填写。");
	    	}
	    	$post['integral_amount'] = round($post['integral'] / $checkout_obj->integral['rate'], 2); //抵换的现金
	    	$post['integral_pointtype'] = $pointtype; //积分类型
	    }

		//判断订单总价(+运费-抵换积分)是否一致
		$post['goods_amount'] = $checkout_obj->goods_amount();
        $order_amount = $post['goods_amount'] + $ship_price - $post['integral_amount'];
        $post['order_amount'] = $order_amount;
        $post_order_amount = _post('order_amount', 0, MF_FLOAT);
        if(!self::floatcmp($order_amount, $post_order_amount)) {
            return $this->add_error('提交的订单总价('.$post_order_amount.')和下单总价('.$order_amount.')不一致，请返回刷新页面。');
        }

		//运费验证
        $shipping_amount = ($order_amount + $post['integral_amount']) - $post['goods_amount']; //运费 = 订单总价 - 商品总价
        if((int)$shipping_amount == 0) {
            //没有运费，就判断下是否符合免运费
            if($ship_price != 0) return $this->add_error('提交的订单运费和下单运费不一致，请返回刷新页面。');
        } else {
            $sp = round($ship_price, 2);
            $sa = round($shipping_amount, 2);
            //运费判断
            if(!self::floatcmp($sp, $sa)) {
            	return $this->add_error('物流价格不正确，请返回刷新页面，重新选择。');
            }
        }

        //指定发货时间
        $delivery_time = _post('delivery_time', '', MF_TEXT);
        if($delivery_time) {
            $now = strtotime(date('Y-m-d', _G('timestamp')));
            $post['delivery_time'] = strtotime($delivery_time);
            if(!$post['delivery_time'] && $post['delivery_time'] < $now) {
                $this->add_error('对不起，您设置的指定发货时间有误。');
            }
        } else {
            $post['delivery_time'] = 0;
        }

        //订单生成时间
        $post['addtime'] = _G('timestamp');

        //提交订单，返回订单号
        $orderid = parent::save($post);

        //消费积分抵现金
        if($post['integral']) {
            $this->member_coin($post['buyerid'], -$post['integral'], $checkout_obj->integral['point'], $post['ordersn']);
        }

        if(!$orderid) {
        	return $this->add_error('订单创建失败。');
        }

        //物流信息记录
        $EX = $this->loader->model('product:orderextm');
        $EX->save($orderid, $address, $ship);

        //订单商品记录
	    $G = $this->loader->model('product:ordergoods');
	    $G->savep($orderid, $checkout_obj->goods);

	    //删除购物车内的商品
	    $cart_slg->delete($cids);

	    return $orderid;
	}

}