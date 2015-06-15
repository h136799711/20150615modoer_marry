<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_module extends ms_model {

    var $table = 'dbpre_modules';
    var $key = 'moduleid';

    function __construct() {
        parent::__construct();
    }

    function read($key, $isflag=False) {
        $result = '';
        if(!$isflag) {
            $result = parent::read($key);
        } else {
            $this->db->from($this->table);
            $this->db->where('flag', $key);
            if($r = $this->db->get()) {
                $result = $r->fetch_array();
            }
        }
        return $result;
    }

    function  read_config($key, $isflag=False) {
        $result = '';
        if(!$info = $this->read($key, $isflag)) {
            return $result;
        }
        $result = $info['config'];
        return $result;
    }

    function read_all($disable=null) {
        $this->db->from($this->table);
        $this->db->select('moduleid,flag,iscore,name,directory,disable,author,version,releasetime,reliant,siteurl,listorder');
        if($disable || is_numeric($disable)) {
            $this->db->where('disable', 0);
        }
        $this->db->order_by(array('listorder'=>'ASC','iscore'=>'DESC','moduleid'=>'ASC'));
        $result = $this->db->get();
        return $result;
    }

    function update($post, $keyvalue) {
        parent::save($post, $keyvalue, FALSE);
        $this->write_cache();
    }

    function list_update($post) {
        if(!is_array($post)) redirect('global_op_unselect');
        foreach($post as $id => $val) {
            $this->db->from($this->table);
            $this->db->set($val);
            $this->db->where('moduleid',$id);
            $this->db->update();
        }
        $this->write_cache();
        $C =& $this->loader->model('config');
        $C->write_cache();
    }

/*
    function save_modcfg(& $cfg, $flag) {
        $this->db->from($this->table);
        $this->db->where('flag', $flag);
        $this->db->set('config', serialize($cfg));
        $this->db->update();
        $this->write_cache_config($flag, 1);
    }
*/
    function versioncheck($keyvalue) {
        $moduleid = (int) $moduleid;
        $moduleinfo = $this->read($keyvalue);
        if(empty($moduleinfo['checkurl'])) {
            redirect(lang('admincp_module_no_versioncheck', $moduleinfo['name']));
        }
        $newversion = @file_get_contents($moduleinfo['checkurl']);
        return $newversion;
    }

    function install_check($directory) {
        empty($directory) and redirect('admincp_module_install_empty_dir');

        !is_dir(MUDDER_MODULE . $directory) and redirect('admincp_module_install_dir_not_exist');

        $newmodule = array();
        if(!@include(MUDDER_MODULE . $directory . DS . 'install' . DS . 'info.php')) {
            redirect('admincp_module_install_can_not_read_module');
        }
        if(empty($newmodule) || empty($newmodule['flag'])) {
            redirect('admincp_module_install_empty_module');
        }
		if(!$newmodule['support_mc']) redirect('admincp_module_install_not_support_mc');
        if($newmodule['support_mc'] != '1' && $newmodule['support_mc'] > _G('modoer','version')) {
            redirect('admincp_module_install_not_support_version');
        }
		unset($newmodule['support_mc']);

        $this->db->where('flag', $newmodule['flag']);
        $this->db->from($this->table);
        $modexist = $this->db->count() >= 1;
        $modexist and redirect(lang('admincp_module_install_exist_module', $newmodule['name']));

        return $newmodule;
    }

    function install($configfile, $newmodule) {
        $moduleconfig = array();
        if(is_file($configfile)) {
            include $configfile;
        }
        $this->save($newmodule);
        $this->write_cache();

        $C =& $this->loader->model('config');
        $C->save($moduleconfig, $newmodule['flag'], TRUE); //同时更新js
    }

    function update_check(&$detail) {
        $flag = $detail['flag'];
        $infofile = MUDDER_MODULE . $flag . DS . 'install' . DS . 'info.php';
        if(!is_file($infofile)) return false;
        $newmodule = array();
        include $infofile;
        if(!$newmodule) return false;
        if(isset($newmodule['version']) && $newmodule['version'] > $detail['version']) {
            return $newmodule['version'];
        }
        return false;
    }

    function reliant_check($keyvalue, $isflag=FALSE) {
        if(!$isflag) {
            $info = $this->read($keyvalue);
            $flag = $info['flag'];
        } else {
        	$flag = $keyvalue;
        }
        $this->db->from($this->table);
        $this->db->where_like('reliant', "%{$flag}%");
        $existreliant = $this->db->count() >= 1;

        return $existreliant;
    }

    function uninstall($keyvalue) {
        if(is_numeric($keyvalue)) {
            $info = $this->read($keyvalue);
            $moduleid = $keyvalue;
        } else {
            $info = $keyvalue;
            $moduleid = $info['moduleid'];
        }
        $dir = MUDDER_MODULE . $info['flag'];
        /*
        if($dir != MUDDER_ROOT && (strlen($dir) - strlen(MUDDER_ROOT) > 2)) {
            //$this->global['loader']->helper('dir');
            //dir_remove($dir);
        }
        */
        $this->db->from('dbpre_config');
        $this->db->where('module', $info['flag']);
        $this->db->delete();
        @unlink(MUDDER_ROOT . 'data' . DS . 'cachefiles' . DS . $info['flag'] . '_config.php');

        $this->delete($moduleid);
        $this->write_cache();
    }

    function load_local_modules() {
        $dirs = array();
        if (is_dir(MUDDER_MODULE)) {
            if ($dh = opendir(MUDDER_MODULE)) {
                while (($file = readdir($dh)) !== false) {
                    if($file=='.'||$file=='..'||$file=='modoer'||$file=='system') continue;
                    if(filetype(MUDDER_MODULE . $file) == 'dir') $dirs[] = $file;
                }
                closedir($dh);
            }
        }
        if(!$dirs) return;
        $modules = array();
        foreach($dirs as $dn) {
            $ifile = MUDDER_MODULE . $dn . DS . 'install' . DS . 'info.php';
            if(!is_file($ifile)) continue;
            $newmodule = array();
            include $ifile;
            if(!$newmodule) continue;
            foreach(array('flag','name','version','support_mc') as $key) {
                if(!$newmodule[$key]) continue;
            }
            $newmodule['directory'] = $dn;
            $modules[$newmodule['flag']] = $newmodule;
        }
        return $modules;
    }

    function uninstalled() {
        $allmodules = $this->load_local_modules();
        $result = array();
        foreach ($allmodules as $flag => $value) {
            if(!check_module($flag)) $result[$flag] = $value;
        }
        return $result;
    }

    function write_cache($return = FALSE) {
        $result = array();

        $this->db->select('moduleid,flag,extra,iscore,name,directory,disable,liccode');
        $this->db->from($this->table);
        $this->db->where('disable',0);
        $this->db->order_by('listorder');
        $row = $this->db->get();

        if($row) {
            while($value = $row->fetch_array()) {
                $result[$value['flag']] = $value;
            }
            $row->free_result();
        }
        ms_cache::factory()->write('modoer_modules', $result);
        //write_cache('modules', arrayeval($result), $this->model_flag);
        if($result) return $result;
    }

}
?>