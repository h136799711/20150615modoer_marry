<?php
/**
* user register
* @author moufer<moufer@163.com>
* @copyright Moufer Studio(www.modoer.com)
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'vcard');

$member = array();
if($uid = _input('uid',0,MF_INT_KEY)) {
    $member = $member = $_G['loader']->model(':member')->read($uid);
}

require_once template('member_vcard');
output();
?>