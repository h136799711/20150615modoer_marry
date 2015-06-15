<?php
/**
* 配置缓存生成类
*/
class variable_group {

    function category() {
        $C = _G('loader')->model('group:category');
        return $C->write_cache(true);
    }

}