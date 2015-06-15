<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'product');

if(($_GET['Pathinfo']||$_GET['Rewrite']) && $_GET['keyword'] && $_G['charset'] != 'utf-8' && $_G['cfg']['utf8url']) {
    $_GET['keyword'] = charset_convert($_GET['keyword'],'utf-8',$_G['charset']);
}

//商品搜索类
$search_obj = _G('loader')->model('product:search');

//查询参数设置
$search_obj->set_param('catid',     _get('catid', 0, MF_INT_KEY));
$search_obj->set_param('att',       _get('att', null, MF_TEXT));
$search_obj->set_param('orderby',   _get('orderby', 'sales', MF_TEXT));
$search_obj->set_param('sort',      strtolower(_get('sort', '', MF_TEXT)));
$search_obj->set_param('filter',    _get('filter', '', MF_INT));
$search_obj->set_param('keyword',   _get('keyword', '', MF_TEXT));
$search_obj->set_param('num',       _get('num', 0, MF_INT));
$search_obj->set_param('page',      $_GET['page']);

//默认值
$search_obj->default_num = 10;

//查询
if(!$search_obj->search()) {
    redirect($search_obj->error());
}

//赋值
$total = $search_obj->total();
$data = $search_obj->data();
$params = $search_obj->params();
$atts = $search_obj->atts();

$atturl = product_att_url();

if($total > 0) {
	$multipage = mobile_page($total, $params['num'], $params['page'], 
		url("product/mobile/do/list/catid/{$params['catid']}/att/$atturl/orderby/{$params['orderby']}/filter/{$params['filter']}/num/{$params['num']}/keyword/{$params['keyword']}/page/_PAGE_")
	);
}

if($_G['in_ajax']) {
	include mobile_template('product_list_loop');
	output();
}

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];

$header_title = $_HEAD['title'] = '商品列表';
include mobile_template('product_list');

function product_att_url($catid=null, $attid=null, $del=false) {
    global $atts;
    $myatts = $atts;
    if($catid) {
        if($del) {
            unset($myatts[$catid]);
        } else {
            $myatts[$catid] = $attid;
        }
    }
    $url = $split = '';
    if($myatts) foreach($myatts as $catid=>$attid) {
        if(!$catid || !is_numeric($catid)) continue;
        $url .= $split . $catid .'.'.$attid;
        $split = '_';
    }
    return $url;
}