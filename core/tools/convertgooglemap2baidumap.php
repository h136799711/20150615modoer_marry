<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2011 Moufersoft
* @website www.modoer.com
*/
_G('loader')->model('tool',FALSE);
class msm_tool_convertgooglemap2baidumap extends msm_tool {

    protected $name = '谷歌地图转换百度地图';
    protected $descrption = '主题内部的谷歌地图坐标转换成百度地图坐标。<span class="font_1">注意转换操作只能执行一次，多次转换会造成地图坐标失效。</span>';
    protected $acttype = 'other';

    private $files = array();
    private $basedir = '';

    private $ak = '';
    private $lockfile = 'convertgooglemap2baidumap.lock';

    public function create_form() {
        $this->loader->helper('form');
        $elements = array();
        $elements[] = 
            array(
            'title' => '警告'.$this->ak,
            'content' => '<b>!!!</b><span class="font_1">坐标转换操作只需要执行一次，多次转换会造成地图坐标失效。
            建议执行完毕后，删除本脚本文件，本文件的存放位置在 core/tools/convertgooglemap2baidumap.php</span>',
        );
        $elements[] = 
            array(
            'title' => '提示',
            'content' => '
            <input type="hidden" name="offset" value="50" />
            <input type="hidden" name="type" value="subject" />    
            坐标转换需使用百度API服务，请确认您已经再后台地图API设置了2.0版本的百度地图和ak密钥，同事请确保您的服务器打开PHP的curl扩展，并支持服务器访问外网。',
        );
        $elements[] = 
            array(
            'title' => '建议',
            'content' => '执行操作前备份下数据库，以防止中途出现错误造成只处理一部分的问题。',
        );
        return $elements;
    }

    public function run() {
        if(is_file(MUDDER_DATA.$this->lockfile)) {
            redirect('对不起，您之前执行过本脚本，不能再次执行，如果您确实需要执行，请在网站空间内删除文件【'
                .str_replace(MUDDER_ROOT,DS,MUDDER_DATA).$this->lockfile.'】后，再执行本脚本。');
            exit;
        }
        $this->loader->helper('baidumap');
        $this->ak = BaiduMap::getApiKey();
        if(!$this->ak) redirect('对不起，未在百度地图api地址里找到ak密钥。');
        if(_get('type')=='subject') {
            $ret = $this->convert_subject();
            if($ret) {
                $this->params['type'] = 'area';
                $this->message = '正在转换地图坐标[1/2]...完成.';
            }
        } else {
            $ret = $this->cunvert_area();
            if($ret) $this->completed = true;
        }
        if($this->completed) {
            //已执行标记
            @file_put_contents(MUDDER_DATA.$this->lockfile, _G('timestamp'));
        }
    }

    private function convert_subject() {
        $offset = _get('offset', 50, MF_INT_KEY);
        $start = _get('start', 0, MF_INT_KEY);
        //$count = _G('db')->from('dbpre_subject')->where('map_lng', 1)->where('map_geohash', '')->count();
        $list = _G('db')->from('dbpre_subject')
            ->select('sid,map_lng,map_lat')
            ->where_not_equal('map_lng', 0)
            //->where('map_geohash', '')
            ->order_by('sid','ASC')
            ->limit($start, $offset)
            ->get();
        if(!$list) {
            return  true;
        } else {
            $sids = array();
            while ($val=$list->fetch_array()) {
                $sids[] = $val['sid'];
                $Points[] = array(
                    'lng' => $val['map_lng'],
                    'lat' => $val['map_lat'],
                );
            }
            $list->free_result();
            if($Points) {
                $bdpoints = $this->getPoints($Points); //获取百度地图坐标
                foreach ($bdpoints as $key => $point) {
                    $sid = $sids[$key];
                    $this->setSubjectPoint($sid, $point['lng'], $point['lat']); //更新数据库
                }
            }
            $this->params['start'] = $start + $offset;
            $this->params['offset'] = $offset;
            $this->params['type'] = 'subject';
            $this->message = '正在转换地图坐标[1/2]...'.($start).'-'.($this->params['start']);
        }
    }

    private function cunvert_area() {
        //mappoint
        $offset = _get('offset', 50, MF_INT_KEY); //一次转换50条
        $start = _get('start', 0, MF_INT_KEY);
        //$count = _G('db')->from('dbpre_area')->where_not_equal('mappoint', '')->count();
        $list = _G('db')->from('dbpre_area')
            ->select('aid,mappoint')
            ->where_not_equal('mappoint', '')
            ->order_by('aid','ASC')
            ->limit($start, $offset)
            ->get();
        if(!$list) {
            return true;
        } else {
            $sids = array();
            while ($val = $list->fetch_array()) {
                $aids[] = $val['aid'];
                list($lng, $lat) = explode(',', $val['mappoint']); //解析字符串成为经纬度
                //$lng = get_numeric(trim($lng)); //经度
                //$lat = get_numeric(trim($lat)); //纬度
                // if($lng < 1000) {
                //     $lng = substr(str_replace('.','',$lng),0,7);
                // }
                // if($lat < 1000) {
                //     $lat = substr(str_replace('.','',$lat),0,6);
                // }
                $Points[] = array(
                    'lng' => $lng,
                    'lat' => $lat,
                );
            }
            $list->free_result();
            if($Points) {
                $bdpoints = $this->getPoints($Points);
                foreach ($bdpoints as $key => $point) {
                    $aid = $aids[$key];
                    $this->setAreaPoint($aid, $point['lng'], $point['lat']);
                }
            }
            $this->params['start'] = $start + $offset;
            $this->params['offset'] = $offset;
            $this->params['type'] = 'area';
            $this->message = '正在转换地图坐标[2/2]...'.($start).'-'.($this->params['start']);
        }
    }

    private function getPoints($map_points) {
        $coords = '';
        foreach ($map_points as $point) {
            $coords .= ";{$point['lng']},{$point['lat']}";
        }
        $coords = trim($coords,';');
        $from = 3; // 3：google地图、soso地图、aliyun地图、mapabc地图和amap地图所用坐标
        $to = 5; // 5: baidu地图坐标
        $apiurl = "http://api.map.baidu.com/geoconv/v1/?coords=$coords&from=$from&to=5&ak=$this->ak&output=json";
        $data = http_get($apiurl);
        if($data) $data = json_decode($data, true);
        $result = array();
        if($data['status'] === 0&&is_array($data['result'])) {
            foreach ($data['result'] as $i => $bdpoint) {
                $lng = $bdpoint['x'];
                $lat = $bdpoint['y'];
                $result[] = array('lng' => $lng, 'lat' => $lat);
            }
            return $result;
        } elseif($data['status'] > 0) {
            redirect('百度坐标转换API参数错误。');
        } else {
            redirect('未知错误。');
        }

        /*
        1   内部错误
        21  from非法
        22  to非法
        24  coords格式非法
        25  coords个数非法，超过限制
        */
    }

    private function setSubjectPoint($sid, $lng, $lat) {
        if($lng && $lat) {
            $this->loader->helper('location','item');
            $map_geohash = item_location::createHash($lng, $lat);
        } else {
            $map_geohash = '';
        }
        //vp("$sid,$lng,$lat,$map_geohash");return;
        _G('db')->from('dbpre_subject')
            ->set('map_lng', $lng)
            ->set('map_lat', $lat)
            ->set('map_geohash', $map_geohash)
            ->where('sid', $sid)
            ->update();
    }

    private function setAreaPoint($aid, $lng, $lat) {
        $mappoint = substr($lng, 0, 16) . ",". substr($lat, 0, 16);
        //vp($aid,$mappoint);return;
        _G('db')->from('dbpre_area')
            ->set('mappoint', $mappoint)
            ->where('aid', $aid)
            ->update();
    }
}
/* end */