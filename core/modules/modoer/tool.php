<?php
/**
* @author fex<fex2013@163.com>
*/
!defined('IN_MUDDER') && exit('Access Denied');
//redirect('1234');

switch($_GET['do']){
	default:
	$template = 'modoer_tool_yusuan'; break;
	break;
}

include template($template);
?>