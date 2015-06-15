<?php
/**
* 
*/
class item_location {

	function createHash($lng, $lat) {
		$geohash = new ms_geohash();
		$map_geohash = $geohash->encode($lat, $lng);
		return $map_geohash;
	}
	
	function getData($geohash, $len=5) {
		$loader = _G('loader');
		$db = _G('db');
		extract($params);

		$r = $db->from('dbpre_subject')
			->select('sid,map_lng,map_lat')
			->where('status', 1)
			->where_like('map_geohash', substr($geohash, 0, 5) . "%")
			->get();

		$result = array();
		while ($v = $r->fetch_array()) {
			if(isset($result[$dt])) $dt++;
			$result[$dt] = $v['sid'];
		}
		$r->free_result();
		return $result;
	}

	//通过2个经纬度坐标，计算相对距离（米）
    function getDistance($lat1, $lng1, $lat2, $lng2) {
        $R = 6378137;
        $radLat1 = deg2rad($lat1);
        $radLat2 = deg2rad($lat2);
        $radLng1 = deg2rad($lng1);
        $radLng2 = deg2rad($lng2);
        $s = acos(cos($radLat1)*cos($radLat2)*cos($radLng1-$radLng2)+sin($radLat1)*sin($radLat2))*$R;
        $s = round($s* 10000) / 10000;
        return round($s);
    }

    //获取一个友好的距离单位
	function showDistanceUnit($distance) {
		$distance = (int) $distance;
		if($distance < 1000) {
			for ($i = 1000; $i >= 0 ; $i = $i - 100) {
				if($i<=$distance) return lang('item_map_distance_lt') . ($i+100) . lang('item_map_distance_unit');
			}
		} elseif($distance < 5000) {
			for ($i = 5000; $i > 0 ; $i = $i - 500) { 
				if($i<$distance) return lang('item_map_distance_lt') . ($i+500) . lang('item_map_distance_unit');
			}
		} else {
			return lang('item_map_distance_gt') . "5000" . lang('item_map_distance_unit');
		}
	}
}
