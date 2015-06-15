<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2011 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_mylist_seosetting extends msm_seosetting {

    public function __construct() {
        parent::__construct('mylist');
    }

    protected function _init() {

        $this->pages['index'] = array(
            'des' => '榜单首页',
            //系统初始默认SEO设置
            'default' => array(
                'title' => '{city_name}网友评选出的吃喝玩乐热门排行榜',
                'description' => '{site_name}为您提供网友创建的城市{city_name}的吃喝玩乐排行榜，收录了{city_name}好玩的,好玩的地方等',
            ),
        );

        $this->pages['list'] = array(
            //页面说明
            'des' => '榜单列表页（榜单首页有catid参数时）',
            //可用标签
            'tags' => array(
                'category_name' => '当前分类名称',
                'lo_name' => '排序名称',
                'page' => '当前分页编号',
            ),
            //缺省设置
            'default' => array(
                'title' => '{city_name}{category_name}{lo_name}榜单',
            ),
        );

        $this->pages['tag'] = array(
            'des' => '榜单标签页（榜单首页有tagid参数时）',
            'tags' => array(
                'tag_name' => '标签名称',
                'page' => '当前分页编号',
            ),
            'default' => array(
                'title' => '{city_name}{tag_name}榜单',
            ),
        );

        $this->pages['search'] = array(
            'des' => '榜单搜索页（榜单首页有k搜索关键字参数时）',
            'tags' => array(
                'keyword' => '搜索关键字',
                'page' => '当前分页编号',
            ),
            'default' => array(
                'title' => '{city_name}"{keyword}"榜单',
            ),
        );

        $this->pages['detail'] = array(
            'des' => '榜单内容页',
            'tags' => array(
                'title' => '榜单名',
                'username' => '榜主昵称',
                'intro' => '榜单介绍前100字',
                'tags' => '榜单标签（用逗号分割）',
            ),
            'default' => array(
                'title' => '{title}_榜主:{username}',
                'keywords' => '{username},{tags}',
                'description' => '{intro}',
            ),
        );

        foreach(array_keys($this->pages) as $key) {
            $this->pages[$key]['setting'] = $this->global_setting;
        }
    }

}
?>