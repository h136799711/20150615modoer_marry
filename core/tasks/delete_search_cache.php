<?php
/**
* @author moufer<moufer@163.com>
* @copyright (C)2001-2007 Moufersoft
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_task_delete_search_cache extends ms_task
{
	protected $name 		= '删除过期的搜索缓存';
    protected $descrption 	= '删除search_cache表内已经过期的缓存记录。';

    //默认每月1号0点运行脚本
    protected $time_exp     = 'hour=0|minute=30';

    protected $setting 		= '';

    public function __construct()
    {
    	parent::__construct();
    }

    /**
     * 执行脚本
     * @return boolean 执行成功返回true，反之false
     * 
     */
    public function run()
    {
        //读取设置的过期时间设定值
        $search_life = (int) S('search_life');
        //未设置，默认为60分钟
        empty($search_life) && $search_life = 60;
        $time = $this->timestamp - $search_life * 60;
        _G('db')->from('dbpre_search_cache')->where_less('dateline', $time)->delete();
    	return true;
    }
}