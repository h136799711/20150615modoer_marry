<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class ms_video_qq extends ms_video_base  {

    function __construct($url) {
        $this->charset = 'utf-8';
        parent::__construct($url);
    }

    function get_url() {
        if(preg_match('%cover/q/.*?\.html\?vid=(.*?)$%', $this->url, $match)) {
            return $this->get_view($match[1]);
        } else {
            return $this->get_video();
        }
    }

    function get_video() {
        if(preg_match('/vid\s?:\s?[\'|"](.*?)[\'|"]/', $this->html, $match)) {
             return $this->get_view($match[1]);
        } else {
            return false;
        }
    }

    function get_view($vid) {
        return "http://static.video.qq.com/TPout.swf?vid=$vid&auto=0";
    }
}
?>