<?php
/**
* 微信指令会话
*/
class msm_weixin_converse extends ms_model
{
	var $table = 'dbpre_weixin_converse';
	var $key = 'id';
	var $model_flag = 'weixin';

	//会话删除条件，过期时间
	public $expiry_interval = 300;

	function __construct() {
		parent::__construct();
		$this->model_flag = 'weixin';
		$this->modcfg = $this->variable('config');
	}

	//取得一个session信息,返回类 mc_weixin_session 
	function read_session($hash)
	{
		$data = $this->db->from($this->table)
			->where('hash', $hash)
			->get_one();
		return new mc_weixin_session($data);
	}

	//更新session信息
	function update($cmd_session)
	{
		//设置最后更新时间
		$cmd_session->last_time = $this->timestamp;
		//获取全部字段
		$post = $cmd_session->fetch_all();
		//序列化交互内容
		$post['data'] = $post['data'] ? serialize($post['data']) : '';
		//删除不需要更新的字段
		if(! $cmd_session->is_new()) {
			unset($post['id'], $post['hash'], $post['openid']);
		}
		//更新保存
		parent::save($post, $cmd_session->id);
	}

	//删除会话记录
	function delete($cmd_session)
	{
		if(! $cmd_session->is_new()) {
			parent::delete($cmd_session->id);
		}
	}

	//重置会话记录
	function reset($cmd_session)
	{
		$cmd_session->last_cmd = '';
		$cmd_session->clear_data();
		$this->update($cmd_session);
	}

	//删除过期的session记录
	function delete_expiry()
	{
		$expiry_time = $this->timestamp - $expiry_interval;
		$this->db->from($this->table)
			->where_less('last_time', $expiry_time)
			->delete();
	}
}
