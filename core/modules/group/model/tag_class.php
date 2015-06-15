<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
class msm_group_tag extends ms_model {

    var $table = 'dbpre_group_tag';
	var $key = 'id';
    var $model_flag = 'group';

    var $max_len = 8;
    var $split = ' ';

	function __construct() {
		parent::__construct();
        $this->model_flag = 'group';
        $this->modcfg = $this->variable('config');
        $this->modcfg['tag_max_len'] = (int) $this->modcfg['tag_max_len'];
        $this->max_len = $this->modcfg['tag_max_len']>0 ? $this->modcfg['tag_max_len'] : 8;

        if(S('tag_split')) {
            $this->split = $this->modcfg['tag_split'] ? ',' : ' ';
        } else {
            $this->split = S('tag_split') == 'comma' ? ',' : ' ';
        }
	}

    function save($gid, $taglist) {
        if(!is_array($taglist)) {
            $taglist = $this->check_filter($taglist);
        }
        $this->delete_gid($gid);
        foreach ($taglist as $key => $tagname) {
            $this->add($gid, $tagname);
        }
        return $taglist;
    }

    function add($gid,$tagname) {
        $this->db->from($this->table);
        $this->db->set('gid',$gid);
        $this->db->set('tagname',$tagname);
        $this->db->insert();
    }

    function check_filter($tagname) {
        $taglist = explode($this->split,trim($tagname));
        if(!$taglist) return '';
        $result = array();
        foreach ($taglist as $key => $value) {
            if(strposex($value, '|')) redirect("标签名称不能存在符号“|”。");
            if(strlen_ex($value) > $this->max_len) redirect("标签[{$value}]名称过长，不能超过{$this->max_len}个字。");
            $result[] = $value;
         } 
         return $result ? $result : '';
    }

    function delete($id) {
        $id = (int) $id;
        if(!$id && $id < 0)return;
        $this->db->from($this->table)->where('id',$id)->delete();
    }

    function delete_gid($gid) {
        $gid = (int) $gid;
        if(!$gid && $gid < 0)return;
        $this->db->from($this->table)->where('gid',$gid)->delete();
    }

    function field_to_array($tagname) {
        if(!$tagname) return '';
        $tagname = trim($tagname, '|');
        return explode('|', $tagname);
    }

    function array_to_field($taglist) {
        if(!$taglist) return '';
        return '|' . implode('|', $taglist) . '|';
    }

    function field_to_string($tagname) {
        if(!$tagname) return '';
        return str_replace('|', $this->split, trim(trim($tagname, '|')));
    }

    function array_to_json($taglist) {
        if(!$taglist||!is_array($taglist)) return '';
        if(strtolower($this->global['charset'])!='utf-8') {
            foreach ($taglist as $key => $value) {
                $taglist[$key] = charset_convert($value, $this->global['charset'], 'utf-8');
            }
        }
        return json_encode($taglist);
    }

}
?>