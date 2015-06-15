<?php
/**
* 配置缓存生成类
*/
class variable_article {
    
    function category() {
        $C = _G('loader')->model('article:category');
        return $C->write_cache(true);
    }

    function __call($name, $arguments) {
        if($name == 'category') {
            return $this->category();
        }
        show_error("类方法不存在 ".get_class($this)."::$name()");
        return false;
    }
}