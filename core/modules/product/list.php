<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'product');

if($_GET['sid']) location(U("product/shop/sid/{$_GET['sid']}"));

if(($_GET['Pathinfo']||$_GET['Rewrite']) && $_GET['keyword'] && $_G['charset'] != 'utf-8' && $_G['cfg']['utf8url']) {
    $_GET['keyword'] = charset_convert($_GET['keyword'],'utf-8',$_G['charset']);
}

//商品搜索类
$search_obj = _G('loader')->model('product:search');

//查询参数设置
$catid = _get('catid', 0, MF_INT_KEY);
if(!$catid) redirect('对不起，您未指定商品分类参数。');
$search_obj->set_param('catid',     _get('catid', 0, MF_INT_KEY));
$search_obj->set_param('att',       _get('att', null, MF_TEXT));
$search_obj->set_param('orderby',   _get('orderby', 'sales', MF_TEXT));
$search_obj->set_param('sort',      strtolower(_get('sort', '', MF_TEXT)));
$search_obj->set_param('filter',    _get('filter', '', MF_INT));
$search_obj->set_param('keyword',   _get('keyword', '', MF_TEXT));
$search_obj->set_param('num',       _get('num', 0, MF_INT));
$search_obj->set_param('page',      $_GET['page']);

//默认值
$search_obj->default_num = 24;

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

$pid = $params['pid'];
$catid = $params['catid'];
$category = _G('loader')->variable('gcategory_'.$pid, 'product');

$w_gcatid = $search_obj->w_gcatid;
$atts = $search_obj->atts();
$attcats = $search_obj->attcats();

if($total > 0) {
    $multipage = multi($total, $params['num'], $params['page'], 
        url("product/list/catid/$catid/att/$atturl/orderby/$orderby/filter/$filter/num/$num/keyword/$keyword/page/_PAGE_")
    );
}

/*
if($catid != $pid) {
    if($level>2) {
        $w_gcatid = "gcatid='$catid'";
    } else {
        $w_gcatid = "gcatid IN (".implode(',', $where['p.gcatid']).")";
    }
} else {
    $w_gcatid = "pgcatid='$pid'";
}
*/

$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("product/index"));


$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];
include template('product_list');
exit;
$catid = _get('catid', 0, MF_INT_KEY);
$att = _get('att', null, MF_TEXT);
$keyword = _get('keyword', '', MF_TEXT);
$orderby = _get('orderby', 'sales', MF_TEXT);
$sort = strtolower(_get('sort', '', MF_TEXT));
$filter = _get('filter', '', MF_INT);
$num = _get('num', 0, MF_INT);

$P =& $_G['loader']->model(':product');
$category = $_G['loader']->variable('gcategory','product');

$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("product/index"));

$where = array();
$where['p.status'] = 1;
$where['p.is_on_sale'] = 1;

if(!$pid = $P->get_pid($catid)) {
    redirect('product_cat_empty');
}

//分类分级变量1主2子
$category = $_G['loader']->variable('gcategory_' . $pid, 'product');
//判断子分类是否禁用
if(!$category[$catid]['enabled']) redirect('product_cat_disabled');
//当前catid的级别
$category_level = $category[$catid]['level'];
$subcats = $category[$catid]['subcats'];
$urlpath[] = url_path($category[$pid]['name'], url("product/list/catid/$pid"));
$w_gcatid = "";
if($catid != $pid) {
    if($category_level > 2) {
        $urlpath[] = url_path($category[$category[$catid]['pid']]['name'], url("product/list/catid/{$category[$catid]['pid']}"));
        $attcats = explode(',', trim($category[$catid]['attcat'], ','));
        vp($attcats);
        $where['p.gcatid'] = $catid;
        $w_gcatid = "gcatid='$catid'";
    }else{
    	$attcats = explode(',', trim($category[$catid]['attcat'], ','));
    	$where['p.gcatid'] = $subcats? array_merge(array($catid),explode(',',trim($subcats,','))) : array($catid);
        $w_gcatid = "gcatid IN (".implode(',', $where['p.gcatid']).")";
    }
    if($attcats) {
        foreach ($attcats as $key => $value) {
            if(preg_match("/^[0-9]+\|[0-9]{1}$/", $value)) {
                list($_catid,$is_multi) = explode('|', $value);
                $attcats[$key]=$_catid;
            } elseif(is_numeric($value)) {
                $attcats[$key]=$value;
            }
        }
    }
    $urlpath[] = url_path($category[$catid]['name'], url("product/list/catid/$catid"));
} else {
	$catelist = $_G['loader']->variable('gcategory_'.$pid,'product');
    $ids = array();
    foreach($catelist as $key=>$val) {
        $ids[] = $key;
    }
    $where['p.pgcatid'] = $pid;
    $w_gcatid = "pgcatid='$pid'";
}
//城市
$where['p.city_id'] = array(0, (int)$_CITY['aid']);

//属性组处理
$atts = array();
if($att) {
    $att = explode('_', $att);
    foreach($att as $att_v) {
        list($att_catid, $att_id) = explode('.', $att_v);
        if(!$att_catid || !$att_id) continue;
        $atts[$att_catid] = $att_id;
    }
}
$atturl = product_att_url();

if(($_GET['Pathinfo']||$_GET['Rewrite']) && $_GET['keyword'] && $_G['charset'] != 'utf-8' && $_G['cfg']['utf8url']) {
    $_GET['keyword'] = charset_convert($_GET['keyword'],'utf-8',$_G['charset']);
}

if($keyword) {
	$where['subject'] = array('where_like',array("%$keyword%"));
	$urlpath[] = url_path($keyword, url("product/list/catid/$catid/keyword/$keyword"));
}

$orderby_arr=array('sales','price','comments','last_update');
if(!in_array($orderby,$orderby_arr)) $orderby = $orderby_arr[0];

$sort == 'asc' ? $listorder = 'ASC' : $listorder = 'DESC';
$order_by = $orderby ? $orderby : 'sales';

if($filter) $where['p.promote'] = array('where_more',array("0.01"));

$num > 0 ? $offset = $num : $offset = 24;
$start = get_start($_GET['page'], $offset);

list($total, $list) = $P->find('p.*',$where,array('p.'.$order_by => $listorder), $start, $offset, TRUE, 's.name,s.subname', $atts);
if($total) $multipage = multi($total, $offset, $_GET['page'], url("product/list/catid/$catid/att/$atturl/orderby/$orderby/filter/$filter/num/$num/keyword/$keyword/page/_PAGE_"));

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];

include template('product_list');

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
    foreach($myatts as $catid=>$attid) {
        if(!$catid || !is_numeric($catid)) continue;
        $url .= $split . $catid .'.'.$attid;
        $split = '_';
    }
    return $url;
}


/** end **/