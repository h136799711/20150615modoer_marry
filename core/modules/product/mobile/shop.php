<?php
/**
 * 商户产品页面
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2009 Moufersoft
 * @website www.modoer.com
 */
!defined('IN_MUDDER') && exit('Access Denied');

$sid = _get('sid',null,MF_INT); //主题ID

//取得主题信息
$S = _G('loader')->model('item:subject');
if(!$subject = $S->read($sid)) redirect('item_empty');

$search_obj = _G('loader')->model('product:search');
$search_obj->default_num = 10;
//查询参数设置
$search_obj->set_param('sid',     		$sid);
$search_obj->set_param('s_catid',     	_get('catid', 0, MF_INT_KEY));
$search_obj->set_param('sort',      	strtolower(_get('sort', '', MF_TEXT)));
$search_obj->set_param('orderby',   	_get('orderby', 'sales', MF_TEXT));
$search_obj->set_param('keyword',   	_get('keyword', '', MF_TEXT));
$search_obj->set_param('page',      	$_GET['page']);

//查询
if(!$search_obj->search()) {
    redirect($search_obj->error());
}

//赋值
$total = $search_obj->total();
$data = $search_obj->data();
$params = $search_obj->params();

if($total > 0) {
	$multipage = mobile_page($total, $params['num'], $params['page'], 
		url("product/mobile/do/shop/sid/{$params['sid']}/catid/{$params['s_catid']}/sort/{$params['sort']}/orderby/{$params['orderby']}/keyword/{$params['keyword']}/page/_PAGE_")
	);
}

if($_G['in_ajax']) {
	include mobile_template('product_list_loop');
	output();
}

$_HEAD['title'] = $subject['name'].'_商品列表';
$header_title = '商品列表';
include mobile_template('product_shop');