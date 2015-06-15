<?php
/**
* @author moufer<moufer@163.com>
* @copyright (C)2001-2007 Moufersoft
*/
!defined('IN_MUDDER') && exit('Access Denied');
/**
* 会员登录
*/
class msm_member_login extends ms_base
{
	//第三方账号没有与本地的账号进行绑定
	const PASSPORT_UNBOUND 	 = 10008;

	//是否已登录
	public $isLogin 		 = false;
	//是否自动登录
	public $isAutoLogin 	 = false;
	//同步登录代码，用户在登录登出，使用
	public $sync_code 		 = '';

	//自动登录hash
	protected $hash 		 = '';
	//记住密码默认时间，30天
	protected $remember_time = 2592000;
	//会员管理模型
	protected $member_model	 = null;

	//会员字段内容
	protected $_data 	  	 = array();

	function __construct()
	{
		parent::__construct();
		$this->member_model = $this->loader->model(':member');

		$this->uid		= 0;
		$this->username	= '';
		$this->groupid  = 1;	//1:游客
	}

	function __set($key, $value)
	{
		$this->_data[$key] = $value;
	}

	function __get($key)
	{
		return isset($this->_data[$key]) ? $this->_data[$key] : null;
	}

	//账号登录
	function login($username, $password, $remember_time = null)
	{
		//通过账号密码，检查会员是否存在
		$member = $this->check_login($username, $password);
		if(!$member)
		{
			return false;
		}

		//设置记住密码时间
		if(is_numeric($remember_time) && $remember_time >= 0) {
			$this->remember_time = $remember_time;
		}

		//登录操作
		$this->_login($member);

		//保存登录状态
		$this->save_login();
		
		return true;
	}

	//第三方账号登录
	function passport_login($auth_token_or_passport_id, $remember_time = null) 
	{
		//记住密码时间
		if(is_numeric($remember_time) && $remember_time >= 0) {
			$this->remember_time = $remember_time;
		}

		//加载第三方账号模型类
		$passport_obj = $this->loader->model('member:passport');

		//第一个参数是passport的主键时
		if (!is_numeric($auth_token_or_passport_id))
		{
			$token = $auth_token_or_passport_id;
			//拉取第三方账号的绑定数据
			$passport = $passport_obj->get_data($token->name, $token->id);
			$passport_id = $passport['id'];
		} 
		else
		{
            $passport_id = $auth_token_or_passport_id;
            $passport = $passport_obj->read($passport_id);
		}

        //数据不存在，说明当前第三方账号没有绑定
        if(!$passport || !$passport['uid']) 
        {
            $this->add_error('PASSPORT_UNBOUND', self::PASSPORT_UNBOUND);
            return;
        }

		//找不到这个UID的会员
		if(!$member = $this->member_model->read($passport['uid']))
		{
			//删除绑定记录
			$passport_obj->delete($passport_id);
			return;
		}

		//更新 token 有效期
		if ($token)
		{
			$passport_obj->update_token($passport_id, $token);
		}

		//登录
		$this->_login($member);

		//保存登录状态
		$this->save_login();

		return true;
	}

	//自动登录，指定UID，不需要密码
	function auto_login($uid, $remember_time = null)
	{
		if(is_numeric($remember_time) && $remember_time >= 0) {
			$this->remember_time = $remember_time;
		}

		//获取会员数据
		if(!$member = $this->member_model->read($uid)) {
			return;
		}

		//登录
		$this->_login($member);

		//保存登录状态
		$this->save_login();

		return true;
	}

    //快速登录，在URL指定登录hash参数
    function fast_login()
    {
        $hash = _get('hash', '', 'trim');
	    if(!$hash) {
            $this->add_error('登录参数无效。');
            return;
        }

        $result = $this->remember($hash);
        if(!$result) {
            $this->add_error('登录失败！');
            return false;
        }
        //保存登录状态
		$this->save_login();

        return true;
    }

	//记住登录
	function remember($hash = '') 
	{
		if( ! $hash) {
		//一般用在基于Flash的图片上传时使用
			if($this->in_ajax && $_POST['auto_login_hash'] != '') {
				$hash = $_POST['auto_login_hash'];
			} else {
				$hash = _cookie('hash');
				//vp("read\t".base64_decode($hash));
			}
		}
	
		list($c_uid, $c_hash) = explode("\t", authcode($hash, 'DECODE'));

		//本机没有 cookies 登录记录
		$c_uid = (int) $c_uid;
		if(!$c_uid || !$c_hash)
		{
			return false;
		}
		elseif($c_uid > 0) //判断会员是否存在
		{
			if( ! $member = $this->member_model->read($c_uid)) return false;
		}

		$hash = create_formhash($member['uid'], $member['username'], $member['password']);

		if (strcmp($c_hash, md5($hash)) == 0) {
			//自动登录标记
			$this->isAutoLogin = true;
			$this->_login($member);
			return true;
		}

		return false;
	}

	//记住登录,cookies保存
	function save_login()
	{
		$str = $this->uid . "\t" . md5($this->hash);
		set_cookie('hash', authcode($str,'ENCODE'), $this->remember_time);
		//登录完成时HOOK
		_G('hook')->hook('login_after', array($this, $this->remember_time));
	}

	//登录账号检测，成功则返回会员数据
	function check_login($username, $password) 
	{
		if(!$username) {
			$this->add_error('member_post_empty_name');
			return;
		}
		if(!$password) {
			$this->add_error('member_post_empty_pw');
			return;
		}

		$member = array();

		//用户名登录验证
		if(!$member = $this->member_model->read($username, MEMBER_READ_USERNAME)) {
			//UID登录
			if(is_numeric($username) && strlen($username) <= 10 && $username > 0) {
				$member = $this->member_model->read($username, MEMBER_READ_UID);
			} 
			//手机号登录验证
			elseif(S('member:mobile_login') && preg_match("/^[0-9]{9,15}$/", $username)) {
				$member = $this->member_model->read($username, MEMBER_READ_MOBILE);
			}
		}

		//密码判断，不相同则表示登录失败
		$md5pw = md5($password);
		if(strcasecmp($member['password'], $md5pw) == 0) {
			return $member;
		}

        $this->add_error('member_login_lost');
		return false;
	}

	//登出
	function logout() 
	{
		$this->hook->hook('member_logout', array($this));
		$this->_logout();
	}

	//返回将登录用户的数据
	function fetch() {
		return $this->_data;
	}

	//保存登录信息
	protected function _login($member)
	{
		$this->isLogin	= true;
		$this->hash 	= create_formhash($member['uid'], $member['username'], $member['password']);

		foreach ($member as $key => $value) {
			$this->$key = $value;
		}

		//登录时就行一些数据更新
		$this->_update_data();

		//登陆后后续操作
		$this->_login_after();
	}

	protected function _login_after()
	{
		//挂载 会员登录
		$this->hook->hook('member_login', array($this));
	} 

	//更新登录信息
	protected function _update_data() 
	{
		$data = array();
		//更新用户登录信息
		$update_login_data = false;
		//本次是自动登录否
		if($this->isAutoLogin)  {
			$last_login_date = date('Y-m-d', $this->logintime);
			$now_date = date('Y-m-d', $this->timestamp);
			
			//自动登录，在第二天登录则算登录一次，或者 IP变动，也算重新登录
			$update_login_data = $now_date != $last_login_date || $this->loginip != $this->ip;
		}

		//新的登录信息
		if($update_login_data) {
			$data['logincount'] = array('set_add', array(1));
			$data['logintime'] 	= $this->timestamp;
			$data['loginip'] 	= $this->ip;
		}

		//等级积分变动后，检查是否会员等级是否升级
		$usergroup = msm_member_usergroup::fetch_usergroup($this->groupid);
		if($usergroup && $usergroup['grouptype'] == 'member') {
			$next_groupid = msm_member_usergroup::point_by_usergroup($this->point);
			//会员组升级
			if($this->groupid != $next_groupid) {
				$data['groupid'] = $next_groupid;
			}
		} elseif($this->nexttime > 0)  {
			//特殊组会员到期时间判断
			$now = strtotime(date('Y-m-d', $this->timestamp));
			if($this->nexttime < $now) {
				//到期后指定的下一个groupid
				$data['groupid']	 = $this->nextgroupid ? $this->nextgroupid : msm_member_usergroup::point_by_usergroup($this->point);
				$data['nexttime'] = 0;
			}
		}
		//没人任何数据更新
		if( ! $data) return;
		//更新会员表
		$this->member_model->db->from($this->member_model->table)
			->set($data)
			->where('uid', $this->uid)
			->update();
	}

	//登出
	protected function _logout() 
	{
		del_cookie(array(
			'uid','hash','username','activationauth',
			'passport_name','passport_id','passport_token_access','passport_expires_in',
		));

		$this->_logout_after();

		//还原属性内容
		$this->isLogin  	= false;
		$this->isAutoLogin	= false;

		$this->_data    = array();
		$this->uid		= 0;
		$this->username	= '';
	}

	//登出以后的操作
	protected function _logout_after()
	{
		//会员登出钩子
		$this->hook->hook('logout_after', $this);
	}
}

/** end **/