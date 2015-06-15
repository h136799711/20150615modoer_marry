<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

//Hook返回值类型--每个模块一个数组
define('MF_HOOK_RETURN_ARRAY', 1);
//Hook返回值类型--每个模块返回值进行连接
define('MF_HOOK_RETURN_CONCAT', 2);
//Hook返回值类型--覆盖之前的返回值
define('MF_HOOK_RETURN_REWRITE', 3);
//Hook返回值类型--各个返回值累加
define('MF_HOOK_RETURN_ADD', 4);
//Hook返回值类型--各个返回值相减
define('MF_HOOK_RETURN_DEC', 5);
//Hook返回值类型--返回值为TURE时，停止Hook
define('MF_HOOK_RETURN_BREAK', 6);

class ms_hook {

    private $_listeners = array();

    public function __construct() {
        $this->_init();
    }

    private function _init() {
        foreach(_G('modules') as $m) {
            $hkfile =  MUDDER_MODULE . $m['flag'] . DS . 'inc' . DS . 'hook.php';
            if(is_file($hkfile)) {
                include_once $hkfile;
                $class = "hook_" . $m['flag'];
                if (class_exists($class)) {
                    new $class($this);
                }
            }
        }
    }

    public function register($hook, &$class, $method='') {
        if(is_array($hook)) {
            foreach ($hook as $hook_arr) {
                if(is_array($hook_arr)) {
                    list($hook_name, $hook_fun) = $hook_arr;
                } else {
                    $hook_name = $hook_fun = $hook_arr;
                }
                $this->register($hook_name, $class, $hook_fun);
            }
        } else {
            if(!$method) $method = $hook;
            $key = get_class($class).'->'.$method;
            $this->_listeners[$hook][$key] = array(&$class, $method);            
        }
    }

    //v3.3 hook字段改进，支持只hook某个模块，如 module:mobile_member_link
    function hook($hook, $params='', $return=FALSE, $return_type = MF_HOOK_RETURN_ARRAY) {
        if(strposex($hook,':')) {
            list($module, $hook) = explode(':', $hook);
        }
        $r_result = $foreach_break = '';
        //查看要实现的钩子，是否在监听数组之中
        if (isset($this->_listeners[$hook]) && is_array($this->_listeners[$hook]) && count($this->_listeners[$hook]) > 0) {
            // 循环调用开始
            foreach ($this->_listeners[$hook] as $listener) {
                // 取出插件对象的引用和方法
                $class =& $listener[0];
                if($module) {
                    list(, $current_module) = explode('_', get_class($class));
                    if($current_module != $module) continue;
                }
                $method = $listener[1];
                if(method_exists($class, $method)) {
                    $result = $class->$method($params);
                    if($return && $result!=null) {
                        switch ($return_type) {
                            case MF_HOOK_RETURN_CONCAT:
                                // 串联返回值
                                $r_result .= $result;
                                break;
                            case MF_HOOK_RETURN_REWRITE:
                                // 覆盖返回值并作为参数
                                $r_result = $params = $result;
                                break;
                            case MF_HOOK_RETURN_ADD:
                                $r_result += $result;
                                break;
                            case MF_HOOK_RETURN_DEC:
                                $r_result -= $result;
                                break;
                            case MF_HOOK_RETURN_BREAK:
                                if($result) $foreach_break = true;
                                break;
                            default:
                                if(!$r_result) $r_result = array();
                                if($result[0]) {
                                    foreach($result as $k => $val) {
                                        array_push($r_result, $val);
                                    }
                                } else {
                                    array_push($r_result, $result);
                                }
                                break;
                        }
                        if($foreach_break) break;
                    }
                }
            }
        }
        if($return) return $r_result;
    }
}
?>