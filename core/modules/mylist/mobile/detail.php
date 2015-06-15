<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'mylist');

$id = _get('id', 0, MF_INT_KEY);

$M = $_G['loader']->model(':mylist');
$detail = $M->read($id);
if(!$detail) redirect('mylist_empty');

//是否进入自己的榜单
$myself = $user->isLogin && $user->uid==$detail['uid'];

//榜单内主题列表
$MI = $_G['loader']->model('mylist:item');

$offset = 10; //单页显示数量

$where = array('mylist_id'=>$id);
//总数量
$total = $MI->db->from($MI->table)->where($where)->count();

$start = get_start($_GET['page'], $offset);
//取得列表
$list = $MI->db->join($MI->table,'mi.sid', 'dbpre_subject', 's.sid', 'LEFT JOIN')
	->select('mi.*,s.name,s.subname,s.avgsort,s.avgsort,s.avgprice,s.reviews,s.pictures,s.thumb,s.pid,s.catid,s.favorites')
	->where($where)->order_by(array('listorder'=>'ASC'))->limit($start, $offset)->get();
//分页
if($total) $multipage = mobile_page($total, $offset, $_GET['page'], url("mylist/mobile/do/detail/id/$id/page/_PAGE_"));

if($_G['in_ajax']) {
	include mobile_template('mylist_detail_item_li');
	output();
}

//更新pv
if(!$myself) $M->update_pv($id, 1);

//seo标签
$SEO->tags->title 		= $detail['title'];
$SEO->tags->username 	= $detail['username'];
$SEO->tags->intro 		= trimmed_title($detail['intro'], 100);
$SEO->tags->tags 		= $detail['tags'] ? implode(',', $detail['tags']) : '';
//解析seo设置赋值
$_HEAD['title'] 		= $SEO->pares('detail')->title;
$_HEAD['keywords'] 		= $SEO->keywords;
$_HEAD['description'] 	= $SEO->description;

//使用评论模需要的相关配置信息
$comment_cfg = array (
    'idtype'        => 'mylist',
    'id'            => $id,
    'title'         => $detail['title'],
    'comments'      => $detail['responds'],
    'enable_post'   => true,
    'enable_grade'  => false,
);

//$header_title = '榜单详情';
include mobile_template('mylist_detail');