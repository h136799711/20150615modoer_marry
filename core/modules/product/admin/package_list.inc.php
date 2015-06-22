<?php
/**
 * @author moufer<moufer@163.com>
 * @copyright www.modoer.com
 */
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');
//
$P = &$_G['loader'] -> model('product:package');
//dump($P);
//操作
$op = _input('op', null, '_T');
$_G['loader'] -> helper('form', 'product');
$_G['loader'] -> helper('form', 'member');
$usergroup = &$_G['loader'] -> variable('usergroup', 'member');
$forward = get_forward(cpurl($module, $act));

switch($op) {
	case 'list_product' :
		$p = _get('page',1,MF_INT_KEY);
		$pname = _post('pname','');
		if (IN_AJAX) {
			$info = getProductList($p,$pname);
//			header("Content-type:application-json")
			echo json_encode(array('status' => true, 'info' => $info));
			exit();
		}
		
		echo json_encode(array('status' => false, 'info' => '非ajax请求!'));
		break;
	case 'edit' :
		$id = _get('id',0,MF_INT_KEY);
		$detail = $P->read($id,true);
		$sid = $detail['sid'];
		
        if($sid) $subject = $_G['loader']->model('item:subject')->read($sid,'*',false);
		
		//套餐编辑
		$admin -> tplname = cptpl('package_edit', MOD_FLAG);
		break;
	case 'edit_save' :
		
		$id = _post("id",0,MF_INT_KEY);
		//新增
		$pkg = $_G['loader'] -> model("product:package");
		$post = $pkg -> get_post($_POST);
		
		$post['start_time'] = strtotime($post['start_time']);
		$post['end_time'] = strtotime($post['end_time']);
		$post['update_time'] = time();
		
		$pid = $pkg -> edit_save($id,$post);

		$navs = array( array('name' => 'product_redirect_productpkglist', 'url' => cpurl($module, $act, 'list')), );

		redirect('global_op_succeed', $navs);
		break;
	case 'add' :
		//		$atts_q = $_G['db'] -> from('dbpre_product_package') -> where('id',1) -> get_one();
		//		dump($atts_q);
		//		$atts_q = $_G['db'] -> from('dbpre_product_package') -> where('id',2) -> get_all();
		//		dump($atts_q);

		//		$atts_q = $_G['db'] ->join('dbpre_product','p.pid', 'dbpre_product_package','pkg.pids', 'LEFT JOIN') ->where('pids','6') -> get_all();
		//		$pkgid  = 1;
		
   		$gcatid = (int) $_GET['catid'];
		$admin -> tplname = cptpl('package_add', MOD_FLAG);
		//		$pkg = $_G['loader']->model("product:package")->read($pkgid);

		break;
	case 'add_save' :
		//新增
		$pkg = $_G['loader'] -> model("product:package");
		$post = $pkg -> get_post($_POST);
		
		$gcatid = $post['gcatid'];
		
    		$category = $_G['loader']->model('product:gcategory')->read($gcatid);
		
//		dump($category);
    		//判断子分类是否禁用
   		if(!$category['enabled']) redirect('product_cat_disabled');
		
		$post['start_time'] = strtotime($post['start_time']);
		$post['end_time'] = strtotime($post['end_time']);
		$post['create_time'] = time();
		$post['update_time'] = time();
		$pid = $pkg -> add_save($post);

		$navs = array( array('name' => 'product_redirect_productpkglist', 'url' => cpurl($module, $act, 'list')), );

		redirect('global_op_succeed', $navs);
		break;

	case 'succeed' :
		$id = _get('id', 0, MF_INT_KEY);
		//		$navs = array(
		//		array('name' => 'package_redirect_packagelist', 'url' => cpurl($module, $act, 'list')),
		//		array('name' => 'global_redirect_return', 'url' => get_forward(cpurl($module, $act, 'list'), 1)),
		//		array('name' => 'package_redirect_newpackage', 'url' => cpurl($module, $act, 'add', array('sid' => $sid))),
		//		array('name' => 'package_redirect_subjectproduct', 'url' => cpurl($module, $act, 'add') ),
		//		);
		redirect('global_op_succeed', get_forward(cpurl($module, $act, 'list')));
		break;

	case 'update' :
		$P -> update($_POST['packages']);
		redirect('global_op_succeed', $forward);

	case 'delete' :
		//删除
		$P -> delete($_POST['ids']);
		redirect('global_op_succeed', get_forward(cpurl($module, $act, 'list')));
		break;
	case 'checkup' :
		$P -> checkup($_POST['pids']);
		redirect('global_op_succeed', get_forward(cpurl($module, $act, 'list')));
		break;
	case 'checklist' :
		$admin -> tplname = cptpl('product_check', MOD_FLAG);
		break;
	default :
		$products = getProductList(1);
		$op = 'list';
		$P -> db -> from("dbpre_product_package");
		$_GET['id'] && $P -> db -> where('p.id', $_GET['id']);
		
		$_GET['subject'] && $P -> db -> where_like('p.name', "%" . trim($_GET['subject'] . "%"));

		$_GET['starttime'] && $P -> db -> where_more('p.create_time', strtotime($_GET['starttime']));
		$_GET['endtime'] && $P -> db -> where_less('p.create_time', strtotime($_GET['endtime']));

		$P -> db -> join($P -> table, 'p.sid', 'dbpre_subject', 's.sid', 'LEFT JOIN ');

		if ($total = $P -> db -> count()) {
			$P -> db -> select('s.`sid`,s.`name` as subname,p.`sid`,p.`id`,p.`name`,p.`ori_price`,p.`price`,p.`update_time`,p.`pageview`,p.`finer`,p.`create_time`,p.`start_time`,p.`end_time`,p.`onshelf`,p.`tags`,p.`desc`,p.`city_id`');
			$P -> db -> sql_roll_back('from,where');
			!$_GET['orderby'] && $_GET['orderby'] = 'id';
			!$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
			
			$P -> db -> order_by('p.' . $_GET['orderby'], $_GET['ordersc']);
			$P -> db -> limit(get_start($_GET['page'], $_GET['offset']), $_GET['offset']);
			$list = $P -> db -> get();
			$multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module, $act, 'list', $_GET));
		}

		$_G['loader'] -> helper('form', MOD_FLAG);
		$admin -> tplname = cptpl('package_list', MOD_FLAG);
}


function getProductList($p=1,$pname='') {
	global $_G;
	
	$offset = 5;
	
	$product = &$_G['loader'] -> model(':product');
	if(!empty($pname)){
		$product -> db -> where_like("p.subject", '%'.$pname.'%');
	}
	$product -> db -> where('p.status', 1);
	$product -> db -> join($product -> table, 'p.sid', 'dbpre_subject', 's.sid');
	if ($total = $product -> db -> count()) {
		$product -> db -> select('p.*,s.name,s.subname');
		$product -> db -> sql_roll_back('from,where');
		
		$product -> db -> order_by('p.pid' ,'DESC');
		$product -> db -> limit(get_start($p, $offset), $offset);
		$list = $product -> db -> get_all();
		
		$multipage = multi($total, $offset, $p, cpurl("product", "package_list", 'list_product', $_GET));
	}
//	dump($multipage);
	return  array('list'=>$list,'page'=>$multipage);
}

?>