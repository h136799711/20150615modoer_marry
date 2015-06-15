<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'article');

if(($_GET['Pathinfo']||$_GET['Rewrite']) && $_GET['keyword'] && $_G['charset'] != 'utf-8' && $_G['cfg']['utf8url']) {
    $_GET['keyword'] = charset_convert($_GET['keyword'],'utf-8',$_G['charset']);
}

$sid = _get('sid', null, MF_INT_KEY); //主题ID
if($sid > 0) {
    location(url("article/item/sid/$sid"), TRUE);
} elseif($catid = (int) $_GET['catid']) {
    $_G['loader']->helper('misc','article');
    $subtitle = misc_article::category_path($catid,'&nbsp;&raquo;&nbsp;',url("article/list/catid/_CATID_"));
    $SEO->tags->category_name = implode($_CFG['titlesplit'],
        array_reverse(explode($_CFG['titlesplit'], misc_article::category_path($catid,$_CFG['titlesplit']))));
} else {
    $subtitle = $_GET['keyword'] ? _T($_GET['keyword']) : lang('global_all');
}

$A =& $_G['loader']->model(':article');
$category = $A->variable('category');
//投稿权限
$access_post = $user->check_access('article_post',$A,0);

if($catid && !$category[$catid]['pid']) {
    $tplname = 'article_list_root';
} else {
    $pid = $category[$catid]['pid'];
    $tplname = 'article_list_sub';
    $select = 'articleid,catid,subject,a.dateline,pageview,comments,digg,uid,author,copyfrom,thumb,picture,introduce';
    $orderby = array(($sid?'sl.':'').'dateline'=>'DESC');
    $offset = $MOD['list_num'] > 0 ? $MOD['list_num'] : 10;
    $start = get_start($_GET['page'],$offset);
    $_GET['status'] = 1;
    list($total, $list) = $A->search($select, $orderby, $start, $offset);
    if($total) $multipage = multi($total, $offset, $_GET['page'], url("article/list/catid/$catid/keyword/$_GET[keyword]/sid/$sid/page/_PAGE_"));
}

//解析seo设置赋值
$_HEAD['title'] = $SEO->pares('list')->title;
$_HEAD['keywords'] = $SEO->keywords;
$_HEAD['description'] = $SEO->description;

include template($tplname);
?>