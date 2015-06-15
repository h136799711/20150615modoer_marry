<?php
/**
* @author moufer<moufer@163.com>
* @copyright (C)2001-2007 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->helper('form',MOD_FLAG);
//正在处理的主题ID
$sid = (int)$_G['manage_subject']['sid'];

$SS = $_G['loader']->model('item:subjectsetting');

if(check_submit('dosubmit')) {
    $PS = $_G['loader']->model('product:subjectsetting');
    $post = array(
        'offlinepay'=>_post('offlinepay','',MF_TEXT),
        'copy_disable'=>_post('copy_disable','',MF_INT_KEY),
        'use_deliverytime'=>_post('use_deliverytime','',MF_INT_KEY),
        'onedaydelivery_limit'=>_post('onedaydelivery_limit','',MF_INT_KEY),
    );
    $PS->save($sid, $post);
    redirect('global_op_succeed', url("product/member/ac/g_setting"));
} else {
	$_G['loader']->helper('form');

    $setting = $SS->read($sid);
    $tplname = 'setting';
}
?>