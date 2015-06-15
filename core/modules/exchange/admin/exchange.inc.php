<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$EX =& $_G['loader']->model(MOD_FLAG.':exchange');
$op = $_GET['op'] ? $_GET['op'] : $_POST['op'];

$status_name = array();
for($i=1;$i<=4;$i++) {
    $status_name[$i] = lang('exchange_status_'.$i);
}

switch($op) {
case 'update':
    $exchangeid = (int)$_POST['exchangeid'];
    if(!$detail = $EX->read($exchangeid)) redirect('exchange_empty');
    if($detail['status']==4) redirect('兑换已退款，不能再次更新。');
    $EX->update($exchangeid,$_POST['status'],$_POST['des']);
    redirect('global_op_succeed', get_forward(cpurl($module,$act,'list'),1));
    break;
case 'delete':
    $EX->delete($_POST['exchangeids'],$_POST['member_point'],$_POST['gift_num']);
    redirect('global_op_succeed', get_forward(cpurl($module,$act,'list')));
    break;
case 'detail':
    $exchangeid = (int)$_GET['exchangeid'];
    if(!$detail = $EX->read($exchangeid)) redirect('exchange_empty');
    $GT =& $_G['loader']->model(MOD_FLAG.':gift');
    $gift = $GT->read($detail['giftid']);
	if($gift['sort']=='2') {
		$serial = $_G['loader']->model('exchange:serial')->getlist($exchangeid,$detail['uid']);
	}
    $admin->tplname = cptpl('exchange_detail', MOD_FLAG);
    break;
default:
    $op='list';
    if(!$status=(int)$_GET['status']) $status = 1;
    $offset = 20;
    $start = get_start($_GET['page'],$offset);
	$select = 'exchangeid,giftid,giftname,uid,username,price,number,exchangetime,status';
	$where = array();
	if(!$admin->is_founder) $where['city_id'] = $admin->check_global() ? array(0,$_CITY['aid']) : $_CITY['aid'];
	$where['status'] = $status;
    list($total,$list) = $EX->find($select, $where, array('exchangetime'=>'DESC'), $start, $offset, true);
    if($total) {
        $multipage = multi($total, $offset, $_GET['page'], cpurl($module,$act,'list',$_GET));
    }
    $status_group = $EX->status_total($user->uid, $admin->is_founder?null:$_CITY['aid']);
    $admin->tplname = cptpl('exchange_list', MOD_FLAG);
}

/** end **/