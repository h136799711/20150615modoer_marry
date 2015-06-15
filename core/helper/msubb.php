<?php
class msubb {

	function clear($content) {
		return preg_replace("/\[\/\S+?\/\]/", "", $content);
	}
	
	function pares($content, $detail = null) {
        $content = msubb::smilies($content);
        $content = msubb::username($content);
        $content = msubb::image($content, $detail['pictures']);
        $content = msubb::video($content);
        return nl2br($content);
    }

    function smilies($content) {
    	return preg_replace("/\[\/([0-9]{1,2})\/\]/", "<img class=\"ubb-face\" src=\"".URLROOT."/static/images/smilies/\\1.gif\" />", $content);
    }

    function username($content) {
    	$username_arr = msubb::get_username($content);
    	if(!$username_arr) return $content;
    	$search = $replace = array();
    	foreach ($username_arr as $k => $value) {
    		$search[$k] = "[/@$value/]";
    		$replace[$k] = "<a href='".url("space/index/username/$value")."' target=\"_blank\">@$value</a>";
    	}
    	return str_replace($search, $replace, $content);
    }

    function image($content, $pictures) {
        $pictures = is_serialized($pictures) ? unserialize($pictures) : '';
        if(!$pictures) return $content;
        $img_arr = msubb::get_image($content);
        if(!$img_arr) return $content;
        $search = $replace = array();
        foreach ($img_arr as $k => $value) {
            $imgsrc = $pictures[$value];
            if(!$imgsrc) continue;
            $search[$k] = "[/img:$value/]";
            $replace[$k] = "<img src=\"".URLROOT."/$imgsrc\" class=\"ubb_show_image\" />";
        }
        return str_replace($search, $replace, $content);
    }

    function video($content) {
        $video_arr = msubb::get_video($content);
        if(!$video_arr) return $content;
        $search = $replace = array();
        foreach ($video_arr as $k => $value) {
            $search[$k] = "[/video:$value/]";
            if(check_flash_domain($value)) {
                $replace[$k] = "<div class=\"show_video\" id=\"video_$k\" params=\"{'video':'$value'}\">".(is_ios()?'视频不支持ios系统播放':'视频加载中...')."</div>";
            } else {
                $replace[$k] = "<div class=\"block_video\">视频已屏蔽，管理员限制了 ".get_fl_domain($value)."  下的视频在本站显示。</div>";
            }
        }
        return str_replace($search, $replace, $content);
    }

    function get_username($content) {
    	 if ( ! preg_match_all('%\[/@(\S+?)/\]%', $content, $matches)) return;
    	 return $matches[1];
    }

    function get_image($content) {
         if ( ! preg_match_all('%\[/img:(\S+?)/\]%', $content, $matches)) return;
         return $matches[1];
    }

    function get_video($content) {
         if ( ! preg_match_all('%\[/video:(\S+?)/\]%', $content, $matches)) return;
         return $matches[1];
    }
}
?>