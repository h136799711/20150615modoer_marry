<?php
/**
* 中间层基类
*/
class mc_product_middle extends ms_base
{
	//默认参数
	protected $default_params = array();

	//输入的参数
	protected $params = array();
	//最后返回信息数组
	protected $result = array();
	//关联的数据库模型类
	protected $model;

	public function __construct($default_params = null) {
		parent::__construct();
		//参数合并
		if($default_params && is_array($default_params)) {
			$this->default_params = array_merge($this->default_params, $default_params);
		}
		$this->init();
	}

	/**
	 * 执行业务，并返回执行结果
	 * @param  array  $params      [description]
	 * @param  string $result_type 设置返回数据的格式（array,json,object），默认array
	 * @return mixed              返回数据，格式有函数第二个参数指定
	 */
	public function show($params = array(), $result_type = '')
	{
		//参数赋值
		if($this->set_params($params)) {
			//业务代码
			$this->work();
		}
		//返回结果
		return $this->get_result($result_type);
	}

	//初始化
	protected function init(){}

	/**
	 * 业务代码实现
	 */
	protected function work() {}

	protected function set_params($params) 
	{
		$this->params = $params;
		return true;
	}

	protected function get_param($var, $default = '',$filter_fun = '')
	{
	    if(isset($this->params[$var])) {
	        if($filter_fun) return $filter_fun($this->params[$var]);
	        return $this->params[$var];
	    }
	    return $default;
	}

	/**
	 * 获取返回数据信息
	 * @param  string $result_type 设置返回数据的格式（array,json,object），默认array
	 * @return [type] [description]
	 */
	protected function get_result($result_type = '')
	{
		if(!$result_type || !in_array($result_type, array('array','json','object'))) {
			$result_type = 'array';
		}
		if($this->has_error()) {
	        $this->result['code'] = $this->erron();
	        $this->result['message'] = $this->error();
		} else {
			$this->result['code'] = 200; //成功code
			$this->result['params'] = $this->params;
		}
		if($result_type == 'json') return $this->to_json(); //返回json格式
		if($result_type == 'object') return $this->to_object(); //返回对象形式
		return $this->result;
	}

	/**
	 * 最后的返回值数组转换成对象
	 * @return stdClass
	 */
	protected function to_object()
	{
		//TODO
	}

	/**
	 * 返回值数组转换成json
	 * @return string
	 */
	protected function to_json()
	{
		if(_G('charset') == 'utf-8') {
			return json_encode($this->result);
		} else {
			//非UTF8编码进行转码
			return json_encode($this->convert_utf8($this->result));
		}
	}

	/**
	 * 非UTF-8编码的数组转换成UTF-8
	 * @param  array $value 准备转换编码的数组
	 * @return array        转换成UTF-8编码的数组
	 */
	private function convert_utf8($array)
	{
		foreach ($array as $key => $value) {
			if(is_array($value)) {
				$array[$key] = $this->convert_utf8($val);
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

/** end **/