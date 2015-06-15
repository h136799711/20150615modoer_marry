<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$C =& $_G['loader']->model('config');
$_G['loader']->helper('form','member');
if($_POST['dosubmit']) {

	if(!is_numeric($_POST['modcfg']['cash_rate'])||$_POST['modcfg']['cash_rate']<1) {
		$_POST['modcfg']['cash_rate'] = 10;
	}
	if(!is_numeric($_POST['modcfg']['giveintegral_percent'])||$_POST['modcfg']['giveintegral_percent']<=0||$_POST['modcfg']['giveintegral_percent']>100) {
		$_POST['modcfg']['giveintegral_percent'] = '';
	}
	if(!is_numeric($_POST['modcfg']['brokerage'])||$_POST['modcfg']['brokerage']<1||$_POST['modcfg']['brokerage']>100) {
		$_POST['modcfg']['brokerage'] = 0;
	}

    $C->save($_POST['modcfg'], MOD_FLAG);
    $_G['db']->from('dbpre_modules')->where('flag','product')->set('extra','pro')->update();
    redirect('global_op_succeed', cpurl($module, 'config'));

} else {

    $modcfg = $C->read_all(MOD_FLAG);
    $admin->tplname = cptpl('config', MOD_FLAG);
}
?>