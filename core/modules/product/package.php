<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------


!defined('IN_MUDDER') && exit('Access Denied');
$op = _get("op","");


switch($op){
	/**
	 * 20150626修改
	 */
	case "delete_bespeak":
		//删除预约
		$id = _post("pid",0,intval);
		
		
		$rows = $_G['loader']->model("product:pkg_bespeak")->delete($id);
		
		if(!$user->isLogin) {
		    if(defined('IN_AJAX')) {
		        $forward = base64_encode($_G['web']['referer']);
		        dialog(lang('global_op_title'), '', 'login');
		    } else {
		        $forward = $_G['web']['reuri'] ? ($_G['web']['url'] . $_G['web']['reuri']) : url('modoer','',1);
		        location(url('member/login/forward/'.base64_encode($forward)));
		    }
		}
				
		if(defined('IN_AJAX')) {
			if($rows >= 0){
				echo json_encode(array('status'=>true,'info'=>'操作成功!'));
			}else{
				echo json_encode(array('status'=>false,'info'=>'操作失败!'));
			}
		}else{
				echo json_encode(array('status'=>false,'info'=>'操作失败!'));
		}
		
		break;
	case "bespeak":
		define('SCRIPTNAV', 'assistant');
		$page = _get("page",1,intval);
		//预约管理
		require_once MUDDER_MODULE.'member'.DS.'assistant'.DS.'menu.php';
		
		if(!$user->isLogin) {
		    if(defined('IN_AJAX')) {
		        $forward = base64_encode($_G['web']['referer']);
		        dialog(lang('global_op_title'), '', 'login');
		    } else {
		        $forward = $_G['web']['reuri'] ? ($_G['web']['url'] . $_G['web']['reuri']) : url('modoer','',1);
		        location(url('member/login/forward/'.base64_encode($forward)));
		    }
		}
		
		
		$list = $_G['loader']->model("product:pkg_bespeak")->find($_G['manage_subject']['sid'],$page);
		
		if(is_array($list['page'])){
//			$mpurl = ;
			$pager = multi($list['page'][0], $list['page'][1], $list['page'][2], url("product/package/op/bespeak"));
			
		}
		$total = $list['page'][0];
		$list = ($list['list']);
//		dump($pager);
		
		$tplname = '';
		
//		$scriptname = MOD_ROOT . 'assistant' . DS . $op . '.php';
//		if(!is_file($scriptname)) show_error(lang('global_file_not_exist', str_replace(MUDDER_ROOT, DS, $scriptname)));
//		require_once MOD_ROOT . 'assistant' . DS . $op . '.php';
		
		if(empty($tplname)) $tplname = $op;
		$_HEAD['title'] = lang('member_operation_title');
		require_once template($tplname, 'member', MOD_FLAG);


		break;
	case "order":
		define('SCRIPTNAV', 'product');
		$pkgorder = $_G['loader']->model("product:pkg_bespeak");
		
		//预约保存
//		$pkgid = _post("pkgid",0);
//		
//		if(empty($id)){
//			show_error('未指定ID参数!');
//		}
		
		$post = $pkgorder->get_post($_POST);
		$post['create_time'] = time();
		$result = $pkgorder->add_save($post);
		
		redirect('预约成功,', get_forward());
		break;
	default:
		define('SCRIPTNAV', 'product');
		$urlpath = array();
		$urlpath[] = url_path($MOD['name'], url("product/package"));
		
		$_HEAD['keywords'] = $MOD['meta_keywords'];
		$_HEAD['description'] = $MOD['meta_description'];
		
		$pkg_name = _post("pkg_name","",MF_TEXT);
		
		$num = _get('num', 0, 'intval');
		$num ? $offset = $num : $offset = 5;
		$start = get_start($_GET['page'], $offset);
		$where = array();
        $where['p.`name`'] = array('where_like',array("%".$pkg_name."%"));
		$P = $_G['loader']->model("product:package");
		
		list($total, $list) = $P->find($where,$start,$offset);
		if($total) $multipage = multi($total, $offset, $_GET['page'], url("product/package/num/$num/page/_PAGE_"));
		
//		dump($total);
//		dump($offset);
//		dump($list);
//		dump($multipage);
		
		include template('product_package');
		break;
}
