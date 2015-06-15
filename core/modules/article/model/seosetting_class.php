<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2011 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_article_seosetting extends msm_seosetting {

    public function __construct() {
        parent::__construct('article');
    }

    protected function _init() {
        $this->pages['index'] = array(
            'des' => '模块首页',
            //系统初始默认SEO设置
            'default' => array(
                'title' => '{city_name}新闻资讯',
                'description' => '{site_name}热点新闻，关注民生，关注发生在我们身边的故事。',
            ),
        );

        $this->pages['list'] = array(
            'des' => '新闻列表页',
            'tags' => array(
            	'category_name'=>'当前分类名称',
            	'page' => '当前分页编号',
            ),
            //系统初始默认SEO设置
            'default' => array(
                'title' => '{category_name}_{city_name}新闻资讯',
            ),
        );

        $this->pages['item_list'] = array(
            'des' => '主题新闻列表页',
            'tags' => array(
                'subject_name'=>'主题名称',
            ),
            //系统初始默认SEO设置
            'default' => array(
                'title' => '{subject_name}资讯_{city_name}新闻资讯',
            ),
        );

        $this->pages['search'] = array(
            'des' => '新闻搜索页',
            'tags' => array(
            	'keyword'=>'搜索关键字',
            	'page' => '当前分页编号',
            ),
            //系统初始默认SEO设置
            'default' => array(
                'title' => '{keyword}_{city_name}新闻资讯',
            ),
        );

        $this->pages['detail'] = array(
            'des' => '新闻内容页',
            'tags' => array(
            	'subject' => '文章标题',
            	'category_name'=>'文章分类名称',
            	'keywords' => '文章关键字',
            	'introduce' => '文字简介'
            ),
            //系统初始默认SEO设置
            'default' => array(
                'title' => '{subject}_{city_name}新闻资讯',
                'keywords' => '{keywords}',
                'description' => '{introduce}',
                'page' => '当前分页编号',
            ),
        );

        foreach(array_keys($this->pages) as $key) {
            $this->pages[$key]['setting'] = $this->global_setting;
        }
    }

}
?>