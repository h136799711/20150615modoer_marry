<?php
/**
* 配置缓存生成类
*/
class variable_item {
    
    function category($key = '') {
        $C = _G('loader')->model('item:category');
        return $C->_get_cache($key);
    }

    function model($key='') {
        $result = array();
        $result = _G('loader')->model('item:model')->write_cache($key, true);
        return $result;
    }

    function field($key) {
        $result = array();
        if(is_numeric($key)) {
            $result = _G('loader')->model('item:field')->_write_cache($key, true);
        }
        return $result;
    }

    function att_cat() {
        return _G('loader')->model('item:att_cat')->write_cache(true);
    }

    function att_list($key) {
        $result = _G('loader')->model('item:att_list')->write_cache($key, true);
    }

    function taggroup() {
        $result = _G('loader')->model('item:taggroup')->write_cache($key, true);
    }

    function __call($name, $arguments) {
        if(substr($name,0,8) == 'category') {
            $id = substr($name, 9);
            if(!$id) return;
            return $this->category($id);
        } elseif(substr($name,0,5) == 'model') {
            $id = substr($name, 6);
            if(!$id) return;
            return $this->model($id);
        } elseif(substr($name,0,5) == 'field') {
            $id = substr($name, 6);
            if(!$id) return;
            return $this->field($id);
        } elseif(substr($name,0,8) == 'att_list') {
            return $this->field(substr($name, 9));
        } elseif ($name == 'att_cat') {
            return $this->att_cat();
        }
        show_error("类方法不存在 ".get_class($this)."::$name()");
        return false;
    }
}