<?php
define('IN_LICCODE', FALSE);
if (!$in_ajax && $module && $act && !in_array("$module:$act", array(
    "modoer:cpmenu",
    "modoer:cphome"
))) {
    $oplog = "";
    $oplog.= date('Y-m-d H:i:s', $_G['timestamp']) . "\t" . $admin->adminname . "\t" . $_G['ip'] . "\t";
    $oplog.= "$module:$act" . "\t" . serialize($_GET) . "\t" . serialize($_POST);
    log_write('adminop', $oplog);
}
$_G['loader']->helper('form');
if (empty($module) || $module == 'modoer') {
    $module = 'modoer';
    if (empty($act)) {
        $tab = 'home';
        include MUDDER_ADMIN . 'cpindex.inc.php';
        exit(0);
    }
    if ($act == 'cphome') {
        $licinfo = $_G['siteinfo'];
        $___include_js = TRUE;
    }
    if (!$admin->check_access('modoer', $act, _input('op')) && !in_array($act, array(
        'cpheader',
        'cpmenu',
        'cphome',
        'help',
        'admin'
    ))) {
        redirect('global_op_access');
    }
    if ($act == 'license') {
		
    } else {
        $actfile = MUDDER_ADMIN . $act . '.inc.php';
        if (!is_file($actfile)) show_error(lang('global_file_not_exist', '[ADMIN_DIR]' . DS . $act . '.inc.php'));
        include $actfile;
    }
    $acts = array(
        'cpheader',
        'cpmenu'
    );
    if (!$in_ajax && !in_array($act, $acts)) cpheader();
    if ($admin->tplname) {
        if (!is_file(MUDDER_CORE . $admin->tplname)) {
            show_error(lang('global_file_not_exist', $admin->tplname));
        }
        include MUDDER_CORE . $admin->tplname;
    }
    if (!$in_ajax && !in_array($act, $acts)) cpfooter();
    if ($___include_js) {
        $output = ob_get_contents();
        ob_end_clean();
        $urls = str_replace('&amp;', '&', http_query(cp_getmodoerinfo()));
        $jssrc = "<script type=\"text/javascript\" src=\"http://www.modoer.com/version.php\"></script>";
        if ($i = strrpos($output, '</body>')) {
            $output = substr($output, 0, $i) . $jssrc . substr($output, $i);
        } else {
            $output.= $jssrc;
        }
        $_G['cfg']['gzipcompress'] ? @ob_start('ob_gzhandler') : ob_start();
        echo $output;
        exit;
    }
} elseif (isset($_G['modules'][$module])) {
    if (!$admin->check_access($module, $act, _input('op'))) redirect('global_op_access');
    $adminfile_path = 'modules' . DS . $module;
    require_once MUDDER_CORE . $adminfile_path . DS . 'common.php';
    if (preg_match("/^[0-9a-z\_\.]+$/i", $act)) {
        $actfile = MOD_ROOT . 'admin' . DS . $act . '.inc.php';
        if (!is_file($actfile)) {
            show_error(lang('global_file_not_exist', $_G['modules'][$module]['directory'] . DS . 'admin' . DS . $act . '.inc.php'));
        }
        include $actfile;
        if (!$in_ajax) cpheader();
        if ($admin->tplname) {
            if (!is_file(MUDDER_CORE . $admin->tplname)) {
                show_error(lang('global_file_not_exist', $admin->tplname));
                include MUDDER_CORE . $admin->tplname;
            }
            include MUDDER_CORE . $admin->tplname;
        }
        if (!$in_ajax) cpfooter();
    } else {
        show_error(lang('global_op_unkown'));
    }
} else {
    show_error(lang('global_not_found_module', $module));
}
function cp_getmodoerinfo() {
    global $_G, $_MODULES;
    $params = array();
    $params['v'] = $_G['modoer']['version'];
    $params['b'] = $_G['modoer']['build'];
    $params['r'] = $_G['modoer']['releaseid'];
    $params['m'] = $_G['db']->version();
    $params['d'] = get_fl_domain();
    $params['t'] = _G('cfg', 'sitename');
    $params['w'] = $_SERVER['SERVER_SOFTWARE'];
    $params['c'] = _G('charset');
    $params['p'] = phpversion();
    $params['ms'] = implode(',', array_keys($_MODULES));
    return $params;
}

function cplicstatus() {
    return false;
}

function cplocalck() {
    $cd = get_current_domain();
    $lt = array(
        'www.demo.com',
		'localhost',
        '127.0.0.1'
    );
    return in_array(strtolower($cd) , $lt);
}
function cpequalck($dm) {
    $dm = strtolower($dm);
    $domain = get_fl_domain();
    if ($domain == $dm) return true;
    if (strlen($dm) > strlen($domain)) if (substr($dm, -(strlen($domain) + 1)) == '.' . $domain) return true;
    if (strlen($domain) > strlen($dm)) if (substr($domain, -(strlen($dm) + 1)) == '.' . $dm) return true;
    return false;
}
function cp_module_asname($keyname) {
    $module_as = array(
        'fenlei' => 'M1',
        'party' => 'M2',
        'tuan' => 'M3',
        'product_pro' => 'M4',
        'ask' => 'M5'
    );
    return $module_as[$keyname];
}
function cp_module_install_check($module) {
    $module_ac = array(
        'F1' => 'NULL',
        'F2' => 'M1,M2,M3,M4',
        'F3' => 'M1,M2,M3,M4,M5',
        'F4' => 'ALL',
        'F10' => 'NULL'
    );
    $keyname = $module['flag'] . ($module['extra'] ? ("_{$module['extra']}") : '');
    $asname = cp_module_asname($keyname);
    if (!$asname) return true;
    $vername = $module_ac['F' . $sitelic['v']];
    if (!$vername) return false;
    if ($vername == 'ALL') return true;
    if ($vername == 'NULL') return false;
    $vername_arr = explode(',', $vername);
    return in_array($asname, $vername_arr);
}

