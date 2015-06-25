<?php
/**
* System init
* @author moufer<moufer@163.com>
* @copyright (C)2001-2012 Moufersoft
*/
define('IN_MUDDER',     TRUE);
define('DEBUG',         TRUE);
define('DS',            DIRECTORY_SEPARATOR);

define('MUDDER_CORE',   dirname(__FILE__) .  DS);
define('MUDDER_ROOT',   dirname(MUDDER_CORE) . DS);
define('MUDDER_DATA',   MUDDER_ROOT . 'data' . DS);
define('MUDDER_CACHE',  MUDDER_DATA . 'cachefiles' . DS);
define('MUDDER_MODULE', MUDDER_CORE . 'modules' . DS);
define('MUDDER_PLUGIN', MUDDER_CORE . 'plugins' . DS);
define('MUDDER_UPLOAD', MUDDER_ROOT . 'uploads' . DS);
define('MUDDER_TEMPLATE', MUDDER_ROOT . 'templates' . DS);
define('MUDDER_DOMAIN', $_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME']);

if(DEBUG) {
    error_reporting(E_ALL & ~(E_STRICT|E_NOTICE));
    @ini_set('display_errors', 'On');
} else {
    error_reporting(0);
    @ini_set('display_errors', 'Off');
}

if(function_exists('set_magic_quotes_runtime')) {
    @set_magic_quotes_runtime(0);
}

$_G = $_C = $_CFG = $_HEAD = $_QUERY = $MOD = array();

if(function_exists('memory_get_usage')) {
    $_G['memory_start'] = memory_get_usage();
}

$_G['mtime'] = explode(' ', microtime());
$_G['starttime'] = $_G['mtime'][1] + $_G['mtime'][0];

require MUDDER_DATA . 'config.php';
require MUDDER_CORE . 'version.php';

//timezone
if(function_exists('date_default_timezone_set')) {
    if($_G['timezone'] == 8) $_G['timezone'] = 'Asia/Shanghai';
    @date_default_timezone_set($_G['timezone']);
}

define('TIMESTAMP', time());
$_G['timestamp'] = TIMESTAMP;

header('Content-type: text/html; charset=' . $_G['charset']);

require MUDDER_CORE . 'function.php'; // global function
require MUDDER_CORE . 'loader.php';

// web info
$_G['web'] = get_webinfo();
$_G['ip'] = get_ip();

define('SELF', $_G['web']['self']);
define('URLROOT', get_urlroot());

if($_G['attackevasive'] && (!defined('IN_ADMIN') || SCRIPTNAV != 'seccode')) {
    include MUDDER_CORE . 'fense.php';
}

$_G['loader'] = new ms_loader();
//包含core/lib/cache.php文件
$_G['loader']->helper('cache');

//database
$_G['db'] = new ms_activerecord($_G['dns']);
//dbcache
$_G['dbcache'] = $_G['loader']->model('dbcache');
$_G['dbcache']->find('comm_dbcache_expire,comm_task_nexttime,comm_session_expire,comm_session_online');

//input
$_G['cookie'] = $_G['loader']->cookie();
//从数据库config表中获取配置数据并缓存.
$_G['cfg'] = $_G['loader']->variable('config');
//从数据库modules表中获取配置数据并缓存.
$_G['modules'] = $_G['loader']->variable('modules');

$_C =& $_G['cookie'];
$_MODULES =& $_G['modules'];
$_CFG =& $_G['cfg'];

/*
if(!$_CFG || !$_G['modules']) {
    include MUDDER_MODULE . 'modoer' . DS . 'inc' . DS . 'cache.php';
    $_G['modules'] = read_cache(MUDDER_CACHE . 'modoer_modules.php');
    foreach(array_keys($_G['modules']) as $flag) {
        $file = MUDDER_MODULE . $flag . DS . 'inc' . DS . 'cache.php';
        if(is_file($file)) include $file;
    }
    show_error('global_cache_succeed');
}
*/

//ob
if($_CFG['gzipcompress'] && function_exists('ob_gzhandler')) {
    @ob_start('ob_gzhandler');
} else {
    $_CFG['gzipcompress'] = 0;
    ob_start();
}

$_CFG['siteurl'] = trim($_CFG['siteurl'], '/') . '/';

if($_CFG['siteclose'] && !defined('IN_ADMIN') && $_GET['act'] != 'seccode') {
    show_error($_CFG['closenote']);
}
if($_CFG['useripaccess'] && !check_ipaccess($_CFG['useripaccess'])) {
    show_error(lang('global_ip_without_list'));
}
if($_CFG['ban_ip'] && check_ipaccess($_CFG['ban_ip'])) {
    show_error(lang('global_ip_not_have_access'));
}

//session
$_G['session'] = $_G['loader']->model('session');
//dump($_G['session']);
//hook
$_G['hook'] = $_G['loader']->lib('hook');
//dump($_G['hook']);

//url
$_G['url'] = $_G['loader']->lib('url');
if($_G['url']->is_404()) http_404();
//input
define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
if(MAGIC_QUOTES_GPC) {
    $_POST = strip_slashes($_POST);
    $_GET = strip_slashes($_GET, TRUE);
    $_COOKIE = strip_slashes($_COOKIE, TRUE);
    $_REQUEST = strip_slashes($_REQUEST);
}

if(!defined('IN_ADMIN')) {
    $_POST = strip_sql($_POST);
    $_GET = strip_sql($_GET);
    $_COOKIE = strip_sql($_COOKIE);
    $_REQUEST = strip_sql($_REQUEST);
}

//ajax
if($_G['in_ajax'] = _input('in_ajax', 0, MF_TEXT)) {
    define('IN_AJAX', TRUE);
    $_G['output_charset'] = _input('output_charset', 0, MF_TEXT);
}
//json
if($_G['in_json'] = _input('in_json', 0, MF_TEXT)) {
    define('IN_JSON', TRUE);
}

//iframe
if($_G['in_iframe'] = _input('in_iframe', 0, MF_TEXT)) {
    define('IN_IFRAME', TRUE);
}

/*
if(!$in_ajax && function_exists('get_headers')) {
    if($headers = @get_headers($php_self, 1)) {
        $_G['in_ajax'] = $headers['X-Requested-With'] == 'XMLHttpRequest';
    }
}
*/

//init hook
$_G['hook']->hook('init');

//city
$_G['city'] = array();
$_CITY =& $_G['city'];

if($_CITY = get_city())
{
    init_city($_CITY['aid']);
}
if(!$_G['in_ajax'] && !defined('IN_ADMIN'))  {
    //rewrite_location 301
    if($_CFG['rewrite_location'] && !$_G['url']->rewirte_mod_compare())
    {
//		dump(($_G['url']->get_url_exp()));
//		dump(url($_G['url']->get_url_exp(), '', true));
        location(url($_G['url']->get_url_exp(), '', true), true);
    }
    //global url
    if($_G['url']->get_sldomain() && $_G['url']->global_url($_GET['m'], $_GET['act']))
    {
    	
//		dump(($_G['url']->get_url_exp()));
//		dump(url($_G['url']->get_url_exp(), '', true));
        location(url($_G['url']->get_url_exp(), '', true), true);
    }
}

//sldomain hook
if($_GET['unkown_sldomain']) {
//  $_G['hook']->hook('init_sldomain', $_GET['unkown_sldomain'], MF_HOOK_RETURN_BREAK);
}

// mutipage分页
$_GET['page'] = (int) _get('page');
$_GET['page'] = $_GET['page'] < 1 ? 1 : $_GET['page'];
$_GET['offset'] = (int) _get('offset');
$_GET['offset'] = $_GET['offset'] < 1 ? 20 : $_GET['offset'];

// header form
$_G['random'] = random(5);
$_G['show_sitename'] = TRUE;
$_HEAD['title'] = $_CFG['sitename'] . $_CFG['titlesplit'] . $_CFG['subname'];
$_HEAD['keywords'] = $_CFG['meta_keywords'];
$_HEAD['description'] = $_CFG['meta_description'];
$_HEAD['css'] = '';
$_HEAD['js'] = '';

if(defined('IN_ADMIN') && !preg_match("/^[0-9A-Za-z_\.\/]+$/", $_G['web']['self'])) {
//  location($_CFG['siteurl']);
}
// datacall
$_G['datacall'] = $_G['loader']->model('datacall');
//$_G['datacall']->plan_delete();

//dump($_G['datacall']);

//user login
if(!defined('IN_ADMIN')) {
    $_G['user'] = $_G['loader']->model('member:user');
    $_G['user']->login->remember();
    //login access
    if($_G['user']->get_access('member_forbidden') && $_GET['act'] != 'login')
    {
        show_error('member_access_forbidden');
    }
    //session
    if ($_G['user']->isLogin) {
        $_G['session']->set_uid($_G['user']->uid);
    }
    //global
    $user =& $_G['user'];
}
//init end hook
$_G['hook']->hook('init_end');

//plan task ，计划任务
if(!$_G['in_ajax']) {
    $cache_task_nexttime = $_G['dbcache']->fetch('comm_task_nexttime');
    if($cache_task_nexttime===false||$cache_task_nexttime<$_G['timestamp']) {
        $plan_task_obj = new msm_plan_task();
        $plan_task_obj->run();
        unset($plan_task_obj);
    }
    unset($cache_task_nexttime);
}
/* end */