<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class hook_review extends ms_base {

    function __construct(&$hook) {
        parent::__construct();
        $hook->register(
            array('admincp_subject_edit_link','subject_detail_link','mobile_member_link','space_nav_link'), 
            $this
        );
    }

	function admincp_subject_edit_link($sid) {
		$url = cpurl('review','review','list',array('idtype'=>'item_subject','id'=>$sid));
		return array(
			'flag' => 'review:list',
			'url' => $url,
			'title'=> '点评管理',
		);
	}

	function subject_detail_link(&$params) {
		extract($params);
		$result = array();
		$result[0] = array (
			'flag' => 'review',
			'url' => url('review/item/sid/'.$sid),
			'title'=> '点评',
		);
		return $result;
	}

	function space_nav_link($space) {
		$result = array();
		$result[0] = array (
			'flag' => 'review',
			'url' => url("space/{$space->uid}/pr/review"),
			'title'=> "点评",
		);
		return $result;
	}

	function mobile_member_link() {
		$result[] = array (
			'flag' => 'review/member/review',
			'url' => url('review/mobile/do/review/op/my'),
			'title'=> '我的点评',
		);
		return $result;
	}

	function footer() {
	}

}
?>