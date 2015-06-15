<?php
/**
* 可执行脚本基类
* @author moufer<moufer@163.com>
* @copyright (C)2001-2007 Moufersoft
*/
!defined('IN_MUDDER') && exit('Access Denied');

interface IRunScript {
    public function run();
}

class ms_runscript extends ms_base implements IRunScript {

    /**
     * 脚本名称
     * @var string
     */
    protected $name = '';
    /**
     * 脚本说明
     * @var string
     */
    protected $descrption = '';
    /**
     * 脚本类型(delete,rebuild,repair,...)
     * @var string
     */
    protected $type = 'other';

    /**
     * 获取脚本名称
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * 获取脚本类型
     * @return string 
     */
    public function descrption()
    {
        return $this->descrption;
    }

    /**
     * 获取脚本类型
     * @return string
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * 执行脚本
     * @return boolean 返回脚本是否成功执行
     */
    public function run()
    {
        return true;
    }

}

/** end **/