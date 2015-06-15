<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'link');

if(!$MOD['open_apply']) redirect('link_post_apply_disabled');

$LK =& $_G['loader']->model('link:mylink');
if(check_submit('dosubmit')) {
	check_seccode($_POST['seccode']);
    $post = $LK->get_post($_POST);
    $LK->save($post);
    redirect('global_op_succeed_check', url('link/index'));
} else {
	$_G['loader']->helper('form');
    $_HEAD['keywords'] = $MOD['meta_keywords'];
    $_HEAD['description'] = $MOD['meta_description'];
    include template('link_apply');
}
?>