<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op');
$M = $_G['loader']->model(':mylist');

switch($op) {
	case 'add':
		if($MOD['add_closed']) redirect('管理员已经关闭创建榜单的功能。');
		$tplname = 'save';
		break;
	case 'edit':
		if($MOD['add_closed']) redirect('管理员已经关闭创建榜单的功能。');
		$id = _get('id', null, MF_INT_KEY);
		$detail = $M->read($id);
		if(!$detail) redirect('mylist_empty');
		if($detail['uid']!=$user->uid) redirect('mylist_manage_access_denied');

		//主题列表
		$MI = $_G['loader']->model('mylist:item');
		$where = array('mylist_id' => $id);
		$total = $MI->db->from($MI->table)->where($where)->count();
		if($total > 0) {
			$start = get_start($_GET['page'], $offset=100);
			$list = $MI->db->join($MI->table,'mi.sid', 'dbpre_subject', 's.sid', 'LEFT JOIN')
				->select('mi.*,s.name,s.subname,s.avgsort,avgsort,reviews,thumb')
				->where($where)
				->order_by('listorder','ASC')
				->limit($start, $offset)
				->get();
			$multipage = multi($total, $offset, $_GET['page'], url("mylist/member/ac/manage/op/edit/id/$id/page/_PAGE_"));
		}
		$tplname = 'save';
		break;
	case 'save':
		if($MOD['add_closed']) redirect('管理员已经关闭创建榜单的功能。');
		if(_post('do')=='edit') {
			$id = _post('id',null,MF_INT_KEY);
			if(!$id) redirect('未指定榜单ID，请返回。');
		} else {
			$id = null;
		}
		$post = $M->get_post($_POST);
		$post['city_id'] = $_CITY['aid'];
		$id = $M->save($post, $id);
		//处理主题排序
		$items = _post('items',null);
		if($items) {
			$MI = $_G['loader']->model('mylist:item');
			$MI->update_listorder($items);
		}

		$url = url('mylist/' . $id);
		if(DEBUG) redirect('global_op_succeed', $url);
		location($url);
		break;
	case 'delete':
		$id = _post('id', 0, MF_INT_KEY);
		$M->delete($id);
		echo 'ok';
		output();
		break;
	case 'favorite':
		$do = _input('do');
		$id = _post('id', 0, MF_INT_KEY);
		$fun = $do == 'del' ? 'delete' : 'add';
		$F = $_G['loader']->model('mylist:favorite');
		if($do=='add') {}
		$ret = $F->$fun($id);
		if($ret==-1) {
			echo 'yet';
		} else {
			$mylist = $M->read($id);
			echo $mylist['favorites'];
		}
		output();
		break;
	case 'flower':
		$do = _input('do');
		$id = _post('id', 0, MF_INT_KEY);
		$fun = $do == 'del' ? 'delete' : 'add';
		$F = $_G['loader']->model('mylist:flower');
		if($do=='add') {}
		$ret = $F->$fun($id);
		if($ret==-1) {
			echo 'yet';
		} else {
			$mylist = $M->read($id);
			echo $mylist['flowers'];
		}
		output();
		break;
	default:
		//我的榜单
		$offset = 10; //每页数量
		$start = get_start($_GET['page'], $offset);
		//查询
		$where = array();
		$where['uid'] = $user->uid;
		$orderby = array('modifytime'=>'DESC');

		$total = $_G['db']->from('dbpre_mylist')->where($where)->count();
		if($total>0) $list = $_G['db']->sql_roll_back('from,where')
			->order_by($orderby)->limit($start, $offset)->get();

		if($total > $offset) {
			$multipage = multi($total, $offset, $_GET['page'], url("mylist/member/ac/manage/page/_PAGE_"));
		}
		$tplname = 'list';
}

/** end **/