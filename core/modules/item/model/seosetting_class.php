<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2011 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
/**
* 主题模块seo设置项
*/
class msm_item_seosetting extends msm_seosetting {

    public function __construct() {
        parent::__construct('item');
    }

    protected function _init() {

        $global_tags = array(
            'root_category_name' => '主分类名称',
            'current_category_name' => '当前分类名称',
            'current_area_name' => '当前地区名称',
            'root_category_keywords' => '主分类参数设置里的页面keywords',
            'root_category_description' => '主分类参数设置里的页面description',
        );
        $category = $this->loader->variable('category','item');

        $this->pages['category'] = array(
            'des' => '分类页面',
            'default' => array(
                'title' => '{city_name}生活分类导航',
                'keywords' => '{city_name}生活指南大全,{city_name}生活指南指南,{city_name}生活指南导航',
                'description' => '{site_name}为您提供详尽的{city_name}生活指南大全，涵盖所有热门商区，分类和价位。',
            ),
        );

        foreach($category as $catid => $cate) {
            $this->pages['list_'.$catid] = array(
                'des' => '【'.$cate['name'].'】分类列表页',
                'tags' => array(
                    'subject_total' => '主题数量',
                    'page' => '当前分页编号',
                ),
                'default' => array(
                    'title' => '{city_name}{current_area_name}{current_category_name}商户列表_第{page}页',
                    'keywords' => '{city_name},{current_area_name},{root_category_name},{current_category_name},{site_name}',
                    'description' => '{site_name}为您找到{city_name}{current_area_name}附近{subject_total}家{current_category_name}商户信息。点击查看更多关于{city_name}地区附近商户电话、地址、价格、评价、排行榜等详情。',
                ),
            );
            $this->pages['list_'.$catid]['tags'] = array_merge($global_tags, $this->pages['list_'.$catid]['tags']);
        }

        foreach($category as $catid => $cate) {
            $this->pages['detail_'.$catid] = array(
                'des' => '【'.$cate['name'].'】分类内容页',
                'tags' => array(
                    'name' => '主题名称',
                    'description' => '主题简介',
                    'content' => '主题详细介绍前100个文字',
                    'category_name' => '主题所属分类',
                    'area_name' => '主题所在地区',
                    '主题模型字段名' => '主题模型文本字段可以作为标签使用，例如：电话字段为c_tel，标签可用{c_tel}',
                ),
                'default' => array(
                    'title' => '{name}_电话,地址,价格,营业时间,网友评论(图)',
                    'keywords' => '{name},{category_name},{city_name},{area_name},地址,电话,评论,网友点评,推荐菜,照片/图片,营业时间,公交信息',
                    'description' => '{city_name}{name}：进入{name}页面,查看更多关于{name}的地址、电话、菜单、价格、营业时间介绍,了解{name}最新的折扣优惠券、用户点评信息。',
                ),
            );
        }

        $this->pages['tops'] = array(
            'des' => '排行榜页面',
            'tags' => array(
                'current_category_name'=>'当前分类名称',
            ),
            'default' => array(
                'title' => '{city_name}商户{current_category_name}排行榜',
                'keywords' => '{current_category_name}排行榜,本周热门,编辑推荐,最佳性价比',
                'description' => '{city_name}{current_category_name}排行榜_完全由网友票选得出，最客观公正的{current_category_name}指南',
            ),
        );

        $this->pages['map'] = array(
            'des' => '地图页面',
            'tags' => array(
                'current_category_name'=>'当前分类名称',
                'current_area_name'=>'当前地区',
                'subject_total' => '主题数量',
                'page' => '当前分页编号',
            ),
            'default' => array(
                'title' => '{city_name}{current_area_name}{current_category_name}商户地图坐标_第{page}页',
                'keywords' => '{city_name}{current_area_name},{current_category_name}商户,地图坐标',
                'description' => '',
            ),
        );

        $this->pages['albums'] = array(
            'des' => '相册列表页',
            'tags' => array(
                'current_category_name'=>'当前分类名称',
                'album_total' => '相册数量',
                'page' => '当前分页编号',
            ),
            'default' => array(
                'title' => '{city_name}商户{current_category_name}相册_照片列表_第{page}页',
                'keywords' => '{current_category_name}相册照片',
                'description' => '{city_name}{current_category_name}相册_这里有{city_name}最全的商户环境产品照片，完全有网友在商户消费时所拍摄的真实照片。',
            ),
        );

        foreach(array_keys($this->pages) as $key) {
            $this->pages[$key]['setting'] = $this->global_setting;
        }

    }

}
?>