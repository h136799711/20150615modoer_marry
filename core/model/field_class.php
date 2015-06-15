<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_field extends ms_model {

    var $table = 'dbpre_field';
    var $key = 'fieldid';
    var $fieldtypes = '';
    var $fielddir = '';

    var $class_flag  = '';
    var $class_fieldsetting  = 'fieldsetting';
    var $class_fieldform = 'fieldform';
    var $class_fieldvalidator = 'fieldvalidator';
    //字段使用横表，横表表示在表内新建表字段，纵表则不再表内增加任何物理字段，即关系key-value型表结构
    var $horizontal = true;

    function __construct() {
        parent::__construct();
        $this->loader->helper('sql');
        if($this->fielddir) {
            $this->fieldtypes = read_cache($this->fielddir.DS.'typelist.php');
        } else {
            $this->fieldtypes = read_cache(MOD_ROOT.'model'.DS.'fields'.DS.'typelist.php');
        }
    }

    function field_list($idtype, $id, $p_cfg = FALSE) {
        $this->db->select('fieldid,idtype,id,tablename,fieldname,type,title,unit,iscore,enablesearch,isadminfield,listorder,show_list,show_detail,disable');
        $this->db->from($this->table);
        $this->db->where('idtype', $idtype);
        $this->db->where('id', $id);
        $this->db->order_by(array('listorder'=>'ASC','fieldid'=>'ASC'));
        $row = $this->db->get();

        $result = array();
        if(!$row) return $result;

        while($value = $row->fetch_array()) {
            if($p_cfg) {
                $value['config'] = unserialize($value['config']);
            }
            $result[] = $value;
        }
        $row->free_result();

        return $result;
    }

    function add($field, $createfield=true, $updatecache=true) {
        $idtype = $field['idtype'];
        $id = $field['id'];
        foreach($field as $key => $val) {
            if(is_string($val)) $field[$key] = trim($val);
        }
        if(!$field['fieldname']||$field['fieldname']=='c_') redirect('admincp_field_post_name_empty');
        if(!$field['type']) {
            redirect(lang('admincp_field_empty'));
        } elseif(!array_key_exists($field['type'], $this->fieldtypes)) {
            redirect(lang('admincp_field_unkown_type', $field['type']));
        } elseif(!preg_match("/^[a-z0-9_]+$/",$field['fieldname'])) {
            redirect('admincp_field_name_invalid');
        } elseif($createfield && $this->horizontal && sql_exists_field($field['tablename'], $field['fieldname'])) {
            redirect(lang('admincp_field_exists_field', array($field['tablename'], $field['fieldname'])));
        }
        // 字段属性设置验证
        $FS =& $this->loader->model(($this->class_flag?($this->class_flag.':'):'').$this->class_fieldsetting);
        // 返回序列化的设置属性
        $field['config'] = $FS->setting($field['type'], $field['config']);
        if($createfield && $this->horizontal) {
            $field['iscore'] > 0 && $FS->datatype = $field['datatype'];
            $default = isset($FS->default) ? "DEFAULT '$FS->default'" : '';
        }
        $field['datatype'] = $FS->datatype;
        $field['config'] = serialize($field['config']);
        parent::save($field, NULL, FALSE);

        // 物理创建
        if($createfield && $this->horizontal) {
            sql_alter_field($field['tablename'], $field['fieldname'], 'ADD', "`{$field['fieldname']}` $FS->datatype NOT NULL $default");
        }

        $updatecache && $this->write_cache($id);
        return $this->db->insert_id();
    }

    function edit($fieldid, $field, $alterField=false, $updatecache=true) {
        if(!$fieldid) return FALSE;
        foreach($field as $key => $val) {
            if(is_string($val)) $field[$key] = trim($val);
        }
        if(!$info = $this->read($fieldid)) {
            redirect('admincp_field_not_exists');
        } elseif($this->horizontal && $info['fieldname'] != 'mappoint' && !sql_exists_field($info['tablename'], $info['fieldname'])) {
            redirect(lang('admincp_field_not_exists_field', array($info['tablename'], $info['fieldname'])));
        }
        // 字段属性设置验证
        $FS = $this->loader->model(($this->class_flag?($this->class_flag.':'):'').$this->class_fieldsetting);
        // 返回序列化的设置属性
        $field['config'] = $FS->setting($info['type'], $field['config']);

        $fieldname = $field['fieldname'];
        if(isset($field['fieldname'])) unset($field['fieldname']);
        if($alterField && $this->horizontal) {
            $default = $FS->default != null ? "DEFAULT '$FS->default'" : '';
            sql_alter_field($info['tablename'], $fieldname, 'CHANGE', "`$fieldname` `$fieldname` $FS->datatype NOT NULL $default");
        }
        $field['datatype'] = $FS->datatype;
        $field['config'] = serialize($field['config']);
        parent::save($field, $fieldid, false);
        $updatecache && $this->write_cache($info['id']);
        return TRUE;
    }

    function delete($fieldid) {
        $where = array();
        $where['fieldid'] = $fieldid;
        $this->_delete($where, true); //删除后更新
    }

    function delete_id($type, $id) {
        $where = array();
        $where['type'] = $type;
        $where['id'] = $id;
        $this->_delete($where, false); //删除后不更新
    }

    function listorder($neworder,$id='') {
        if(!is_array($neworder) || empty($neworder)) return;
        foreach($neworder as $fieldid => $order) {
            $this->db->from($this->table);
            $this->db->set('listorder', $order);
            $this->db->where('fieldid', $fieldid);
            $this->db->update();
        }
        $this->write_cache($id);
    }

	function disable($fieldid, $value) {
        $field = $this->read($fieldid);
        if(!$field) return;
		$this->db->from($this->table);
		$this->db->set('disable', (int)$value);
		$this->db->where('fieldid', $fieldid);
		$this->db->update();
		$this->write_cache($field['id']);
	}

    function from($catid) {
        $fields = $this->field_list($catid);
        $contents = '';
        $isadmin = defined("IN_ADMIN");

        $FF =& $this->loader->model(($this->class_flag?($this->class_flag.':'):'').$this->class_fieldform);
        foreach($fields as $val) {
            if($val['isadminfield'] && !$isadmin) continue;
            $contents .= $FF->form($val);
        }
        return $contents;
    }

    function validator($catid, &$post) {
        $this->fields = $this->variable('field_' . $catid);
        if(!$this->fields) return $post;
        $FV =& $this->loader->model(($this->class_flag?($this->class_flag.':'):'').$this->class_fieldvalidator);
		$FV->all_data($post);
        $data = array();
        foreach($this->fields as $val) {
            $value = $FV->validator($val, $post[$val['fieldname']]);
            if(!$FV->leave) $data[$val['fieldname']] = $value;
        }
        return $data;
    }

    function fieldid_by_name($catid, $name) {
        $this->fields = $this->variable('field_' . $catid);
        if(!$this->fields) return null;
        foreach ($this->fields as $key => $value) {
            if($value['fieldname']==$name) return $value['fieldid'];
        }
        return null;
    }

    // 继承类实现之
    function write_cache($id) {}

    //删除字段
    function _delete($where, $up_cache=true) {
        if(!$where) return;
        $this->db->from($this->table);
        $this->db->where($where);
        $r = $this->db->get();
        if(!$r) return;
        $fieldids = array();
        while ($v = $r->fetch_array()) {
            $fields[] = $v;
            $fieldids[] = $v['fieldid'];
        }
        $r->free_result();

        foreach ($fields as $field) {
            //如果是横表，则删除相应的字段
            if($this->horizontal && $field['fieldname']) {
                sql_alter_field($field['tablename'], $field['fieldname'], 'DROP', $field['fieldname']);
            } elseif(!$this->horizontal && $field['tablename']) {
                //纵表，则删除表里的字段值
                $this->db->from('dbpre_' . $field['tablename']);
                $this->db->where('fieldid',$field['fieldid']);
                $this->db->where('fieldname',$field['fieldname']);
                $this->db->delete();
            }
        }
        parent::delete($fieldids, $up_cache);
    }

    function _write_cache($idtype, $id, $model_flag, $return = false) {
        $this->db->from($this->table);
        $this->db->where('idtype', $idtype);
        $this->db->where('id', $id);
        $this->db->where('disable', 0);
        $this->db->order_by(array('listorder'=>'ASC', 'fieldid'=>'ASC'));
        $row = $this->db->get();

        $result = array();
        if(!$row) return $result;

        while($value = $row->fetch_array()) {
            $value['config'] = unserialize($value['config']);
            $result[$value['fieldid']] = $value;
        }
        $row->free_result();

        $key = $model_flag . '_field_' . $id;
        ms_cache::factory()->write($key, $result);
        //write_cache('field_' . $id, arrayeval($result), $model_flag);
        if($return) return $result;
    }

}
?>