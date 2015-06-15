<?php
/**
* 删除游客访问个人空间记录
* @author moufer<moufer@163.com>
* @copyright (C)2001-2007 Moufersoft
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_task_delete_space_visitor extends ms_task
{
	protected $name 		= '删除个人空间访问记录';
    protected $descrption 	= '删除120天前的个人空间的访问记录';

    //默认每月1号0点运行脚本
    protected $time_exp     = 'day=1|hour=0|minute=0';

    protected $setting 		= '';

    public function __construct()
    {
    	parent::__construct();
    	$this->setting['day'] = 120; //删除120天前的访问记录
    }

    public function run()
    {
    	$db = _G('db');
    	$time = _G('timestamp') - (86400 * $this->setting['day']); //删除此时间之前的访问记录
    	$db->from('dbpre_visitor')->where_less('dateline', $time)->delete();
    	return true;
    }
}