<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2011 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_coupon_seosetting extends msm_seosetting {

    public function __construct() {
        parent::__construct('coupon');
    }

    protected function _init() {
        $this->pages['index'] = array(
            'des' => '模块首页',
            'default' => array(
                'title' => '{city_name}优惠券_代金券_折扣券_打折信息',
                'keywords' => '{city_name},{site_name},优惠券,电子优惠券,优惠打折,餐饮美食优惠券,娱乐活动优惠券，免费下载,手机下载,免费打印',
                'description' => '{site_name}为你提供城市宁波购物电子优惠券，优惠打折信息,可免费打印或用手机短信下载，经常更新，可Email订阅。',
            ),
        );

        $this->pages['list'] = array(
            'des' => '列表页',
            'tags' => array(
            	'category_name'=>'当前分类名称',
            ),
            'default' => array(
                'title' => '{category_name}_{city_name}优惠券搜索',
                'keywords' => '{city_name},{category_name},电子优惠券,打折信息',
            ),
        );

        $this->pages['item_list'] = array(
            'des' => '主题列表页',
            'tags' => array(
                'subject_name'=>'主题名称',
            ),
            'default' => array(
                'title' => '{subject_name}优惠券_{city_name}优惠券',
            ),
        );

        $this->pages['detail'] = array(
            'des' => '内容页',
            'tags' => array(
            	'title' => '优惠券标题',
                'subject_name'=>'主题名称',
                'des'=>'简介',
            ),
            'default' => array(
                'title' => '{subject_name}优惠券_{title}_{city_name}优惠券',
                'description' => '{des}',
            ),
        );

        foreach(array_keys($this->pages) as $key) {
            $this->pages[$key]['setting'] = $this->global_setting;
        }
    }

}
?>