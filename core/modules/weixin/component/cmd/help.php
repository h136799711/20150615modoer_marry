<?php
/**
* 查看指令列表
*/
class weixin_cmd_help extends mc_weixin_cmd
{
	public static function get_name()
	{
		return '获取指令列表';
	}

	public static function get_mark()
	{
		return 'help';
	}

	public static function intro()
	{
		return "发送：\n".self::get_mark()."\n获得指令列表。";
	}

    public static function match($msgObj)
    {
        $content = strtolower(trim($msgObj->Content));
		return $content == self::get_mark();
    }

	public function execute($msgObj, mc_weixin_session $session)
	{
		$cmds = array();
		foreach (glob(MUDDER_MODULE.'weixin'.DS.'component'.DS.'cmd'.DS.'*.php') as $filename) {
			if(basename($filename,'.php') == 'base') continue;
			$classname = "weixin_cmd_".pathinfo(strtolower($filename), PATHINFO_FILENAME);
			$cmds[] = $classname; 
		}
		
		if ($cmds) {
			$message = "指令列表：\n";
			foreach ($cmds as $cmd_class) {
                $message .= "\n".call_user_func(array($cmd_class, 'intro'));
				$message .= "\n~~~~~~~~~~~~";
			}
		} else {
			$message = "没有指令信息。";
		}

		$session->action = mc_weixin_session::ACTION_RESET;

		$reply_obj = mc_weixin_reply::factory('text');
		$reply_obj->set_user($msgObj->FromUserName, $msgObj->ToUserName);
		$reply_obj->set_content($message);
		//回复消息
		$reply_obj->send();
	}
	
}
/** end **/