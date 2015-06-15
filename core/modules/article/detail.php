<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'article');

if(!$articleid = (int)$_GET['id']) redirect(lang('global_sql_keyid_invalid', 'id'));

$A =& $_G['loader']->model(MOD_FLAG.':article');
if((!$detail = $A->read($articleid)) || $detail['status']!=1) redirect('article_empty');

//手机模块适应
if(check_module('mobile') && is_mobile() && S('mobile:auto_switch')) {
    location(url("article/mobile/do/detail/id/$articleid"));
}

//判断是否当前内容所属当前城市，不是则跳转
if(check_jump_city($detail['city_id'])) location(U("city:$detail[city_id]/article/detail/id/$articleid",true));

//投稿权限
$access_post = $user->check_access('article_post', $A, 0);

if($_POST['op'] == 'digg') {
    if($A->digg($articleid)) echo 'OK';
    output();
}

//更新浏览量
$A->pageview($articleid);

//获取主题列表字段
if($detail['sid'] > 0) {
    $I =& $_G['loader']->model('item:subject');
    if(!$subject = $I->read($detail['sid'])) redirect('item_empty');
    if($detail['city_id'] && $detail['city_id'] != $_CITY['aid']) {
        init_city($detail['city_id']);
    }
    $subject_field_table_tr = $I->display_sidefield($subject);
    $links = $_G['hook']->hook('subject_detail_link',$subject,TRUE);
    define('SUB_NAVSCRIPT','article');
}

//手动分页
$CP =& $_G['loader']->lib('cutpage');
$CP->pagestr = str_replace('[page]number[/page]',$CP->cut_custom, $detail['content']);
//$CP->page_word = $MOD['page_word']>500?$MOD['page_word']:500;
$CP->manual_cut();
$total = $CP->sum_page;
$detail['content'] = $CP->pagearr[$_GET['page']-1];
$total>1 && $multipage = multi($total, 1, $_GET['page'], url("city:$detail[city_id]/article/detail/id/$articleid/page/_PAGE_"));

$_G['loader']->helper('misc','article');
$SEO->tags->subject = $detail['subject'];
$SEO->tags->keywords = $detail['keywords'] ? str_replace(" ",",",$detail['keywords']) : '';
$SEO->tags->introduce = trimmed_title(strip_tags(nl2br($detail['introduce'])),0,100);
$SEO->tags->category_name = misc_article::category_path($detail['catid'], $_CFG['titlesplit']);
//解析seo设置赋值
$_HEAD['title'] = $SEO->pares('detail')->title;
$_HEAD['keywords'] = $SEO->keywords;
$_HEAD['description'] = $SEO->description;

if($subject) {
    $category = $I->get_category($subject['catid']);
    if(!$subject['templateid'] && $category['config']['templateid']>0) {
        $subject['templateid'] = $category['config']['templateid'];
    }
}

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

if($subject && $subject['templateid'] && S('article:use_itemtpl')) {
    include template('article_detail', 'item', $subject['templateid']);
} else {
    include template('article_detail');
}
?>