<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'group');

$G = $_G['loader']->model(':group');
$gid = _get('id', 0, MF_INT_KEY);
$group = $G->read($gid);
if(!$group) redirect('group_empty',url('group/index'));
if($group['status']<1) redirect('当前小组没有完成审核，或已经删除，您无法访问。', url('group/index'));

//是否小组成员
$gmember = $G->member->read($gid, $user->uid);
//禁言到期，自动恢复状态
if($gmember['status'] == '-1' && $gmember['bantime'] <= $_G['timestamp']) {
    $G->member->unban_post($gid, $user->uid, false);
}
//浏览权限
$readaccess = $G->setting->read($gid,'readaccess');


$GT = $_G['loader']->model('group:topic');
//获取数据
$where = array();
$where['gt.gid'] = $gid;
$where['gt.status'] = 1;
$select = 'tpid,gt.gid,subject,uid,username,replies,replytime,gt.dateline,top,digest,closed,pageview,gt.typeid,source,gtt.name as typename';
$orderby = array('top'=>'DESC','replytime'=>'DESC');
$offset = 10;
$start = get_start($_GET['page'],$offset);
list($total, $list) = $GT->find($select, $where, $orderby, $start, $offset, TRUE);
if($total > 0) {
    $multipage = mobile_page($total, $offset, $_GET['page'], url("group/mobile/do/group/id/$gid/page/_PAGE_"));
}

if($_G['in_ajax']) {
    include mobile_template('group_show_loop');
    output();    
}

$urlpath[] = url_path($group['groupname']);
$_HEAD['title'] = $group['groupname'].'话题列表'._G('cfg','titlesplit')."第{$_GET[page]}页";;
$_HEAD['keywords'] = $group['groupname'].','.$group['username'];
if($group['tags']) {
    $_HEAD['keywords'] .= ','.trim(str_replace('|',',',$group['tags']),',');
}
$_HEAD['description'] = trimmed_title(strip_tags(nl2br($group['des'])),100,'');

$header_forward = url("group/mobile/do/list");
include mobile_template('group_show');

/* end */