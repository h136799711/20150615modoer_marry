<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_mylist_tag extends ms_model {

    var $table = 'dbpre_mylist_tag_index';
	var $key = 'id';
    var $model_flag = 'mylist';

	function __construct() {
		parent::__construct();
        $this->model_flag = 'mylist';
        $this->modcfg = $this->variable('config');
	}

	//添加tag索引
	function add($tags) {
		$tags = $this->parse($tags);
		if(!$tags) return null;
		if(count($tags) > 10) redirect('标签数量不能大于10个。');
		foreach ($tags as $key => $value) {
			$tags[$key] = _T($value);
		}
		return $this->get_tagids($tags);
	}

	function get_tagids($tags) {
		$result = array();
		foreach ($tags as $value) {
			$result[$value] = 0;
		}
		$q = $this->db->from($this->table)->where('name', $tags)->get();
		if($q) while ($v = $q->fetch_array()) {
			$result[$v['name']] = $v['id'];
		}
		foreach ($result as $name => $id) {
			if(!$id) $result[$name] = $this->_add($name);
		}
		return array_flip($result); //键值互换
	}

    //对标签进行解析
    function parse($strtag) {
        if(!$strtag) return;
        $strtag = preg_replace("/\s+/", " ", $strtag);
        /*
        $modcfg = $this->variable('config');
        $split = ","; // 标签分隔符号
        $match = "/(，|、|\/|\\\|\||——|=|\s+)/is"; // 过滤正则
        */
        //分隔符
        $split = S('tag_split')=='comma' ? ',' : ' ';
        
        //$strtag = preg_replace($match, $split, $strtag);
        return explode($split, $strtag);
    }

    function _add($name) {
    	$this->db->from($this->table)
    		->set('name',$name)
    		->set('addtime',$this->timestamp)
    		->insert();
    	return $this->db->insert_id();
    }
}
?>