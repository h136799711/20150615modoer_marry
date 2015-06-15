<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class hook_modoer extends ms_base {

    function __construct(&$hook) {
        parent::__construct();
        // $hook->register(
        //     array('init_begin','init_end'), 
        //     $this
        // );
    }

    function init_begin() {
    }

    function init_end() {

    }

    function footer() {
    }

}
?>