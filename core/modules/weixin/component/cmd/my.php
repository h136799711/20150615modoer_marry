<?php
/**
* 查看我的个人信息
*/
class weixin_cmd_my extends mc_weixin_cmd
{
	public static function get_name()
	{
		return '绑定微信，显示帐号信息';
	}

	public static function get_mark()
	{
		return 'my';
	}

	public static function intro() 
	{
		return "发送：\n".self::get_mark()."\n显示我的帐号信息。";
	}

    public static function match($msgObj)
    {
    	//发送Hi指令
        $content = strtolower(trim($msgObj->Content));
		return $content == self::get_mark();
    }

	public function execute($msgObj, mc_weixin_session $session)
	{
		$session->action = mc_weixin_session::ACTION_RESET;

		$openid = _T($msgObj->FromUserName);
		$time = strtotime(date('Y-m-d'), $this->timestamp);

		$passport_obj = $this->loader->model('member:passport'); 
		$uid = $passport_obj->get_uid('wechat', $openid);

		if($uid>0) {
            $member = $this->loader->model(':member')->read($uid);
            if(!$member) redirect('对不起，被绑定账号已删除。');
			$content = '绑定账号：'.$member['username'];
			$content .= "\n帐号等级：".display('member:group',"groupid/$member[groupid]");
            $content .= "\n点评数量：".$member['reviews'];
            $content .= "\n鲜花叔量：".$member['flowers'];

            $hash = create_formhash($member['uid'], $member['username'], $member['password']);
            $hash = authcode($member['uid'] . "\t" . md5($hash), 'ENCODE');

            $content .= "\n\n<a href=\"".U("member/mobile/do/login/op/fastlogin/hash/$hash",true)."\">进入我的助手</a>";
		} else {
            $bind_obj = new mc_weixin_bind;
            $sign = $bind_obj->get_sign_hash($openid, $time);
			$content = '对不起，您的微信帐号尚未绑定，<a href="'.U("weixin/bind/openid/$openid/sign/$sign", TRUE).'">请点击这里绑定微信帐号</a>。';
		}

		//回复类
		$reply_obj = mc_weixin_reply::factory('text');
		$reply_obj->set_user($msgObj->FromUserName, $msgObj->ToUserName);
		$reply_obj->set_content($content);

		//回复消息
		$reply_obj->send();
	}
	
}
/** end **/