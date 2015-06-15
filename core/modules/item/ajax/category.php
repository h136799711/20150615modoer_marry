<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->helper('query','item');

$pid = _get('pid', 0, MF_INT_KEY);
$data = query_item::category(array('pid'=>$pid));
if(_G('charset') != 'utf-8') {
    foreach ($data as $key => $value) {
        if(!is_numeric($value)) {
            $data[$key] = charset_convert($value, _G('charset'), 'utf-8');
        }
    }
}

if(!$data) {
	$ret = array(
		'code' => 110003,
		'message' => 'empty',
	);
} else {
	$ret = array(
		'code' => 200,
		'data' => $data,
	);
}


echo json_encode($ret);
output();