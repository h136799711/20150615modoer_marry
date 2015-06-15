<?php
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');
$modmenus = array(
    array(
        'title' => '功能设置',
        'product|模块设置|config',
        'product|商品模型|model_list',
        'product|分类管理|category',
    ),
    array(
        'title' => '商品管理',
        'product|添加商品|product_list|add',
		'product|商品审核|product_list|checklist',
		'product|商品列表|product_list',
		'product|订单管理|product_order',
		'product|销售统计|order_count',
    ),
);
?>