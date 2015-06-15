<?php
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'article');

!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'article');

if(!$articleid = (int)$_GET['id']) redirect(lang('global_sql_keyid_invalid', 'id'));

$A =& $_G['loader']->model(MOD_FLAG.':article');
if((!$detail = $A->read($articleid)) || $detail['status']!=1) redirect('article_empty');

//判断是否当前内容所属当前城市，不是则跳转
if(check_jump_city($detail['city_id'])) location(U("city:$detail[city_id]/article/mobile/do/detail/id/$articleid",true));

//更新浏览量
$A->pageview($articleid);

//手动分页
/*
$CP =& $_G['loader']->lib('cutpage');
$CP->pagestr = $detail['content'];
//$CP->page_word = $MOD['page_word']>500?$MOD['page_word']:500;
$CP->manual_cut();
$total = $CP->sum_page;
$detail['content'] = $CP->pagearr[$_GET['page']-1];
$total>1 && $multipage = multi($total, 1, $_GET['page'], url("city:$detail[city_id]/article/mobile/do/detail/id/$articleid/page/_PAGE_"));
*/

//使用评论模需要的相关配置信息
$comment_cfg = array (
    'idtype'        => 'article',
    'id'            => $articleid,
    'title'         => $detail['subject'],
    'comments'      => $detail['comments'],
    'grade'         => $detail['grade'],
    'enable_post'   => ($MOD['post_comment'] && !$detail['closed_comment']),
    'enable_grade'  => true,
);

if (strposex($_SERVER['HTTP_REFERER'],'sendsms')){
    $header_forward = url("coupon/mobile/do/list/catid/$detail[catid]");
} elseif($_SERVER['HTTP_REFERER']) {
    $header_forward = $_SERVER['HTTP_REFERER'];
}
include mobile_template('article_detail');