<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'product');

$P =& $_G['loader']->model(':product');

$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("product/index"));

$where = array();
$where['p.status'] = 1;
$where['p.is_on_sale'] = 1;
$where['p.city_id'] = array(0,$_CITY['aid']);

$filter = _get('filter', NULL, 'intval');
if($filter) $where['p.promote'] = array('where_more',array("0.01"));

$keyword = _get('keyword','','_T');
$tag = _get('tag','','_T');
if($keyword) {
    if(preg_match("/^[EAN|条形码]+[:|：]+([0-9]{8,13})$/i",$keyword,$match)) {
        //条形码搜索
        $where['shape_code'] = $match[1];
        $urlpath[] = url_path("条形码：".$match[1], url("product/search/keyword/$keyword"));
    } else {
        $where['subject'] = array('where_like',array("%$keyword%",'and','('));
        $where['tag_keyword'] = array('where_like',array("%|$keyword|%",'or',")"));
        $urlpath[] = url_path("搜索：".$keyword, url("product/search/keyword/$keyword"));
    }
    $title = "产品搜索：$keyword";
    $params = "keyword/$keyword";
} elseif($tag) {
    $tag = _get('tag','','_T');
    if($tag) {
        $where['tag_keyword'] = array('where_like',array("%|$tag|%"));
        $urlpath[] = url_path("标签：".$tag, url("product/search/tag/$tag"));
    }
    $title = "产品标签：$tag";
    $params = "tag/$tag";
}

$orderby_arr=array('sales','price','comments','last_update');
$orderby = _get('orderby', 'sales', MF_TEXT);
if(!in_array($orderby,$orderby_arr)) $orderby = $orderby_arr[0];

$sort = _get('sort','DESC','_T');
'ASC' != $sort && $sort = 'DESC';
$sort ? $listorder = $sort : $listorder = 'DESC';
$order_by = $orderby ? $orderby : 'sales';

$orderby_arr = array('sales','price','comments','last_update');
$orderby = _get('orderby','sales',MF_TEXT);
if(!in_array($orderby,$orderby_arr))$orderby=$orderby_arr[0];


$num = _get('num', NULL, 'intval');
$num ? $offset = $num : $offset = 24;
$start = get_start($_GET['page'], $offset);

list($total, $list) = $P->find('p.*',$where,array('p.'.$order_by=>'$listorder'),$start,$offset,TRUE,'s.name,s.subname',$atts);
if($total) $multipage = multi($total, $offset, $_GET['page'], url("product/tag/orderby/$orderby/filter/$filter/num/$num/$params/page/_PAGE_"));

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];

include template('product_search');
?>