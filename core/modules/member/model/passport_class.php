<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_member_passport extends ms_model 
{

	var $table = 'dbpre_member_passport';
	var $key = 'id';
	var $model_flag = 'member';

	function __construct() 
	{
		parent::__construct();
		$this->modcfg = $this->variable('config');
	}

	function get_list($uid) 
	{
		$r = $this->db->from($this->table)->where('uid',$uid)->get();
		if(!$r) return;
		$s = array();
		while ($v=$r->fetch_array()) {
			$s[$v['psname']] = $v['isbind'];
		}
		$r->free_result();
		return $s;
	}

	function get_token_status($uid)
	{
		$r = $this->db->from($this->table)->where('uid', $uid)->get();
		if(!$r) return;
		$s = array();
		while ($v = $r->fetch_array()) {
			$s[$v['psname']] = $v['expired'] > $this->global['timestamp'];
		}
		$r->free_result();
		return $s;
	}

	function get_data($psname, $psuid)
	{
		$this->db->from($this->table);
		$this->db->where('psname',	$psname);
		$this->db->where('psuid',	$psuid);
		return $this->db->get_one();
	}

	function get_uid($psname, $psuid) 
	{
		$this->db->from($this->table);
		$this->db->select('uid');
		$this->db->where('psname',	$psname);
		$this->db->where('psuid',	$psuid);
		return $this->db->get_value();
	}

	function save($uid = 0, $token) 
	{
		$where = array();
		$where['psname'] 		= $token->name;
		$where['psuid'] 		= $token->id;

		$post = array();
		$post['uid']			= $uid;
		$post['access_token']	= $token->access_token;
		$post['expired'] 		= $token->expires_in;
		$post['token_data']		= isset($token->token_data)?$token->token_data:'';

		$detail = $this->db->from($this->table)
							->where($where)
							->get_one();
		if($detail) {
			$id = parent::save($post, $detail['id']);
		} else {
			$post['psname'] = $token->name;
			$post['psuid'] 	= $token->id;
			$id = parent::save($post);
		}
		return $id;
	}

	function get_token($uid, $psname) 
	{
		return $this->db->from($this->table)
			->where('uid',		$uid)
			->where('psname',	$psname)
			->get_one();
	}

	//通过主键ID更新 token 信息
	function update_token($id, $token)
	{
		$this->db->from($this->table)
			->set('access_token', $token->access_token)
			->set('expired', $token->expires_in)
			->where('id', $id)
			->update();
	}

	function check_exists($uid) 
	{
		$this->db->from($this->table);
		$this->db->where('uid',$uid);
		return $this->db->count() > 0;
	}

	function bind_smple($id, $uid) 
	{
		$this->db->from($this->table);
		$this->db->where('id', $id);
		$this->db->set('uid', $uid);
		$this->db->update();
	}

	function bind_exists($psname, $psuid) 
	{
		$this->db->from($this->table);
		$this->db->where('psname', $psname);
		$this->db->where('psuid', $psuid);
		return $this->db->count() > 0;
	}

	//检车本地是否已经保定了制定的第三方网站
	function bind_exists_by_uid($psname, $uid)
	{
		$this->db->from($this->table);
		$this->db->where('psname', $psname);
		$this->db->where('uid', $uid);
		return $this->db->count() > 0;
	}

	function bind($uid, $token) 
	{
		return $this->save($uid, $token);
	}

	// function bind($uid, $psname, $psuid, $access_token='', $expires_in='') {
	//     if(!$access_token) $access_token = _T($this->global['cookie']['passport_'.$psname.'_token']);
	//     if(!$expires_in) $expires_in = (int) $this->global['cookie']['passport_'.$psname.'_expires_in'];
	//     $this->db->from($this->table)
	//         ->set('uid', $uid)
	//         ->set('psname', $psname)
	//         ->set('psuid', $psuid)
	//         ->set('access_token', $access_token)
	//         ->set('expired', $expires_in)
	//         ->replace();
	// }

	function unbind($psname, $uid) 
	{
		if(!$uid || !$psname) return;
		$this->db->from($this->table)
			->where('uid', $uid)
			->where('psname', $psname)
			->delete();
	}
}
?>