<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'article');


//解析seo设置赋值
$_HEAD['title'] = $SEO->pares('index')->title;
$_HEAD['keywords'] = $SEO->keywords;
$_HEAD['description'] = $SEO->description;

include template('article_index');
?>