<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
return array(

    'product_title' => '产品',
    'product_hook_comment_name' => '产品评论',

	'product_redirect_productlist' => '返回产品列表页',
	'product_redirect_newproduct' => '添加主题新产品',
	'product_redirect_subjectproduct' => '添加新主题产品',

    'product_mysubject_empty' => '您目前没有任何可管理的主题。',
    'product_mysubject_nonentity' => '您选择的主题不是您的主题。',

    'product_category_empty' => '您选择的分类不存在，请返回。',
    'product_category_name_empty' => '没有填写分类名称，请返回填写。',
    'product_category_name_exists' => '对不起，您输入的分类名称已存在，请重新输入一个。',

    'product_model_empty' => '对不起，您选择的主题分类未指定产品模型ID，请返回。',
    'product_cat_empty' => '对不起，主题分类不存在，请返回。',
    'product_cat_invalid' => '对不起，你选择的主题分类不是一个有效的分类，请返回。',
    'product_cat_disabled' => '对不起，您查看的分类已停用。',

    'product_post_subject_empty' => '对不起，您未填写产品名称，请返回填写。',
    'product_post_sid_empty' => '对不起，您未选择产品主题，请返回选择。',
    'product_post_catid_empty' => '对不起，您未选择产品店内分类，请返回选择。',

    'product_empty' => '对不起，产品信息不存在或已删除。',
    'product_shipping_empty' => '对不起，配送信息不存在或已删除。',

    'product_order_empty' => '订单不存在。',

    'product_price_unkown' => '暂无',

    'product_subject_empty' => '商品所属的商户不存在。',
    
    'product_status_1' => '待付款',
    'product_status_2' => '已付款',
    'product_status_3' => '待发货',
    'product_status_4' => '已发货',
    'product_status_5' => '已完成',
    'product_status_6' => '已取消',

    'product_orderby' => array('sales'=>'销量', 'price'=>'价格', 'last_update'=>'上架时间'),

    'product_freight_price_snail' => '平邮',
    'product_freight_price_exp' => '快递',
    'product_freight_price_ems' => 'EMS',

    'product_message_subject' => '请查收你购买的商品',
	'product_message_content' => '请进入“我的助手 - 我的商品订单 - 已发货 - 订单号：%s %s - 详情”查看订单。',
	'product_update_point_payrmb_des' => '产品订单支付(订单号:%s)',
    'product_update_price_order_cancel' => '取消订单，返还现金订单号:%s)',
    'product_update_point_order_cancel' => '取消订单，返还消费积分(订单号:%s)',

    'product_pay_point_order' => '消费积分抵现金(订单号:%s)',
    'product_sell_gain_price' => '商品销售现金入账(订单号:%s,已扣佣金:%s)',

    'product_order_succeed_give_point' => '订单完成赠送消费积分(订单号:%s)',

    'product_notice_pay_succeed' => '%s 购买了您的商品，订单号：%s，请及时处理订单。',
    'product_notice_send_goods' => '您购买的商品已发货，订单号：%s，请注意查收。',

    'product_sms_pay_succeed_sell' => '您有新的商城订单 %s，请及时处理订单。',
    'product_sms_pay_succeed_buy' => '您的商城订单 %s 购买成功，请关注卖家的发货状态。',
    'product_sms_deal_close' => '您的商城订单 %s，买家已确认付款。',

    'product_notice_deal_close' => '%s 已确认付款，订单号：%s，交易完成，现金已转入您的帐户（货到付款订单除外）。',
    'product_notice_change_amount' => '商家更新了您的订单 %s ，支付金额更新为：%s，请注意查看。',
    'product_notice_refund' => '您的商城订单 %s 已经取消，如您是通过在线支付或者有抵换积分的，网站将会把相关现金和积分退还到您的帐号。',
);
?>