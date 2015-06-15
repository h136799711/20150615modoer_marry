<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
/**
* 百度地图API接口功能封装
*/
class BaiduMap {

	//获取mapapi里的ak参数
	public static function getApiKey() {
		static $ak = 'null';
		if($ak=='null') {
			$ak = '';
			$urls = parse_url(S('mapapi'));
			$params = explode('&',$urls['query']);
			foreach ($params as $param) {
				list($key,$value) = explode('=',trim($param));
				if(strtolower($key) == 'ak') $ak = trim($value);
			}
		}
		return $ak;
	}

	//gps坐标转换成百度坐标
	public static function gps2baidu($lat, $lng) {
		$coords = "$lng,$lat";
		$data = self::bdlatlngCache($lat, $lng);
		if($data) return $data;

		$ak = self::getApiKey();
		if(!$ak) return null;
		$apiurl = "http://api.map.baidu.com/geoconv/v1/?coords=$coords&from=1&to=5&ak=$ak&output=json";
		$data = http_get($apiurl);
		if($data) $data = json_decode($data,true);
		if($data['status'] === 0 && is_array($data['result'])) {
			$bd_latlng = array('lat'=>$data['result'][0]['y'],'lng'=>$data['result'][0]['x']);
			set_cookie('bd_lat', $lat.'='.$bd_latlng['lat']);
			set_cookie('bd_lng', $lng.'='.$bd_latlng['lng']);
			return $bd_latlng;
		}
		return null;
	}

	//gps坐标是否在本机cookies缓存（可省去连接百度地图api）
	public static function bdlatlngCache($lat, $lng) {
		global $_C;
		if($_C['bd_lat'] && $_C['bd_lng']) {
			list($gps_lat, $bd_lat) = explode('=', $_C['bd_lat']);
			list($gps_lng, $bd_lng) = explode('=', $_C['bd_lng']);
			if(substr($lat, 0, 6) == substr($gps_lat, 0, 6) && substr($lng, 0, 6) == substr($gps_lng, 0, 6)) {
				return array('lat'=>$bd_lat,'lng'=>$bd_lng);
			}
		}
		return null;
	}

	//获取一个静态地图的链接地址
	public static function getStaticImage($params) {
		extract($params);
		$url = 'http://api.map.baidu.com/staticimage?';
        $urlpa = array();
        $urlpa['center'] = "$lng,$lat";
        $urlpa['zoom'] = S('map_view_level');
        $urlpa['width'] = $width;
        $urlpa['height'] = $height;
        if($show) {
            $urlpa['markers'] = "$lng,$lat";
            $urlpa['markerStyles'] = 'm';
        }
        $urlpa['scale'] = $scale;
        $urlpa['copyright'] = 1;
        foreach ($urlpa as $key => $value) {
            $url .= $key .'='. urlencode($value) .'&';
        }
        $url = trim($url,'&');
        return $url;
	}

	//坐标跳转到百度地图
	public static function getjumpToWeb($params) {
		extract($params);
		$url = "http://api.map.baidu.com/marker?";
		$urlpa = array();
		$urlpa['location'] = "$lat,$lng";
        $urlpa['title'] = $title;
        if($content) $urlpa['content'] = $content;
        $urlpa['output'] = "html";
        //$urlpa['src'] = "yourComponyName|yourAppName";
        foreach ($urlpa as $key => $value) {
            $url .= $key .'='. urlencode($value) .'&';
        }
        $url = trim($url,'&');
        return $url;
	}

	//通过GPS坐标获取地址信息
	public static function gps2address($lat, $lng, $only_city=false) {
		$ak = self::getApiKey();
		if(!$ak) return null;
		//coordtype：
		//bd09ll （百度经纬度坐标）
		//gcj02ll （国测局经纬度坐标）
		//wgs84ll （GPS经纬度）
		$coordtype = 'wgs84ll';
		$location = "$lat,$lng";
		$ret_type = 'json';
		$pois = 0;
		$apiurl = "http://api.map.baidu.com/geocoder/v2/?ak={$ak}&coordtype=$coordtype&location=$location&output=$ret_type&pois=$pois";
		$data = http_get($apiurl);
		if($data) $data = json_decode($data, true);
		if($data['status']=='0') {
			if(!$only_city) return $data['city'];
			return $data;
		}
		return;
	}

}
/* end */