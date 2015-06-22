<?php
/**
* 通用函数库
* @author moufer<moufer@163.com>
* @copyright (C)2001-2007 Moufersoft
*/
!defined('IN_MUDDER') && exit('Access Denied');

//输入参数过滤函数名称定义
/**
 * 转换1个字符串为大于等与0的数字
 */
define('MF_INT_KEY',    '_int_keyid');
/**
 * 转换字符串为一个数字
 */
define('MF_INT',        'intval');
/**
 * 转换字符串为浮点数
 */
define('MF_FLOAT',      'floatval');
/**
 * 过滤字符串内可能纯在的xss标签
 */
define('MF_HTML',       '_HTML');
/**
 * 过滤字符串内的html标签
 */
define('MF_TEXT',       '_T');
/**
 * 过滤文件名内可能存在的路径变换符号
 */
define('MF_FILENAME',   '_F');

/**
 * 获取模块配置信息【v3.3新增】
 * @static var array $setting
 * @param string $key 支持二维，三维数组查询,如:aa.bbb，表示: 二维数组['aaa']['bbb']；参数为"@all":表示获取指定文件的全部数据；
 * @param string $module 模块名称
 * @return array|null
 */
function S($key, $module = 'modoer') {
    static $setting = array();
    // 如果key参数前面有xxx:最前缀，说明xxx为一个模块标识，可以省略后面的module参数
    if(strpos($key, ':')) {
        list($module, $key) = explode(':', $key);
    }
    //核心网站配置直接从全局变了$_G['cfg']中读取
    if(!$module||$module=='modoer') {
        return _G('cfg', $key);
    }
    if(!isset($setting[$module]) && check_module($module)) {
        $setting[$module] = _G('loader')->variable('config', $module);
    }
    if($key == '@all') return $setting[$module];
    if(strpos($key, '.')) {
        $keys = explode('.', $key);
        $tmp =& $setting[$module];
        foreach ($keys as $k) {
            if(!is_array($tmp) || !isset($tmp[$k])) return null;
            $tmp =& $tmp[$k];
        }
        return $tmp;
    } else {
        if(isset($setting[$module][$key])) return $setting[$module][$key];
    }
    return null;
}
/**
 * 生成一个系统可用的URL地址【v3.3新增】
 * @param string $url_exp     URL表达式
 * @param Bool $full_url     是否返回完整一个路径
 * @param string $rewrite_mod URL重写模式(default,html,pathinfo,normal)
 */
function U($url_exp, $full_url = false, $rewrite_mod = 'default') {
    if(_G('sldomain') || _G('fullalways')) {
        $full_url = true;
    }
    if($rewrite_mod == 'default') $rewrite_mod = null;
    return _G('url')->create($url_exp, $full_url, $rewrite_mod);
}
/**
 * 取得$_GET里的变量
 * @param  string $var         参数名
 * @param  mixed $default     默认值
 * @param  string $convert_fun 获取的参数就行置顶函数过滤
 * @return mixed
 */
function _get($var, $default = null, $convert_fun='') {
    if(isset($_GET[$var])) {
        if($convert_fun) return $convert_fun($_GET[$var]);
        return $_GET[$var];
    }
    return $default;
}
/**
 * 取得$_GET里的变量
 * @param  string $var         参数名
 * @param  mixed $default     默认值
 * @param  string $convert_fun 获取的参数进行指定函数过滤
 * @return mixed
 */
function _post($var, $default = null, $convert_fun='') {
    if(isset($_POST[$var])) {
        if($convert_fun) return $convert_fun($_POST[$var]);
        return $_POST[$var];
    }
    return $default;
}
/**
 * 取得$_COOKIE里的变量
 * @param  string $var         参数名
 * @param  mixed $default     默认值
 * @param  integer $prefix  是否使用 cookie 参数前缀
 * @return mixed
 */
function _cookie($var, $default = null, $prefix = 1) {
    global $_G;
    if($prefix) {
        return isset($_G['cookie'][$var]) ? $_G['cookie'][$var] : $default;
    } else {
        return isset($_COOKIE[$var]) ? $_COOKIE[$var] : $default;
    }
}
/**
 * 取得一个输入变量
 * @param  string $var         参数名
 * @param  mixed $default     默认值
 * @param  string $convert_fun 获取的参数就行置顶函数过滤
 * @param  string $sx          获取的输入参数数组g:GET,p:post
 * @return mixed
 */
function _input($var, $default = null, $convert_fun='', $sx='pg') {
    $r = $default;
    $c = strlen($sx);
    $funs = array('p'=>'_post','g'=>'_get');
    for($i=0;$i<$c;$i++) {
        $x = $sx{$i};
        $f = isset($funs[$x]) ? $funs[$x] : '';
        if(!$f) continue;
        $r = $f($var, $default, $convert_fun);
        if(!empty($r) && $r != $default) return $r;
    }
    if(!$r) $r = $default;
    return $r;
}
//设置$_POST里的变量
function set_post($var, $value = '') {
    $_POST[$var] = $value;
}
//设置$_POST里的变量
function set_get($var, $value = '') {
    $_GET[$var] = $value;
}
//设置cookie
function set_cookie($var, $value, $life = 0, $prefix = 1) {
    $life = $life ? _G('timestamp') + $life : 0;
    $secure = $_SERVER['SERVER_PORT'] == '443' ? 1 : 0;
    $var = ($prefix ? _G('cookiepre') : '') . $var;
    
    $r = setcookie($var, $value, $life, _G('cookiepath'), _G('cookiedomain'), $secure);
    return $r;
}
//删除cookie
function del_cookie($var, $prefix = 1) {
    if(is_array($var)) {
        foreach($var as $val) set_cookie($val, '', -360000, $prefix);
    } else {
        set_cookie($var, '', -360000, $prefix);
    }
}
// Get Global value
function _G() {
    global $_G;
    $max_level = 5;
    $result = '';
    $args_num = func_num_args();
    if($args_num > $max_level) return $result;
    $args = func_get_args();
    $val =& $_G;
    foreach ($args as $v) {
        if(!isset($val[$v])) return $result;
        $val =& $val[$v];
    }
    return $val;
}
//获取一个数字型的数据库主键id值
function _int_keyid($string) {
    if(is_array($string)) {
        foreach($string as $key => $val) $string[$key] = _int_keyid($val);
        return $string;
    } else {
        return abs((int)$string);
    }
}
//过滤文件名
function _F($string) {
    return str_replace(array('../','..\\'), '', trim($string));
}
//过滤HTML，用于Text
function _T($string) {
    if(is_array($string)) {
        foreach($string as $key => $val) $string[$key] = _T($val);
        return $string;
    } else {
        if(phpversion()>='5.4') {
            $string = is_string($string) ? preg_replace("/&amp;(#[0-9]+|[a-z]+);/i", "&$1;", htmlspecialchars($string, ENT_QUOTES, _G('charset'))) : $string;
        } else {
            $string = is_string($string) ? preg_replace("/&amp;(#[0-9]+|[a-z]+);/i", "&$1;", htmlspecialchars($string, ENT_QUOTES)) : $string;
        }
        return str_replace($wu, $rp, trim($string));
    }
}
//过滤HTML，用于TextArea
function _TA($string) {
    $string = is_string($string) ? preg_replace("/&amp;(#[0-9]+|[a-z]+);/i", "&$1;", htmlspecialchars($string, ENT_QUOTES)) : $string;
    $string = str_replace($wu, $rp, $string);
    return preg_replace("/(\r\n|\n\r|\n|\r)/", "\r\n", $string);
}
//支持HTML格式,过滤可能引起安全问题的HTML标记
function _HTML($string) {
    $search_arr = array(
        "/<(\/?)(script|javascript|jscript|js|vbscript|vbs|about)/i",
        "/on(mouse|exit|error|click|dblclick|key|load|unload|change|move|submit|reset|cut|copy|select|start|stop)/i",
        "/<script([^>]*)>/i",
        "/<javascript([^>]*)>/i",
        "/<vbscript([^>]*)>/i",
        "/<iframe([^>]*)>/i",
        "/<frame([^>]*)>/i",
        "/<link([^>]*)>/i",
        "/@import/i",
        '%<embed\s+(.*)allowscriptaccess\s?=\s?["|\'].*["|\'](.*)/>%i'
    );
    $replace_arr = array(
        "＜\\1<i>\\2</i>\n",
        "<i>on\n\\1</i>",
        "＜script\\1＞",
        "＜javascript\\1＞",
        "＜vbscript\\1＞",
        "＜iframe\\1＞",
        "＜frame\\1＞",
        "＜link\\1＞",
        "@\nimport",
        '<embed \1allowscriptaccess="never"\2/>'
    );
    $string = preg_replace($search_arr, $replace_arr, $string);
    //$string = str_replace("&#", "&\n#", $string);
    return $string;
}
//格式化换行符号
function _NL($string) {
    return trim(preg_replace("/\s*(\r\n|\n\r|\n|\r)\s*/", "\r\n", $string));
}
//把字符串转化为JS的字符变量
function _JStr($string) {
	return str_replace(array('"', "\r\n", "\n"), array('\"', '', ''), $string);
}
//把一维数组转换为字符串
function _ArrayToStr($array, $split=',') {
    if(empty($array)) return '';
    if(!is_array($array)) return $array;
    return implode($split, $array);
}
//简单加密
function authcode($string, $operation = 'DECODE') {
    $string = $operation == 'DECODE' ? base64_decode($string) : base64_encode($string);
    return $string;
}
//数组格式化
function arrayeval($array, $level = 0) {
    if(!is_array($array)) {
        return "'".$array."'";
    }
    if(is_array($array) && function_exists('var_export')) {
        return var_export($array, true);
    }
    $space = '';
    for($i = 0; $i <= $level; $i++) {
        $space .= "\t";
    }
    $evaluate = "array (\n\r";
    $comma = $space;
    if(is_array($array)) {
        foreach($array as $key => $val) {
            $key = is_string($key) ? '\''.add_cs_lashes($key).'\'' : $key;
            $val = !is_array($val) && (!preg_match("/^\-?[0-9]\d*$/", $val) || strlen($val) > 12) ? '\''.add_cs_lashes($val, '\'\\').'\'' : $val;
            if(is_array($val)) {
                $evaluate .= "$comma$key => ".arrayeval($val, $level + 1);
            } else {
                $evaluate .= "$comma$key => $val";
            }
            $comma = ",\n\r$space";
        }
    }
    $evaluate .= "\n\r$space)";
    return $evaluate;
}
//以 C 语言风格使用反斜线转义字符串中的字符
function add_cs_lashes($string) {
    return $string ? addcslashes($string, '\'\\') : '';
}
//转换数组里的全部数值
function new_intval($number) {
    if(is_array($number))
        foreach($number as $key => $val) $number[$key] = new_intval($val); 
    else
        return intval($number);
    return $number;
}
//Un-quotes a quoted string
function strip_slashes($string, $filter_line=false) {
    if(is_array($string))
        foreach($string as $key => $val) $string[$key] = strip_slashes($val); 
    else
        $string = is_string($string) ? stripslashes($string) : $string;
        if($filter_line) $string = str_replace(array("\r\n","\n"),'', $string);
    return $string;
}
//过滤SQL
function strip_sql($string) {
    $pattern_arr = array("/ union /i", "/ select /i", "/ update /i", "/ outfile /i", "/ or /i");
    $replace_arr = array('&nbsp;union&nbsp;', '&nbsp;select&nbsp;', '&nbsp;update&nbsp;','&nbsp;outfile&nbsp;', '&nbsp;or&nbsp;');
    return is_array($string) ? array_map('strip_sql', $string) : preg_replace($pattern_arr, $replace_arr, $string);
}
//过滤orderby
function strip_order($string) {
    $string = preg_replace('/.?select.+from.+/i', '', $string);
    $string = preg_replace("/.?delete.+from.+/i", '', $string);
    $string = preg_replace("/.?update.+set.+/i", '', $string);
    $string = preg_replace("/.?select.+union.+/i", '', $string);
    return $string;
}
//转换浮点数
function cfloat($float) {
    $num = (float)$float;
    return $num;
}
//从开端截取
function trimmed_title($text, $limit=12, $ext='') {
    if ($limit) {
        $val = csubstr($text, 0, $limit);
        return $val[1] ? $val[0].$ext : $val[0];
    } else {
        return $text;
    }
}
//截取
function csubstr($text, $start=0, $limit=12) {
    $charset = _G('charset');
    if (function_exists('mb_substr')) {
        $more = (mb_strlen($text, $charset) > $limit) ? true : false;
        $text = mb_substr($text, $start, $limit, $charset);
        return array($text, $more);
    } elseif (function_exists('iconv_substr')) {
        $more = (iconv_strlen($text) > $limit) ? true : false;
        $text = iconv_substr($text, $start, $limit, $charset);
        return array($text, $more);
    } elseif (strtolower($charset) == "utf-8") {
        preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $text, $ar);
        if(func_num_args() >= 3) {
            if (count($ar[0])>$limit) {
                $more = true;
                $text = join("",array_slice($ar[0],$start,$limit))."...";
            } else {
                $more = false;
                $text = join("",array_slice($ar[0],$start,$limit));
            }
        } else {
            $more = false;
            $text = join("",array_slice($ar[0],$start));
        }
        return array($text, $more);
    } else {
        $fStart = 0;
        $fStart = $fStart * 2; 
        $limit = $limit * 2; 
        $strlen = strlen($text);
        for ( $i = 0; $i < $strlen; $i++ ) { 
            if ($i >= $fStart && $i < ($fStart + $limit ) ) { 
                if (ord(substr($text, $i, 1)) > 129) $tmpstr .= substr($text, $i, 2); 
                else $tmpstr .= substr($text, $i, 1); 
            } 
            if (ord(substr($text, $i, 1)) > 129 ) $i++; 
        } 
        $more = strlen($tmpstr) < $strlen; 
        return array($tmpstr, $more);
    }
}
//计算字符数量，非占用字节
function strlen_ex($str) {
    $charset = _G('charset');
    if(function_exists('mb_strlen')) {
        return mb_strlen($str, $charset);
    } elseif(function_exists('iconv_strlen')) {
        return iconv_strlen($str, $charset);
    } elseif($charset == 'utf-8') {
        preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $str, $ar);
        return count($ar[0]);
    } else {
        $len = 0;
        $strlen = strlen($str);
        for( $i = 0; $i < $strlen; $i++ ) { 
            $len++;
            if (ord(substr($str, $i, 1)) > 129 ) $i++; 
        }
        return $len;
    }
}
//截取，按字节数量截取
function substr_ex($text, $start=0, $limit=255) {
    $charset = _G('charset');
    list($s,) = csubstr($text, $start, $limit);
    $len = strlen($s);
    if($len <= $limit) return $s;
    $i = $l =0;
    $mo = $charset == 'utf-8' ? 3 : 2;
    $str = '';
    while($l < $limit) {
        $z = floor(($limit - $l) / $mo);
        !$z && $z = 1;
        $y = csubstr($s, $i, $z);
        $i += $z;
        $l = strlen($str) + strlen($y[0]);
        if($l <= $limit) {
            $str .= $y[0];
        } else {
            break;
        }
    }
    return $str;
}
//转换unix时间戳
function newdate($date, $format='Y-m-d H:i', $dnum=2, $dunit='月') {
    $timestamp = _G('timestamp');
    $date == 'NOW' ? $timestamp : $date;
    if(!$date) return '';
    $date = is_numeric($date) ? $date : (!$date ? $timestamp : strtotime($date));
    $date = ($date == -1 || !$date) ? $timestamp : $date;
    if($format != 'w2style') {
        return date($format, $date);
    } else {
        $tm = $timestamp - $date;
        $num = 0;
        if($tm < 60) {
            $num = $tm;
            $unit = 'second';
        } elseif($tm < 3600) {
            $num = floor($tm / 60);
            $unit = 'minute';
        } elseif($tm < 3600 * 24) {
            $num = floor($tm / 3600);
            $unit = 'hour';
        } elseif($tm < 3600 * 24 * 30) {
            $num = floor($tm / (3600 * 24));
            $unit = 'day';
        } elseif($tm < 3600 * 24 * 30 * 3) {
            $num = floor($tm / (3600 * 24 * 30));
            $unit = 'month';
        } elseif($tm < 3600 * 24 * 30 * 12) {
            return date('m-d', $date);
        }
        if($dnum <= $num && $dunit == $unit) {
            return date('Y-m-d H:i', $date);
        }
        return $num > 0 ? (lang('global_time_format', array($num, lang('global_time_'.$unit)))) : date('Y-m-d', $date);
    }
}
//判断电子邮件
function isemail($email) {
    return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}
//判断是不是图片
function is_image($imgfile) {
    if(!$imgfile) return;
    $ext = strtolower(pathinfo($imgfile, PATHINFO_EXTENSION));
    $exts = array('png','jpeg','jpg','gif');
    if(!in_array($ext, $exts)) return false;
    if(!function_exists('getimagesize')) return false;
    if(!is_file($imgfile)) return false;
    return @getimagesize($imgfile);
}
//判断字符串是否被序列化
function is_serialized( $data ) {
    // if it isn't a string, it isn't serialized
    if ( !is_string( $data ) )
        return false;
    $data = trim( $data );
    if ( 'N;' == $data )
        return true;
    if ( !preg_match( '/^([adObis]):/', $data, $badions ) )
        return false;
    switch ( $badions[1] ) {
        case 'a' :
        case 'O' :
        case 's' :
            if ( preg_match( "/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data ) )
                return true;
            break;
        case 'b' :
        case 'i' :
        case 'd' :
            if ( preg_match( "/^{$badions[1]}:[0-9.E-]+;\$/", $data ) )
                return true;
            break;
    }
    return false;
}
//判断文件是否可写
function is__writable($path) {
    if ($path{strlen($path)-1}=='/')
        return is__writable($path.uniqid(mt_rand()).'.tmp');
    else if (is_dir($path))
        return is__writable($path.'/'.uniqid(mt_rand()).'.tmp');
    $rm = file_exists($path);
    $f = @fopen($path, 'a');
    if ($f===false)
        return false;
    fclose($f);
    if (!$rm)
        unlink($path);
    return true;
}
//获取随机数 ALL(数字或字母),NUM(数字),WORD(字母)
function random($length=8, $idtype='ALL') {
    PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
    $hash = '';
    for ($i = 0; $i < $length;  $i++) {
        if ( 'NUM' == $idtype ) {
            if (0==$i) {
                $hash .= chr(rand(49, 57));
            } else {
                $hash .= chr(rand(48, 57));
            }
        } else if ( 'WORD' == $idtype ){
            $hash .= chr(rand(65, 90));
        } else {
            if ( 0==$i ) {
                $hash .= chr(rand(65, 90));
            } else {
                $hash .= (0==rand(0,1))?chr(rand(65, 90)):chr(rand(48,57));
            }
        }
    }
    return $hash;
}
//生成参数序列
function create_identifier($params) {
    return substr(md5(serialize($params)),0,8);
}
//生成表单序列
function create_formhash($p1, $p2 = '', $p3 = '') {
    $authkey = _G('cfg','authkey');
    return md5($authkey . $p1 . $p2 . $p3);
}
//替换全角数字
function cdc2dbc($number) {
    $search_arr = array('０','１','２','３','４','５','６','７','８','９');
    $replace_arr = array('0','1','2','3','4','5','6','7','8','9');
    return str_replace($search_arr, $replace_arr, $number);
}
//判断字串长度范围
function string_length($string, $min, $max) {
    return strlen($string) >= $min && strlen($string) <= $max;
}
/**
 * 判断是否为0 ，支持浮点数
 * @param  [type]  $num        [description]
 * @param  boolean $alow_minus [description]
 * @return [type]              [description]
 */
function num_empty($num, $alow_minus = TRUE) {
    if(!$alow_minus) {
        $num = (int) $num;
        return $num == 0;
    }
    if($num > 0 || $num < 0) return false;
    return true;
}
/**
 * 在字符串中查找指定的字符
 * @return Boolean
 * */
function strposex($string, $find, $offset = 0) {
    return !(strpos($string, $find, $offset) === false);
}
/**
 * 根据字节获取相应大小的单位
 * @param  int $filesize 字节
 * @return string           
 */
function size_unit($filesize) {
	if($filesize >= 1073741824) {
		$filesize = round($filesize / 1073741824 * 100) / 100 . ' GB';
	} elseif($filesize >= 1048576) {
		$filesize = round($filesize / 1048576 * 100) / 100 . ' MB';
	} elseif($filesize >= 1024) {
		$filesize = round($filesize / 1024 * 100) / 100 . ' KB';
	} else {
		$filesize = $filesize . ' bytes';
	}
	return $filesize;
}
//取得字节单位
function size_bytes($val) {
    $val = trim($val);
    $last = strtolower($val{strlen($val)-1});
    switch($last) {
        case 'g':
             $val *= 1024;
        case 'm':
             $val *= 1024;
        case 'k':
             $val *= 1024;
    }
    return $val;
}
//分页
function multi($num, $perpage, $curpage, $mpurl, $anchor = '', $onclick = '') {
    $multipage = '';
    if(substr($mpurl,0,11) != 'javascript:') {
        $mpurl = preg_replace("/(\?|&amp;)page=(.*?)(&|$)/i","\\1page=_PAGE_\\3",$mpurl);
        if(!strpos($mpurl, '_PAGE_')) {
            $mpurl .= strposex($mpurl, '?') ? '&amp;' : '?';
            $mpurl = $mpurl . 'page={PAGE}' . $anchor;
        } else {
            $mpurl = str_replace('_PAGE_', '{PAGE}', $mpurl) . $anchor;
        }
    }
    if($onclick) {
        $onclick = ' onclick="return ' . $onclick . '";';
    }
    if($num > $perpage) {
        $page = 10;
        $offset = 5;
        $pages = @ceil($num / $perpage);
        if($page > $pages) {
            $from = 1;
            $to = $pages;
        } else {
            $from = $curpage - $offset;
            $to = $curpage + $page - $offset - 1;
            if($from < 1) {
                $to = $curpage + 1 - $from;
                $from = 1;
                if(($to - $from) < $page && ($to - $from) < $pages) {
                    $to = $page;
                }
            } elseif($to > $pages) {
                $from = $curpage - $pages + $to;
                $to = $pages;
                if(($to - $from) < $page && ($to - $from) < $pages) {
                    $from = $pages - $page + 1;
                }
            }
        }
        $multipage = ($curpage - $offset > 1 && $pages > $page ? '<a href="'.str_replace('{PAGE}',1,$mpurl).'"'.str_replace('{PAGE}',1,$onclick).' class="p_redirect">&lt;&lt;</a>' : '').($curpage > 1 ? '<a href="'.str_replace('{PAGE}',$curpage-1,$mpurl).'"'.str_replace('{PAGE}',$curpage-1,$onclick).' class="p_redirect">&lt;</a>' : '');
        for($i = $from; $i <= $to; $i++) {
            $multipage .= $i == $curpage ? '<span class="p_curpage">'.$i.'</span>' : '<a href="'.str_replace('{PAGE}',$i,$mpurl).'"'.str_replace('{PAGE}',$i,$onclick).' class="p_num">'.$i.'</a>';
        }
        $multipage .= ($curpage < $pages ? '<a href="'.str_replace('{PAGE}',$curpage+1,$mpurl).'"'.str_replace('{PAGE}',$curpage+1,$onclick).' class="p_redirect" nextpage="Y">&gt;</a>' : '').($to < $pages ? '<a href="'.str_replace('{PAGE}',$pages,$mpurl).'"'.str_replace('{PAGE}',$pages,$onclick).' class="p_redirect">&gt;&gt;</a>' : '');
        $multipage = $multipage ? '<div class="p_bar"><span class="p_info">'.$num.'</span>'.$multipage.'</div>' : '';
    }
    return $multipage;
}
//分页2
function multi_w($count, $offset, $page, $mpurl, $anchor = '') {
    $multipage = '';

    if(substr($mpurl,0,11) != 'javascript:') {
        $mpurl = preg_replace("/page=(.*?)(&|$)/i","page=_PAGE_\\2",$mpurl);
        if(!strpos($mpurl, '_PAGE_')) {
            $mpurl .= strposex($mpurl, '?') ? '&amp;' : '?';
            $mpurl = $mpurl . 'page={PAGE}' . $anchor;
        } else {
            $mpurl = str_replace('_PAGE_', '{PAGE}', $mpurl) . $anchor;
        }
    }

    $start = ($page-1) * $offset;
    if($start > 1) $multipage .= '<a href="'.str_replace('{PAGE}',$page-1,$mpurl).'" class="p_redirect">&lt;</a>';
    if($count > 0) $multipage .=  '<span class="p_info">' . ($start + 1) . '~' . ($start + $count) . '</span>';
    if($count >= $offset) $multipage .= '<a href="'.str_replace('{PAGE}',$page+1,$mpurl).'" class="p_redirect" nextpage="Y">&gt;</a>';
    $multipage = $multipage ? '<div class="p_bar">'.$multipage.'</div>' : '';
    return $multipage;
}
//检测模块是否存在
function check_module($flag) {
    $modules = _G('loader')->variable('modules');
    $module = $modules[$flag];
    return $module && is_array($module);
}
//检测提交是否有效
function check_submit($var, $allowget = 0) {
    if(empty($_POST[$var])) return FALSE;
    if($allowget || ($_SERVER['REQUEST_METHOD'] == 'POST' && (empty($_SERVER['HTTP_REFERER']) || 
        preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", get_fl_domain($_SERVER['HTTP_REFERER'])) == get_fl_domain(preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST']))))) {
        return TRUE;
    }
    redirect('global_op_submit_invalid');
}
//检测验证码
function check_seccode($seccode, $return = FALSE, $del=true) {
    $sec_obj = new ms_seccode();
    $result = $sec_obj->check($seccode, $del);
    if(! $result && ! $return) {
        redirect($sec_obj->error());
    }
    return $result;
}
//检测IP
function check_ipaccess($ipstr) {
    $myip = _G('ip');
    if(!$ipstr) return false;
    $iplist = explode("\r\n", $ipstr);
    if(!$iplist) return false;
    foreach ($iplist as $ipmatch) {
        if(substr_count($ipmatch,'.') != 3) continue;
        if(strposex($ipmatch, '*')) {
            $match = str_replace(array('.','*'), array('\\.','(.*)'), $ipmatch);
            if(preg_match("/$match/", $myip)) return true;
        } elseif($myip == $ipmatch) {
            return true;
        }
    }
    return false;
}
//消息提示
function redirect($lang, $url = 'javascript:history.go(-1);', $navs = null, $min = '3') {
    global $_G, $_CITY;
    //数组情况，0：lang，1：参数
    if(is_array($lang)) {
        list($lang, $params) = $lang;
    }
    if(preg_match('/^[a-zA-Z0-9_\-]+$/', $lang)) {
        if(isset($params)) {
            $msg = lang($lang, $params);
        } else {
            $msg = lang($lang);
        }
    } else {
        $msg = $lang;
    }

    if($lang == 'member_not_login') {
        $extra = 'login';
    } elseif(strpos($lang, '_succeed') === false) {
        $extra = 'error';
    } else {
        $extra = '';
    }

    $caption = lang('global_op_title');
    $url = str_replace('&amp;','&',$url);
    $trace = debug_backtrace();

    //自动跳转的URL
    $auto_url = is_string($url) ? trim($url) : '';
    if($auto_url == 'stop'  || !$auto_url) $auto_url = '';
    if($auto_url == 'javascript:history.go(-1);' && !$_G['web']['referer']) $auto_url = '';

    if(defined('IN_AJAX')&&$_G['output_charset']&&$_G['output_charset']!=$_G['charset']) {
        $caption = charset_convert($caption, $_G['charset'], $_G['output_charset']);
        $msg = charset_convert($msg, $_G['charset'], $_G['output_charset']);
    }

    if(defined('IN_AJAX')) {
        dialog(lang('global_op_title'), $msg, $extra, $auto_url);
    }elseif(defined("IN_JSON_AJAX")) {
        exit(json_encode(array('message'=>$msg)));
    }  elseif(defined("IN_ADMIN")) {
        set_cookie('ad'.'min_id', 0);
        cpmsg($msg, $url);
    } elseif(defined("IN_IFRAME")) {
        redirect_lite($msg, $url);
    } else {
        $auto_url = trim($url);
        if($auto_url == 'stop'  || !$auto_url) $auto_url = '';
        if($auto_url == 'javascript:history.go(-1);' && !$_G['web']['referer']) $auto_url = '';
        
        $new_navs = array();
        if(is_array($navs)) $new_navs = array_merge($new_navs, $navs);
        $new_navs[] = array(lang('global_index'), url('modoer/index'));
        if(!$_G['user']->isLogin) {
            $new_navs[] = array(lang('member_login_title'),url('city:0/member/login'));
            $new_navs[] = array(lang('member_reg_title'),url('city:0/member/reg'));
        } else {
            $new_navs[] = array(lang('member_operation_title'),url('city:0/member/index'));
        }
        $new_navs[] = array(lang('global_window_close'),'javascript:window.close();');
        $navs = $new_navs;

        if($auto_url=='javascript:history.go(-1);' && $_G['web']['referer'] && (strpos($_G['web']['referer'],'reg')||strpos($_G['web']['referer'],'login')) && $_G['user']->isLogin) {
            $auto_url = url('member/index');
        }
        if(function_exists('mobile_template') && ($_G['in_mobile'] || _cookie('in_mobile'))) {
            require_once mobile_template('redirect');
        } else {
            require_once template('modoer_redirect');
        }
        output();
    }
}
//跨域提交表单返回信息
function redirect_lite($lang, $url = 'javascript:history.go(-1);', $navs = null, $min = '3') {
    if(preg_match('/^[a-zA-Z0-9_\-]+$/', $lang)) {
        $msg = lang($lang);
    } else {
        $msg = $lang;
    }
    $content = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>';
    $content .= '<head><script type="text/javascript">document.domain="'.$domain.'";</script></head><body>';
    if($url) {
        $url = str_replace('&amp;', '&', $url);
        $content .= '<script type="text/javascript">
        window.onload = function() { setTimeout(do_location, 1500); }
        function do_location() { document.location.href = \''.$url.'\';}
        </script>';
    }
    $content .= '<div style="width:100%;text-align:center;margin-top:20px;font-size:12px;line-height:20px;">';
    $content .= '<div style="padding:10px;">'.$msg.'</div>';
    if($url) {
        $content .= '<div style="padding:10px;"><a href="'.$url.'">'.lang('global_message_des').'</a></div>';
    }
    $content .= '</div>';
    $content .= '</body></html>';
    echo $content;
    output();
}
//ajax消息提示
function dialog($caption, $msg, $extra = '', $url = '') {
    $caption = _T($caption);
    $msg = trim($msg);
    $search = array('"',"\r\n","\r","\n","\n\r");
    $replace = array('\\"',"{LF}","{LF}","{LF}","{LF}");
    if($extra == 'error' || $extra == 'login') {
        $msg = 'ERROR:' . $msg;
    }
    $setdomain = $_POST['in_iframe'] && $_POST['set_domain']!='N';
    if($setdomain) {
        $domain = get_fl_domain();
        echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>';
        if($domain !='' && is_domain($domain)) {
            echo '<head><script type="text/javascript">document.domain="'.$domain.'";</script></head><body>';
        } else {
            echo '<head></head><body>';
        }
    }
    echo '{ caption:"' . $caption . '",message:' . '"'.str_replace($search, $replace, $msg) . '"';
    if($url && $url != "javascript:history.go(-1);") echo ',url:"'.$url.'"';
    if($extra == 'login') {
        $forward = _G('web','referer') ? _G('web','referer') : _G('cfg','siteurl');
        $url = get_url('member', 'login', array('forward'=>base64_encode($forward)), '', 1);
        echo ',extra:"'.$extra.'",url:"'.$url.'" }';
    } elseif($extra == 'dlg') {
        echo ',extra:"dlg" }';
    } elseif($extra == 'msg') {
        echo ',extra:"msg" }';
    } else {
        echo '}';
    }
    if($setdomain) {
        echo '</body></html>';
    }
    if(DEBUG) {
        $trace = debug_backtrace();
        $content = "=======================================================\n";
        foreach($trace as $k=>$t) {
            $content .= str_replace(MUDDER_ROOT, './', $t['file']);
            $content .=  "\t" . $t['line'];
            $content .= "\t" . ($t['class']?("{$t['class']}->"):'') . "$t[function]()\n";
        }
        file_put_contents(MUDDER_DATA . 'logs' . DS . 'debug.txt', $content);
    }
    output();
}
//跨域提交表单返回信息
function fetch_iframe($string,$set_domain = true) {
    $domain = get_fl_domain();
    $content = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>';
    if($set_domain && is_domain($domain)) {
        $content .= '<head><script type="text/javascript">document.domain="'.$domain.'";</script></head><body>';
    } else {
        $content = '<head></head><body>';
    }
    $content .= $string;
    $content .= '</body></html>';
    return $content;
}
/**
 * 提示页面不存在
 * @param string $nexturl 自动返回的URL地址
 * */
function http_404($nexturl = '') {
    global $_G, $_CFG, $_CITY, $_C;
    if(!$_CITY) {
        //没有城市记录的，使用缺省城市
        if($d_city = get_default_city()) {
            if(!$_CITY['aid']&&!$_C['city_id']) {
                init_city($d_city['aid']);
            }
            $_CITY = $d_city;
        }
    }
    if($nexturl) {
        $nexturl = str_replace('&amp;','&',$nexturl);
    } else {
        $nexturl = url('modoer/index');
    }
    $msg = lang('global_http_404');
    @header('HTTP/1.1 404 Not Found');
    @header('Status: 404 Not Found');
    //vp($_GET);
    require_once template('modoer_http_404');
    output();
}
//数组转换url
function http_query($array, $pathinfo=FALSE, $check_rewritecompatible=TRUE) {
    global $_G;
    if($check_rewritecompatible) {
        foreach($array as $key => $val) {
            if(is_string($val)) {
                $v = rawurlencode($val);
                if($_G['cfg']['rewrite'] && $_G['cfg']['rewrite_hide_index'] && $_G['cfg']['rewritecompatible']) {
                    $v = rawurlencode($v);
                }
                $array[$key] = $v;
            }
        }
    }
    $param = url_implode($array,null,'','',false);
    if($pathinfo) {
        return str_replace(array('&','='), '/', $param);
    }
    return $param;
}
//转换模板url标签 rewrite_mod=null表示按配置生成url
function url($url_exp, $au='', $fullurl=FALSE, $rewrite_mod=NULL, $city_id=NULL) {
    if(_G('fullalways')) $fullurl = TRUE;
    if(is_numeric($city_id) && $city_id >= 0) {
        if(!preg_match("/^city:([0-9]+)\//i", $url_exp)) {
            $url_exp = "city:$city_id/" . $url_exp;
        }
    }
    if($rewrite_mod === true || $rewrite_mod=='1') {
        $rewrite_mod = 'normal';
    }
    $url = _G('url')->create($url_exp, $fullurl, $rewrite_mod);
    return $url;

    if(strtolower(substr($pathinfo,0,4)) == 'http') return $pathinfo;
    $params = array();
    $info = explode("/", $pathinfo);
    if(preg_match("/^city:([0-9]+)/i",$info[0],$matches)) {
        $city_id = $matches[1];
        $info = explode('/', substr($pathinfo, strlen($matches[0])+1));
    }

    if(empty($info)) {
        $module = "modoer";
        $script = "";
    } elseif(count($info) == 1) {
        $module = $info[0];
        $script = "";
    } else {
        $module = $info[0];
        $script = $info[1];
        if(count($info) > 2) {
            for($i=2; $i<count($info); $i++) {
                $tmp = $info[++$i];
                if($tmp == '') continue;
                $params[$info[$i-1]] = $tmp; 
            }
        }
    }

    //不需要城市目录的
    $citypath_without = _G('cfg', 'citypath_without');
    $city_sldomain = _G('cfg','city_sldomain');
    is_string($citypath_without) && $citypath_without = explode("\r\n", $citypath_without);
    if($city_sldomain) {
        if($city_sldomain=='2'||$module=='member'||$script=='member') {
            if($citypath_without && in_array("$module/$script",$citypath_without)) $city_id = 0;
            foreach($citypath_without as $match) {
                list($m,$a) = explode('/',$match);
                if($module==$m && $a=='*') $city_id = 0;
                if($script==$a && $m=='*') $city_id = 0;
            }
        }
    }

    return get_url($module, $script, $params, $au, $fullurl, $no_rewrite, $city_id);
}
//URL路径
function url_path($name, $url='', $target='') {
    if($target) $target = ' target="'.$target."'";
    if($url)
        return '<a href="'.$url.'"'.$target.'>'.$name.'</a>';
    else
        return $name;
}
//转换数组成为URL
function url_implode($data, $prefix=null, $sep='', $key='', $encode=true) {
    $ret = array();
    foreach((array)$data as $k => $v) {
       $k = urlencode($k);
       if(is_int($k) && $prefix != null) {
           $k = $prefix . $k;
       }
       if(!empty($key)) {
           $k = $key . "[" . $k . "]";
       }
       if(is_array($v) || is_object($v)) {
           array_push($ret,url_implode($v, "", $sep, $k));
       }
       else {
           array_push($ret, $k . "=" . ($encode?rawurlencode($v):$v));
       }
    }
   if(empty($sep)) $sep = "&amp;";
   return implode($sep, $ret);
}
//替换URL的其中一个参数的值
function url_replace($url, $name, $value, $del_page = TRUE) {
    $value = rawurlencode($value);
    if(strposex($url, $name)){
        $url = preg_replace("/$name=(.*?)(&|$)/i","$name=$value\\2", $url);
    } else {
        $url .= (strpos($url,'?') ? "&" : "?") . "$name=$value";
    }
    if(!$del_page) return $url;
    return preg_replace("/(\?|&amp;&|)a=(.*?)(&|$)/i","\\2", $url);
}
//获取服务器信息
function get_webinfo() {
    $result = array();
    $result['self'] = strtolower($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']);
    $result['domain'] = strtolower($_SERVER['SERVER_NAME']);
    $result['agent'] = $_SERVER['HTTP_USER_AGENT'];
    $result['referer'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    $result['scheme'] = $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
    $result['reuri'] = request_uri();
    $result['port'] = $_SERVER['SERVER_PORT'] == '80' ? '' : ':'.$_SERVER['SERVER_PORT'];
    $result['url'] = $result['scheme'] . $result['domain'] . $result['port'];
    return $result;
}
function request_uri()
{
    if (isset($_SERVER['REQUEST_URI'])) {
        $uri = $_SERVER['REQUEST_URI'];
    } else {
        if (isset($_SERVER['argv'])) {
            $uri = $_SERVER['PHP_SELF'] .(empty($_SERVER['argv'])?'':('?'. $_SERVER['argv'][0]));
        } else {
            $uri = $_SERVER['PHP_SELF'] .(empty($_SERVER['QUERY_STRING'])?'':('?'. $_SERVER['QUERY_STRING']));
        }
    }
    return $uri;
} 
//获取当前访问的城市
function get_city($new_city_id=null) {
	global $_G, $_CITY, $_C;

	$_G['citys'] = $_G['loader']->variable('area');
    $citys =& $_G['citys'];
	$city_id = 0;

	if($_G['cfg']['city_sldomain']=='1') {
		$sldomain = get_sl_domain();
    } elseif($_G['cfg']['city_sldomain']=='2'&&$_G['cfg']['rewrite']&&$_G['cfg']['rewrite_mod']=='pathinfo') {
        $sldomain = $_G['url']->get_sldomain();
	}
    if($sldomain) {
        foreach($citys as $val) {
            if($val['domain']==$sldomain) {
                $city_id = $val['aid'];
                $_GET['city_domain'] = $sldomain;
            }
        }
        if(!$city_id) $_GET['unkown_sldomain'] = $sldomain;
    } elseif($sl_domain = get_sl_domain()) {
        if(strtolower( $sl_domain ) != strtolower(substr($_G['cfg']['siteurl'],7,strlen($sl_domain)))) {
            $_GET['unkown_sldomain'] = $sl_domain;
        }
    }

	if(!$city_id) $city_id = abs((int) $_G['cookie']['city_id']);
    if(!$city_id || !isset($citys[$city_id])) return;

    if(!$citys[$city_id]['enabled'] && !defined('IN_ADMIN')) {
        if($d_city = get_default_city()) {
            $_CITY = init_city($d_city['aid']);
        } else {
            set_cookie('city_id', '', 0);
        }
        if(!defined('IN_AJAX') && !defined('IN_JS')) {
            redirect(lang('global_area_city_disabled', $citys[$city_id]['name']), url('city:0/index/city'));
        } else {
            show_error(lang('global_area_city_disabled', $citys[$city_id]['name']));
        }
    }

    //save cookie
    if($_C['city_id'] != $city_id) set_cookie('city_id', $city_id, 2592000);
    define('_NULL_CITYID_',"0,$city_id");

    return $citys[$city_id];
}
//判断并获取唯一城市
function get_single_city() {
    global $_G;
    $citys = $_G['loader']->variable('area');
    if(count($citys)==1) return array_pop($citys);
    $enable_citys = array();
    foreach($citys as $val) {
        if($val['enabled']) {
            $enable_citys[] = $val;
        }
    }
    if(count($enable_citys)==1) return array_pop($enable_citys);
    return false;
}
//获取默认城市
function get_default_city() {
    global $_G;
    if(!$city_id = get_cityid_ip()) {
        $city_id = _G('cfg','city_id');
    }
    $citys = $_G['loader']->variable('area');
    if(!$city = $citys[$city_id]) {
        if($citys) return array_shift($citys);
        return;
    }
    if(!$citys[$city_id]['enabled']) {
        foreach($citys as $val) {
            if($val['enabled']) return $val;
        }
    }
    return $citys[$city_id];
}
//初始化
function init_city($city_id=null,$jump=false) {
    global $_G;

    $citys = $_G['loader']->variable('area');
    $city = $citys[$city_id];
    //SEO替换
    foreach(array('sitename','meta_keywords','meta_description') as $k) {
	    if($city['config'][$k]) {
             $_G['cfg'][$k] = $city['config'][$k];
        }
        $_G['cfg'][$k] = str_replace('{city}', $city['name'], $_G['cfg'][$k]);
    }
    //风格替换
    if($_G['city']['templateid'] > 0) {
        $_G['cfg']['templateid'] = $_G['city']['templateid'];
    }
    set_cookie('city_id', $city_id, 2592000);
    $_G['city'] = $city;
    return $city;
}
function get_cityurl($city_id,$urlparams='') {
    global $_G;

    $citys = $_G['loader']->variable('area');
    if(!$city = $citys[$city_id]) $_G['cfg']['city_sldomain'] = 0;
    if(!$city['domain']) $_G['cfg']['city_sldomain'] = 0;
    if($_G['cfg']['city_sldomain']=='1'||$_G['cfg']['city_sldomain']=='2') {
        $siteurl = display('modoer:cityurl',"domain/$city[domain]");
    } else {
        $siteurl = $_G['siteurl'];
    }
    $url = url($urlparams,'','','',$city_id);
    return $url;
}
function get_city_domain($city_id) {
    global $_G;

    $citys = $_G['loader']->variable('area');
    if(!$city = $citys[$city_id]) $_G['cfg']['city_sldomain'] = 0;
    if(!$city['domain']) $_G['cfg']['city_sldomain'] = 0;
    if($_G['cfg']['city_sldomain']=='1') {
        $siteurl = 'http://' . $city['domain'] . '.' . get_fl_domain() . '/';
    } elseif($_G['cfg']['city_sldomain']=='2'&&$_G['cfg']['rewrite']&&$_G['cfg']['rewrite_mod']=='pathinfo') {
        $urlpre = $_G['cfg']['rewrite_hide_index'] ? '' : 'index.php/';
        $siteurl = $_G['cfg']['siteurl'] . $urlpre . $city['domain'] . '/';
    } else {
        $siteurl = $_G['cfg']['siteurl'];
    }
    return $siteurl;
}
//获取主域名
function get_current_domain() {
    $ServerName = strtolower($_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME']);
    if($i=strpos($ServerName,':')) $ServerName = substr($ServerName,0,$i);
    return str_replace(array('http://','https://'),'',$ServerName);
}
//获取顶级域名
function get_fl_domain($full_domain='') {
    static $library = '';
    if(!$library) {
        $library = file_get_contents(MUDDER_DATA . 'domain_library.inc');
        if($library) $library = str_replace('.', '\.', preg_replace("/\s*(\r\n|\n\r|\n|\r)\s*/", "|", $library));
        if(!$library) $library = 'com\.cn|com\.hk|net|com|cn|us|tw|hk';
    }
    $url = $full_domain ? $full_domain : get_current_domain();
    if(preg_match('/[\w][\w-]*\.(?:' . $library . ')(\/|$)/isU', $url, $domain)) {
        return rtrim($domain[0], '/');
    }
    $url = str_replace(array('http://','https://','\\'),array('','','/'),strtolower($url));
    if($i = strpos($url,'/')) {
        $url = substr($url, 0, $i);
    }
    _G('loader')->helper('validate');
    if(validate::is_ip($url)) return $url;
    if(strposex($url,'/')||strposex($url,'.')) return;
    return $url;
}
//获取二级域名前缀
function get_sl_domain() {
    $domain = get_current_domain();
	$list = explode('.', $domain);
	if(count($list)<=2 || $list[0]=='www') return '';
	return $list[0];
}
//判断添加的视频地址是否是允许添加的
function check_flash_domain($url) {
    static $library = array();
    if(!$library) {
        if(!$library = _G('cfg','swf_domain')) {
            $library = file_get_contents(MUDDER_DATA . 'domain_swf.inc');
        }
        if($library) $library = explode('|', preg_replace("/\s*(\r\n|\n\r|\n|\r)\s*/", "|", $library));
        $library[] = get_fl_domain();
    }
    $fldm = get_fl_domain($url);
    return in_array($fldm, $library);
}
//获取二级域名对应的地图api地址
function get_mapapi() {
	global $_G;
	if(!$sldomain = get_sl_domain()) return $_G['cfg']['mapapi'];
	$citys = $_G['loader']->variable('area');
	foreach($citys as $val) {
		if($val['domain'] == $sldomain && $val['config']['mapapikey']) {
			return $val['config']['mapapikey'];
		}
	}
	return $_G['cfg']['mapapi'];
}
//判断当前是一个有效的域名
function is_domain($domain) {
    if(!$domain) return false;
    if(!strpos($domain, '.')) return false;
    if(preg_match("/^[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+$/", $domain)) return false;
    return preg_match ("/^[a-z0-9][a-z0-9\-]+[a-z0-9](\.[a-z]{2,4})+$/i", $domain);
}
//获取uc头像
function get_uc_face($uid,$size='small')
{
    return UC_API.'/avatar.php?uid='.$uid.'&type=virtual&size='.$size;
}
//获取头像
function get_face2($uid, $size = 'small', $full = false)
{
    $sizes = array('small','big');
    $size = strtolower($size);
    if(!in_array($size, $sizes)) $size='big';

    if(defined('IN_UC')) {
        return UC_API.'/avatar.php?uid='.$uid.'&type=virtual&size='.$size;
    }

    $filename = '/uploads/'.mc_member_face::get_filename($uid, $size);
    if(is_file(MUDDER_ROOT.$filename)) {
        return ($full?_G('cfg','siteurl'):URLROOT).$filename;
    }
    return ($full?_G('cfg','siteurl'):URLROOT).'/static/images/noface.jpg';
}
//获取头像链接
function get_face($uid, $create_src = FALSE, $show_root = TRUE) {
    static $root = 'null';
    if(defined('IN_UC')) {
        return UC_API.'/avatar.php?uid='.$uid.'&type=virtual&size=small';
    } else {
        if($root=='null') $root = _G('cfg','siteurl');
        $uid = abs(intval($uid));
        $uid = sprintf("%09d", $uid);
        $dir1 = substr($uid, 0, 3);
        $dir2 = substr($uid, 3, 2);
        $dir3 = substr($uid, 5, 2);
        $src = 'uploads/faces/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2) . ".jpg";
        if($create_src || is_file(MUDDER_ROOT . $src)) return ($show_root ? $root : (URLROOT.'/')) . $src;
        return ($show_root ? $root : URLROOT) . 'static/images/noface.jpg';
    }
}
//转换页码为数据开始下标
function get_start($page, $offset) {
    if(!$offset || $offset < 1) $offset = 10;
    return ($page-1) * $offset;
}
//转换输出URL
function get_url($module='modoer', $scriptname='', $param='', $au='', $full = FALSE, $no_rewrite = FALSE, $city_id=NULL) {
    global $_G, $_CITY;

    $_modules =& _G('modules');

    if(defined('IN_AJAX')) $full = TRUE;
    if(isset($_G['fullalways']) && $_G['fullalways']) $full = TRUE;

    static $instance = array();
    if(!defined("IN_ADMIN") && !empty($_G['mod'])) {
        $cmod =& _G('mod');
        //$current_empty_dir = empty($cmod['directory']);
        $current_empty_dir = TRUE;
    }

    if($module != 'modoer' || !$module) {
        if(!$param) $param = array();
        if($scriptname && is_array($param)) {
            array_unshift($param, array('act' => $scriptname));
        } elseif($scriptname && is_string($param)) {
            $param = 'act=' . $module . ($param ? ('&' . $param) : '');
        }
        $scriptname = $module;
    }

    $scriptname && $scriptname .= '.php';
    if(is_array($param) && $param) {
        if(!$no_rewrite && _G('cfg','rewrite')) {
            foreach($param as $k=>$v) $param[$k] = str_replace('-', '_f_',$v);
        }
        $paramstr = $scriptname . '?' . http_query($param,0,!$no_rewrite);
    } elseif(is_string($param) && $param) {
        $paramstr = $scriptname .'?' . $param;
    } else {
        $paramstr = $scriptname;
    }

    if(is_numeric($city_id)) {
        if($city_id > 0) $domain = display('modoer:area',"aid/$city_id/keyname/domain");
        if(!$city_id) $domain = '{GLOBAL}';
    }

    if(!$no_rewrite && _G('cfg','rewrite') && $paramstr) {
        $RW =& $_G['loader']->lib('urlrewriter');
        $paramstr = $RW->preg_parse($paramstr, $domain);
    } elseif(_G('cfg','city_sldomain')=='1') {
        !$domain && $domain = $_CITY['domain'];
        $newdomain = $domain == '{GLOBAL}' ? _G('cfg','siteurl') : ('http://' . $domain.'.'.get_fl_domain().'/');
        if($domain == $_GET['city_domain']) $newdomain = '';
        $paramstr = $newdomain . $paramstr;
    }

    //$fldomain = get_fl_domain();
    if($_G['sldomain']) $fullurl = TRUE;
    $is_fullurl = substr($paramstr,0,7) == 'http://';

    $siteurl = _G('cfg', 'siteurl');
    if(_G('cfg','city_sldomain')!='1') {
        $c_url = $_G['cfg']['siteurl'];
    } else {
        $c_url = 'http://' . get_current_domain() . URLROOT . '/';
    }    if($_G['sldomain']) $c_url = $_G['cfg']['siteurl'];
    $urlpre = $full ? ($is_fullurl?'':$c_url) : ($is_fullurl?'':(URLROOT.'/'));

    $austr = '';
    $au && $austr = '#' . $au;
    return $urlpre . $paramstr . $austr;
}
//获取IP
function get_ip() {
    $onlineip = null;
    $onlineipmatches = array();
    if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $onlineip = getenv('HTTP_CLIENT_IP');
    } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $onlineip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $onlineip = getenv('REMOTE_ADDR');
    } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] &&
        strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $onlineip = $_SERVER['REMOTE_ADDR'];
    }
    $onlineip = addslashes($onlineip);
    @preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
    $onlineip = $onlineipmatches[0] ? $onlineipmatches[0] : 'unknown';
    unset($onlineipmatches);
    return $onlineip;
}
//获取根路径
function get_urlroot($ignore_pre = false) {
    global $_G;
    $url = str_replace('\\', '/', dirname(SELF));
    //忽略前缀文件夹
    if(!$ignore_pre && defined('URLROOT_PRE')) {
        $str = substr($url, 0, strlen(URLROOT_PRE));
        if($str == URLROOT_PRE) {
            $url = substr($url, strlen(URLROOT_PRE));
        }
    }
    if(strposex($url, '/index.php')) {
        $x = strpos($url, '/index.php');
        $url = substr($url, 0, $x);
        $_G['index_url_rewrite'] = TRUE;
    }
    if($url == '/' || $url == '\\') {
        $url = '';
    }
    return $url;
}
//获取IP地址真实地址
function get_cityid_ip($ip=null) {
    $addr = _G('loader')->lib('ipaddress')->set_ip($ip)->get_address();
    if($addr && !is_numeric($addr)) {
        $citys = _G('loader')->variable('area');
        foreach($citys as $val) {
            if(strposex($addr, $val['name'])) return $val['aid'];
        }
    }
    return 0;
}
//通过城市域名获取id
function get_city_for_doman($domain) {
    global $_G;
    $citys = $_G['loader']->variable('area');
    foreach($citys as $city) {
        if($city['domain']==$domain) return $city;
    }
    return false;
}
//获取上一页URL，并设置没有时的默认返回
function get_forward($default = null, $get_post = FALSE) {
    $web = _G('web');
    $siteurl = _G('siteurl');
    if($get_post) {
        $forward = $_POST['forward'];
    } else {
        $forward = $web['referer'] ? $web['referer'] : '';
    }
    if($forward && substr($forward, 0, strlen($siteurl)) != $siteurl) $forward = '';
    if(!$forward && $default) {
        return $default == 'home' ? (defined('IN_ADMIN') ? SELF : url('modoer/index')) : $default;
    }
    return _T($forward);
}
//判断是否是对应类型的模板
function is_template($templateid,$type) {
    global $_G;
    $templates = $_G['loader']->variable('templates');
    return isset($templates[$type][$templateid]);
}
//载入模板
function template($file, $type='main', $templateid='', $custom_dir='', $update=FALSE) {
    global $_G, $_CFG;

    static $tpl_obj;
    if(!$tpl_obj) {
        $tpl_obj = new ms_template;
    }
    $tpl_obj->tpl_root_dir = 'templates';    //模板基准目录
    $tpl_obj->cache_root_dir = 'data/templates';    //模板缓存目录

    //文件名过滤
    $file = _F($file);

    if($type == 'member') {

        $module = $templateid ? $templateid : 'member';
        $tpl_dir = "$module/assistant/templates";
        $tpl_file = $tpl_dir.'/'.$file;

        $tpl_obj->tpl_root_dir = 'core/modules';
        $tpl_obj->cache_root_dir = 'data/templates/member';

    } elseif($type && is_numeric($templateid) || $type == 'main') {

        $templates = $_G['loader']->variable('templates');

        if($type == 'main' && !$templateid) {
             $templateid = (int)S('templateid');
             if(!$templateid) $templateid = 1;
        }
        $tpl_dir = isset($templates[$type][$templateid]) ? $templates[$type][$templateid]['directory'] : 'default';
        $tpl_file = "$tpl_dir/$file";

        $tpl_obj->tpl_root_dir = "templates/$type";
        $tpl_obj->cache_root_dir = "data/templates/$type";

    } else {
        show_error(lang('global_template_unknow',_T($type).":{$file}:{$templateid}"));
    }

    $ext = S('tplext') ? S('tplext') : '.htm';
    $ret = $tpl_obj->get_cache_file($tpl_file.$ext, $update);
    if(!$ret) {
        show_error($tpl_obj->error());
    }

    if($type) {
        $define_name = strtoupper($type).'_TPL_DIR';
        !defined($define_name) && define($define_name, URLROOT.'/'.$ret->tpl_url);
        //兼容性
        $_G['tplurl_'.$type] = $ret->tpl_url;
        if(!$templateid) {
            $templateid = (int)S('templateid');
            if(!$templateid) $templateid = 1;
        }
        $tpl_dir = isset($templates['main'][$templateid]) ? $templates['main'][$templateid]['directory'] : 'default';
        $_G['tplurl'] = 'templates/main/'.$tpl_dir.'/';
    }
    return MUDDER_ROOT.$ret->cache_file;
}

//模板尾部
function footer($templateid = '') {
    global $_G, $_CFG;

    if(!$templateid) {
        $mainid = $_G['cfg']['templateid'] ? $_G['cfg']['templateid'] : 1;
    } else {
        $mainid = $templateid;
    }

    $_G['fullalways'] = 1;

    if(function_exists('memory_get_usage')) {
        $_G['memory_end'] = memory_get_usage();
    }
    if($_G['cfg']['scriptinfo']) {
        $mtime = explode(' ', microtime());
        $totaltime = number_format(($mtime[1] + $mtime[0] - $_G['starttime']), 6);
        $gzip = $_G['cfg']['gzipcompress'] ? 'enabled' : 'disabled';
        //页面执行事件和数据库查询次数
        $sitedebug = 'Processed in ' . $totaltime . ' second(s), ' . $_G['db']->query_num . ' queries';
        //内存使用
        if($_G['memory_end']) {
            $sitedebug .= ', Memory ' . size_unit($_G['memory_end']-$_G['memory_start']);
        }
        //在线人数
        $sitedebug .= ', Online ' . _G('session')->get_online_total();
        $sitedebug .= ', Gzip '.$gzip;
    }
    if(!defined('IN_AJAX')) {
        require template('modoer_footer', 'main', $mainid);
    }
    output();
}
//程序结束ob处理
function output() {
    global $_G;
    //处理缓存更新
    //if($_G['datacall']) $_G['datacall']->cachelife();
    $output = ob_get_contents();
    ob_end_clean();
    $_G['cfg']['gzipcompress'] ? @ob_start('ob_gzhandler') : ob_start();
    echo $output;
    exit;
}
//取得语言
function lang($lng, $param = null) {
    global $_G;
    $string = '';
    list($file, $page, ) = explode("_", $lng);
    if(!preg_match("/^[a-zA-Z_\.]+$/", $file)) {
        $string = $lng;
    } elseif(!isset($_G['lng'][$file])) {
        $filename = MUDDER_CORE . 'lang' . DS . $_G['lang_directory'] . DS . $file . '.php';
        if(!is_file($filename)) {
            $string = $lng;
        } else {
			$_G['lng'][$file] = include $filename;
		}
    }
    if(!$string && !isset($_G['lng'][$file][$lng])) {
        $string = $lng;
    } elseif(!$string) {
		$string = $_G['lng'][$file][$lng];
	}
	if(isset($param)&&$param!==false) {
		if(!is_array($param)) $param = array($param);
		$string = vsprintf($string, $param);
	}
    return $string;
}
//跳转
function location($url, $header_301 = false) {
    if(!$url) return;
	$url = str_replace('&amp;', '&', $url);
    if(defined('IN_AJAX')) {
        echo '{ caption:"301",message:' . '"'.$url. '"';
        echo ',extra:"location" }';
        output();
    } else {
        if($header_301) header("HTTP/1.1 301 Moved Permanently");
        header('Location:' . $url);
        exit;
    }
}
//终端程序，抛出错误信息
function show_error($lang, $param=null) {
    global $_G;
    if(preg_match('/^[a-zA-Z0-9_\-]+$/', $lang)) {
        $message = lang($lang);
    } else {
        $message = $lang;
    }
    $trace = debug_backtrace();
    $urlroot = URLROOT;
    ob_end_clean();
    $_G['cfg']['gzipcompress'] ? @ob_start('ob_gzhandler') : ob_start();
	print <<< EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=$_G[charset]">
<meta http-equiv="x-ua-compatible" content="ie=7" />
<link rel="stylesheet" type="text/css" href="$urlroot/static/images/error.css">
</head>
<body>
<div class="error">
    <h3>Modoer reporting</h3>
    <p class="error">$message</p>
</div>
EOT;
    if(DEBUG){
	print <<< EOT
<div class="debug">
    <h3>Debug backtrace</h3>
    <table border='0' cellspacing='0' cellpadding='0' class="trace">
        <tr>
            <th width="400" align="left">File</th>
            <th width="50">Line</th>
            <th align="right">Function</th>
        </tr>
EOT;
        foreach($trace as $k=>$t) {
            //if(!$k) continue;
            echo "<tr><td>".str_replace(MUDDER_ROOT,'',$t['file'])."</td>
            <td align='center'>$t[line]</td>
            <td align='right'>".($t['class']?("{$t['class']}->"):'')."$t[function]()</td></tr>";
        }
	print <<< EOT
    </table>
</div>
EOT;
    }
	print <<< EOT
</body>
</html>
EOT;
exit;
}

//中断程序，输出信息
function diex($msg) {
    global $_CFG;
    include MUDDER_CORE . 'diex.php';
    exit(0);
}
//格式化显示数组
function vp() {
    $in_ajax = _G('in_ajax');
    $args = func_get_args();
    if(!$args) return;
    if(!$in_ajax)echo "<div style=\"border:1px solid #ddd;background:#F7F7F7;padding:5px 10px;\">\r\n";
    foreach($args as $var) {
        if(!$in_ajax)echo "<pre style=\"font-family:Arial,Vrinda;font-size:14px;\">\r\n";
        var_dump($var);
        if(!$in_ajax)echo "\r\n</pre>\r\n";
        if(!$in_ajax) echo "<hr />";
        if($in_ajax) echo "============================\n";
    }
    if(!$in_ajax) echo "</div>";
}
//格式化显示数组
function pr($var) {
    $args = func_get_args();
    if(!$args) return;
    foreach($args as $var) {
        echo "<div style=\"border:1px solid #ddd;background:#F7F7F7;padding:5px 10px;\">\r\n";
        echo "<pre style=\"font-family:Arial,Vrinda;font-size:14px;\">\r\n";
        print_r($var);
        echo "\r\n</pre>\r\n";
        echo "</div>";
    }
}
//显示debug信息
function vp_debug() {
    $trace = debug_backtrace();
    $content = "<div style=\"border:1px solid #ddd;background:#F7F7F7;padding:5px 10px;\">\r\n";
    $content .= "<table>";
    foreach($trace as $k=>$t) {
        $content .= "<tr><td>".str_replace(MUDDER_ROOT, './', $t['file'])."</td>";
        $content .=  "<td>".$t['line']."</td>";
        $content .= "<td>".($t['class']?("{$t['class']}->"):'') . "$t[function]()</td></tr>";
    }
    $content .= "</table></div>";
    echo $content;
}
//获取系统需要的文件修改时间
function myfilemtime($filename) {
    $timezone = _G('timezone');
    return filemtime($filename) + $_G['timezone'] * 3600;
}
//获取一个数字
function get_numeric($num) {
    if(is_numeric($num)&&!strposex($num,'.')) return $num;
    $len = strlen($num);
    $sub = 0;
    for($i=$len-1;$i>=0;$i--) {
        if($num{$i} == '0') $sub++; else break;
    }
    if($sub>0) {
        $num = substr($num, 0, -$sub);
        if(substr($num,-1)=='.') $num = substr($num, 0, -1);
    }
    return $num;
}
//把秒转换成时间
function get_time($time) {
    $h = floor($time / 3600);
    $i = floor(($time  - 3600 * $h) / 60);
    $s = $time  - 3600 * $h - 60 * $i;
    return array($h, $i, $s);
}
/**
 * 获取一个以当前时间的固定格式文字，用于时间为名称的文件名
 * @return string
 */
function get_timename() {
    list($ms, $time) = explode(' ', microtime());
    return date("ymd_His", $time) . '_' . substr($ms, 2, 5);
}
/**
 * 检测一个时间是否再有效期内
 * @param  integer $check_datetime   unix时间戳.需要检测的有效期,输入0则检测当前时间
 * @param  integer $datetime_start unix时间戳.有效期开始时间
 * @param  integer $datetime_end unix时间戳.有效期截至时间
 * @param  string $unit        判断的时间单位， day:精确到天,time:精确到时间
 * @return intrger             在有效期内返回 true，再有效期之后（过期）返回 false; 在有效期之前ze返回 0 
 */
function in_expiry_date($check_datetime, $datetime_start, $datetime_end, $unit = 'day')
{
    if(!$check_datetime) $check_datetime = _G('timestamp');
    if($unit == 'day') {
        $check_datetime = strtotime(date('Y-m-d', _G('timestamp')));
        $datetime_start = strtotime(date('Y-m-d', $datetime_start));
        $datetime_end = strtotime(date('Y-m-d', $datetime_end));
    }
    if($check_datetime < $datetime_start) return 0;
    if($check_datetime > $datetime_end) return false;
    return true;
}
//获取图片连接(预留支持附件服务器)
function get_imageurl($path, $full=true) {
    if(!$full) return URLROOT.'/'.$path;
    return trim(S('siteurl'),'/').'/'.$path;
}
//获取url的内容
function get_url_content($url) {
    $t = parse_url($url);
    $host = $t['host'];
    $file = $t['path'] . '?' . $t['query'];
    $errno = 0;
    $errstr = '';
    $time_out = 10;
    $html = '';
    $fp = @fsockopen($host, 80, $errno, $errstr, $time_out);
    if(!$fp) {
        echo $url;
        echo '<br />';
        echo $errstr;
        return false;
    } else {
        $header = "GET $file HTTP/1.1\r\n";
        $header .= "Host: $host\r\n";
        $header .= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.0; zh-CN; rv:1.9.0.1) Gecko/2008070208 Firefox/4.0.1\r\n";
        $header .= "Referer: http://$host\r\n";
        $header .= "Connection: Close\r\n\r\n";
        @fwrite($fp, $header);
        //跳过HTTP头信息
        while(!feof($fp)) {
            if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) break;
        }
        while (!feof($fp)) {
            $s = @fgets($fp,128);
            $html .= $s;
        }
        @fclose($fp);
        return $html;
    }
}
//获取视频地址
function get_video_url(&$html, $flag) {
    global $_G;
    if($_G['charset'] != $flag['charset']) {
        $_G['loader']->lib('chinese', NULL, FALSE);
        $CHS = new ms_chinese($flag['charset'], $_G['charset']);
        $html = $CHS->Convert($html);
    }
    if(preg_match('%'.$flag['preg'].'%i', $html, $match)) { 
        return $match[1];
    } else {
        return false;
    }
}
//函数调用的默认参数处理
function query_default($params) {
    global $_CITY;
    if(!isset($params['start']) || (!$params['start'] || $params['start'] < 0)) {
        $params['start'] = 0;
    }
    if(isset($params['row'])) $params['rows'] = $params['row'];
    if(!isset($params['rows']) || (!$params['rows'] || $params['rows'] < 0)) {
        $params['rows'] = 10;
    }
    if(!isset($params['cachetime']) || (!$params['cachetime'] || $params['cachetime'] < 0)) {
        $params['cachetime'] = 0;
    }
    if(!isset($params['ordersc']) || strtoupper($params['ordersc'])!='DESC') {
        $params['ordersc'] = 'ASC';
    }
    $cityid = '0,' . $_CITY['aid'];
    if(isset($params['city_id'])) {
        $params['city_id'] = str_replace(array('_NULL_CITYID_', '_CITYID_'), array($cityid, $_CITY['aid']), $params['city_id']);
    }
    if(isset($params['where'])) {
        $params['where'] = str_replace(array('_NULL_CITYID_', '_CITYID_'), array($cityid, $_CITY['aid']), $params['where']);
    }
    return $params;
}
//载入helper/display的函数(仅供模板引擎使用)
function template_print($flag,$fun,$params) {
    global $_G;
    if(!$fun) return '';
    $module = '';
    if($flag && $flag != 'modoer') {
        $modules =& $_G['modules'];
        if(!isset($modules[$flag])) return '';
        $module = $flag;
    }
    $_G['loader']->helper('display', $module);
    $classname = !$module ? 'display' : ('display_' . $module);
    $result = call_user_func(array($classname, $fun), $params);
    return $result;
}
//载入helper/url的函数(仅供模板引擎使用)
function template_url($flag, $params) {
    global $_G;
    list($module, $fun) = explode(':', $flag);
    $modules =& $_G['modules'];
    if($module == 'modoer') $module = '';
    if($module) if(!isset($_G['modules'][$module])) return '';
    $_G['loader']->helper('url', $module);
    $classname = !$module ? 'url' : ('url_' . $module);
    $result = call_user_func(array($classname, $fun), params2array($params));
    return $result;
}
//载入helper/display的函数(PHP内部使用)
function display($flag, $params) {
	global $_G;
	list($module, $fun) = explode(':', $flag);
	$modules =& $_G['modules'];
	if($module == 'modoer') $module = '';
	if($module) if(!isset($_G['modules'][$module])) return '';
	$_G['loader']->helper('display', $module);
	$classname = !$module ? 'display' : ('display_' . $module);
	$result = call_user_func(array($classname, $fun), params2array($params));
	return $result;
}
//获取星星个数
function get_star($point, $scoretype) {
    if($scoretype==5) return round($point);
    if($scoretype==10) return round($point/2);
    if($scoretype==100) return round($point/20);
}
//获取a/b形式到数组array
function params2array($str) {
    if(is_array($str)) return $str;
	$info = explode('/', $str);
	if(count($info)<2) return null;
	$result = array();
    for($i=0; $i<count($info); $i++) {
        $tmp = $info[++$i];
        if($tmp == '') continue;
        $result[$info[$i-1]]= $tmp;
    }
	return $result;
}
//获取seo全局标签值
function get_seo_tags() {
    global $_G;
    return array(
        'site_name' => _G('cfg','sitename'),
        'city_name' => _G('city','name'),
        'module_name' => _G('mod','name'),
        'site_keywords' => _G('cfg','meta_keywords'),
        'site_description' => _G('cfg','meta_description'),
    );
}
//获取替换后的seo设置里的标签
function parse_seo_tags($str,$mapping) {
    if(!$str) return '';
    if(!$mapping||!is_array($mapping)) return $str;
    if(_G('cfg','auto_seo_tag')) {
        foreach($mapping as $key=>$val) {
            if(empty($val)) {
                $f = '{'.$key.'}';
                $i = strpos($str,$f);
                if($i===FALSE) continue;
                $atts = array(',','|','-','_');
                $x = FALSE;
                foreach ($atts as $key) {
                    if($x===FALSE) $x = strpos($str, $key, $i+strlen($f));
                }
                if($x===FALSE) {
                    $str = substr($str, 0, $i);
                } else {
                    $str = substr($str, 0, $i) . substr($str, $x+1);
                }
            }
        } 
    }
    $keys = array();
    foreach(array_keys($mapping) as $val) {
        $keys[] = '{'.$val.'}';
    }
    $str = str_replace($keys,array_values($mapping),$str);
    return preg_replace("/\{[a-z0-9_]+\}/i", "", $str);
}
//判断是否使用总站地址访问
function check_use_global_url($urlact) {
    global $_G;
    if(!$_GET['city_domain'] || !$_G['cfg']['citypath_without']) return false;
    list($mm,$aa) = explode('/', $urlact);
    if($_G['cfg']['city_sldomain']!='2' && $mm!='member') return false;
    if(in_array($urlact, $_G['cfg']['citypath_without'])) return true;
    foreach($_G['cfg']['citypath_without'] as $match) {
        list($m,$a) = explode('/',$match);
        if($mm==$m && $a=='*') return true;
        if($aa==$a && $m=='*') return true;
    }
}
//判断页面内容是否属于当前城市，返回是否URL跳转
function check_jump_city($city_id) {
    global $_CITY;
    if(!$_CITY['aid']) {
        $_CITY = init_city($city_id);
        return false;
    }
    if($city_id && $city_id != $_CITY['aid']) {
        $_CITY = init_city($city_id);
        return $_GET['city_domain']!='';
    } elseif(!$city_id && $_GET['city_domain']) {
        return true;
    }
    if($city_id > 0) init_city($city_id);
}
//判断是否为url
function check_is_url($url) {
    return preg_match('/http:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is', $url);
}
//编码转换
function charset_convert($str, $src='gbk', $dst='utf-8') {
    global $_G;
    if(is_numeric($str)) return $str;
    $_G['loader']->lib('chinese', NULL, FALSE);
    $CHS = new ms_chinese($src, $dst);
    if(is_array($str)) {
        foreach ($str as $key => $value) {
            $str[$key] = $CHS->Convert($value);
        }
    } else {
        $str = $CHS->Convert($str);
    }
    return $str;
}
//清空网站配置缓存
function clear_site_setting_cache() {
    foreach (glob(MUDDER_CACHE."*_*.php") as $filename) {
        if(str_replace(MUDDER_CACHE,'',$filename)!='' && is_file($filename)) {
            @unlink($filename);
        }
    }
}
//写入log
function log_write($file, $log) {
    $yearmonth = gmdate('Ym', _G('timestamp'));
    $logdir = MUDDER_DATA.'logs'.DS;
    $logfile = $logdir.$yearmonth.'_'.$file.'.php';
    if(@filesize($logfile) > 2048000) {
        $dir = opendir($logdir);
        $length = strlen($file);
        $maxid = $id = 0;
        while($entry = readdir($dir)) {
            if(strposex($entry, $yearmonth.'_'.$file)) {
                $id = intval(substr($entry, $length + 8, -4));
                $id > $maxid && $maxid = $id;
            }
        }
        closedir($dir);
        $logfilebak = $logdir.$yearmonth.'_'.$file.'_'.($maxid + 1).'.php';
        @rename($logfile, $logfilebak);
    }
    if($fp = @fopen($logfile, 'a')) {
        @flock($fp, 2);
        $log = is_array($log) ? $log : array($log);
        foreach($log as $tmp) {
            fwrite($fp, "<?PHP exit;?>\t".str_replace(array('<?', '?>'), '', $tmp)."\n");
        }
        fclose($fp);
    }
}
//简单加密
function authcode_ex($string, $operation = 'E') {
    global $_G;
    $key = $_G['cfg']['authkey'];
    $key = md5($key);
    $key_length = strlen($key);
    $string = $operation == 'D'?base64_decode($string):substr(md5($string.$key),0,8) . $string;
    $string_length = strlen($string);
    $rndkey = $box = array();
    $result = '';
    for($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($key[$i % $key_length]);
        $box[$i] = $i;
    }
    for($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    for($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256]));
    }
    if($operation == 'D') {
        if(substr($result,0,8)==substr(md5(substr($result,8).$key),0,8)) {
            return substr($result,8);
        } else {
            return'';
        }
    } else {
        return str_replace('=','',base64_encode($result));
    }
}
//解码js通过 encodeURIComponent 函数编码的字串
function decodeURIComponent($value) {
    $value = rawurldecode($value);
    if(_G('charset') != 'utf-8') {
        $value = charset_convert($value, 'utf-8', _G('charset'));
    }
    return $value;
}
//判断是否手机浏览器
function is_mobile() {
    if($ua = strtolower($_SERVER['HTTP_USER_AGENT'])) {
        $uachar = "/(mobile|iphone|ipad|android|webos|ios|wap|blackberry|meizu|mobi|uc)/i";
        if(preg_match($uachar, $ua)) {
            return true;
        }
    }
    return false;
}
//判断是否ios系统
function is_ios() {
    if($ua = strtolower($_SERVER['HTTP_USER_AGENT'])) {
        $uachar = "/(iphone|ipad|ios)/i";
        if(preg_match($uachar, $ua)) {
            return true;
        }
    }
    return false;
}
//判断是否搜索引擎蜘蛛
function is_spider() {
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    if (!empty($agent)) {
        $spiderSite= array (
            "TencentTraveler", "Baiduspider+", "BaiduGame", "Googlebot", "msnbot", "Sosospider+", "Sogou web spider", "ia_archiver",
            "Yahoo! Slurp", "YoudaoBot", "Yahoo Slurp", "MSNBot", "Java (Often spam bot)", "BaiDuSpider", "Voila", "Yandex bot", "BSpider",
            "twiceler", "Sogou Spider", "Speedy Spider", "Google AdSense", "Heritrix", "Python-urllib", "Alexa (IA Archiver)", "Ask",
            "Exabot", "Custo", "OutfoxBot/YodaoBot", "yacy", "SurveyBot", "legs", "lwp-trivial", "Nutch", "StackRambler", "The web archive (IA Archiver)",
            "Perl tool", "MJ12bot", "Netcraft", "MSIECrawler", "WGet tools", "larbin", "Fish search",
        );
        foreach($spiderSite as $val) {
            $str = strtolower($val);
            if (strpos($agent, $str) !== false) {
                return true;
            }
        }
    } else {
        return false;
    }
}
//debug运行记录
function debug_log($cate,$act,$log) {
    global $_G;
    if(!$cate) $cate = 'common';
    if(!$act) $act = 'common';
    $_G['debug_log'][$cate][$act][] = $log;
}
//获取二维码图片地址
function get_qrcode($content) {
    if(!$src = S('qrcode_api_src')) {
        $src = URLROOT . "/api/qr.php?content={CONTENT}";
    }
    $content = str_replace('&amp;', '&', $content);
    return str_replace('{CONTENT}', rawurlencode($content), $src);
    //https://chart.googleapis.com/chart?cht=qr&chs=120x120&choe=UTF-8&chld=L|4&chl={CONTENT}
}
//清除ubb代码
function strip_msubb($str,$sublen = 0,$extstr = '...') {
    $string = preg_replace("/(\[\/.*?\/\])/i", '', $str);
    $string = preg_replace("/\s*(\r\n|\n\r|\n|\r)\s*/", "", strip_tags($string));
    if($sublen > 0) return '1'.trimmed_title($string, $sublen, $extstr);
    return '2'.$string;
}
//数据调用
function datacall_get($fun, $params, $flag) {
    $DC = _G('loader')->model('datacall');
    return $DC->datacall_get($fun, $params, $flag);
}
//数据调用
function datacall_name($callname) {
    return _G('loader')->model('datacall')->datacallname($callname);
}
//数据调用
function datacall($key) {
    return _G('loader')->model('datacall')->datacallname($key);
}
//模板hook，模板hook没有返回值
function hook() {
    $args = func_get_args();
    if(!$args) return '';
    $hook_name = $args[0];
    $hook_params = isset($args[1]) ? $args[1] : null;
    _G('hook')->hook($hook_name, $hook_params);
}
//POST行为获取页面返回
function http_post($url, $params, $is_data = false) {

    if(!function_exists('curl_exec')) {
        echo '<h3>PHP function (curl_exec) does not exist.</h3>'; exit;
    }

    if($is_data) {
        $postdata = $params;
    } else {
        $postdata = '';
        foreach($params as $key => $value){
            $postdata .= "{$key}={$value}&";
        }
        $postdata  = rtrim($postdata,'&');
    }

    $ch = curl_init ($url."?");
    curl_setopt ($ch, CURLOPT_HEADER, 0);
    curl_setopt ($ch, CURLOPT_POST, 1);
    curl_setopt ($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  false);
    $result = curl_exec ($ch);
    curl_close ($ch);
    return $result;
}
//GET行为获取页面返回
function http_get($url) {
    if (function_exists('curl_exec')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result =  curl_exec($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);
        if($curl_error) {
            $message = "<h3>$curl_error</h3>";
            $message .= "<h5>$url</h5>";
            show_error($message);
        }
        return $result;
    } elseif (ini_get("allow_url_fopen") == "1") {
        $result = file_get_contents($url);
        if($result) return $result;
         $message = "<h3>file_get_contents:failed to open stream!</h3>";
         $message .= "<h5>$url</h5>";
         show_error($message);
    } else {
        $message = '<h3>PHP function (curl_exec) does not exist.</h3>';
        $message .= "<h5>$url</h5>";
        show_error($message);
    }
}
//框架类自动加载
function fm_autoload($classname)
{
    global $_G;
    if (class_exists($classname)) return true;

    $aps    = explode('_', $classname);
    $count  = count($aps);
    if ( ! $aps || $count <= 1) return false;

    //类的前缀
    $pre = trim($aps[0]);
    $aps = array_slice($aps, 1);


    if($pre == 'ms') {  //框架类库

        $filename = MUDDER_CORE.'lib'.DS.implode('_', $aps).'.php';
        if( ! is_file( $filename)) return false;

    } elseif ($pre == 'msm' || $pre == 'mc') { //模块模型和组件

        //文件夹
        $directory = $pre == 'msm' ? 'model' : 'component';

        //核心类库
        $filename_core = MUDDER_CORE.$directory.DS.implode('_', $aps).'_class.php';

        $module = $aps[0];
        $aps = array_slice($aps, 1);

        //现在各个模块里找
        $name = $aps ? implode('_', $aps) : $module;
        $filename = MUDDER_MODULE.$module.DS.$directory.DS.$name.'_class.php';
        if( ! is_file($filename)) {
            //最后在core/model里找
            if( ! is_file( $filename_core)) return false;
            $filename = $filename_core;
        }

    }

    //加载
    if($filename) {
        require_once($filename);
        debug_log('file','autoload',$filename);
    }
    //通过检查是否存在这个类来判断是否有类加载
    return class_exists($classname);
}

//backtrace信息格式化txt
function backtrace_txt($trace_array)
{
    $t = '';
    foreach ($trace_array as $trace) {
        $t .= str_replace(MUDDER_ROOT,DS,str_replace(array("\\","/"),DS,$trace['file']))
            ."\t".$trace['line']
            ."\t".($trace['class']?("{$trace['class']}::"):'')
            .$trace['function']."()\n";
    }
    $t .= request_uri()."\n";
    return $t;
}

/**
 * 浏览器友好的变量输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
function dump($var, $echo=true, $label=null, $strict=true) {
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    }else
        return $output;
}


spl_autoload_register( 'fm_autoload' );
?>