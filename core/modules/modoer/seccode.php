<?php
/**
* sec code
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'seccode');

$refererhost = parse_url($_SERVER['HTTP_REFERER']);
$refererhost['host'] .= !empty($refererhost['port']) ? (':'.$refererhost['port']) : '';

/*
if($refererhost['host'] != $_SERVER['HTTP_HOST']) {
    exit('Access Denied');
}
*/

$width = _get('width', 0, MF_INT);
$height = _get('height', 0, MF_INT);

$sec_obj = new ms_seccode();
$sec_obj->create($width, $height);

/** end **/
