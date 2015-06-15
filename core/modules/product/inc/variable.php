<?php
/**
* 配置缓存生成类
*/
class variable_product {

    function gcategory($key = '') {
        $C = _G('loader')->model('product:gcategory');
        return $C->get_and_write_cache($key);
    }
    
    function field($key) {
        $F = _G('loader')->model('product:field');
        return $F->write_cache($key, true);
    }

    function model($key) {
        $M = _G('loader')->model('product:model');
        return $M->write_cache($key, true);
    }

    function __call($name, $arguments) {
        if(substr($name,0,5) == 'model') {
            return $this->model(substr($name, 6));
        } elseif(substr($name,0,5) == 'field') {
            return $this->field(substr($name, 6));
        } elseif(substr($name,0,9) == 'gcategory') {
            return $this->gcategory(substr($name, 10));
        } 
        show_error("类方法不存在 ".get_class($this)."::$name()");
        return false;
    }
}