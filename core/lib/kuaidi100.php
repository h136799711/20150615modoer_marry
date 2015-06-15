<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
class ms_kuaidi100 {

    public $comlist = array(
        'quanfengkuaidi'=>'全峰快递',
        'jietekuaidi'=>'捷特快递',
        'jinyuekuaidi'=>'晋越快递',
        'jinguangsudikuaijian'=>'京广速递',
        'lianbangkuaidi'=>'联邦快递',
        'shentong'=>'申通快递',
        'shunfeng'=>'顺丰速递',
        'tiantian'=>'天天快递',
        'ups'=>'UPS',
        'yuantong'=>'圆通速递',
        'yunda'=>'韵达快运',
        'yuntongkuaidi'=>'运通快递',
        'zhongtong'=>'中通速递',
        'zhaijisong'=>'宅急送',
    );

    public $htmlcom = array('ems','shentong','shunfeng');

    private $key = '';
    private $powered = '查询数据由：<a href="http://kuaidi100.com" target="_blank">KuaiDi100.Com（快递100）</a> 网站提供 ';

    function __construct() {
        $this->key = _G('cfg', 'dk100_api_key');
    }

    public function get_data($typeCom, $typeNu) {
        if(!isset($this->comlist[$typeCom])) return;
        if(in_array($typeCom, $this->htmlcom)) {
            return $this->get_url($typeCom, $typeNu);

        } else {
            $result = $this->get_text($typeCom, $typeNu);
            if($this->is_json($result)) {
                $result = json_decode($result);

            }
            $result .= $powered;
        }
        return $result;
    }

    private function get_url($typeCom, $typeNu){
        $url = 'http://www.kuaidi100.com/applyurl?key='.$this->key.'&com='.$typeCom.'&nu='.$typeNu;
        $result = $this->post($url);
        if(substr($result, 0,4)=='http') {
            return array('url', $result);
        } else {
            return array('message', '查询失败！');
        }
    }

    private function get_text($typeCom, $typeNu){
        $result = array();
        $url ='http://api.kuaidi100.com/api?id='.$this->key.'&com='.$typeCom.'&nu='.$typeNu.'&show=2&muti=1&order=asc';
        $result = $this->post($url);
        if($result) {
            return array('message', $result.'<br />'.$this->powered);
        } else {
            return array('message', '查询失败！');
        }
    }

    private function post($url) {
        if (function_exists('curl_init') == 1){
          $curl = curl_init();
          curl_setopt ($curl, CURLOPT_URL, $url);
          curl_setopt ($curl, CURLOPT_HEADER,0);
          curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt ($curl, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
          curl_setopt ($curl, CURLOPT_TIMEOUT,5);
          $get_content = curl_exec($curl);
          curl_close ($curl);
        }else{
          include("snoopy.php");
          $snoopy = new ms_snoopy();
          $snoopy->referer = 'http://www.google.com/';//伪装来源
          $snoopy->fetch($url);
          $get_content = $snoopy->results;
        }
        return $get_content;
    }

    private function is_json($str) {
        return preg_match("/^\{.*:.*\}$/",$str);
    }
}
?>