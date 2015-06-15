<?php
/**
* 收货地址保存（新增和编辑）
*/
class mc_product_address_save extends mc_product_middle
{
	protected $model;

	protected $uid = 0;

	protected function init()
	{
		$this->model = $this->loader->model('member:address');
	}

	protected function work()
	{
		$this->uid = $this->get_param('uid',0,MF_INT_KEY);
		if(!$this->uid) {
			//获取自己的地址信息
			if(_G('user')->isLogin) {
				$this->params['uid'] = $this->uid = _G('user')->uid;
			}
		}
		if(!$this->uid) {
			return $this->add_error('未提供会员UID参数。');
		}

		$op = $this->get_param('op');
		if($op == 'add') {
			$address_id = $this->add();
		// } elseif($op == 'edit') {
		// 	$this->edit();
		} else {
			return $this->add_error('global_op_unkown');
		}
		if($address_id > 0) {
			$data = $this->model->read($address_id);
			if(!$data) return $this->add_error('地址信息不存在。');
			$this->result['data'] = $data;
		}

	}

	//新增地址信息
	protected function add()
	{
		$post = $this->model->get_post($this->params);
		$address_id = $this->model->save($post);
		if(!$address_id) {
			$this->get_error();
			return;
		}
		return $address_id;
	}

	//编辑地址信息
	protected function edit()
	{
		//todo
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
}