<?php
/**
* @author moufer<moufer@163.com>
* @copyright Moufer Studio(www.modoer.com)
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op', null, MF_TEXT);

$IV = & $_G['loader']->model('member:invite');
list($total,$list) = $IV->getlist($user->uid,0,30);

/* end */