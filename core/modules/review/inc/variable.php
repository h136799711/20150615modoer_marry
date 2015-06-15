<?php
/**
* 配置缓存生成类
*/
class variable_review {

    function opt_group() {
        $result = _G('loader')->model('review:opt_group')->write_cache(true);
        return $result;
    }
    
    function opt($key) {
        $result = array();
        if(is_numeric($key)) {
            $result = _G('loader')->model('review:opt')->_write_cache($key, true);
        }
        return $result;
    }

    function __call($name, $arguments) {
        if($name == 'opt_group') {
            return $this->opt_group();
        } elseif(substr($name,0,3) == 'opt') {
            return $this->opt(substr($name, 4));
        } 
        show_error("类方法不存在 ".get_class($this)."::$name()");
        return false;
    }
}