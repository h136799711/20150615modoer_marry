<?php
/**
* 配置缓存生成类
*/
class variable_coupon {
    
    function category() {
        $C = _G('loader')->model('coupon:category');
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