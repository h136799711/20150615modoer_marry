<?php
/**
 * 购物车增减页面
 * @author moufer<moufer@163.com>
 * @copyright www.modoer.com
 */
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'product');

//我的购物车类
$cart_slg = mc_product_cart::instance();

$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("product/index"));

$op = _input('op', '', MF_TEXT);

switch($op) {
    case 'product':
        $pid = _get('pid', 0, MF_INT_KEY);
        $service = new mc_product_service();
        $product = _G('loader')->model(':product')->read($pid);
        if(!$product || !$product['status']) {
            $service->add_error('product_empty');
        } else {
            $service->product = $product;
            $service->buyattr = _G('loader')->model('product:buyattr')->get_product_buyattr($pid);
        }
        echo $service->fetch_all_attr('json');
        output();
        break;
    case 'checkout':
        $service = new mc_product_service();

        $sid = _post('sid', 0, MF_INT_KEY);
        $cids = _post('cids', 0, MF_INT_KEY);

        if(!$cids) {
            $service->add_error('您尚未选择购买的商品。');
        } else {
            $cart_obj = _G('loader')->model('product:cart');
            $products = $cart_obj->get_products_by_sid($cart_slg->cartid, $sid);
            if(!$products) {
                $service->add_error('商品数据不存在。');
            }
            foreach ($cids as $cid) {
                $exists = false;
                foreach ($products as $product) {
                    if($product['cid'] == $cid) {
                        $exists = true;
                        if(!$product['stock']) {
                            $service->add_error("商品:{$product['subject']} 库存不足。");
                        } elseif(!$product['is_on_sale']) {
                            $service->add_error("商品:{$product['subject']} 已下架。");
                        }
                    }
                }
                if(!$exists) {
                    $service->add_error("购物车内有商品不存在[CID:{$cid}]不存在。");
                }
            }
            if(!$service->has_error()) {
                _G('session')->cids = implode(',', $cids);
                //审核完成，提供下单连接
                $service->url = U("product/member/ac/order/cids/".implode('_', $cids), true);
                $service->url_mobile = U("product/mobile/do/order/cids/".implode('_', $cids), true);
            }
        }
        echo $service->fetch_all_attr('json');
        output();
        break;
    case 'add':
        $pid = (int)$_POST['pid'];
        $num = (int)$_POST['num'];
        $buyattr = _post('buyattr','', MF_TEXT);

        if($pid < 1) {
            redirect(lang('global_sql_keyid_invalid', 'pid'));
        }
        if($num < 1) $num = 1;

        if(!$cid = $cart_slg->add($pid, $num, $buyattr)) {
            redirect($cart_slg->error());
        }
        echo $cid;
        output();
        break;
		
    case 'add_many':
        $pids = _post('pid',"", MF_TEXT);
		$num = 1;
        $buyattr = _post('buyattr','', MF_TEXT);
        if(empty($pids)) {
	        echo json_encode(array("status"=>false,"info"=>"该套餐没有商品!"));
			exit();
        }
		
		if(!(strpos($buyattr, "-1") === FALSE)){
	        echo json_encode(array("status"=>false,"info"=>"请选择商品规格!"));
			exit();
		}
		
		$pid_arr = explode(",",  $pids);
		$cid_arr = array();
		//清空购物车
		$cart_slg->clear();
		foreach($pid_arr as $vo){
//			dump($vo);
			if(empty($vo)){
				continue;
			}
	        if(!$cid = $cart_slg->add($vo, $num, $buyattr)) {
	        		echo json_encode(array("status"=>false,"info"=>$cart_slg->error()));
				exit();
	        }
			array_push($cid_arr,$cid);		
		}
		
        echo json_encode(array("status"=>TRUE,"info"=>$cid_arr));
        exit();
        break;
    case 'delete':
        $cart_slg->delete(_post('cids', 0, MF_INT_KEY));
        if(defined('IN_AJAX')) {
            echo 'OK';
            output();
        } else {
            redirect('global_op_succeed_delete', url("product/cart"));
        }
        break;
    case 'clear':
        $cart_slg->clear();
        echo 'OK';
        output();
        break;
    case 'buyattr':
        if(!$cid = _input('cid', 0, MF_INT_KEY)) redirect(lang('global_sql_keyid_invalid', 'cid'));
        if(!$buyattr = _post('buyattr', '', MF_TEXT)) redirect(lang('global_sql_keyid_invalid', 'buyattr'));
        if(!$cart_slg->update_buyattr($cid, $buyattr)) {
            redirect($cart_slg->error());
        }
        echo 'OK';
        output();
        break;
    case 'num_dec':
        if(!$cid = _input('cid', 0, MF_INT_KEY)) redirect(lang('global_sql_keyid_invalid', 'cid'));
        if(!$cart_slg->dec_quantity($cid, 1)) {
            redirect($cart_slg->error());
        }
        echo 'OK';
        output();
        break;
    case 'num_add':
        if(!$cid = _input('cid', 0, MF_INT_KEY)) redirect(lang('global_sql_keyid_invalid', 'cid'));
        if(!$cart_slg->add_quantity($cid, 1)) {
            redirect($cart_slg->error());
        }
        echo 'OK';
        output();
        break;
    case 'num_change':
        if(!$cid = _input('cid', 0, MF_INT_KEY)) redirect(lang('global_sql_keyid_invalid', 'cid'));
        if(!$num = _input('num', 0, MF_INT)) redirect(lang('global_sql_keyid_invalid', 'num'));
        if(!$cart_slg->change_quantity($cid, $num)) {
            redirect($cart_slg->error());
        }
        echo 'OK';
        output();
    case 'change':
        if(!$cid = _input('cid', 0, MF_INT_KEY)) redirect(lang('global_sql_keyid_invalid', 'cid'));
        if(!$num = _input('num', 0, MF_INT)) redirect(lang('global_sql_keyid_invalid', 'num'));
        $buyattr = _post('buyattr', '', MF_TEXT);
        if(!$cart_slg->update_change($cid, $num, $buyattr)) {
            redirect($cart_slg->error());
        }
        echo 'OK';
        output();
        break;
    default:
        $cartid = $cart_slg->cartid;
        $urlpath[] = url_path('查看购物车，购物车编号：'.$cartid, url("product/cart"));
        //删除虚拟商品
        $cart_slg->delete_virtual_goods();
        //加载购物车商品数据
        list($total, $list) = $cart_slg->get_goods_list();
        include template('product_cart');
}
?>