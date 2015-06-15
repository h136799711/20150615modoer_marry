<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('item:itembase', FALSE);
class msm_review_flower extends msm_item_itembase {

    var $table = 'dbpre_flowers';
	var $key = 'fid';

	function __construct() {
		parent::__construct();
        $this->modcfg = $this->variable('config');
		$this->init_field();
	}

	function init_field() {
		$this->add_field('id');
		$this->add_field_fun('id', 'intval');
	}

	function find($where, $start, $offset, $total = TRUE) {
	    $this->db->from($this->table);
	    $this->db->where('idtype','review');
		$this->db->where($where);

        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) {
                return $result;
            }
            $this->db->sql_roll_back('from,where');
        }
        
		$this->db->select('fid,idtype,id,uid,username,dateline');
        $this->db->order_by('dateline', 'DESC');
        $this->db->limit($start, $offset);
        $result[1] = $this->db->get();
        return $result;
	}

	function save($post) {

        $this->check_post($post);
        if($this->submitted($this->global['user']->uid, $post['id'])) {
            redirect('review_flower_submitted');
        }

        $review = $this->loader->model(':review')->read($post['id']);
        if(!$review) redirect('review_empty');

        $post['uid'] = $this->global['user']->uid;
        $post['username'] = $this->global['user']->username;
        $post['dateline'] = $this->global['timestamp'];
        $post['idtype'] = 'review';

		$fid = parent::save($post, null, false, false, false);
        $this->review_total_add($post['id']);
        $this->member_total_add($review['uid']);
        $this->_notice($post['id']);

        return $fid;
	}

    function submitted($uid, $id) {
        $this->db->from($this->table);
        $this->db->where('uid', $uid);
        $this->db->where('idtype', 'review');
        $this->db->where('id', $id);
        return $this->db->count() >= 1;
    }

	function check_post(& $post, $isedit = FALSE) {
        if($isedit && !is_numeric($post['id'])) {
            redirect(lang('global_sql_keyid_invalid', 'id'));
        }
	}

	function delete($fids, $update_total = TRUE) {
		if(empty($fids)) redirect('global_op_unselect');
		if(!is_array($fids)) $fids = array((int)$fids);
        $fids = parent::get_keyids($fids);
		$this->db->from($this->table);
		$this->db->where_in('fid', $fids);
		$this->db->select('fid,idtype,id,status');
		if(!$result = $this->db->get()) return;
		$uids = array();
		while($value = $result->fetch_array()) {
            $update_total && $this->review_total_dec($value['id']);
		}

		//删除记录
		$this->db->from($this->table);
		$this->db->where_in('fid', $fids);
		$this->db->delete();
	}

	function review_total_add($rid, $num=1) {
		$this->db->from($this->review_table);
		$this->db->set_add('flowers');
		$this->db->where('rid', $rid);
		$this->db->update();
	}

	function review_total_dec($rid, $num=1) {
		$this->db->from($this->review_table);
		$this->db->set_dec('flowers');
		$this->db->where('rid', $rid);
		$this->db->update();
	}

    function member_total_add($uid, $num=1) {
		$this->db->from('dbpre_members');
		$this->db->set_add('flowers', $num);
		$this->db->where('uid', $uid);
		$this->db->update();
    }

    //鲜花赠送提薪
    function _notice($rid) {
        if(!$rid) return;

        $review = $this->loader->model(':review')->read($rid);
        if(!$review||!$review['uid']) return;

        $c_username = '<a href="'.url("space/index/uid/{$this->global[user]->uid}").'" target="_blank">'.$this->global['user']->username.'</a>';
        $c_review = url("review/detail/id/$review[rid]");
        $note = lang('review_notice_new_flower',array($c_username, $c_review));

        $N = $this->loader->model('member:notice');
        $N->save($review['uid'],'review','flower',$note);
    }
}
?>