<?php
/**
* 查看指令列表
*/
class weixin_cmd_welcome extends mc_weixin_cmd
{
	public static function get_name()
	{
		return '显示欢迎信息';
	}

	public static function get_mark()
	{
		return 'hi';
	}

	public static function intro() 
	{
		return "发送：\n".self::get_mark()."\n显示欢迎信息。";
	}

    public static function match($msgObj)
    {
    	$mark = self::get_mark();
    	//用户关注时
    	if($msgObj->MsgType == 'event' && $msgObj->Event == 'subscribe') {
    		return true;
    	}
    	//发送Hi指令
        $content = strtolower(trim($msgObj->Content));
		return $content == $mark;
    }

	public function execute($msgObj, mc_weixin_session $session)
	{
		$content = S('weixin:default_message');
        $content .= "\n\n<a href=\"".U('mobile/index', TRUE)."\">点击进入手机微信站</a>";
		$content .= "\n\n您可以回复“help”来获取相关指令信息。";

		$session->action = mc_weixin_session::ACTION_RESET;

		//回复类
		$reply_obj = mc_weixin_reply::factory('text');
		$reply_obj->set_user($msgObj->FromUserName, $msgObj->ToUserName);
		$reply_obj->set_content($content);

		//回复消息
		$reply_obj->send();
	}
	
}
/** end **/