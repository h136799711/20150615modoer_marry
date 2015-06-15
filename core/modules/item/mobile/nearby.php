<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$city_id = _get('city_id', null, MF_INT_KEY);
if($city_id>0) {
	$citys = $_G['loader']->variable('area');
    if(!$city = $citys[$city_id]) redirect('global_area_city_id_invalid');
    if(!$city['enabled']) redirect('global_area_city_disabled');
    init_city($city_id);
}

$catid = _input('catid', (int)$MOD['pid'], MF_INT_KEY);
!$catid and location(url('item/mobile/do/category/p/location'));

$map_lat = (float)$_C['gps_lat'];
$map_lng = (float)$_C['gps_lng'];

//$map_lat = 29.8768465525;
//$map_lng = 121.587340021;

$lat = _input('lat', 0, MF_FLOAT);
$lng = _input('lng', 0, MF_FLOAT);

$distances = array(500, 1000, 2000, 3000, 4000, 5000);
$max_distance = $distances[count($distances) - 1];
$distance = _input('distance', 5000, MF_INT_KEY);
if($distance > $max_distance) $distance = $max_distance;

$page = _input('page', 0, MF_INT_KEY);
if($page < 1) $page = 1;
$offset = 10;

$_G['loader']->helper('location','item');

if($_G['in_ajax']) {

	if(!$lat || !$lng) {
		redirect('无效的定位坐标。');
	}

	if('baidu' == strtolower(S('mapflag'))) {
		$_G['loader']->helper('baidumap');
		$bd_lnglat = BaiduMap::gps2baidu($lat, $lng);
		if(!$bd_lnglat) {
			redirect('对不起，定位坐标转换出错。');
		}
		$lat = $bd_lnglat['lat'];
		$lng = $bd_lnglat['lng'];
	}

	//SELECT sid,name,map_lng,map_lat,ModoerGetDistance(121.56515,29.87731,map_lng,map_lat) as distance 
	//FROM `modoer_subject` WHERE city_id=1 AND `map_lng`!=0 ORDER BY `city_id` ASC LIMIT 0,20
	//SQL错误捕获
	$_G['db']->catch = TRUE;
	$start = get_start($page, $offset);
	$list = $_G['db']->from('dbpre_subject')
		->select('sid,name,subname,map_lng,map_lat,thumb,avgprice,reviews,favorites')
		->select_param('ModoerGetDistance(%s,%s,map_lng,map_lat) AS distance', array($lng, $lat))
		->where('status', 1)
		->where('city_id', $_CITY['aid'])
		->where('pid', $catid)
		->where_not_equal('map_lng', 0)
		->order_by('distance')
		->limit($start, $offset)
		->get();
	if($error = $_G['db']->error()) {
		if(strpos($error, 'ModoerGetDistance')!==FALSE) {
			redirect("对不起，网站未启用定位查询功能。");
		} else {
			redirect("对不起，系统错误！\n" . $error);
		}
    }
	if($list && $list->num_rows() > 0) {
		$tplname = 'item_list_li';
	} else {
		echo 'EMPTY';
		output();
	}
	
} else {
	$tplname = 'item_nearby';
}

include mobile_template($tplname);