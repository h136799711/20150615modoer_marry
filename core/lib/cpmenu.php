<?php
/**
* 后台菜单载入
* @author moufer<moufer@163.com>
* @copyright modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class ms_cpmenu {

    private $_menus = array();
    public $_tabs = array();

    public function load($module) {
        if(isset($this->_menus[$module])) return $this->_menus[$module];
        $menus = array();
        if($module == 'modoer') {
            $menus = $this->_load_system();
        } else {
            $menus = $this->_load_module($module);
        }
        $this->_menus[$module] = $this->_format($module, $menus);
        return $this->_menus[$module];
    }

    public function get_tab($hash) {
        foreach ($this->_tabs as $key => $value) {
            if(in_array($hash, $value)) return $key;
        }
        return false;
    }

    private function set_tab($key, $menus) {
        $newmenus = array();
        foreach ($menus as $value) {
            if(strpos($value, '|') === FALSE) continue;
            list($module,$title,) = explode('|', $value);
            $keyname = str_replace("|$title", '', $value);
            $newmenus[] = $keyname;
        }
        $this->_tabs[$key] = $newmenus;
    }

    private function _load_system() {
        global $_G;
        $menulist = array();
        foreach (array('setting','website','module') as $keyname) {
            foreach (lang('admincp_menu_arrry_' . $keyname) as $menus) {
                $arr = (array)$menus;
                $menulist = array_merge($menulist, $arr);
                $this->set_tab($keyname, array_values($menulist));
            }
        }
        return $menulist;
    }

    private function _load_module($module) {
        global $_G;
        $path = 'modules' . DS . $module;
        $file = $path . DS . 'admin' . DS . 'menus.inc.php';
        if(!is_file(MUDDER_CORE . $file)) {
            return;
            show_error(lang('global_file_not_exist', MUDDER_CORE . $file));
        }
        include MUDDER_CORE . $file;
        if(empty($modmenus) || !is_array($modmenus)) show_error('admincp_module_menu_empty');
        foreach($modmenus as $key => $val) {
            if(is_string($val)) {
                $result[] = $val;
            } elseif(is_array($val)) {
                foreach($val as $_key => $_val) {
                    //if($_key=='title') continue;
                    if(is_string($_val)) {
                        $result[] = $_val;
                    }
                }
            }
        }
        $this->set_tab($module, array_values($result));
        return $result;
    }

    private function _format($in_module,$menus) {
        $newmenus = array();
        foreach ($menus as $key => $value) {
            if(strpos($value, '|') === FALSE) continue;
            list($module,$title,) = explode('|', $value);
            if($module != $in_module) continue; //剔除不是本模块的
            $keyname = str_replace("|$title", '', $value);
            $newmenus[$keyname] = $title;
        }
        return $newmenus;
    }
}
/* end */