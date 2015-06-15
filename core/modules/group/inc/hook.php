<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class hook_group extends ms_base {

    function __construct(&$hook) {
        parent::__construct();
        $hook->register(
            array('subject_detail_link','mobile_index_link','space_nav_link'), 
            $this
        );
    }

    function subject_detail_link(&$params) {
        extract($params);
        if(_G('loader')->model(':group')->exists_related_subject($sid)) {
            $result = array();
            $result[] = array (
                'flag' => 'group',
                'url' => url("group/item/sid/$sid"),
                'title'=> lang('group_title'),
            );
            return $result;
        }
        return null;
    }

    function mobile_index_link() {
        $result[] = array (
            'flag' => 'group/list',
            'url' => url('group/mobile/do/list'),
            'title'=> '小组',
            'icon' => 'group',
        );
        return $result;
    }

    //个人空间导航
    function space_nav_link($space) {
        $result = array();
        $result[] = array (
            'flag' => 'group',
            'url' => url("space/{$space->uid}/pr/group_topics"),
            'title'=> "帖子",
        );
        return $result;
    }
}
?>