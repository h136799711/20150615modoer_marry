<?php
/**
* AJAX文件上传类
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class ms_ajaxupload {

    private $ajax_filename = '';
    private $upload_filename = '';

    private $dir_mod = '';
    private $root_dir = 'temp';

    private $_file = array();

    function __construct($filename, $rootdir = '') {
        $this->ajax_filename = $filename;
        $this->_file = $_FILES[$filename];
        if(!$file_ext) $file_ext = 'rar zip 7z txt';
        $this->setFileExt($file_ext);
        $this->max_size = size_bytes(ini_get('upload_max_filesize'));
        if($rootdir) $this->root_dir = $rootdir;
    }

    function setMaxSize($size) {
        $this->max_size = min($this->max_size, size_bytes($size . 'k'));
    }

    function setSaveDirMod($mod) {
        $s = array('WEEK','DAY','MONTH');
        if(in_array($mod, $s)) {
            $this->dir_mod = $mod;
        }
    }

    function setImageFile() {
        $this->image_file = true;
    }

    function setFileExt($exts) {
        if(!$exts) return '';
        $exts = explode(' ', $exts);
        foreach($exts as $k => $v) {
            if(!$v) {
                unset($exts[$k]);
            } elseif(preg_match("/(php|inc|asp|jsp|aspx|shtml|vbs|do)/i",$v)) {
                unset($exts[$k]);
            } else {
                $exts[$k] = strtolower($v);
            }
        }
        if($exts) $this->limit_ext = $exts;
    }

    function getFileName() {
        return $this->upload_filename;
    }

    function startUpload() {
        //初步检查
        $this->_check();

        //创建目录
        $save_dir = $this->createDir();

        //生成文件名
        //$file_ext = strtolower(pathinfo($this->ajax_filename, PATHINFO_EXTENSION));
        $file_ext = strtolower(pathinfo($this->_file['name'], PATHINFO_EXTENSION));

        if(!$this->lock_name) {
            //list($ms, $time) = explode(' ', microtime());
            $name = get_timename() . '.' . $file_ext;
        } else {
            $name = $this->lock_name . '.' . $file_ext;
        }
        $filename = $save_dir . DS . $name;

        //接收保存上传文件
        $sc = move_uploaded_file($this->_file['tmp_name'], MUDDER_ROOT . $filename);
        if(!$sc) {
            redirect('global_upload_lost');
        }

        /*
        $filesize = @file_put_contents( MUDDER_ROOT . $filename, file_get_contents('php://input') );
        if($filesize < 1) {
            redirect('global_upload_lost');
        } elseif($filesize > $this->max_size) {
            @unlink(MUDDER_ROOT . $filename);
            redirect(sprintf(lang('global_upload_szie_thraw'), floor($this->max_size/1024) ,'KB'));
        }
        */

        //如果是图片文件
        if($this->image_file && !$this->checkImage(MUDDER_ROOT . $filename)) {
            redirect('global_upload_image_unkown');
        }

        //上传成功
        $this->upload_filename = $filename;
        return true;
    }

    function createDir() {
        $upload_dir = 'uploads';
        $path[0] = $upload_dir . DS . $this->root_dir;
        if($subdir == 'WEEK') {
            $subdir = date('Y', _G('timestamp')).'-week-'.date('W', _G('timestamp'));
        } elseif($subdir == 'DAY') {
            $subdir = date('Y-m-d', _G('timestamp'));
        } elseif(!$subdir || $subdir == 'MONTH') {
            $subdir = date('Y-m', _G('timestamp'));
        }
        $path[1] = $path[0] . DS . $subdir;
        //检测是否存在
        foreach ($path as $dir) {
            if(!@is_dir(MUDDER_ROOT . $dir)){
                if(!@mkdir(MUDDER_ROOT . $dir, 0777)) {
                    redirect('global_mkdir_no_access', $dir);
                }
            }
        }
        return array_pop($path);
    }

    function checkFileExt($filename) {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if(!$ext) return FALSE;
        return in_array($ext, $this->limit_ext);
    }

    //判断是否为图片
    function checkImage($filename) {
        if(!function_exists('getimagesize')) return false;
        if(!is_file($filename)) return false;
        $size = @getimagesize($filename);
        return $size && $size[0] > 0 && $size[1] > 0 && strtolower(substr($size['mime'],0,6))=='image/';
    }

    function _check() {
        if(!is_uploaded_file($this->_file['tmp_name'])) {
            @unlink($this->_file['tmp_name']);
            redirect('global_upload_unkown');
        } elseif(!$this->checkFileExt($this->_file['name'])) {
            @unlink($this->_file['tmp_name']);
            redirect(lang('global_upload_type_invalid', implode('，', $this->limit_ext)));
        } elseif($this->_file['size'] > $this->max_size) {
            @unlink($this->_file['tmp_name']);
            redirect(sprintf(lang('global_upload_szie_thraw'), floor($this->max_size/1024) ,'KB'));
        }
        return TRUE;
    }

}