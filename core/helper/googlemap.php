<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
/**
* Google地图API接口功能封装
*/
class GoogleMap {

	//获取一个静态地图的链接地址
	public static function getStaticImage($params) {
		extract($params);
        $url = 'https://maps.googleapis.com/maps/api/staticmap?';
        $urlpa['center'] = "$lat,$lng";
        $urlpa['size'] = "{$width}x{$height}";
        $urlpa['zoom'] = S('map_view_level');
        $urlpa['scale'] = $scale ? $scale : 1;
        $urlpa['sensor'] = $sensor ? 'true' : 'false';
        if($show) {
            $urlpa['markers'] = "$lat,$lng";
        }
        foreach ($urlpa as $key => $value) {
            $url .= $key .'='. urlencode($value) .'&';
        }
        $url = trim($url,'&');
        return $url;
	}

	//坐标跳转到google地图
	public static function getjumpToWeb($params) {
        extract($params);
        $url = 'https://ditu.google.cn/maps?';
        $urlpa = array();
        $urlpa['ll'] = "$lat,$lng";
        $urlpa['z'] = S('map_view_level');
        $urlpa['t'] = 'm';
        if($show) {
            $urlpa['q'] = "$lat,$lng";
            $urlpa['iwloc'] = "A";
        }
        $urlpa['output'] = 'classic';
        $urlpa['dg'] = 'brw';
        foreach ($urlpa as $key => $value) {
            $url .= $key .'='. urlencode($value) .'&';
        }
        $url = trim($url,'&');
        return $url;
	}

    //通过GPS坐标获取地址信息
    public static function gps2address($lat, $lng, $only_city=false) {
        return false;
    }
}
/* end */