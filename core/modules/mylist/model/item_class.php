<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_mylist_item extends ms_model {

    var $table = 'dbpre_mylist_item';
	var $key = 'id';
    var $model_flag = 'mylist';

    var $mylist = null;

	function __construct() {
		parent::__construct();
        $this->model_flag = 'mylist';
		$this->init_field();
        $this->modcfg = $this->variable('config');

        $this->mylist = $this->loader->model(':mylist');
	}

	function init_field() {
		$this->add_field('city_id,title,tags,intro');
		$this->add_field_fun('city_id', 'intval');
        $this->add_field_fun('title', '_T');
        $this->add_field_fun('intro', '_TA');
	}

    function add($mylist_id, $sid) {
        $mylist = $this->mylist->read($mylist_id);
        if(!$mylist) redirect('mylist_empty');
        if(!$this->in_admin) {
            if($mylist['uid'] != $this->global['user']->uid) redirect('mylist_manage_access_denied');
        }
        if($mylist['num']>=500) redirect('对不起，榜单内主题数量不能超过500个。');
        if($this->exists($mylist_id, $sid) > 0) redirect('对不起，主题已经在榜单内，不能重复添加。');
        $subject = $this->loader->model('item:subject')->read($sid);
        if(!$subject||!$subject['status']) redirect('对不起，主题不存在或未被审核。');

        $post=array();
        $post['mylist_id'] = $mylist_id;
        $post['sid'] = $sid;
        $post['uid'] = $this->in_admin ? 0 : $this->global['user']->uid;
        $post['addtime'] = $this->timestamp;
        $post['listorder'] = $mylist['num']+1;
        $post['excuse'] = '';
        if(!$this->in_admin) {
            $review = $this->get_review($sid);
            if($review['rid'] > 0) $post['rid'] = $review['rid'];
        }
        $id = parent::save($post);
        if($id > 0) {
            $this->mylist->update_num($mylist_id, 1);
            //更新最后修改时间
            $this->mylist->update_modiyftime($mylist_id);
        }
        //设置榜单封面
        if($subject['thumb'] && !$mylist['thumb'] && !$mylist['num']) {
            $this->mylist->update_thumb($mylist_id, $subject['thumb']);
        }
        return $id;
    }

    function post_excuse($id, $excuse) {
        $detail = $this->read($id);
        if(!$detail) redirect('mylist_item_empty');
        if(!$this->in_admin && $detail['uid'] != $this->global['user']->uid) redirect('mylist_manage_access_denied');
        //过滤关键字
        $W = $this->loader->model('word');
        $excuse = $W->filter($excuse);
        $this->db->from($this->table)
            ->set('excuse', $excuse)
            ->where('id', $id)
            ->update();
        $arows = $this->db->affected_rows();
        $this->loader->model(':mylist')->update_modiyftime($detail['mylist_id']);
        return $arows;
    }

    function delete($id) {
        $detail = $this->read($id);
        if(!$detail) redirect('对不起，您删除的信息不存在。');
        if(!$this->in_admin) {
            if($detail['uid'] != $this->global['user']->uid) redirect('mylist_manage_access_denied');
        }
        return $this->_delete(array('id'=>$id), true);
    }

    function delete_mylist($mylist_id) {
        return $this->_delete(array('mylist_id'=>$mylist_id), false);
    }

    function exists($mylist_id, $sid) {
        return $this->db->from($this->table)
            ->where('mylist_id', $mylist_id)
            ->where('sid', $sid)
            ->count();
    }

    function get_review($sid) {
        return $this->loader->model(':review')->get_last('subject', $sid);
    }

    function update_listorder($items) {
        if(!$items||!is_array($items)) return;
        foreach ($items as $id => $value) {
            $int = (int) $value['listorder'];
            $this->db->from($this->table)
                ->set('listorder', $int)
                ->where('id', $id)
                ->update();
        }
    }

    function _delete($where, $up_total = true) {
        if($up_total) {
            $q = $this->db->from($this->table)->where($where)->get();
            if(!$q) return;
            $mylist_ids = array();
            while ($v = $q->fetch_array()) {
                if(isset($mylist_ids[$v['mylist_id']])) {
                    $mylist_ids[$v['mylist_id']]++;
                } else {
                    $mylist_ids[$v['mylist_id']]=1;
                }
            }
            if($mylist_ids) foreach ($mylist_ids as $mylist_id => $num) {
                $this->db->from('dbpre_mylist')
                    ->where('id', $mylist_id)
                    ->set_dec('num', $num)
                    ->update();
            }
        }
        $this->db->from($this->table)->where($where)->delete();
        return $this->db->affected_rows();
    }

}
?>