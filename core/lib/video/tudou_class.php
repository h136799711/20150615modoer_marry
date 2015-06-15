<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class ms_video_tudou extends ms_video_base  {

    function __construct($url) {
        $this->charset = 'gb2312';
        parent::__construct($url); 
    }

    function get_url() {
        if(preg_match('%programs/view/(.*?)($|/)%', $this->url, $match)) {
            return $this->get_view($match[1]);
        } elseif(preg_match('%/listplay/(.*?)($|/)%', $this->url, $match)) {
            return $this->get_listplay($match[1]);
        } elseif(preg_match('%/albumplay/(.*?)($|/)%', $this->url, $match)) {
            return $this->get_albumplay($match[1]);
        }
    }

    function get_view($id) {
        return "http://www.tudou.com/v/$id/v.swf";
    }

    function get_listplay($id) {
        if(preg_match('/iid\s?:\s?([0-9]+)/', $this->html, $match)) {
             return "http://www.tudou.com/l/$id/&iid=$match[1]/v.swf";
        } else {
            return false;
        }
    }

    function get_albumplay($id) {
        if(preg_match('/iid\s?:\s?([0-9]+)/', $this->html, $match)) {
             return "http://www.tudou.com/a/$id/&iid=$match[1]/v.swf";
        } else {
            return false;
        }
    }

}
?>