<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'article');

$sid = _get('sid', null, MF_INT); //主题ID

$I =& $_G['loader']->model('item:subject');
if(!$subject = $I->read($sid)) redirect('item_empty');
$subject_field_table_tr = $I->display_sidefield($subject);
$subtitle = '<a href="'.url("item/detail/id/$sid").'">'.trim($subject['name'] . '&nbsp;' . $subject['subname']).'</a>';

//其他模块和功能的链接
$links = $_G['hook']->hook('subject_detail_link',$subject,TRUE);
define('SUB_NAVSCRIPT','article');

//导航
$urlpath = array();
$urlpath[] = url_path($subject['name'].$subject['subname'], url("item/detail/id/$sid"));
$urlpath[] = url_path(lang('article_title'), url("article/list/sid/$sid"));

//分类
$A =& $_G['loader']->model(':article');
$category = $A->variable('category');
//投稿权限
$access_post = $user->check_access('article_post', $A, 0);

//筛选
$pid = $category[$catid]['pid'];
$select = 'articleid,subject,a.dateline,pageview,comments,digg,uid,author,copyfrom,thumb,picture,introduce';
$orderby = array(($sid?'sl.':'').'dateline'=>'DESC');
$offset = $MOD['list_num'] > 0 ? $MOD['list_num'] : 10;
$start = get_start($_GET['page'], $offset);
$_GET['status'] = 1;
list($total, $list) = $A->search($select, $orderby, $start, $offset);
if($total) $multipage = multi($total, $offset, $_GET['page'], url("article/list/catid/$catid/keyword/$_GET[keyword]/sid/$sid/page/_PAGE_"));

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];

$category = $I->get_category($subject['catid']);
if(!$subject['templateid'] && $category['config']['templateid']>0) {
    $subject['templateid'] = $category['config']['templateid'];
}

//页面seo标签
$SEO->tags->subject_name    = $subject['name'].($subject['subname']?"({$subject['subname']})":'');
//解析seo设置赋值
$_HEAD['title'] = $SEO->pares('item_list')->title;
$_HEAD['keywords'] = $SEO->keywords;
$_HEAD['description'] = $SEO->description;

if($subject && $subject['templateid'] && S('article:use_itemtpl')) {
    include template('article_list', 'item', $subject['templateid']);
} else {
    include template('article_list_sub');
}
?>