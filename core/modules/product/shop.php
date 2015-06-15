<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2012 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'product');

if(($_GET['Pathinfo']||$_GET['Rewrite']) && $_GET['keyword'] && $_G['charset'] != 'utf-8' && $_G['cfg']['utf8url']) {
    $_GET['keyword'] = charset_convert($_GET['keyword'],'utf-8',$_G['charset']);
}

$P =& $_G['loader']->model('product:product');
$sid = _get('sid',null,MF_INT); //主题ID
$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("product/index"));

//取得主题信息
$I =& $_G['loader']->model('item:subject');
if(!$subject = $I->read($sid)) redirect('item_empty');
$subject_field_table_tr = $I->display_sidefield($subject);

$M =& $_G['loader']->model('member:member');
$ownerid = $M->read($subject['owner'], TRUE);

//其他模块和功能的链接
$links = $_G['hook']->hook('subject_detail_link',$subject,TRUE);
define('SUB_NAVSCRIPT','product');
$urlpath[] = url_path($subject['name'].$subject['subname'], url("item/detail/id/$sid"));


$catid = _get('catid', null, MF_INT_KEY);
$keyword = _get('keyword', '', '_T');

$where = array();
if($catid) $where['catid'] = $catid;
if($keyword) {
	$where['subject'] = array('where_like',array("%$keyword%"));
	$urlpath[] = url_path($keyword, url("product/shop/sid/$sid/keyword/$keyword"));
}

$offset = 16;
$start = get_start($_GET['page'],$offset);
$where['sid'] = $sid;
$where['status'] = 1;
$where['is_on_sale'] = 1;

list($total, $list) = $P->find('*',$where,array($order_by=>'$listorder'),$start,$offset,TRUE);
if($total) $multipage = multi($total, $offset, $_GET['page'], url("product/shop/sid/$sid/keyword/$keyword/page/_PAGE_"));

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];

if($subject) {
    $scategory = $I->get_category($subject['catid']);
    if(!$subject['templateid'] && $scategory['config']['templateid']>0) {
        $subject['templateid'] = $scategory['config']['templateid'];
    }
}
if($subject['templateid']) {
    include template('product_list', 'item', $subject['templateid']);
} else {
    include template('product_shop');
}
?>