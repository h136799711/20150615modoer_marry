<?php
/**
* 收货地址管理
*/
/**
* sd
*/
class mc_product_address extends ms_base
{

	public $uid = 0;
	protected $model;

	function __construct()
	{
		parent::__construct();
		$this->model = _G('loader')->model('member:address');
	}

	function set_uid($uid)
	{
		$this->uid = $uid;
		if(!$this->uid) {
			return $this->add_error('未提供会员UID参数。');
		}
		if(_G('user')->uid != $this->uid && !$this->in_admin) {
			//不是管理员获取，也不是获取自己，则无权限
			return $this->add_error('global_op_access');
		}

		return true;
	}

	public function get_one($address_id)
	{
		if(!$address_id = _int_keyid($address_id)) return $this->add_error('对不起，未提供地址数据ID。');
		$data = $this->model->read($address_id);
		if(!$data) return $this->add_error('地址信息不存在。');
		//不是管理员，地址信息也不是自己的，则无权限
		if($data['uid'] != $this->uid && !$this->in_admin) {
			return $this->add_error('global_op_access');
		}
		return $data;
	}

	//获取地址列表
	public function get_list()
	{
		$result = array();

		$r = $this->model->get_list($this->uid);
		if(!$r) return;

		$data = array();
		while ($v = $r->fetch_array()) {
			$data[] = $v;
			//if($v['is_default']) $result['defualt_id'] = $v['id'];
		}

		return $data;
	}

	//新增地址信息
	public function add()
	{
		$post = $this->model->get_post($_POST);
		if(_G('charset') != 'utf-8') {
			$post = $this->convert_gbk($post);
		}
		$address_id = $this->model->save($post);
		if(!$address_id) {
			return $this->get_error();
		}
		return $address_id;
	}

	//错误讯息
	protected function get_error()
	{
		if($this->model->has_error()) {
			return $this->add_error($this->model);
		}
		$this->result['error_data'] = $this->model->error;
		return $this->add_error(array_shift($this->model->error));
	}

	private function convert_gbk($array)
	{
        if(!$array) return $array;
		foreach ($array as $key => $value) {
			if(is_array($value)) {
				$array[$key] = $this->convert_gbk($value);
			} else {
				if(is_numeric($value)) {
					$array[$key] = $value;
				} else {
					$array[$key] = charset_convert($value, 'utf-8', _G('charset'));
				}
			}
		}
		return $array;
	}
}

/** end **/