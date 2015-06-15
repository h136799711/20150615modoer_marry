<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2012 Moufersoft
* @website www.modoer.com
*/
_G('loader')->model('tool', FALSE);
class msm_tool_downsubjectimg extends msm_tool {

    protected $name = '下载主题简介内的远程图片';
    protected $descrption = '下载主题模块内主题简介内容内的远程图片到系统所在服务器内。';
    protected $acttype = 'other';

    private $modelid = 0;
    private $days_ago = 0;
    private $count = 0;

    private $max_count = 0;
    private $db = null;

    private $d_total = 0;
    private $d_succed = 0;
    private $d_lost = 0;

    public function create_form() {
        $this->loader->helper('form');
        $this->loader->helper('form','item');
        $elements = array();
        $elements[] = 
            array(
            'title' => '主题模型',
            'des' => '',
            'content' => '<select name="modelid"><option value="0">=全部=</option>'.form_item_models().'</select>',
        );
        $elements[] = 
            array(
            'title' => '主题发布的时间范围',
            'des' => '不限制时间请输入 0',
            'content' => form_input('days_ago', '0', 'txtbox4'),
        );
        return $elements;
    }

    public function run() {
        $this->db =& _G('db');
        $this->modelid = _get('modelid', 0, MF_INT_KEY);
        $this->days_ago = _get('days_ago', 0, MF_INT);
        $this->count = _get('count', 0, MF_INT);
        $this->_run();
    }

    private function get_table() {
        $this->db->from('dbpre_model');
        if($this->modelid>0) $this->db->where('modelid',$this->modelid);
        $this->db->order_by('modelid','ASC');
        $q = $this->db->get();
        if(!$q) return false;
        if($modelid > 0) return $q->fetch_array();
        $models[] = array();
        $i=0;
        while ($val = $q->fetch_array()) {
            $models[$i] = $val;
            $i++;
        }
        $q->free_result();
        $this->max_count = count($models);
        return $models[$this->count];
    }

    private function _run() {
        $model = $this->get_table();
        if(!$model) {
            $this->completed = true;
            return;
        }
        $offset = 1;
        $start = _get('start', 0, MF_INT);
        $table = 'dbpre_' . $model['tablename'];
        $this->db->join('dbpre_subject','s.sid',$table,'sd.sid');
        if($this->days_ago>0) {
            $time = _G('timestamp') - $this->days_ago*24*3600;
            $this->db->where_more('s.addtime',$time);
        }
        $this->db->limit($start, $offset);
        $this->db->order_by('s.sid','ASC');
        $q = $this->db->get();
        if(!$q) {
            if($this->modelid > 0 || $this->max_count <= $this->count+1) {
                $this->message = '没有了。';
                $this->completed = true;
            } else {
                $this->count++;
                $this->params['start'] = $start + $offset;
                $this->params['count'] = $this->count;
                $this->params['days_ago'] = $this->days_ago;
                $this->params['start'] = 0;
                $this->message = "模型[{$model['name']}]下载完毕，进入下一个主题模型...";
            }
        } else {
            while ($val = $q->fetch_array()) {
                $this->download($table,$val);
            }
            $q->free_result();
            if($this->modelid > 0) {
                $this->params['modelid'] = $this->modelid;
            } else {
                $this->params['count'] = $this->count;
            }
            $this->params['days_ago'] = $this->days_ago;
            $this->params['start'] = $start + $offset;
            $this->message = "正在下载主题模型[{$model['name']}]图片，正在处理第{$start}至{$this->params['start']}个主题
            （本次共计下载{$this->d_total}，成功{$this->d_succed}，失败{$this->d_lost}）...";
        }
    }

    private function download($table, $subject) {
        if(!$subject['content']) return;
        list($content, $total, $succed, $lost) = $this->down_images($subject['content']);
        $this->d_total += $total;
        $this->d_succed += $succed;
        $this->d_lost += $lost;
        if($total>0 && $succed>0) {
            $this->db->from($table);
            $this->db->where('sid', $subject['sid']);
            $this->db->set('content', $content);
            $this->db->update();
        }
    }
    private function down_images($content) {
        preg_match_all("/(src|SRC)=\"(http:\/\/(.+)(\.gif|\.jpg|\.jpeg|\.png))/isU", $content, $img_array);
        $img_array = array_unique($img_array[2]);
        @set_time_limit(900);

        $siteurl = _G('cfg','siteurl');
        $siteurl = str_replace(array('http://','https://'), '', _G('cfg','siteurl'));
        $url = get_fl_domain($siteurl);

        $total = $succed = $lost = 0;

        $subdir = _G('cfg','picture_dir_mod');
        if($subdir == 'WEEK') {
            $subdir = date('Y', _G('timestamp')).'-week-'.date('W', _G('timestamp'));
        } elseif($subdir == 'DAY') {
            $subdir = date('Y-m-d', _G('timestamp'));
        } else {
            $subdir = date('Y-m', _G('timestamp'));
        }

        foreach ($img_array as $key => $value) {
            if(strpos($value, $url)) {
                continue;
            }
            $total++;
            $filepath = "uploads/pictures/" . $subdir . "/"; //图片保存的路径目录
            $filename = date("YmdHis", $this->global['timestamp']) . '_' . $key . "." . substr($value, -3 , 3); 
            if($this->_down_images($value, $filename, $filepath)) {
                $succed++;
                $fileArray[] = $filepath . $filename;
                $image_url = URLROOT . '/' . $filepath . $filename;
                $content = preg_replace("/".addcslashes($value,"/")."/isU", $image_url, $content);
            } else {
                $lost++;
                //$lostfile[] = $value;
            }
        }
        return array($content,$total,$succed,$lost);
    }

    //下载图片
    private function _down_images($url, $filename, $filepath) {
        if(!@is_dir(MUDDER_ROOT . $filepath)) {
            if(!@mkdir(MUDDER_ROOT . $filepath, 0777)) {
                return false;
            }
        }
        $fullname = MUDDER_ROOT . $filepath . $filename;

        $t = parse_url($url);
        $host = $t['host'];
        $file = $t['path'];
        $errno = 0;
        $errstr = '';
        $time_out = 10;

        $fp = @fsockopen($host, 80, $errno, $errstr, $time_out);
        if(!$fp) {
            echo $errstr;
            return false;
        } else {
            $header = "GET $file HTTP/1.1\r\n";
            $header .= "Host: $host\r\n";
            $header .= "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:21.0) Gecko/20100101 Firefox/21.0\r\n";
            $header .= "Referer: http://$host\r\n";
            $header .= "Connection: Close\r\n\r\n";
            @fwrite($fp, $header);
            $jpg = @fopen($fullname, "wb");
            //跳过HTTP头信息
            while(!feof($fp)) {
                if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) break;
            }
            //保存图片数据
            while (!feof($fp)) {
                $s = @fgets($fp,128);
                @fwrite($jpg,$s);
            }
            @fclose($jpg);
            @fclose($fp);
            return true;
        }
    }
}
?>