<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

inc_cache_adv();

function inc_cache_adv() {
    $loader =& _G('loader');

    $tmp =& $loader->model('config');
    $tmp->write_cache('adv');

    $cachelist = array('place');
    foreach ($cachelist as $value) {
        $tmp =& $loader->model('adv:'.$value);
        $tmp->write_cache();
    }
}
?>