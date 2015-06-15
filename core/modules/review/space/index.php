<?php
//发表的点评
$review_obj = $_G['loader']->model(':review');

$where = array();
$where['uid'] = $space->uid;
$where['hide_name'] = 0;
$where['status'] = 1;

$offset = 10;
$start = get_start($_GET['page'], $offset);

$select = '*';

list($total, $reviews) = $review_obj->find($select, $where, array('posttime' => 'DESC'), $start, $offset, TRUE);

//分页
if($total) {
    $multipage = multi($total, $offset, $_GET['page'], url("space/$space->uid/pr/review/page/_PAGE_"));
}

$flag = 'review';

//页面SEO
$_HEAD['title']	= '点评列表' . $_CFG['titlesplit'] . $space->spacename;

//设置模板ID
$templateid = $space->space_styleid;

//载入模型的内容页模板
include space_template('review_list', (int)$templateid);