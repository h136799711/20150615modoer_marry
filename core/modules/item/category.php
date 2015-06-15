<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'item_category');

//解析seo设置赋值
$_HEAD['title'] = $SEO->pares('category')->title;
$_HEAD['keywords'] = $SEO->keywords;
$_HEAD['description'] = $SEO->description;

$_G['show_sitename'] = FALSE;
include template('item_category');
?>