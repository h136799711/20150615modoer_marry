<?php
/**
* 微信会话 session 组件
*/
class mc_weixin_session extends ms_base
{
    const ACTION_SAVE 	= 'save';
    const ACTION_RESET 	= 'reset';
    const ACTION_DEL 	= 'delete';

	public $data;   //对话记录信息
    public $action; //指令记录是否需要进行保存，删除等行为

	protected $attr;    //字段属性

	//会话过期时间 300 秒，即5分钟
    protected $expiry_interval = 300;

	function __construct($table_data)
	{
		$this->data = new stdClass;
		if($table_data) {
			$this->set_data($table_data);
		}
	}

	function __set($key, $value)
	{
		$this->attr[$key] = $value;
	}

	function __get($key)
	{
		if (isset($this->attr[$key])) {
			return $this->attr[$key];
		}
		return;
	}

	//是否是新会话（未保存数据库）
	function is_new()
	{
		return !$this->attr['id'];
	}

	//是否已过期
	function is_expiry()
	{
		return $this->last_time + $this->expiry_interval < $this->timestamp;
	}

	//将全部字段属性到处为数组
	function fetch_all()
	{
		$attr =  $this->attr;
		$attr['data'] = array();
		//处理 data
		if($data) {
			$vars = get_class_vars($data);
			if($vars) {
				foreach ($vars as $key => $value) {
					$attr['data'][$key] = $value;
				}
			}
		}
		return $attr;
	}

	//清空会话数据
	function clear_data()
	{
		$this->data = null;
		$this->data = new stdClass;
	}

	//将数据复制给类
	private function set_data($data)
	{
		if (is_array($data) && ! empty($data)) {
			foreach ($data as $key => $value) {
				$this->$key = $value;
			}
			//data 会话自定义信息从序列化转换成类
			if($data['data']) {
				$data = unserialize($data['data']);
				foreach ($data as $key => $value) {
					$this->data->$key = $value;
				}
			}
		}
	}
}
