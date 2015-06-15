<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$page = _get('p');

switch ($page) {
    case 'top':
        $nextpag = 'item/mobile/do/top';
        break;
    
    default:
        $header_title = '分类大全';
        $nextpag = 'item/mobile/do/list';
        break;
}
include mobile_template('item_category');