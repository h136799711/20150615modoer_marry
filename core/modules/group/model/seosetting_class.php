<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2011 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_group_seosetting extends msm_seosetting {

    public function __construct() {
        parent::__construct('group');
    }

    protected function _init() {
        $this->pages['index'] = array(
            'des' => '模块首页',
            //系统初始默认SEO设置
            'default' => array(
                'title' => '{city_name}小组讨论区',
                'keywords' => '{site_name},{city_name}小组,生活社区,网友聚会活动,拼购代购团购,家居装修,结婚日记,亲子乐园',
                'description' => '{site_name}小组社区频道是{city_name}本地网友聚集地，这里讨是论购物团购拼购消费，生活情感经历，家庭装修和娱乐吧话的最佳场所。',
            ),
        );

        $this->pages['list'] = array(
            'des' => '小组列表页面（无分类参数时）',
            'default' => array(
                'title' => '{city_name}地方小组列表_第{page}页',
            ),
        );

        $this->pages['list_tag'] = array(
            'des' => '小组列表页面（有分类参数时）',
            'tags' => array(
                'category_name' => '当前分类名称',
                'category_title'=>'当前分类标题(在小组分类管理处设置)',
                'category_keywords' => '当前分类关键字(在小组分类管理处设置)',
                'category_description' => '当前分类描述(在小组分类管理处设置)',
                'page' => '当前分页编号',
            ),
            //系统初始默认SEO设置
            'default' => array(
                'title' => '{city_name}{category_title}_{category_name}相关小组列表_第{page}页',
                'keywords' => '{category_keywords}',
                'description' => '{category_description}',
            ),
        );

        $this->pages['group'] = array(
            'des' => '小组页(小组话题列表)',
            'tags' => array(
                'group_name' => '小组名称',
                'topic_type'=>'话题类型',
                'group_des' => '小组公告',
                'group_tags' => '小组标签(分类)',
                'page' => '当前分页编号',
            ),
            //系统初始默认SEO设置
            'default' => array(
                'title' => '{group_name}{topic_type}话题列表_第{page}页',
                'keywords' => '{group_tags}',
                'description' => '{group_des}',
            ),
        );

        $this->pages['topic'] = array(
            'des' => '话题内容页',
            'tags' => array(
                'topic_subject' => '话题标题',
                'topic_author' => '话题作者',
                'group_name' => '小组名称',
                'topic_content'=>'话题前100字内容',
            ),
            //系统初始默认SEO设置
            'default' => array(
                'title' => '{topic_subject}_{group_name}小组',
                'keywords' => '{topic_author},{group_name}',
                'description' => '话题：{topic_subject}，内容介绍：{topic_content}',
            ),
        );

        foreach(array_keys($this->pages) as $key) {
            $this->pages[$key]['setting'] = $this->global_setting;
        }
    }

}