<?php
/**
* 微信公众号
*/
class mc_weixin extends ms_base 
{
	private $msgObj = null;
	private $accObj = null;
	
	function __construct() 
	{
		parent::__construct();
		$this->accObj = mc_weixin_account::instant();
	}

	//真实性验证
	public function verify() 
	{
		return $this->checkSignature();
	}

	//微信信息接收
	function response()
	{
		//消息验证通过
		if($this->verify()) {
			//微信服务器首次验证
			if($_GET['echostr']) {
				echo $_GET['echostr'];
				exit;
			}
			//消息接送
			$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
			if (!empty($postStr)) {
				if(DEBUG) log_write('weixin_debug', $postStr);
				$this->msgObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
				//微信信息接受类做详细处理
				$this->process();
			} else {
				//用户发送的信息为空
				$msg = array(
					'msg' => 'POST_DATA EMPTY',
					'url' => $_SERVER['REQUEST_URI'],
				);
				log_write('weixin', $msg);
			}
		} else {
			//参数验证失败
			$msg = array(
				'msg' => 'verify invalid',
				'url' => $_SERVER['REQUEST_URI'],
			);
			//写入日志
			log_write('weixin', $msg);
		}
	}

	//针对用户回复信息进行处理
	function process()
	{
		if (! $this->msgObj) {
			return;
		}
		//实例化模型管理类
		$cmd_manage = new mc_weixin_cmd_manage();
		$cmd_manage->run($this->msgObj);
	}

	//字段参数验证
	private function checkSignature() 
	{
		$signature 	= $_GET["signature"];
		$timestamp 	= $_GET["timestamp"];
		$nonce 		= $_GET["nonce"];	
		
		$tmpArr = array($this->accObj->token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );

		return $tmpStr == $signature;
	}
}