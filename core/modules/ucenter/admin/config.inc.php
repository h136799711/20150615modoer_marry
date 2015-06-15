<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$C =& $_G['loader']->model('config');
$UCER =& $_G['loader']->model(':ucenter');

$ucfile=MUDDER_DATA . 'config_uc.php';
if(!is_file($ucfile)){
	redirect('您安装了uc整合模块，请将文件data/config_uc.new.php改名为config_uc.php');
}

if($_POST['dosubmit']) {

    $UCER->config($_POST['modcfg'], $_POST['uc']);

    $C->save($_POST['modcfg'], MOD_FLAG);
    redirect('global_op_succeed', cpurl($module, 'config'));

} else {

    $modcfg = $C->read_all(MOD_FLAG);
    $ucdbpw = '********';

    list($ucdbname, $uctablepre) = explode('.', str_replace('`', '', UC_DBTABLEPRE));
    $disabled = $UCER->is_write() ? '' : 'disabled ';

    $admin->tplname = cptpl('config', MOD_FLAG);
}
?>