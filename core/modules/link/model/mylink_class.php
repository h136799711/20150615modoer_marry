<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_link_mylink extends ms_model {

    //模型类关联的表
    var $table = 'dbpre_mylinks';
    //模型类主键ID名称
	var $key = 'linkid';
    //模型类所属的模块
    var $model_flag = 'link';

    var $typenames = array();
    var $typeurls = array();
    var $idtypes = array();

	function __construct() {
		parent::__construct();
        $this->model_flag = 'link';
		$this->init_field();
        $this->modcfg = $this->variable('config');
	}

    //指定录入字段和过滤类型
	function init_field() {
        //需要从表单录入的字段
		$this->add_field('title,link,logo,des,displayorder,ischeck,city_id');
        //对录入表单要进行过滤的函数，第一个参数是字段名，第二个参数是过滤函数，例如下面的'intval'就是PHP函数intval
		$this->add_field_fun('displayorder,city_id', 'intval');
        $this->add_field_fun('title,link,logo,des', '_T');
	}

    //获取数据列表
    function find($select="*", $where=null, $orderby=null, $start=0, $offset=10, $total=FALSE) {
	    $this->db->from($this->table);
        if($where && isset($where['nq_logo'])) {
            $this->db->where_not_equal('logo','');
            unset($where['nq_logo']);
        }
		$where && $this->db->where($where);
        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) {
                return $result;
            }
            $this->db->sql_roll_back('from,where');
        }
		$this->db->select($select?$select:'*');
        if($orderby) $this->db->order_by($orderby);
        if($start < 0) {
            if(!$result[0]) {
                $start = 0;
            } else {
                $start = (ceil($result[0]/$offset) - abs($start)) * $offset; //按 负数页数 换算读取位置
            }
        }
        $this->db->limit($start, $offset);
        $result[1] = $this->db->get();

        return $result;
    }

    //保存数据,$post是结果->get_post($_POST)函数获取的字段集，第二个参数是指定修改的主键ID，如果是null表示本次会新增数据
    function save($post, $linkid = NULL) {
        $edit = $linkid != null;
        if($edit) {
            if(!$detail = $this->read($linkid)) redirect('link_empty');
        } else {
            if(!$this->in_admin && !$this->modcfg['open_apply']) redirect('link_post_apply_disabled');
            if(!$this->in_admin) $post['ischeck'] = 0;
        }
        $linkid = parent::save($post,$linkid);
        return $linkid;
    }

    //审核申请记录，参数可以是主键ID数组
    function checkup($linkids) {
        $ids = parent::get_keyids($linkids);
        $this->db->from($this->table);
        $this->db->set('ischeck',1);
        $this->db->where_in('linkid',$linkids);
        $this->db->update();
        $this->write_cache();
    }

    //删除友联记录
    function delete($linkids) {
        $ids = parent::get_keyids($linkids);
        parent::delete($ids);
    }

    //后台列表页的批量更新
    function update($post) {
        if(!$post || !is_array($post)) redirect('global_op_unselect');
        foreach($post as $linkid => $val) {
            parent::save($val,$linkid,FALSE,TRUE,TRUE);
        }
        $this->write_cache();
    }

    //检测提交表单的字段内容
    function check_post(& $post, $edit = false) {
        if(!$post['title']) redirect('link_post_title_empty');
        if(!$post['link']) redirect('link_post_link_empty');
        if($post['city_id']) {
            $city = $this->loader->model('area')->read((int)$post['city_id']);
            if(!$city || $city['level']!='1') redirect('您未选择或选择的连接城市无效。');
        }
    }

    //获取某个城市下没有审核的友联数量
    function get_check_count($city_id) {
        $this->db->from($this->table);
        if($city_id) $this->db->where('city_id', $city_id);
        $this->db->where('ischeck', 0);
        return $this->db->count();
    }
/*
    function write_cache($return = FALSE) {
        $result = array();
        $result['logo'] = $this->_get_link_logo();
        $result['char'] = $this->_get_link_char();

        ms_cache::factory()->write('link_list', $result);
        //write_cache('list', arrayeval($result), $this->model_flag);
        if($result) return $result;
    }
 */
    //获取logo形式连接
    function _get_link_logo() {
        $num_logo = $this->modcfg['num_logo'] > 0 ? $this->modcfg['num_logo'] : 5;
        $result = array();
        $select = 'title,link,logo,des';
        $where = array('ischeck'=>1);
        $where['nq_logo'] = 1;
        list(,$list) = $this->find($select,$where,'displayorder',0,$num_logo,false);
        if($list) {
            while($v=$list->fetch_array()) {
                $result[] = $v;
            }
            $list->free_result();
        }
        return $result;
    }

    //获取文字形式连接
    function _get_link_char() {
        $num_char = $this->modcfg['num_char'] > 0 ? $this->modcfg['num_char'] : 20;
        $result = array();
        $select = 'title,link,logo,des';
        $where = array('ischeck'=>1,'logo'=>'');
        list(,$list) = $this->find($select,$where,'displayorder',0,$num_char,false);
        if($list) {
            while($v=$list->fetch_array()) {
                $result[] = $v;
            }
            $list->free_result();
        }
        return $result;
    }
}
?>