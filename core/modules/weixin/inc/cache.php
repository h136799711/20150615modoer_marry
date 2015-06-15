<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

inc_cache_weixin();

function inc_cache_weixin() {
    $loader =& _G('loader');

    $tmp =& $loader->model('config');
    $tmp->write_cache('weixin');
}
?>