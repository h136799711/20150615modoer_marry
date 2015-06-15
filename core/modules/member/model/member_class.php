<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

define('MEMBER_READ_UID',        0);
define('MEMBER_READ_USERNAME',   1);
define('MEMBER_READ_MOBILE',     2);

class msm_member extends ms_model {

	var $table = 'dbpre_members';
	var $key = 'uid';

	var $modcfg;

	function __construct() {
		parent::__construct();
		$this->model_flag = 'member';
		$this->modcfg = $this->loader->variable('config','member');
		$this->init_field();
	}

	function init_field() {
		$this->add_field('uid,username,password,password2,email,groupid,nexttime,nextgroupid,mobile');
		$this->add_field_fun('username,passport,passport_id,mobile', '_T');
		$this->add_field_fun('uid,groupid,nexttime,nextgroupid', 'intval');
		$this->add_field_fun('password,email', 'trim');
	}

	//type为读取字段 0:uid, 1:username, 2:mobile
	function read($uid, $type = MEMBER_READ_UID, $select='*', $read_profile = TRUE) {
		$result = '';
		if(!$type) {
			if(!$uid = (int) $uid) return $result;
			$keyname = 'm.uid';
		} else {
			$keyname = $type == 1 ? 'username' : 'mobile';
		}
		if(!$read_profile) {
			$this->db->from($this->table,'m');
		} else {
			$this->db->join($this->table,'m.uid','dbpre_member_profile','mp.uid', 'LEFT JOIN');
		}
		$select = trim($select);
		if($select=='*') {
			$select = "mp.*,m.*";
		} else {
			strposex($select,'uid') and $select = str_replace('uid', 'm.uid', $select);
		}
		$this->db->select($select);
		$this->db->where($keyname, $uid);
		$result = $this->db->get_one();
		return $result;
	}

	function find($where, $start, $offset) {
		$result = array();
		$this->db->from($this->table);
		if($where['username']) {
			$this->db->where_like('username', '%'.$where['username'].'%');
			unset($where['username']);
		}
		if($where) {
			foreach($where as $key => $val) $this->db->where($key, $val);
		}
		$total = $this->db->count();
		if(!$total) {
			$result = array(0,'');
			return $result;
		}
		$this->db->sql_roll_back('from,where');
		$this->db->order_by('uid');
		$this->db->limit($start, $offset);
		$result = array($total, $this->db->get());
		return $result;
	}

	function save(& $post, $uid=null) {
		$edit = $uid > 0;
		if($post['password'] != $post['password2']) {
			redirect('member_post_empty_eq_pw');
		}
		if($this->in_admin && empty($post['password'])) unset($post['password']);
		unset($post['password2']);
		$post['nexttime'] = $post['nexttime'] ? strtotime($post['nexttime']) : 0;

		$uid = parent::save($post, $uid);

		// update point
		if(!$edit) {
			$P =& $this->loader->model('member:point');
			$P->update_point($uid, 'reg');
		}

		return $uid;
	}

	//从反向整合
	function save_passport($post) {
		$this->db->from($this->table);
		$this->db->set($post);
		$this->db->insert();
		$uid = $this->db->insert_id();
		if(!$edit) {
			$P =& $this->loader->model('member:point');
			$P->update_point($uid, 'reg');
		}
		return $uid;
	}

	//更新积分
	function update_point($uid, $point) {
		if(!$point||!is_array($point))  redirect(lang('global_sql_keyid_invalid','point'));
		if(!$uid = abs ( (int) $uid)) redirect(lang('global_sql_keyid_invalid','uid'));
		$this->loader->model('member:point')->set_point($uid, $point);
		return TRUE;
	}

	//更新会员组
	function update_group($uid, $groupid) {
		return $this->db->from($this->table)
						->set('groupid', $groupid)
						->where('uid', $uid)
						->update();
	}

	//删除会员
	function delete($ids)
	{
		$ids = $this->get_keyids($ids);

		//passport
		$PT =& $this->loader->model('member:passport');
		$PT->delete($ids, false);

		//其他模块关联的HOOK
		foreach(array_keys($this->global['modules']) as $flag)
		{
			if($flag == $this->model_flag) continue;
			$file = MUDDER_MODULE . $flag . DS . 'inc' . DS . 'member_delete_hook.php';
			if(is_file($file))
			{
				@include $file;
			}
		}

		parent::delete($ids);
	}

	//更新登录密码
	function modify_password($uid, $password)
	{
        $post = array(
			'password' => $password,
        );
        return parent::save($post, $uid);
	}

	//更新支付密码
	function modify_paypw($uid, $password)
	{
        $md5pw = md5($password);
        $this->db->from($this->table);
        $this->db->set('paypw', $md5pw);
        $this->db->where('uid', $uid);
        $this->db->update();
	}

	//更新邮箱号
	function modify_email($uid, $email)
	{
        $post = array(
        	'email' => $email,
        );
        return parent::save($post, $uid);
	}

	//更新手机号
	function modify_mobile($uid, $mobile)
	{
        $post = array(
        	'mobile' => $mobile,
        );
        return parent::save($post, $uid);
	}

	//检测密码是否正确
	function check_password($uid, $password)
	{
        $md5pw = md5($password);
        return $this->db->from($this->table)
        		->where('uid', $uid)
		 		->where('password', $md5pw)
        		->count() > 0;
	}

	//检测支付密码是否正确
	function check_paypw($uid, $password)
	{
        $md5pw = md5($password);
        return $this->db->from($this->table)
        		->where('uid', $uid)
		 		->where('paypw', $md5pw)
        		->count() > 0;
	}
	
	//检测头像是否设置
	function check_avatar($uid)
	{
		$face = get_face($uid, FALSE, FALSE);
		$name = dirname($face);
		return strposex($name, 'uploads/faces');
	}

	function check_post(& $post, $uid) {
		$isedit = $uid > 0;
		$this->loader->helper('validate');
		if(!$isedit && !$post['username']) {
			redirect('member_post_empty_name');
		} elseif(!$isedit && $post['username']) {
			$this->check_username($post['username']);
		}
		if(!$isedit && !$post['password']) {
			redirect('member_post_empty_pw');
		}
		if($post['password']) {
			$this->check_password_format($post['password']);
		}
		if($post['email'] && !validate::is_email($post['email'])) {
			redirect('member_post_empty_email');
		}
		if($post['groupid'] && $post['groupid'] < 0) {
			redirect('member_post_empty_group');
		}
		if(!$isedit) {
			if($this->check_username_exists($post['username'])) 
				redirect('member_post_exists_name');
			if(!$this->modcfg['existsemailreg'] && $post['email'])
				if($this->check_email_exists($post['email'])) redirect('member_post_exists_email');
		} else {
			if(!$this->modcfg['existsemailreg'] && $post['email']) {
				if($this->check_email_exists($post['email'], $uid)) redirect('member_post_exists_email');
			}
		}
	}

	function check_username_exists($username, $without_uid = null) {
		$this->db->from($this->table);
		$this->db->where('username',$username);
		if($without_uid > 0) $this->db->where_not_equal('uid', $without_uid);
		return $this->db->count() > 0;
	}

	function check_email_exists($email, $without_uid = null) {
		$this->db->from($this->table);
		$this->db->where('email',$email);
		if($without_uid > 0) $this->db->where_not_equal('uid', $without_uid);
		return $this->db->count() > 0;
	}

	function check_mobile_exists($mobile, $without_uid = null) {
		$this->db->from($this->table);
		$this->db->where('mobile',$mobile);
		if($without_uid > 0) $this->db->where_not_equal('uid', $without_uid);
		return $this->db->count() > 0;
	}

	function check_username($username, $echo = FALSE)
	{
		if(strlen($username) <= 2) {
			if($echo) {echo lang('member_reg_name_len_min'); exit;}
			return redirect('member_reg_name_len_min');
		}
		if(strlen($username) > 15) {
			if($echo) {echo lang('member_reg_name_len_max'); exit;}
			return redirect('member_reg_name_len_max');
		}
		$guestexp = '^Guest|^\xD3\xCE\xBF\xCD|\xB9\x43\xAB\xC8';
		if(preg_match("/^\s*$|^c:\\con\\con$|[%,\*\"\s\t\<\>\&]|\'|$guestexp/is", $username)) {
			if($echo) {echo lang('member_reg_name_sensitive_char'); exit;}
			redirect('member_reg_name_sensitive_char');   
		}
		if($censorwords = $this->modcfg['censoruser'] ? explode("\r\n", $this->modcfg['censoruser']) : '') {
			foreach($censorwords as $censor) {
				if(!$censor) continue;
				$preg = "/".str_replace("*", ".*?", $censor)."/is";
				if(preg_match($preg, $username)) {
					if($echo) {echo lang('member_reg_name_limit'); exit;}
					redirect('member_reg_name_limit');
				}
			}
		}
		// if (!preg_match("/^[\x7f-\xff]+$/", $username)) {
		//     if($echo) {echo '只允许中文注册中文名称。'; exit;}
		//     return redirect('只允许中文注册中文名称。');
		// }
	}
	
	function check_password_format($password, $return_error = FALSE)
	{
		$len = 5;
		if(strlen($password) < $len)
		{
			$error = sprintf(lang('member_post_empty_pw_len'), $len);
			if($return_error) return $error;
			redirect($error);
		}
		if(!preg_match("/[a-zA-Z0-9_~!@#]+/i", $password))
		{
			if($return_error) return lang('member_reg_pw_limit');
			redirect('member_reg_pw_limit');
		}
	}

	//保存更新之前
	protected function _save_befor()
	{
		if( ! $this->has_error())
		{
			$this->hook->hook('member_save_befor', array($this));
			//对密码进行加密
			if($this->_save_data['password']) 
			{
				$this->_save_data['password'] = md5($this->_save_data['password']);
			}
		}
	}

	//删除之前
	protected function _delete_befor($ids)
	{
		//函数钩子 - 删除前
		$this->hook->hook('member_delete_befor', array($ids));
	}

    //delete操作之后
    protected function _delete_after($ids)
    {
		//函数钩子 - 删除之后
		$this->hook->hook('member_delete_after', array($ids));
    }
}
?>