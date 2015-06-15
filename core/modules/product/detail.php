<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'product');
$pid = abs(_get('pid','0','intval'));
if(!$pid) {
    $pid = abs(_get('id','0','intval'));
    if(!$pid) redirect(lang('global_sql_keyid_invalid', 'pid'));
}

$P =& $_G['loader']->model(':product');
$PAY =& $_G['loader']->model('pay:pay');
$payments = $PAY->payments;
$detail = $P->read($pid);
if(!$detail || !$detail['status']) redirect('product_empty');
if(!$detail['is_on_sale']) redirect('抱歉，该产品已经下架，进入此商家店铺挑选其他产品。', get_forward());
//分类
$GC =& $_G['loader']->model('product:gcategory');
$category = $GC->read($detail['gcatid']);
//生成表格内容
$FD =& $_G['loader']->model('product:fielddetail');
//样式设计
$FD->class = 'key';
$FD->width = '';
$detail_custom_field = '';
$fields = $P->variable('field_' . $category['modelid']);
if($fields) foreach($fields as $val) {
    if(in_array($val['fieldname'], array('content'))) continue;
    if($val['show_detail']) {
        $detail_field .= $FD->detail($val, $detail[$val['fieldname']]) . "\r\n";
    }
}

$S =& $_G['loader']->model('item:subject');
if(!$subject = $S->read($detail['sid'])) redirect('item_empty');
$M =& $_G['loader']->model('member:member');
$ownerid = $M->read($subject['owner'],TRUE);

//我的售价
$myprice = $P->myprice($detail);
//公开售价
$price = floor($detail['price']*100)/100;
if($detail['promote'] > 0){
	$promote = floor($detail['promote']*100)/100;
}

if($detail['tag_keyword']) {
    $detail['tag_keyword'] = trim($detail['tag_keyword'],'|');
	$tags = explode('|',trim($detail[tag_keyword],','));
}

if($user->uid) {
	$arruser = explode(',',trim($detail['usergroup']));
    $arruserprice = explode(',',trim($detail['user_price']));
    $arrkeys = array_keys($arruser,$user->groupid);
    $arrkey = $arrkeys[0];
    $vipprice = $arruserprice[$arrkey];
}

//判断是否当前内容所属当前城市，不是则跳转
if(check_jump_city($subject['city_id'])) location(url("city:$subject[city_id]/product/detail/pid/$pid"));


$modelid = $S->get_modelid($subject['pid']);

//获取主题侧边栏显示字段
$subject_field_table_tr = $S->display_sidefield($subject);

$category = $_G['loader']->variable('gcategory', 'product');
if(!$catid = $P->get_pid($detail['gcatid'])) {
    redirect('product_cat_empty');
}
$subcategory = $_G['loader']->variable('gcategory_' . $catid, 'product');
$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("product/index"));
$urlpath[] = url_path($subcategory[$detail['gcatid']]['name'], url("product/list/catid/$detail[gcatid]"));
$urlpath[] = url_path($detail['subject'], url("product/detail/pid/$pid"));

//更新浏览量
$P->pageview($pid);

//读取属性组（属性组名，属性组值）
list($att_cats, $att_values) = get_att_array($pid);

//其他模块和功能的链接
$links = $_G['hook']->hook('subject_detail_link',$subject,TRUE);
define('SUB_NAVSCRIPT','product');

if($subject) {
    $scategory = $S->get_category($subject['catid']);
    if(!$subject['templateid'] && $scategory['config']['templateid']>0) {
        $subject['templateid'] = $scategory['config']['templateid'];
    }
}

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];

if($subject && $subject['templateid']) {
    include template('product_detail', 'item', $subject['templateid']);
} else {
    include template('product_detail');
}
/*
if($subject && $subject['templateid'] && !$MOD['pointgroup']) {
    include template('product_detail', 'item', $subject['templateid']);
} else {
    include template('product_detail');
}
*/
function get_att_array($pid) {
    _G('db')->join('dbpre_productatt','at.attid','dbpre_att_list','al.attid');
    _G('db')->where('at.pid', $pid);
    _G('db')->select('at.*,al.name');
    $r = _G('db')->get();
    if(!$r) return null;
    while ($v = $r->fetch_array()) {
        $result[$v['att_catid']][$v['attid']] = $v['name'];
    }
    $q = _G('db')->from('dbpre_att_cat')->where('catid',array_keys($result))->get();
    while ($v=$q->fetch_array()) {
        $attcats[$v['catid']] = $v['name'];
    }
    return array($attcats,$result);
}