<?php
/**
* 可执行脚本基类
* @author moufer<moufer@163.com>
* @copyright (C)2001-2007 Moufersoft
*/
!defined('IN_MUDDER') && exit('Access Denied');

class ms_task extends ms_runscript {

    /**
     * 默认的定时执行时间表达式
     * weekday=1|hour=0|minute=0    表示每周1的0:00执行
     * day=1|hour=0|minute=0    表示每月1日的0:00执行
     * hour=1|minute=0    表示每天1:00执行
     * minute=30    表示每30分钟执行
     * 留空表示不主动执行
     * @var array
     */
    protected $time_exp = "";

    /**
     * 获取脚本默认的定时表达式
     */
    public function get_time_exp()
    {
        return $this->time_exp;
    }

}

/** end **/