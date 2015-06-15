<?php
/**
* @author moufer<moufer@163.com>
* @copyright (C)2001-2007 Moufersoft
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_task_delete_upload_temp extends ms_task
{
	protected $name 		= '删除网站上传临时文件夹';
    protected $descrption 	= '删除 uploads/temp 文件夹内的所有文件，但会保留30分钟内上传的文件。';

    protected $time_exp     = 'hour=3|minute=0';  //默认每天3点运行脚本

    protected $setting 		= array();

    public function __construct()
    {
    	parent::__construct();
    	$this->setting['tmpdir'] = MUDDER_ROOT.'uploads'.DS.'temp';
    }

    public function run()
    {
        $this->_delete($this->setting['tmpdir'], false);
        return true;
        //return $this->add_error('测试执行失败！');
    }

    private function _delete($dir, $rmdir = true) {
        if(!is_dir($dir)) return;
        $rmdir = true;
        $dh = opendir($dir);
        if(!$dh) return;
        while ($file=  readdir($dh)) {
            if($file != "." && $file != "..") {
                if($file=='index.html') continue;
                $fullpath = $dir.DS.$file;
                $ctime = $this->timestamp - filemtime($fullpath);
                //获取文件时间，不删除30分钟（1800秒）内上传的文件
                if(is_file($fullpath) && (!$ctime||$ctime > 1800)) {
                    @unlink($fullpath);
                } elseif(is_dir($fullpath)) {
                    $this->_delete($fullpath, true);
                } else {
                    $rmdir = false;
                }
            }
        }
        closedir($dh);

        if(!$rmdir || $dir == $this->setting['tmpdir']) return;
        rmdir($dir);
    }
}