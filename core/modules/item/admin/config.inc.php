<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$C =& $_G['loader']->model('config');
$_G['loader']->helper('function','item');

if($_POST['dosubmit']) {

    if($_POST['modcfg']['use_nearby']) {
        if(!has_dbfunction_nearby()) {
            add_dbfunction_nearby();
        }
    }

	if($_POST['modcfg']['taoke_appsecret'] == '*********') unset($_POST['modcfg']['taoke_appsecret']);
    $C->save($_POST['modcfg'], MOD_FLAG);

    $SG = $_G['loader']->model('item:gourd');
    $SG->init_cfg($_POST['modcfg']);
    $SG->update_condition();

    redirect('global_op_succeed', cpurl($module, 'config'));

} else {

	$_G['loader']->helper('form', MOD_FLAG);
	$_G['loader']->helper('form', 'member');
    $modcfg = $C->read_all(MOD_FLAG);
	
    if($modcfg['taoke_appsecret']) $modcfg['taoke_appsecret'] = '*********';

    if($modcfg['use_nearby']) $modcfg['use_nearby'] = has_dbfunction_nearby() ? 1 : 0;


    $admin->tplname = cptpl('config', MOD_FLAG);
}

?>