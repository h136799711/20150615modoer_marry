<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class ms_seo {

    //seo设置项解析后的内容
    private $_set = array();

    //使用SEO标签智能处理不存在标签内容
    private $auto_seo_tag = false;
    //分割标签连续性的符号
    private $break_char = array(',', '|', '-', '&');

    //模块SEO设置参数
    private $seo_setting = null;

    //赋值和读取标签组
    public $tags = null;

    public function __construct($module) {
        $this->tags = new stdClass();
        $this->auto_seo_tag = _G('cfg','auto_seo_tag');
        $this->_init_global_tags();
        $this->seo_setting = _G('loader')->model("{$module}:seosetting");
    }

    public function pares($page_name) {
        $arr_seo = $this->seo_setting->page_setting($page_name);
        if(!$arr_seo) return;
        $result = array();
        foreach ($arr_seo as $key => $value) {
            $tags = get_object_vars($this->tags);
            $this->$key = $this->_pares($value, $tags);
        }
        return $this;
    }

    //默认设置全局可用tag
    private function _init_global_tags() {
        $this->tags->site_name = _G('cfg','sitename');
        $this->tags->city_name = _G('city','name');
        $this->tags->module_name = _G('mod','name');
        $this->tags->site_keywords = _G('cfg','meta_keywords');
        $this->tags->site_description = _G('cfg','meta_description');
        $this->tags->page = $_GET['page'];
    }

    private function _pares($format_str, $tags) {
        if(!$format_str) return '';
        if(!$tags||!is_array($tags)) return $format_str;
        if($this->auto_seo_tag) {
            foreach($tags as $key => $val) {
                if(empty($val)) {
                    $f = '{'.$key.'}';
                    $i = strpos($format_str,$f);
                    if($i===FALSE) continue;
                    $x = FALSE;
                    foreach ($this->break_char as $key) {
                        if($x===FALSE) $x = strpos($format_str, $key, $i+strlen($f));
                    }
                    if($x===FALSE) {
                        $format_str = substr($format_str, 0, $i);
                    } else {
                        $format_str = substr($format_str, 0, $i) . substr($format_str, $x+1);
                    }
                }
            }
        }
        $keys = array();
        foreach(array_keys($tags) as $val) {
            $keys[] = '{'.$val.'}';
        }
        $format_str = str_replace($keys, array_values($tags), $format_str);
        return preg_replace("/\{[a-z0-9_]+\}/i", "", $format_str); 
    }

    public function __set($name, $value) {
        $this->_set[$name] = $value;
    }

    public function __get($name) {
        return $this->_set[$name];
    }

    public function __toString() {
        return implode(',', $this->_set);
    }
    
}
?>