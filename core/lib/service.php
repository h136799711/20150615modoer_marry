<?php
/**
* 服务型数据返回
*/
class ms_service extends ms_base
{
	//类属性数组
	protected $classAttr     = array();
	
	function __construct() {
		parent::__construct();
		//默认code为200,200表示数据返回成功
		$this->set_attr('code', 200);
	}

	/**
	 * 设置属性
	 * @param string $key   属性键名
	 * @param mixed $value 属性值内容
	 */
	public function __set($key, $value)
	{
		$this->set_attr($key, $value);
	}

	/**
	 * 获取一个属性值
	 * @param  string $key 属性键名
	 * @return mixed      如果属性存在，则返回属性值，不存在则返回null
	 */
	public function __get($key)
	{
		return $this->get_attr($key);
	}

	public function __call($name, $arguments) 
	{
		if(substr($name, 0, 6)=='fetch_') {
			return $this->fetch_all_attr(substr($name, 6));
		}
		parent::__call($name, $arguments);
	}

	/**
	 * 添加一个错误信息(覆盖父类)
	 * @param string  $errmsg     错误信息（多语言标志或原始文本）
	 * @param integer $erron      错误信息编号
	 * @param boolean $show_error 
	 */
	function add_error($errmsg, $erron = 110000, $show_error = false) 
	{
		$result = parent::add_error($errmsg, $erron, $show_error);
		$this->set_attr('code', $this->erron());
		$this->set_attr('message', $this->error());
		return $result;
	}

	/**
	 * 设置属性
	 * @param string $key   属性键名
	 * @param mixed $value 属性值内容
	 */
	public function set_attr($key, $value)
	{
		$this->classAttr[$key] = $value;
	}

	/**
	 * 判断配置属性否存在，如果设置了key参数，则检测属性名为$key的数组值是否存在
	 * @param  string  $key 属性键名
	 * @return boolean      属性值是否存在
	 */
	public function has_attr($key = '')
	{
		//未指定哦按段那个属性值时，则判断是否存在其中任何一个属性值
		if (! $key) {
			return !empty($this->classAttr);
		}
		return isset($this->classAttr[$key]);
	}

	/**
	 * 获取一个属性值
	 * @param  string $key 属性键名
	 * @return mixed      如果属性存在，则返回属性值，不存在则返回null
	 */
	public function get_attr($key)
	{
		if (isset($this->classAttr[$key])) {
			return $this->classAttr[$key];
		}
		return null;
	}

	/**
	 * 以数组方式返回全部属性
	 * @param  string $type 需要返回的属性组类型  
	 * @return mixed       根据$type参数返回指定的类型，包括array(默认)，json，object
	 */
	public function fetch_all_attr($type = '')
	{
		if($type=='json') {
			return $this->attr_to_json();
		} elseif($type=='object') {
			return $this->attr_to_object();
		} else {
			return $this->classAttr;
		}
	}

	/**
	 * 最后的返回值数组转换成对象
	 * @return stdClass
	 */
	protected function to_object()
	{
		return (object)$this->classAttr;
	}

	/**
	 * 返回值数组转换成json
	 * @return string
	 */
	protected function attr_to_json()
	{
		if(_G('charset') == 'utf-8') {
			return json_encode($this->classAttr);
		} else {
			//非UTF8编码进行转码
			return json_encode($this->convert_utf8($this->classAttr));
		}
	}

	/**
	 * 非UTF-8编码的数组转换成UTF-8
	 * @param  array $value 准备转换编码的数组
	 * @return array        转换成UTF-8编码的数组
	 */
	private function convert_utf8($array)
	{
		if(!$array) return $array;
		foreach ($array as $key => $value) {
			if(is_array($value)) {
				$array[$key] = $this->convert_utf8($value);
			} else {
				if(is_numeric($value)) {
					$array[$key] = $value;
				} else {
					$array[$key] = charset_convert($value, _G('charset'), 'utf-8');
				}
			}
		}
		return $array;
	}
	
}

/** end */