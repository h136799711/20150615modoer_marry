<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class hook_mylist extends ms_base {

    function __construct(&$hook) {
        parent::__construct();
        $hook->register(
            array('space_nav_link','mobile_index_link'), 
            $this
        );
    }

	function space_nav_link($space) {
		$result = array();
		$result[] = array (
			'flag' => 'mylist',
			'url' => url("space/{$space->uid}/pr/mylist"),
			'title'=> "榜单",
		);
		return $result;
	}

	function mobile_index_link() {
		$result[] = array (
			'flag' => 'mylist/list',
			'url' => url('mylist/mobile/do/list'),
			'title'=> '榜单',
			'icon' => 'mylist',
		);
		return $result;
	}
}
?>