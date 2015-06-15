<?php
/**
 * 功能实验代码.API 
 */
class ms_api_code {

	/**
	 * api返回码前缀
	 * @var string
	 */
	static protected $_flag_num = '';

	/**
	 * API返回码数组
	 * @var array
	 */
	static protected $_codes_list = array ();

	static function fetch($code, $placeholder = null) {
		$code = (int)$code;
		$ret = array(
			'code' => '0000000',
			'message' => 'Unknown error',
		);
		if($message = self::$_codes_list[$code]) {
			if(is_array($placeholder)) {
				$message = str_replace(array_keys($placeholder), array_values($placeholder), $message);
			}
			$ret['code'] = $_flag_num . sprintf('%4d', $code);
			$ret['message'] = $message;
		}
		return $ret;
	}
}

/**
* ms_api
*/
class ms_api
{
	protected $show_data = null;

	public function show_error($ret) {
		$ret = ms_api_code::ftech($code);
		if($ret['message'] && _G('charset')!='utf-8') {
			$ret['message'] = charset_convert ($ret['message'], _G('charset'), 'utf-8');
		}
		$this->_show($ret);
	}

	public function show_result($data=null) {
		$ret = array(
			'code' => 200,
			'data' => $data,
		);
		$this->_show($ret);
	}

	public function _show($ret) {
		echo json_encode($ret);
		output();
	}

}