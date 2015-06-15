<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class hook_product extends ms_base {

    function __construct(&$hook) {
        parent::__construct();
        $hook->register(
            array('admincp_subject_edit_link','subject_detail_link','mobile_index_link','mobile_member_link'), 
            $this
        );
    }

    function admincp_subject_edit_link($sid) {
        $S =& $this->loader->model('item:subject');
        $subject = $S->read($sid);
        $category = $S->get_category($subject['pid']);
        //if(!$category['config']['product_modelid']) return;
        $result = array();
        $result[] = array (
            'flag' => 'product:subjectsetting',
            'url' => cpurl('product','subjectsetting','',array('sid'=>$sid)),
            'title'=> '商城设置',
        );
        $result[] = array(
            'flag' => 'product:list',
            'url' => cpurl('product','product_list','',array('sid'=>$sid)),
            'title'=> '商品管理',
        );
        return $result;
    }

    function subject_detail_link(&$params) {
        extract($params);
        $title = '商品';
        return array (
            'flag' => 'product',
            'url' => url('product/shop/sid/'.$sid),
            'title'=> $title,
        );
    }

    //手机首页导航
    function mobile_index_link() {
        $modules = _G('loader')->variable('modules');
        $result[] = array (
            'flag' => 'product/category',
            'url' => url('product/mobile/do/category'),
            'title'=> $modules['product']['name'],
            'icon' => 'product',
        );
        return $result;
    }

    function mobile_member_link() {
        $result[] = array (
            'flag' => 'product/member/myorder',
            'url' => url('product/mobile/do/myorder'),
            'title'=> '我的订单',
        );
        return $result;
    }

    function footer() {
    }

}
?>