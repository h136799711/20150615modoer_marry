<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$sid = _get('id', 0, MF_INT_KEY);
if(!$sid) redirect(lang('global_sql_keyid_invalid', 'id'));

$I =& $_G['loader']->model('item:subject');
//取得主题信息
if($sid) {
    $detail = $I->read($sid);
}
if(!$detail) redirect('item_empty');
//主题管理员标记
$is_owner = $user->isLogin && $detail['owner'] == $user->username;
//获取分类
$category = $I->get_category($detail['catid']);
if(!$pid = $category['catid']) {
    redirect('item_cat_empty');
}
$modelid = $I->category['modelid'];
$rogid = $I->category['review_opt_gid'];
//取得分类、模型、点评项以及地区信息
$catcfg =& $category['config'];
if(!$model = $I->get_model($modelid)) redirect('item_model_empty');
if($model['usearea']) $area = $I->loader->variable('area');
//点评项
$reviewcfg = $_G['loader']->variable('config','review');
$R =& $_G['loader']->model(':review');
$reviewpot = $R->variable('opt_' . $rogid);
//生成表格内容
$detail_custom_field = $I->display_detailfield($detail, true);
//获取关联主题字段
$relate_subject_field = $I->get_relate_subject_field($modelid);
//获取多行文本字段
$textarea_field = $I->get_textarea_field($modelid);
//前台不显示字段去除
$arrfields = $I->variable('field_' . $modelid);
//字段配置
$fields = array();
foreach($arrfields as $fd) {
    if(!$fd['show_detail'] && $fd['fieldname']=='content') unset($detail[$fd['fieldname']]);
    $fields[$fd['fieldname']] = $fd;
}
//取得点评
if($detail['reviews'] > 0) {
    $review_filter = 'all';
    $review_orderby = 'posttime';
    //取得点评数据
    $where = array();
    $where['idtype'] = 'item_subject';
    $where['id'] = $sid;
    $where['status'] = 1;
    $orderby = array('posttime'=>'DESC');

    $review_offset = 2;
    $start = get_start($_GET['page'], $review_offset);
    $select = 'r.*,m.point,m.point1,m.groupid';

    list(,$reviews) = $R->find($select, $where, $orderby, 0, $review_offset, FALSE, FALSE, TRUE);
}
//点评行为检测
$review_access = $I->review_access($detail);
$review_enable = $review_access['code'] == 1;
//增加浏览量
if(!$detail['owner'] || $detail['owner'] && $detail['owner'] != $user->username) $I->pageview($sid);
// if (strposex($_SERVER['HTTP_REFERER'],'save')){
//     $header_forward = url("item/mobile/do/list/catid/$detail[pid]");
// } elseif($_SERVER['HTTP_REFERER']) {
//     $header_forward = $_SERVER['HTTP_REFERER'];
// }
$header_forward = url("item/mobile/do/list/catid/$detail[pid]");
//载入模版
include mobile_template('item_subject');
?>