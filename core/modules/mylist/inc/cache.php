<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

inc_cache_mylist();

function inc_cache_mylist() {
    $loader =& _G('loader');

    $tmp =& $loader->model('config');
    $tmp->write_cache('mylist');

    $cachelist = array('category');
    foreach ($cachelist as $value) {
        $tmp =& $loader->model('mylist:'.$value);
        $tmp->write_cache();
    }
}
?>