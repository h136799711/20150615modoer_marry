<?php
/**
* 配置缓存生成类
*/
class variable_member {
    
    /**
     * 生成用户组缓存
     * @param  string $key [description]
     * @return [type]      [description]
     */
    function usergroup($key = '') {
        $U = _G('loader')->model('member:usergroup');
        if(!$key) {
            return $U->write_cache(true);
        } elseif (is_numeric($key)) {
            return $this->write_cache_access($key, true);
        }
    }

    function tasktype() {
        return _G('loader')->model('member:tasktype')->write_cache(true);
    }

    function __call($name, $arguments) {
        if($name == 'tasktype') {
            return $this->tasktype();
        } elseif(substr($name,0,9) == 'usergroup') {
            return $this->usergroup(substr($name, 9));
        }
        show_error("类方法不存在 ".get_class($this)."::$name()");
        return false;
    }
}