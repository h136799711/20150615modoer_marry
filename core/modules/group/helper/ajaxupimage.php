<?php

/**
* 
*/
class ajaxupimage extends ms_base {

	private $dirname = 'pictures';

	function __construct($dirname = null) {
        parent::__construct();
		if($dirname) {
            $this->dirname = $dirname;
        }
        $path = 'uploads' . DS . $dirname;
        if(!@is_dir(MUDDER_ROOT . $path)) {
            if(!mkdir(MUDDER_ROOT . $path, 0777)) {
                show_error(lang('global_mkdir_no_access',$path));
            }
        }
	}

	function post($pics, $old = null) {
        if(!$pics && !$old) return null;
        if($old) {
            if(is_serialized($old)) $old = unserialize($old);
            if(is_array($old)) foreach ($old as $key => $value) {
                if(!isset($pics[$key])) $this->delete($value);
            }
        }
        $result = array();
        if($pics) {
            foreach ($pics as $key => $value) {
                if(!is_image(MUDDER_ROOT . $value)) continue;
                if(strposex($value, '/temp/')) {
                    $file = $this->move($value);
                    if($file) $result[_T(pathinfo($file, PATHINFO_FILENAME))] = $file;
                } elseif(strposex($value, '/'.$this->dirname.'/')) {
                    if(is_file(MUDDER_ROOT . $value)) {
                        $result[_T(pathinfo($value, PATHINFO_FILENAME))] = $value;
                    }
                }
            }
        }
        return $result;
    }

    function delete($file) {
        if(is_array($file)) {
            foreach ($file as $value) {
                $this->delete($value);
            }
        } else {
            if(is_file(MUDDER_ROOT . $file) && (strposex($file, '/'.$this->dirname.'/') || strposex($file, '/temp/'))) {
                @unlink(MUDDER_ROOT . $file);
            }
        }
    }

    function move($pic) {
        $sorcuefile = MUDDER_ROOT . $pic;
        if(!is_file($sorcuefile)) {
            return false;
        }
        if(function_exists('getimagesize') && !@getimagesize($sorcuefile)) {
            @unlink($sorcuefile);
            return false;
        }

        $name = basename($sorcuefile);
        $path = 'uploads';
        if($this->global['cfg']['picture_dir_mod'] == 'WEEK') {
            $subdir = date('Y', _G('timestamp')).'-week-'.date('W', _G('timestamp'));
        } elseif($this->global['cfg']['picture_dir_mod'] == 'DAY') {
            $subdir = date('Y-m-d', _G('timestamp'));
        } else {
            $subdir = date('Y-m', _G('timestamp'));
        }
        $subdir = $this->dirname . DS . $subdir;
        $dirs = explode(DS, $subdir);
        foreach ($dirs as $val) {
            $path .= DS . $val;
            if(!@is_dir(MUDDER_ROOT . $path)) {
                if(!mkdir(MUDDER_ROOT . $path, 0777)) {
                    show_error(lang('global_mkdir_no_access',$path));
                }
            }
        }
        $result = array();
        $filename = $path . DS . $name;
        $picture = str_replace(DS, '/', $filename);
        if(!copy($sorcuefile, MUDDER_ROOT . $filename)) {
            return false;
        }

        $this->loader->lib('image', null, false);
        $IMG = new ms_image();
        $IMG->thumb_mod = $this->global['cfg']['picture_createthumb_mod'];
        $IMG->set_thumb_level($this->global['cfg']['picture_createthumb_level']);
        //打水印
        if($this->global['cfg']['watermark']) {
            $wlw = (int) $this->global['cfg']['watermark_limitsize_width'];
            $wlh = (int) $this->global['cfg']['watermark_limitsize_height'];
            if($IMG->need_watermark(MUDDER_ROOT . $filename, $wlw, $wlh)) {
                $IMG->watermark_postion = $this->global['cfg']['watermark_postion'];
                $wtext = $this->global['cfg']['watermark_text'] ? $this->global['cfg']['watermark_text'] : $this->global['cfg']['sitename'];
                if($this->global['user']->username) {
                    $IMG->set_watermark_text(lang('item_picture_wtext',array($wtext, $this->global['user']->username)));
                } else {
                    $IMG->set_watermark_text($this->global['cfg']['sitename']);
                }
                $wfile = MUDDER_ROOT . 'static' . DS . 'images' . DS . 'watermark.png';
                $IMG->watermark(MUDDER_ROOT . $filename, MUDDER_ROOT . $filename, $wfile);
            }
        }
        if(!DEBUG) @unlink($sorcuefile);
        return $picture;
    }

}

/** end **/