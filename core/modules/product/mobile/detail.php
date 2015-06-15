<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'product');

$product_obj = _G('loader')->model(':product');

$id = _get('id', 0, MF_INT_KEY);
$detail = $product_obj->read($id);

if(!$detail || !$detail['status']) return $this->add_error('product_empty');
if(!$detail['is_on_sale']) return $this->add_error('抱歉，该产品已经下架，进入此商家店铺挑选其他产品。');

//关联商户
$S = _G('loader')->model('item:subject');
$subject = $S->read($detail['sid']);
if(!$subject) redirect('product_subject_empty');

//解析产品图片
if($detail['pictures']) {
	$detail['pictures'] = unserialize($detail['pictures']);
}
//解析主题标签
if($detail['tag_keyword']) {
	$detail['tag_keyword'] = explode('|',trim($detail['tag_keyword'],'|'));
}

//商品分类
$category = $product_obj->gcategory_model->read($detail['gcatid']);

//我的购买价格
$myprice = $product_obj->myprice($detail);

//主题的属性组数据
$atts = $product_obj->att_model->get_product_atts($id);
//读取属性组（属性组名，属性组值）
list($att_cats, $att_values) = $atts;

//自定义字段生成表格内容
$FD = _G('loader')->model('product:fielddetail');
//样式设计
$FD->class = 'key';
$FD->width = '';
$detail_field = '';

$fields = $_G['loader']->variable('field_'.$category['modelid'], 'product');
if($fields) foreach($fields as $val) {
    if(in_array($val['fieldname'], array('content'))) continue;
    //内容页显示
    if($val['show_detail']) {
        $detail_field .= $FD->detail($val, $detail[$val['fieldname']])."\r\n";
    }
}

//商户管理员帐号
$owners = $S->owners($subject['sid']);
if($owners) $ownerid = $owners[0]['uid'];

//不是店主访问时，更新页面缓存
if(!$ownerid || $ownerid > 0 && $ownerid != _G('user')->uid) {
	$product_obj->pageview($id);
}

$_HEAD['title'] = $detail['subject'].'-商品详情';
$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];

include mobile_template('product_detail');

/** end **/