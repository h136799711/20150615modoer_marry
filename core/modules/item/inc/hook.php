<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class hook_item extends ms_base {

	function __construct(&$hook) {
		parent::__construct();
		$hook->register(
			array(
				'init_sldomain','admincp_subject_edit_link','subject_detail_link','mobile_index_link',
				'mobile_member_link','space_nav_link'
			), 
			$this
		);
	}

	//未使用的二(三)级域名
	function init_sldomain($domain) {
		global $_G;
		$domain = strtolower(_G('web','domain'));
		if('http://'.$domain.'/' != $_G['cfg']['siteurl'] && !$_G['in_ajax']) {
			$modcfg = $this->loader->variable('config','item');
			if(!$modcfg['sldomain']) return false;
			if($modcfg['sldomain'] != 1 && $modcfg['sldomain'] != 3) return false;
			if(!$i = strpos($domain,'.')) return false;
			$eq_sldomain = substr($domain, $i+1);
			if($eq_sldomain != $modcfg['base_sldomain']) return false;
			$slname = substr($domain, 0, $i);
			if((!$_GET['m'] || $_GET['m'] == 'item') && !$_GET['act']) {
				$_GET['m'] = 'item';
				$_GET['act'] = 'detail';
				$_GET['name'] = $slname;
			}
			$_G['sldomain'] = $_G['fullalways'] = true;
			return true;
		}
		return false;
	}

	//后台主题编辑
	function admincp_subject_edit_link($sid) {
		$result = array();
		$result[] = array (
			'flag' => 'item:subject_edit',
			'url' => cpurl('item','subject_edit','',array('sid'=>$sid)),
			'title'=> '编辑主题',
		);
		$result[] = array (
			'flag' => 'item:subject_guestbook',
			'url' => cpurl('item','guestbook_list','',array('sid'=>$sid)),
			'title'=> '留言管理',
		);
		$setting = $this->loader->model('item:subjectsetting')->read($sid);
		if($setting['banner']) {
			$result[] = array (
				'flag' => 'item:subject_setting_banner',
				'url' => cpurl('item','setting','banner',array('sid'=>$sid)),
				'title'=> '横幅管理',
			);
		}
		if($setting['bcastr']) {
			$result[] = array (
				'flag' => 'item:subject_setting_bcastr',
				'url' => cpurl('item','setting','bcastr',array('sid'=>$sid)),
				'title'=> '橱窗管理',
			);
		}

		return $result;
	}

	//主题内容页导航
	function subject_detail_link(&$params) {
		extract($params);
		$result = array();
		$IB =& $this->loader->model('item:itembase');
		$model = $IB->get_model($pid, true);
		$result[0] = array (
			'flag' => 'item/detail',
			'url' => url('item/detail/id/'.$sid),
			'title'=> '首页',
		);
		$result[1] = array (
			'flag' => 'item/album',
			'url' => url('item/album/sid/'.$sid),
			'title'=> '相册',
		);
		$result[2] = array (
			'flag' => 'item/guestbook',
			'url' => url('item/guestbook/sid/'.$sid),
			'title'=> '留言',
		);
		$result[3] = array (
			'flag' => 'item/about',
			'url' => url('item/about/sid/'.$sid),
			'title'=> '详情',
		);
		return $result;
	}

	//手机首页导航
	function mobile_index_link() {
		$result[] = array (
			'flag' => 'item/category',
			'url' => url('item/mobile/do/category'),
			'title'=> '商铺分类',
			'icon' => 'categorys',
		);
		if(S('item:use_nearby')) {
			$result[] = array (
				'flag' => 'item/nearby',
				'url' => url('item/mobile/do/nearby'),
				'title'=> '附近商铺',
				'icon' => 'nearby',
			);
		}
		$result[] = array (
			'flag' => 'item/top',
			'url' => url('item/mobile/do/top'),
			'title'=> '排行榜',
			'icon' => 'tops',
		);
		$result[] = array (
			'flag' => 'item/album',
			'url' => url('item/mobile/do/album'),
			'title'=> '商铺相册',
			'icon' => 'album',
		);
		$result[] = array (
			'flag' => 'item/rand',
			'url' => url('item/mobile/do/rand'),
			'title'=> '随便逛逛',
			'icon' => 'rand',
		);
		return $result;
	}

	//手机助手导航
	function mobile_member_link() {
		$result[] = array (
			'flag' => 'item/member/follow',
			'url' => url('item/mobile/do/follow'),
			'title'=> '我的关注',
		);
		return $result;
	}

	//个人空间导航
	function space_nav_link($space) {
		$result = array();
		$result[] = array (
			'flag' => 'item/list',
			'url' => url("space/{$space->uid}/pr/item_follow"),
			'title'=> "主题",
		);
		$result[] = array (
			'flag' => 'item/pictures',
			'url' => url("space/{$space->uid}/pr/item_pictures"),
			'title'=> "图片",
		);
		return $result;
	}

	//主题管理导航
	function subject_manage_link($sid, $catid) {
	}

	//未被使用的二(三)级域名是否是主题二(三)级域名
	private function sldomain_item_route() {
		$modcfg = $this->loader->variable('config','item');
		if(!$modcfg['sldomain']) return false;
		if($modcfg['sldomain'] != 1 && $modcfg['sldomain'] != 3) return false;
		$domain = strtolower(_G('web','domain'));
		if(!$i = strpos($domain,'.')) return false;
		$eq_sldomain = substr($domain,$i+1);
		if($eq_sldomain != $modcfg['base_sldomain']) return;
		$slname = substr($domain,0,$i);
		if((!$_GET['m'] || $_GET['m'] == 'item') && !$_GET['act']) {
			$_GET['m'] = 'item';
			$_GET['act'] = 'detail';
			$_GET['name'] = $slname;
		}
		return TRUE;
	}

}
?>