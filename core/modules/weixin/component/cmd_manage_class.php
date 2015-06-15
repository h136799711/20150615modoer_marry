<?php
/**
* 指令管理器
*/
class mc_weixin_cmd_manage extends ms_base
{
	//全部指令类列表
	protected $cmds;
	//指令会话模型
	protected $model;

	//默认回复指令类
	protected $defult_cmd_classname = 'weixin_cmd_welcome';
	protected $base_cmds = array('welcome','help');

	function __construct()
	{
		parent::__construct();
		$this->cmds = $this->get_installed_cmds();
		$this->model = $this->loader->model('weixin:converse');
	}

	//运行指令
	function run($usermsg)
	{
		if(DEBUG) log_write('weixin_debug', request_uri());

        //找指令对话记录
        $hash = md5($usermsg->FromUserName);
        $session = $this->model->read_session($hash);

        //如果用户输入的是@exit则表示要跳出指令对话
        if(strtolower($usermsg->Content) == '@exit') {
            //重置指令信息
            $this->model->reset($session); 
            return;
        }

		//查找指令
		$cmd_classname = $this->find_cmd($usermsg, $session);
        
        //指令记录不存在或已过期，进行动态复制
        if ($session->is_new() || $session->is_expiry()) {
            $session->hash       = md5($usermsg->FromUserName);
            $session->openid     = _T($usermsg->FromUserName);
            $session->uid        = 0;
            $session->last_time  = $this->timestamp;
        }

		//执行指令
		if (!$cmd_classname) {
			//如果系统没有开启无法识别指令时自动回复还原信息的功能时
			if(!S('weixin:auto_send_message')) {
				return;
			}
            //如果没有找到指令，则判断是否进入默认指令
            $cmd_classname = $this->defult_cmd_classname;
		}
        $session->last_cmd = $cmd_classname;

        //实例化指令类，并执行指令逻辑代码
        $cmd = new $cmd_classname;
        $cmd->execute($usermsg, $session);

        //执行结束后，如果指令对话记录有内容时，需要进行保存
        if ($session->action == mc_weixin_session::ACTION_SAVE) {
            $session->last_time = $this->timestamp;   //最近一次触发指令
            $this->model->update($session);
        } elseif ($session->action == mc_weixin_session::ACTION_RESET) {
            $this->model->reset($session);
        }

        output();
	}

	//查找匹配的指令
	function find_cmd($usermsg, $session)
	{
		//如果没有找到指令session记录，或指令sesion已过期，则通过指令标签对所有指令进行判断
		if($session->is_new() || $session->is_expiry() || !$session->last_cmd) {
            $result = '';
			if($this->cmds) {
				foreach ($this->cmds as $classname) {
                    //自定义菜单点击类型
                    if($usermsg->MsgType == 'event' && $usermsg->Event == 'CLICK') {
    		            $mark = call_user_func("$classname::get_mark");
                        //log_write('weixin_debug', "$classname::get_mark\t".$mark."\t".$usermsg->EventKey);
                        if(strtolower($usermsg->EventKey) == $mark) {
                            return $classname;
                        }
    	            }
                    //通过指令类静态函数match，对用户信息进行检测是否匹配
					$match = call_user_func_array(array($classname, 'match'), array($usermsg));
					if($match) return $classname;
				}
			}
		} elseif(! $session->is_new() &&! $session->is_expiry() && $session->last_cmd) {
			//如果有会话记录，并且尚未超时，则再次进入这个指令
			return $session->last_cmd;
		}
	}

	//获取用户是否在某个指令会话进程中
	function get_cmd_session($usermsg)
	{
		$openid = $usermsg->FromUserName;
		$hash = md5($openid);
		$session = $this->model->read_session($hash);
		//如果数据库内没有这个会话记录，则从usermsg中填充必要数据
		if($cmd_cs->is_new()) {
			$cmd_cs->openid = $openid;
			$session->hash = $hash;
		}
	}

	//获取文件夹内全部指令类
	function get_cmd_list()
	{
		$result = array();
		foreach (glob(MUDDER_MODULE.'weixin'.DS.'component'.DS.'cmd'.DS.'*.php') as $filename) {
			if(basename($filename,'.php') == 'base') continue;
			$classname = "weixin_cmd_".pathinfo(strtolower($filename), PATHINFO_FILENAME);
			$result[] = $classname; 
		}
		return $result;
	}

	//指令类的自动加载
	static function autoload($classname)
	{
		if ( class_exists( $classname ) ) return true;
		$aps = explode( '_', $classname);
		$count = count($aps);
		if (!$aps || $count <= 2 ) return false;
		if($aps[0] == 'weixin' && $aps[1] == 'cmd') {
			$name = strtolower(substr($classname, strlen($aps[0].'_'.$aps[1].'_')));
			$filename = MUDDER_MODULE.'weixin'.DS.'component'.DS.'cmd'.DS. $name.'.php';
		}
		if($filename && is_file( $filename )) {
			debug_log('file','autoload',$filename);
			require $filename;
		}
		return class_exists($classname);
	}

	//获取已安装且可用的指令
	function get_installed_cmds()
	{
		$installed = S('weixin:cmds');
		$installed = $installed ? explode(',', $installed) : $this->base_cmds;
		//必须加入基础指令
		foreach ($this->base_cmds as $cmd) {
			if(!in_array($cmd, $installed)) $installed[]=$cmd;
		}
		//获取全部指令文件
		$cmds = $this->get_cmd_list();
		$result = array();
		//删除已经不存在的指令文件
		foreach ($installed as $name) {
			$classname = 'weixin_cmd_'.$name;
			if(in_array($classname, $cmds)) $result[] = $classname;
		}
		return $result;
	}

}

//自动加载类文件
spl_autoload_register(array('mc_weixin_cmd_manage', 'autoload'));