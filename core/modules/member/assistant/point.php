<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op');
$groups = array('point1','point2','point3','point4','point5','point6',);

$POINT = & $_G['loader']->model('member:point');
$groups = $POINT->group;

switch($op) {
case 'exchange':
	$POINT->exchange($_POST['in_value'], $_POST['in_point'], $_POST['out_point']);
	redirect('global_op_succeed',url('member/index/ac/point'));
    break;
case 'headerget':
    require_once template('point_headerget','member',MOD_FLAG);
    output();
case 'log':
	$logs = $_G['loader']->model('member:point_log');
	$offset = 20;
	$start = get_start($_GET['page'],$offset);
	list($total,$list) = $logs->getlist($user->uid,$start,$offset);
	if($total) {
		$multipage = multi($total, $offset, $_GET['page'], url("member/index/ac/$ac/op/$op/page/_PAGE_"));
	}
	$tplname = 'point_log';
	break;
default:
	
}
?>