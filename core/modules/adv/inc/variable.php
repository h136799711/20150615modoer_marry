<?php
/**
* 配置缓存生成类
*/
class variable_adv {
    
    /**
     * 生成广告位缓存
     * @param  string $key [description]
     * @return [type]      [description]
     */
    function place($key = '') {
        $L = _G('loader')->model('adv:place');
        return $L->write_cache(true);
    }

    function __call($name, $arguments) {
        if($name == 'place') {
            return $this->place();
        }
        show_error("类方法不存在 ".get_class($this)."::$name()");
        return false;
    }
}