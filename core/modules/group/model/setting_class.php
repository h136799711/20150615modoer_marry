<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
class msm_group_setting extends ms_model {

    var $table = 'dbpre_group_setting';
	var $key = 'id';
    var $model_flag = 'group';

    var $typenames = array();
    var $typeurls = array();
    var $idtypes = array();

    var $keys = array(
        'jointype' => ':_set_jointype',
        'readaccess' => ':_set_readaccess',
        'needtypeid' => ':_set_needtypeid',
        'blacklist' => MF_TEXT,
        'postaccess' => ':_set_postaccess',
    );

    var $_gid = 0;

	function __construct() {
		parent::__construct();
        $this->model_flag = 'group';
        $this->modcfg = $this->variable('config');
	}

    function _run_key_fun($fun,$str) {
        if(is_string($fun) && substr($fun, 0,1)==':') {
            return call_user_func(array($this,substr($fun, 1)), $str);
        } else {
            return call_user_func($fun, $str);
        }
    }

    function _set_jointype($type) {
        $type = (int) $type;
        if($type < 0 || $type > 1) redirect('对不起，你选择的会员加入方式有误。');
        return $type;
    }

    function _set_readaccess($type) {
        $type = (int) $type;
        if($type < 0 || $type > 1) redirect('对不起，你选择的浏览权限设置有误。');
        return $type;
    }

    function _set_needtypeid($type) {
        $type = (int) $type;
        if($type < 0 || $type > 2) redirect('对不起，你选择的话题分类设置有误。');
        return $type;
    }

    function _set_postaccess($type) {
        $type = (int) $type;
        if($type < 1 || $type > 3) redirect('对不起，你选择的发帖模式设置有误。');
        return $type;
    }

    function get_post($post=null) {
        if(!$post) $post = $_POST;
        if(!$this->keys) return false;
        $result = array();
        foreach ($this->keys as $key => $fun) {
            if(isset($post[$key])) {
                $result[$key] = $this->_run_key_fun($fun, $post[$key]);
            }
        }
        return $result;
    }

    function save_post($gid) {
        $this->_gid = $gid;
        $set = $this->get_post();
        if(!$set) return;
        foreach ($set as $key => $value) {
            $where = array('gid'=>$gid,'variable'=>$key);
            $id = $this->db->from($this->table)->where($where)->select('id')->get_value();
            if($id > 0) {
                $this->db->from($this->table)->set('value',$value)->where('variable',$key)->where('id',$id)->update();
            } else {
                $this->db->from($this->table)->set('value',$value)->set($where)->insert();
            }
        }
    }

    function read($gid, $variable, $only_read_value = true) {
        $where = array(
            'gid' => $gid,
            'variable' => $variable
        );
        $this->db->from($this->table);
        $this->db->where($where);
        if($only_read_value) {
            return $this->db->select('value')->get_value();
        }
        return $this->db->get_one();
    }

    function read_all($gid) {
        $this->db->from($this->table);
        $this->db->where('gid',$gid);
        $r = $this->db->get();
        if(!$r) return;
        $result = array();
        while ($v = $r->fetch_array()) {
            $result[$v['variable']] = $v['value'];
        }
        $r->free_result();
        return $result;
    }

}
?>