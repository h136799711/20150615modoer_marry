<?php
/**
* 配置缓存生成类
*/
class variable_modoer {

    function _module() {
        return _G('loader')->model('module')->write_cache(true);
    }

    function _template() {
        return _G('loader')->model('template')->write_cache(true);
    }

    function _area($key) {
        return _G('loader')->model('area')->_get_cache($key);
    }

    function _menu($key) {
        $M = _G('loader')->model('menu');
        if(!$key) {
            return $M->write_cache(true);
        } elseif (is_numeric($key)) {
            return $M->_write_cache($key, true);
        }
        return null;
    }

    function _words() {
        return _G('loader')->model('word')->write_cache(true);
    }

    function _datacall() {
        return _G('loader')->model('datacall')->write_cache(true);
    }

    function __call($name, $arguments) {
        if($name == 'templates') {
            return $this->_template();
        } elseif($name == 'modules') {
            return $this->_module();
        } elseif($name == 'words') {
            return $this->_words();
        } elseif($name == 'datacall') {
            return $this->_datacall();
        } elseif(substr($name, 0, 4) == 'area') {
            return $this->_area(substr($name, 5));
        } elseif(substr($name, 0, 4) == 'menu') {
            return $this->_menu(substr($name, 5));
        }
        show_error("类方法不存在 ".get_class($this)."::$name()");
        return false;
    }
}