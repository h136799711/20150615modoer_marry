<?php

/**
* 空间访客记录操作
*/
class msm_space_visit extends ms_model
{
	var $table = 'dbpre_space_visit';
    var $key = 'id';
    var $model_flag = 'space';

    //离上一次访问时间超过设置值时，计算为一次新的访问记录(秒)
    public $history_tnterval	= 300;

	function __construct()
	{
		parent::__construct();
	}

	function add($space, $visit)
	{
		if ( ! $visit->isLogin) return;
		//获取上次访问记录
		$history = $this->get_history($space->uid, $visit->uid);

		if ( $history && $this->timestamp - $history['last_time'] > $this->history_tnterval) 
		{
			//如果记录不再，或超过个允许更新数据库时间的话，进行数据库更新操作
			$this->db->from($this->table)
				->set('last_time', $this->timestamp)
				->set_add('visit_count', 1)
				->update();

			$pkid = $history['id'];
		}
		elseif ( ! $history)
		{
			$post = array();
			$post['space_uid'] 	= $space->uid;
			$post['uid']		= $visit->uid;
			$post['username']	= $visit->username;
			$post['last_time'] 	= $this->timestamp;
			$post['visit_count']= 1;
			//添加新纪录
			$pkid = parent::save($post);
		}

		return $pkid;
	}

	//获取上次访问记录
	function get_history($space_uid, $visit_uid)
	{
		return $this->db->from($this->table)
			->where('space_uid', $space_uid)
			->where('uid', $visit_uid)
			->get_one();
	}

}