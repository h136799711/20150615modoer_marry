<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$catid = isset($_GET['catid']) ? (int)$_GET['catid'] : (int)$MOD['pid'];
!$catid and location(url('item/mobile/do/category'));
$op = _input('op');

$subcatid = _get('subcatid', 0, MF_INT_KEY);
if($subcatid>0) {
    $catid = $subcatid;
}

//实例化主题类
$I =& $_G['loader']->model('item:subject');
$I->get_category($catid);
if(!$pid = $I->category['catid']) {
    location(url('item/mobile/do/category'));
}

//载入配置信息
$catcfg =& $I->category['config'];
$modelid = $I->category['modelid'];
$rogid = $I->category['review_opt_gid'];

//载入模型
$model = $I->variable('model_' . $modelid);
//载入点评选项
$reviewpot = $_G['loader']->variable('opt_' . $rogid, 'review');
//点评配置
$reviewcfg = $_G['loader']->variable('config', 'review');
//分类分级变量1主2子
$category = $_G['loader']->variable('category_' . $pid, 'item');
//判断子分类是否禁用
if(!$category[$catid]['enabled']) redirect('item_cat_disabled');
//当前catid的级别
$category_level = $category[$catid]['level'];
$subcats = $category[$catid]['subcats'];
$urlpath = array();
$urlpath[] = $category[$pid]['name'];
if($catid != $pid) {
    if($category_level > 2) {
        $urlpath[] = $category[$category[$catid]['pid']]['name'];
    }
    $urlpath[] = $category[$catid]['name'];
    $attcats = $category[$catid]['config']['attcat'];
} else {
    $attcats = $catcfg['attcat'];
}

//筛选
$where = array();
//属性组处理
$atts = array();
//分类处理
$atts['category'] = $I->get_attid($catid);
//使用了地图功能
if($model['usearea']) {
    $aid = _get('aid', 0, MF_INT_KEY);

    $subaid = _get('subaid', 0, MF_INT_KEY);
    if($subaid > 0) {
        $aid = $subaid;
    }

    //载入地区
    $area = $_G['loader']->variable('area_'.$_CITY['aid'],null,false);
    //地区级别
    $area_level = $area[$aid]['level'];

    if($area_level == 2) {
        $paid = 0;
    } else {
        $paid = $area[$aid]['pid'];
    }

    if($paid) {
        $urlpath[] = $area[$paid]['name'];
    }
    if($paid != $aid) {
        $urlpath[] = $area[$aid]['name'];
    }

    $boroughs = $streets = '';
    if($area) foreach($area as $key => $val) {
        if($val['level'] == 2) $boroughs[$key] = $val['name'];
        if($val['level'] == 3 && ($aid==$val['pid']||$paid==$val['pid'])) $streets[$key] = $val['name'];
    }

    $area =& $_G['loader']->model('area');
    if($aid) {
        $area_attid = $area->get_attid($aid);
    } else {
        $area_attid = $area->get_attid($_CITY['aid']);
    }
    if($area_attid) $atts['area'] = $area_attid;
}
if($att = _get('att',null,'_T')) {
    if(!preg_match("/^[0-9\.\_]+$/i", $att)) {
        $att = $_GET['att'] = '';
    } else {
        $att = explode('_', $att);
        foreach($att as $att_v) {
            list($att_catid, $att_id) = explode('.', $att_v);
            if(!$att_catid || !$att_id) continue;
            $atts[$att_catid] = $att_id;
            $_attlist = $_G['loader']->variable('att_list_'.$att_catid, 'item', false);
            $seo_tags['att_name_' . $att_catid] = $_attlist[$att_id]['name'];
            $urlpath[] = $_attlist[$att_id]['name'];
        }
    }
}
if($atts) $where['attid'] = $atts;

// 排序数组
$orderlist = lang('item_list_orderlist');

//查询的数组
$order_arr  = array(
    'finer' => array('finer'=>'DESC'),
    'avgsort' => array('avgsort'=>'DESC'),
    'reviews' => array('reviews'=>'DESC'),
    'enjoy' => array('best'=>'DESC'),
    'price' => array('avgprice'=>'DESC'),
    'price_s' => array('avgprice'=>'ASC'),
    'picture' => array('pictures'=>'DESC'),
    'picture_s' => array('pictures'=>'ASC'),
    'addtime' => array('addtime'=>'DESC'),
    'pageviews' => array('pageviews'=>'DESC'),
);

if($catcfg['useprice']) {
    $orderlist['price'] = $catcfg['useprice_title'];
}
$orderby = _get('orderby',$catcfg['listorder'],'_T');
if(!$orderby || !isset($order_arr[$orderby])) {
    $orderby = $catcfg['listorder'];
}

//进入筛选页面
if ($op == 'filter') {
    include mobile_template('item_list_filter');
    exit;
}

$fields = $I->variable('field_' . $modelid);
$select = 's.sid,pid,catid,domain,name,avgsort,sort1,sort2,sort3,sort4,sort5,sort6,sort7,sort8,best,finer,pageviews,reviews,
    pictures,favorites,thumb';
if($model['usearea']) {
    if(false == strpos($select, 'aid')) $select .= ',aid';
    if(false == strpos($select, 'map_lat')) $select .= ',map_lat';
    if(false == strpos($select, 'map_lng')) $select .= ',map_lng';
}
if($catcfg['useprice']) {
    if(false == strpos($select, 'avgprice')) $select .= ',avgprice';
}
$select_arr = explode(',', $select);
$custom_field = array();
foreach($fields as $val) {
    if(!$val['show_list']) continue;
    $ver_field = array('mappoint');
    if(!in_array($val['fieldname'], $select_arr) && !in_array($val['fieldname'], $ver_field)) {
        $select .= ',' . $val['fieldname'];
    }
    if(!in_array($val['fieldname'], array('name','subname','mappoint','owner','status','templateid','listorder'))) {
        $custom_field[] = $val;
    }
}
unset($select_arr, $val);

$num = 10;
$offset = $num;
$start = get_start($_GET['page'], $num);

list($total, $list) = $I->getlist($pid, $select, $where, $order_arr[$orderby], $start, $num, true);

if($total) {
    $atturl = item_att_url();
    $multipage = mobile_page($total, $num, $_GET['page'],
        url("item/mobile/do/list/catid/$catid/aid/$aid/order/$order/att/$atturl/num/$num/total/$total/page/_PAGE_"));
}

//显示模版
if($_G['in_ajax']) {
    include mobile_template('item_list_li');
    output();
}

$title = implode("&lt;",$urlpath);
$header_title = '商户列表';
$header_forward = url("item/mobile/do/category");
include mobile_template('item_list');

function item_att_url($catid=null, $attid=null, $del=false) {
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