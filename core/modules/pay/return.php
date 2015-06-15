<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$PAY =& $_G['loader']->model(':pay');
$result = $PAY->pay_return(_input('api'));
if($result) {
	if(is_string($result)) {
		$url = $result;
	} else {
		$url = $_G['in_mobile'] ? U('member/mobile',TRUE) : U('member/index',TRUE);
	}
	location($url);
} else {
	if($PAY->has_error()) {
		redirect($PAY->error(), 'stop');
	} else {
		$url = $_G['in_mobile'] ? U('member/mobile',TRUE) : U('member/index',TRUE);
		location($url);
	}
}