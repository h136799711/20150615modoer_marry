<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class ms_loader
{

    var $modules = null;
    var $load_files = null;

    var $model_mapping = array(); //模型映射表

    function __construct()
    {
        global $_G;
        $this->modules =& $_G['modules'];
    }

    function lib($classname, $module=NULL, $instance=TRUE, $param=NULL)
    {
        global $_G;
        static $instances = array();

        $path = '';
        if($module && isset($this->modules[$module])) {
            $edpty_dir = true;
            $path = ($edpty_dir ? ('modules'. DS . $module) : ($this->modules[$module]['directory'])) . DS . 'lib' . DS;
        } elseif($module) {
            show_error(lang('global_not_found_module', $module));
        } else {
            $path = 'lib' . DS;
        }

        $filename = MUDDER_CORE . $path . $classname . '.php';
        $fullclassname = 'ms_' . $classname;
        if(!class_exists($fullclassname)) {
            show_error(lang('global_class_not_found', $classname));
            include $filename;
        }

        if($instance) {
            if(!isset($instances[$fullclassname])) {
                if($param) {
                    $instances[$fullclassname] = new $fullclassname($param);
                } else {
                    $instances[$fullclassname] = new $fullclassname();
                }
            }
        }

        return $instances[$fullclassname];
    }

    //载入模型类，classname有3种方式“module”表示modoer框架的module类，“item:subject”表示item模块的subject类，
    //“:article”表示article模块的article类
    function model($flag, $instance=TRUE, $param=NULL, $use_mapping = TRUE)
    {
        global $_G;
        static $instances = array();

        list($module, $classpre) = $this->pares_flag($flag);

        //载入模型时，如果遇到模型有映射表，则读取映射对象（拦截替换）
        if($use_mapping && isset($this->model_mapping["{$module}:{$classpre}"]))
        {
            $flag = $this->model_mapping["{$module}:{$classpre}"];
            list($module, $classpre) = $this->pares_flag($flag);
        }

        $class = 'msm_' . ($module && $classpre != $module ? ($module . '_') : '') . $classpre;
        $path = ($module ? ('modules'.DS.$module) : '') . DS . 'model' . DS;
        $filename = $path . $classpre . '_class.php';

        if(!class_exists($class)) {
            if(!is_file(MUDDER_CORE . $filename)) {
                show_error(lang('global_file_not_exist', $filename));
            }
            if(DEBUG) debug_log('model','include',$filename);
            include_once MUDDER_CORE . $filename;
        }
        if($instance) {
            if(!isset($instances[$class])) {
                if($param) {
                    $instances[$class] = new $class($param);
                } else {
                    $instances[$class] = new $class();
                }
            }
        }

        return $instances[$class];
    }

    function helper($filename, $module=NULL)
    {
        global $_G;

        if(strpos($filename,',')) {
            $filenames = explode(',', $filename);
            if($filenames) {
                foreach($filenames as $val) {
                    $this->helper($val,$module);
                }
                return;
            }
        } elseif(strpos($module,',')) {
            $modules = explode(',', $module);
            if($modules) {
                foreach($modules as $val) {
                    $this->helper($filename,$val);
                }
                return;
            }
        }

        static $instances = array();

        $path = '';
        if($module && isset($this->modules[$module])) {
            $edpty_dir = TRUE;
            $path = ($edpty_dir ? ('modules'. DS . $module) : ($this->modules[$module]['directory'])) . DS . 'helper' . DS;
        } elseif($module) {
            show_error(lang('global_not_found_module', $module));
        } else {
            $path = 'helper' . DS;
        }

        $file = MUDDER_CORE . $path . $filename . '.php';
        if(!in_array($file, $instances)) {
            if(!is_file($file)) {
                show_error(lang('global_file_not_exist', $path . $filename . '.php'));
            }
            $instances[] = $file;

            if(DEBUG) debug_log('helper','include',$file);
            include $file;
        }
    }

    function cookie()
    {
        $prelen = strlen(_G('cookiepre'));
        $result = array();
        foreach($_COOKIE as $key => $value) {
            if(substr($key, 0, $prelen) == _G('cookiepre')) {
                $var = substr($key, $prelen);
                $result[$var] = $value;
            }
        }
        return $result;
    }

    function _check_variable_class($module)
    {
        $varfile = MUDDER_MODULE . $module . DS . 'inc' . DS . 'variable.php';
        return is_file($varfile);
    }

    function _get_variable_class($module)
    {
        static $instances = array();
        $varfile = MUDDER_MODULE . $module . DS . 'inc' . DS . 'variable.php';
        if(!is_file($varfile)) return false;
        $classname = 'variable_' . $module;
        if(!class_exists($classname)) {
            include $varfile;
        }
        if(!isset($instances[$classname])) {
            $instances[$classname] = new $classname();
        }
        return $instances[$classname];
    }

    function cache($filename, $module=NULL, $show_error=TRUE)
    {
        if($filename == 'config') return $this->_config($module);
        //使用内存缓存时进入,支持了新缓存接口的模块进入
        if($this->_check_variable_class($module)) {
            $key = $module . '_' . $filename;
            $value = ms_cache::factory()->read($key);
            if($value == false) {
                if(DEBUG) log_write('variable',"$module\t$filename");
                $value = $this->_get_variable_class($module)->$filename();
            }
            return $value;
        }
        //默认文件缓存获取
        $result = '';
        $filename = ($module ? $module : 'modoer') . '_' . $filename . '.php';
        if(!is_file(MUDDER_CACHE . $filename)) {
            $show_error && show_error(lang('global_cachefile_not_exist', str_replace(MUDDER_ROOT,'',MUDDER_CACHE) . $filename));
        } else {
            $result = @include MUDDER_CACHE . $filename;
        }
        if(DEBUG) debug_log('cache', 'load', MUDDER_CACHE . $filename);
        return $result;
    }

    function _config($module)
    {
        $key = $module . '_config';
        $cache = ms_cache::factory();
        $value = $cache->read($key);
        if($value == false) {
            $value = $this->model('config')->get_config($module);
            $bool = $cache->write($key, $value);
            if($module == 'modoer') $this->model('config')->write_js();
        }
        return $value;
    }

    function variable($keyname, $module=NULL, $show_error=TRUE)
    {
        global $_G;
        //$t = backtrace_txt(debug_backtrace());
        //log_write('variable', date('Y-m-d H:i:s',_G('timestamp'))."\t$keyname\t$module\n".$t);
        $module == NULL && $module = 'modoer';
        $key = ($module ? $module . '_' : '') . $keyname;
        if(!isset($_G[$key])) {
            $_G[$key] = $this->cache($keyname, $module, $show_error);
            if(!$_G[$key]) {
                unset($_G[$key]);
                return FALSE;
            }
        }
        return $_G[$key];
    }

    //加入进行映射的模型累
    function add_model_mapping($source, $dest = null)
    {
        if(is_array($source)) {
            foreach($source as $s => $d) {
                $this->add_model_mapping($s, $d);
            }
        } else {
            if( ! $dest) return;
            foreach (array('source','dest') as $varname) {
                list($module, $class) = $this->pares_flag(trim($$varname));
                $$varname = $module.":".$class;
            }
            $this->model_mapping[$source] = $dest;
        }
    }

    //解析载入文件（类）表达式，返回组数(模块名和文件名前缀)array(module,classpre)
    function pares_flag($str)
    {
        $arrs = explode(':',$str);
        if(count($arrs)==1) {
            $module = null;
            $classpre = $arrs[0];
        } elseif($arrs[0]=='') {
            $module = $arrs[1];
            $classpre = $arrs[1];
        } else {
            $module = $arrs[0];
            $classpre = $arrs[1];
        }
        if($module && !check_module($module)) show_error(lang('global_not_found_module', $module));
        return array($module,$classpre);
    }

    /**
     * 显示文件载入记录 
     *
     */
    function debug_print() {
        global $_G;
        if($_GET) foreach ($_GET as $key => $value) {
            debug_log('input','GET',"$key="._T($value));
        }
        if($_POST) foreach ($_POST as $key => $value) {
            debug_log('input','POST',"$key=".$value?_T($value):'');
        }
        foreach (_G('session')->fetch_all() as $key => $value) {
            debug_log('input','Session',"$key=".$value?_T($value):'');
        }
        $included_files = get_included_files();
        foreach ($included_files as $value) {
            debug_log('file','included_files',$value);
        }
        $dbcache = _G('dbcache')->fetch_data_all();
        foreach ($dbcache as $value) {
            debug_log('cache','dbcache',var_export($value, true));
        }
        $logs = _G('debug_log');
        if(!$logs) return;
        $style = 'margin:5px auto;width:98%;line-height:18px;font-family:Courier New;text-align:left;background:#eee;border-width:1px; border-style:solid;border-color:#CCC;';
        $content = '';
        foreach ($logs as $key => $value) {
             foreach($value as $k => $v) {
                $content .='<div style="'.$style.'">';
                $id = 'debug_log_' . $key . '_' . $k;
                $content .='<h3 style="font-size:16px;border-bottom:1px solid #FF9900;margin:5px;padding:0 0 5px;">
                    <a href="javascript:;" onclick="$(\'#'.$id.'\').toggle();">'.$key.' '.$k.'</a> ('.count($v).')</h3>';
                $content .= '<ul style="margin:0;padding:0 0 5px;list-style:none;display:none;" id="'.$id.'">';
                foreach ($v as $_v) {
                    $content .= '<li style="padding:1px 8px;font-size:12px;">' . str_replace(MUDDER_ROOT, '', $_v) . '</li>';
                }
                $content .= '</ul>';
                $content .= '</div>';
            }
        }
        $trace = debug_backtrace();
        $id = 'debug_log_debug';
        $content .="<div style=\"$style\">".'<h3 style="font-size:16px;border-bottom:1px solid #FF9900;margin:5px;padding:0 0 5px;">
                    <a href="javascript:;" onclick="$(\'#'.$id.'\').toggle();">debug backtrace</a></h3>';
        $content .= "<table style='display:none;' id='$id'>";
        foreach($trace as $k=>$t) {
            $content .= "<tr><td>".str_replace(MUDDER_ROOT, './', $t['file'])."</td>";
            $content .=  "<td>".$t['line']."</td>";
            $content .= "<td>".($t['class']?("{$t['class']}->"):'') . "$t[function]()</td></tr>";
        }
        $content .= "</table></div>";
        return $content;
    }
    
}

