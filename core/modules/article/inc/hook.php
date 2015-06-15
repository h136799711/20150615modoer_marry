<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class hook_article extends ms_base {

    function __construct(&$hook) {
        parent::__construct();
        $hook->register(
        	array('admincp_subject_edit_link','subject_detail_link','mobile_index_link'), 
        	$this
        );
    }

	function admincp_subject_edit_link($sid) {
		$url = cpurl('article','article','list',array('sid'=>$sid));
		return array(
			'flag' => 'article:list',
			'url' => $url,
			'title'=> '资讯管理',
		);
	}

	function subject_detail_link(&$params) {
		extract($params);
		$title = '资讯';
		return array (
			'flag' => 'article',
			'url' => url('article/item/sid/'.$sid),
			'title'=> $title,
		);
	}

	function mobile_index_link() {
		$result[] = array (
			'flag' => 'article/list',
			'url' => url('article/mobile/do/list'),
			'title'=> '新闻资讯',
			'icon' => 'article',
		);
		return $result;
	}
}
?>