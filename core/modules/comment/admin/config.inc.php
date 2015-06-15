<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$C =& $_G['loader']->model('config');
//评论系统接口
$ITFN = $_G['loader']->model('comment:interface_manage');

if($_POST['dosubmit']) {

    $IFN = $ITFN->factory($_POST['modcfg']['comment_interface']);
	$setting = $IFN->check_setting();

	if($setting) foreach ($setting as $key => $value) {
		$_POST['modcfg'][$key] = $value;
	}
    $_POST['modcfg']['interface_hooks'] = $IFN->get_hook() ? implode(',', $IFN->get_hook()) : '';

    $C->save($_POST['modcfg'], MOD_FLAG);
    redirect('global_op_succeed', cpurl($module, 'config'));

} else {

    $modcfg = $C->read_all(MOD_FLAG);

    $radio_interface = array();
    $ITFN->scan_interfaces();
    if($interfaces = $ITFN->interface_list()) {
    	foreach ($interfaces as $key => $info) {
    		$radio_interface[$key] = $info['name'];
    	}
    }
    $tmp = $radio_interface['local'];
    unset($radio_interface['local']);
    $radio_interface=array('local'=>$tmp)+$radio_interface;

    $admin->tplname = cptpl('config', MOD_FLAG);
}
?>