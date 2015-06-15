<?php
!defined('IN_MUDDER') && exit('Access Denied');
//实例化点评类
$R =& $_G['loader']->model(':review');
$S =& $_G['loader']->model('item:subject');
if($sid = _get('sid', null, MF_INT_KEY)) {
    $subject = $S->read($sid);
    if(empty($subject)) redirect('item_empty');
    $fullname = $subject['name'] . ($subject['subname']?"($subject[subname])":'');
    $subject_field_table_tr = $S->display_sidefield($subject);
}
$urlpath = array();
$urlpath[] = $fullname;
$urlpath[] = '点评';
//取得分类、模型、点评项以及地区信息
$category = $S->get_category($subject['catid']);
$modelid = $S->category['modelid'];
$rogid = $S->category['review_opt_gid'];
$catcfg =& $category['config'];
//载入模型
$model = $S->variable('model_' . $modelid);
//载入点评选项
$reviewpot = $_G['loader']->variable('opt_' . $rogid, 'review');

$filter = _get('filter','all',MF_TEXT);
//排序
$orderby_arr = array(
    'new' => array('posttime' => 'DESC'),
    'enjoy' => array('best' => 'DESC'),
    'flower' => array('flowers' => 'DESC'),
    'respond' => array('responds' => 'DESC'),
);
$orderby = _cookie('list_display_review_list_orderby','new','_T');
!isset($orderby_arr[$orderby]) && $orderby ='new';

//取得点评数据
$where = array();
$where['id'] = $sid;
$where['idtype'] = 'item_subject';
$where['status'] = 1;

$offset = 10;
$start = get_start($_GET['page'], $offset);
$select = 'r.*,m.point,m.point1,m.groupid';
list($total, $reviews) = $R->find($select, $where, $orderby_arr[$orderby], $start, $offset, TRUE, FALSE, TRUE);
if($total) {
    //$multipage = multi($total, $offset, $_GET['page'], url("review/item/sid/$sid/filter/$filter/page/_PAGE_"));
    $multipage = mobile_page($total, $offset, $_GET['page'], url("review/mobile/do/reviews/sid/$sid/page/_PAGE_"));
}

//点评行为检测
$review_access = $S->review_access($subject);
$review_enable = $review_access['code'] == 1;

$active = array();
$active['filter'][$filter] = ' class="selected"';
$active['orderby'][$orderby] = ' class="selected"';

$orderby_arr_lng = array(
    'new' => lang('review_type_new'),
    'enjoy' => lang('review_type_enjoy'),
    'flower' => lang('review_type_flower'),
    'respond' => lang('review_type_respond'),
);

$filter_arr_lng = array(
    'all' => lang('review_filter_all'),
    'best' => lang('review_filter_best'),
    'bad' => lang('review_filter_bad'),
    'pic' => lang('review_filter_pic'),
    'digest' => lang('review_filter_digest'),
);

//点评内容全格式显示
$show_full = true;

//ajax
if($_G['in_ajax']) {
    include mobile_template('reviews_li');
    output();
}

include mobile_template('reviews');