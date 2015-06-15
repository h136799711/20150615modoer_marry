<?php

define('IN_EWTRYTU', '20140336');
if (!defined('IN_OKNJUYGF')) {
    show_error('File Error.');
    exit(0);
}
!defined('IN_MUDDER') && exit('Access Denied');
class msm_cpuser extends msm_admin {
    var $isLogin = FALSE;
    var $access = '';
    var $tplname = '';
    var $id = 0;
    var $is_founder = 'N';
    var $mymodules = array();
    var $mycitys = array();
    var $session = null;
    var $offtime = 3600;
    var $session_table = 'dbpre_admin_session';
    function __construct() {
        parent::__construct();
        $this->offtime = (int)$this->global['admincp']['offtime'];
        if ($this->offtime < 60) $this->offtime = 3600;
        $this->delete_session();
        $hash_str = _cookie('admin_hash', '');
        list($admin_id, $admin_hash) = explode("\t", authcode_ex($hash_str, 'D'));
        $admin_id = (int)$admin_id;
        $admin_hash = $admin_hash;
        if ($admin_id) {
            $this->get_session($admin_id);
            if (!$this->session) {
                return;
            }
            $admin = $this->read($admin_id);
            if (strcasecmp($admin_hash, $this->session['adminhash']) == 0 && strcasecmp($this->session['adminhash'], $this->get_hash($admin['adminid'], $admin['password'])) == 0) {
                $this->_set_variable($admin);
                $this->isLogin = TRUE;
                $this->adminhash = $this->session['adminhash'];
                $this->access = $this->get_access();
            }
        }
        if (!$this->isLogin) $this->errmsg = lang('admincp_login_invalid');
    }
    function login() {
        $username = _post('admin_name', ' ', MF_TEXT);
        $password = _post('admin_pw', '', MF_TEXT);
        if (!$username || !$password) {
            return $this->add_error('admincp_login_invalid');
        }
        if (_G('cfg', 'console_seccode')) {
            $sec_obj = new ms_seccode();
            if (!$sec_obj->check(_post('seccode'))) {
                return $this->add_error($sec_obj);
            }
        }
        $logs = "";
        $logs.= date('Y-m-d H:i:s', $this->timestamp) . "\t" . $username . "\t" . $this->global['ip'];
        $succeed = false;
        $admin_md5pwd = md5($password);
        $admin = $this->db->from($this->table)->where('adminname', $username)->get_one();
        if ($admin['adminid'] > 0 && $admin['password'] == $admin_md5pwd) {
            $succeed = true;
            $private_key = strtolower(random(8));
            $newpassword = md5($private_key . $password);
            $admin['password'] = $newpassword;
            $update = array(
                'password' => $newpassword,
                'private_key' => $private_key,
            );
            $this->db->from($this->table)->set($update)->where('adminid', $admin['adminid'])->update();
        } elseif ($admin['adminid'] > 0 && $admin['private_key']) {
            $succeed = strcasecmp(md5($admin['private_key'] . $password) , $admin['password']) == 0;
        }
        if ($succeed) {
            $logs.= 'Login successfully.';
            log_write('adminlogin', $logs);
            $this->adminid = (int)$admin['adminid'];
            $this->adminname = $admin['adminname'];
            $this->adminhash = $this->get_hash($admin['adminid'], $admin['password']);
            $this->adminuniq = _G('session')->get_id();
            $this->record_login();
            if ($this->get_access() == '1') {
            }
            $hash_str = $this->adminid . "\t" . $this->adminhash;
            set_cookie("admin_hash", authcode_ex($hash_str, 'E'));
            return true;
        } else {
            $logs.= 'Logon failed.';
            log_write('adminlogin', $logs);
            $this->clearvar();
            return $this->add_error('admincp_login_invalid');
        }
    }
    function logout() {
        _G('session')->admin_id = _G('session')->admin_hash = '';
        if ($this->adminid) {
            $this->db->from($this->session_table);
            $this->db->where('adminid', $this->adminid);
            if ($this->global['admincp']['checkip']) {
                $this->db->where("ipaddress", $this->global['ip']);
            }
            $this->db->delete();
            $this->clearvar();
        }
        return true;
    }
    function clearvar() {
        $this->adminid = 0;
        $this->adminname = '';
        $this->email = '';
        $this->admintype = 0;
        $this->is_founder = false;
        $this->isLogin = false;
        $this->adminhash = '';
        $this->access = 0;
    }
    function get_access() {
        if ($this->global['cfg']['adminipaccess'] && !check_ipaccess($this->global['cfg']['adminipaccess'])) {
            return 2;
        }
        if ($this->closed) {
            return 3;
        }
        if (!$this->session) {
            $this->get_session($this->adminid);
        }
        if (!$this->session) {
            if (!$this->check_mycity()) return 4;
            $this->insert_session();
            return 11;
        }
        if ($this->session['errno'] == - 1) {
            if (!$this->check_mycity()) return 4;
            $this->update_session();
            return 11;
        } elseif ($session['errno'] <= 3) {
            return 1;
        } else {
            return 0;
        }
    }
    function _set_variable(&$admin) {
        foreach ($admin as $key => $val) {
            $this->$key = $val;
            if ($key == 'mymodules' && $val != '') {
                $this->mymodules = explode(',', $val);
            } elseif ($key == 'mycitys' && $val != '') {
                $this->mycitys = explode(',', $val);
            }
        }
        $this->is_founder = $this->is_founder == 'Y';
        if ($this->is_founder) {
            $this->mymodules = array(
                'modoer'
            );
            foreach ($this->global['modules'] as $k => $v) {
                $this->mymodules[] = $v['flag'];
            }
        }
    }
    function get_hash($id, $pw) {
        return md5($id . $pw . $this->global['cfg']['authkey']);
    }
    function record_login() {
        $this->db->from($this->table);
        $this->db->where('adminid', $this->adminid);
        $this->db->set('logintime', $this->global['timestamp']);
        $this->db->set('loginip', $this->global['ip']);
        $this->db->set_add('logincount', 1);
        $this->db->update();
    }
    function insert_session() {
        $this->db->set('adminid', $this->adminid);
        $this->db->set('adminhash', $this->adminhash);
        $this->db->set('ipaddress', $this->global['ip']);
        $this->db->set('lasttime', $this->global['timestamp']);
        $this->db->set('errno', -1);
        $this->db->from($this->session_table);
        $this->db->insert();
    }
    function get_session($adminid) {
        if (!$adminid) return;
        $this->db->from($this->session_table);
        $this->db->where("adminid", $adminid);
        if ($this->global['admincp']['checkip']) {
            $this->db->where("ipaddress", $this->global['ip']);
        }
        $this->db->where_more("lasttime", $this->global['timestamp'] - $this->offtime);
        $this->session = $this->db->get_one();
    }
    function update_session() {
        $this->db->from($this->session_table);
        $this->db->set('lasttime', $this->global['timestamp']);
        $this->db->set('adminhash', $this->adminhash);
        $this->db->set('errno', '-1');
        $this->db->where('adminid', $this->adminid);
        if ($this->global['admincp']['checkip']) {
            $this->db->where("ipaddress", $this->global['ip']);
        }
        $this->db->update();
    }
    function delete_session() {
        $this->db->from($this->session_table);
        $this->db->where_less("lasttime", $this->global['timestamp'] - $this->offtime);
        $this->db->delete();
    }
    function get_online_sessions() {
        $this->db->join($this->session_table, 'ass.adminid', 'dbpre_admin', 'a.adminid');
        $this->db->select('ass.ipaddress,ass.lasttime,a.adminid,a.adminname');
        $this->db->order_by('ass.lasttime', 'DESC');
        $query = $this->db->get();
        return $query;
    }
    function check_global() {
        return $this->is_founder || in_array(-1, $this->mycitys);
    }
    function check_mycity() {
        $aid = $this->global['city']['aid'];
        $result = $this->is_founder || in_array($aid, $this->mycitys);
        if (!$result && $this->mycitys) {
            $new_cityid = $this->mycitys[0] != - 1 ? $this->mycitys[0] : $this->mycitys[1];
            if ($new_cityid > 0) {
                $this->global['city'] = init_city($new_cityid);
                return TRUE;
            }
            return FALSE;
        } elseif ($result) {
            return TRUE;
        }
        return FALSE;
    }
    function get_mymodules() {
        return $this->mymodules;
    }
    function check_access($module = 'modoer', $act = null, $op = null) {
        if ($module == 'ALL' || $this->is_founder) return true;
        !empty($act) && $act = "|$act";
        !empty($op) && $op = "|$op";
        $hash = $module . $act . $op;
        if (in_array($hash, $this->mymodules)) {
            return true;
        }
        $cpmenu = new ms_cpmenu();
        $menus = $cpmenu->load($module);
        if (isset($menus[$hash])) return false;
        return true;
    }
    function check_access_menu($hash) {
        if ($module == 'ALL' || $this->is_founder) return true;
        return in_array($hash, $this->mymodules);
    }
    function check_access_module($module = 'modoer') {
        if ($module == 'ALL' || $this->is_founder) return true;
        foreach ($this->mymodules as $value) {
            if ("$module|" == substr($value, 0, strlen($module) + 1)) return true;
        }
        return false;
    }
} 
?>
