<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class display {

    //参数 table
    function total($params) {
        if(!$params['table']) return 0;
        $table = 'dbpre_'.$params['table'];
        return _G('db')->from($table)->count();
    }

    //参数 aid,keyname
    function area($params) {
        extract($params);
        if(!$aid) return lang('global_global');
        if(!$keyname) $keyname = 'name';
        $loader =& _G('loader');
        $A =& $loader->model('area');
        $city_id = $A->get_parent_aid($aid, 1);
        if($city_id == $aid) {
            $area = $loader->variable('area');
        } else {
            $area = $loader->variable('area_' . $city_id,'',0);
        }
        if(!isset($area[$aid][$keyname])) return lang('global_global');
        if($parent && $area[$aid]['pid']) {
            return self::area(array('aid'=>$area[$aid]['pid']));
        }
        return $area[$aid][$keyname];
    }

	//参数 domain,city_id
	function cityurl($params) {
		extract($params);
        $loader =& _G('loader');
        $city_id = (int) $city_id;
        $citys = $loader->variable('area');
        $domain = $citys[$city_id]['domain'];
        if($domain && _G('url')->get_city_mod()) {
            if($forward) {
                $forward = str_replace('!', '/', rawurldecode($forward));
                return url("city:$city_id/$forward", '', TRUE);
            } else {
                return url("city:$city_id", '', TRUE);
            }
        } else {
            if(!$forward) 
                $forward = null;
            else
                $forward = str_replace('%2F', '%21', $forward);
		    return url("index/city/city_id/$city_id/forward/$forward", '', TRUE);
        }
	}

    //显示地图的静态图
    function map_static_image($params) {
        $params['size'] && list($width, $height) = explode('x', strtolower($params['size']));
        if(!$params['p']) $params['p'] = _G('city','mappoint');
        list($lng, $lat) = explode(',', $params['p']);

        !(int)$width && $width = 200;
        !(int)$height && $height = 200;
        $params['width'] = $width;
        $params['height'] = $height;
        $params['lng'] = $lng;
        $params['lat'] = $lat;

        if('baidu'==strtolower(S('mapflag'))) {
            _G('loader')->helper('baidumap');
            $url = BaiduMap::getStaticImage($params);
        } else {
            _G('loader')->helper('googlemap');
            $url = GoogleMap::getStaticImage($params);
        }
        return $url;
    }

    //地图坐标跳转到web
    function map_jump_web($params) {
        if(!$params['p']) $params['p'] = _G('city','mappoint');
        list($lng, $lat) = explode(',', $params['p']);
        $params['lng'] = $lng;
        $params['lat'] = $lat;
        if('baidu'==strtolower(S('mapflag'))) {
            _G('loader')->helper('baidumap');
            $url = BaiduMap::getjumpToWeb($params);
        } else {
            _G('loader')->helper('googlemap');
            $url = GoogleMap::getjumpToWeb($params);
        }
                    _G('loader')->helper('googlemap');
            $url = GoogleMap::getjumpToWeb($params);
        return $url;
    }

}
?>