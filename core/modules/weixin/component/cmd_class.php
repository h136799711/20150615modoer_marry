<?php
/**
* 微信指令接口
*/
interface mi_weixin_cmd
{
	//获取指令名称
	public static function get_name();

	//获取指令标记
	public static function get_mark();

	//指令格式,返回一个只用使用说明字符串
	public static function intro();

	//指令标志判断
	public static function match($msgObj);

	//执行命令，如果命令无法解析，返回值为false
    public function execute($usermsg, mc_weixin_session $session);

}

abstract class mc_weixin_cmd extends ms_base implements mi_weixin_cmd {

	function __construct() 
	{
		parent::__construct();
	}

}
/** end **/