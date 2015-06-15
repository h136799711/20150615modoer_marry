<?php
/**
* @author moufer<moufer@163.com>
* @copyright (C)2001-2007 Moufersoft
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_task_delete_member_notice extends ms_task
{
	protected $name 		= '删除过期系统提醒记录';
    protected $descrption 	= '删除120天前的已阅读系统提醒记录';

    //默认每月1号0点运行脚本
    protected $time_exp     = 'day=1|hour=5|minute=0';

    protected $setting 		= '';

    public function __construct()
    {
    	parent::__construct();
    	$this->setting['day'] = 120; //删除120天前的访问记录
    }

    public function run()
    {
        $days_ago = $this->setting['day'];
        _G('db')->from('dbpre_notice');
        _G('db')->where('isread', 1);
        if($days_ago > 0) {
            $datetime = strtotime("-{$days_ago} days", _G('timestamp'));
            _G('db')->where_less('dateline', $datetime);
        }
        _G('db')->delete();
    	return true;
    }
}