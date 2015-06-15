<?php
/**
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2011 Moufersoft
 * @website www.modoer.com
 */
class msm_comment_uyan extends msm_comment_interface {

	protected $interface_info = array(
		'name' => '友言',
		'url' => 'http://www.uyan.cc/',
		'intro' => '简单、友好的社会化评论框！网站评论框不再是冷冰冰的用户名和email，而是来自各大微博及社交网络的真实用户的言论，增加了交流氛围和乐趣。',
	);

	//接口需要挂载的系统函数钩子
	protected $hooks = array();

	public function display($comment_cfg) {
		echo "<!-- UY BEGIN -->\n";
		echo "<script type=\"text/javascript\">\n";
		echo "var uyan_config = {
			'title':'{$comment_cfg['title']}', 
			'su':'{$comment_cfg['idtype']}_{$comment_cfg['id']}' 
			};";
		echo "</script>\n";
		echo "<div id=\"uyan_frame\"></div>\n";
		echo "<script type=\"text/javascript\" src=\"http://v2.uyan.cc/code/uyan.js?uid=".$this->cfg['uyan_id']."\"></script>\n";
		echo "<!-- UY END -->\n";
		echo "<script type=\"text/javascript\">\n";
		echo "var uyan_idtype='{$comment_cfg['idtype']}';\nvar uyan_id={$comment_cfg['id']};\nvar local_comments={$comment_cfg['comments']};\n";
		echo "</script>\n";
		echo "<script type=\"text/javascript\" src=\"".URLROOT."/core/modules/comment/interface/uyan.js\"></script>";
	}

	public function check_setting() {
		$fields = array('uyan_id','uyan_sso_key');
		$setting = array();
		foreach ($fields as $key) {
			$setting[$key] = trim($_POST[$key]);
		}
		if(!$setting['uyan_id']) redirect('对不起，您为填写友言用户ID');
		if($setting['uyan_sso_key']) {
			//同步登录需要挂载登录钩子才能实现
			$this->hooks = array('login_after', 'logout_after');
		}
		return $setting;
	}

	public function form_elements() {
        $elements = array();
        $elements[] = 
            array(
            'title' => '友言用户ID',
            'des' => '注册并登录友言在http://www.uyan.cc/sites头部可以看到ID号',
            'content' => form_input('uyan_id', $this->cfg['uyan_id'], 'txtbox2'),
        );
        $elements[] = 
            array(
            'title' => '开启单点登录（SSO）认证密钥',
            'des' => '单点登录后，在本站登录时，友言评论框即可同步登录。在友言后台控制面板里找到“单点登录”页面，在该填写“认证密匙”后，复制此密钥到这里即可，保持双边密钥相同。',
            'content' => form_input('uyan_sso_key', $this->cfg['uyan_sso_key'], 'txtbox'),
        );
        return $elements;
	}

	public function get_info() {
		//$this->id;
	}

	public function hook($name, $params) {
		if($name=='login_after') {
			$this->_sso_login($params);
		} elseif($name=='logout_after') {
			$this->_sso_logout();
		}
	}

	//单点登录 SSO
	private function _sso_login($params) {
		$user = $params[0];
		$life = $params[1];

		$login_key = $this->cfg['uyan_sso_key'];
		if(!$login_key) return;

		$login_data = array( 
			'uid' 	=> $user->uid, 
			'uname'	=> $user->username, 
			'uface'	=> get_face($user->uid, false, true), 
			'ulink'	=> url("space/$user->uid", '',true),
		);
		if(_G('charset') != 'utf-8') {
			$login_data['uname'] = charset_convert($login_data['uname'], _G('charset'), 'utf-8');
		}
		set_cookie('syncuyan', $this->_des_encrypt($login_data, $login_key), 2592000, false);
	}

	private function _sso_logout() {
		del_cookie('syncuyan', false);
	}

	private function _des_encrypt($params, $login_key) {
		//$params = json_encode($params);
		$url = "http://api.uyan.cc?mode=des";
		foreach ($params as $key => $value) {
			$url .= "&$key=".urlencode($value);
		}
		$url .= '&key=' . $login_key;
		$encode = file_get_contents($url);
		return $encode;
	}

}
/** end **/