<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class ms_video_ku6 extends ms_video_base  {

    function __construct($url) {
        $this->charset = 'gb2312';
        parent::__construct($url); 
    }

    function get_url() {
        if(preg_match('%/show/(.*?)\.html%', $this->url, $match)) {
            return $this->get_show($match[1]);
        } elseif(preg_match('%special/show.*?/(.*?)\.html%', $this->url, $match)) {
            return $this->get_show($match[1]);
        } elseif(preg_match('%/film/show.*?/(.*?)\.html%', $this->url, $match)) {
            return $this->get_show($match[1]);
        } elseif(preg_match('%/film/index_[0-9]+\.html%', $this->url, $match)) {
            return $this->get_film();
        }

    }

    function get_show($id) {
        return "http://player.ku6.com/refer/$id/v.swf";
    }

    function get_film() {
        if(preg_match('/commvid\s?:\s?"(.*?)"/', $this->html, $match)) {
             return $this->get_show($match[1]);
        } else {
            return false;
        }
    }

}
?>