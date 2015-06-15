<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'group');

$G = $_G['loader']->model(':group');
$gid = _get('gid', 0, MF_INT_KEY);
$group = $G->read($gid);
if(!$group) redirect('group_empty',url('group/index'));
if($group['status']<1) redirect('当前小组没有完成审核，或已经删除，您无法访问。', url('group/index'));

//手机模块适应
if(check_module('mobile') && is_mobile() && S('mobile:auto_switch')) {
    location(U("group/mobile/do/group/id/$gid"));
}

$pr = _get('pr','',MF_TEXT);
list($digest,$typeid) = explode('_',$pr);
$digest = $digest ? 1 : 0;//筛选精华
$typeid = $typeid > 0 ? $typeid : 0; //筛选分类
$pr = "$digest_$typeid";//重组pr
//面包屑
$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("group/index"));

//是否小组成员
$gmember = $G->member->read($gid, $user->uid);
//禁言到期，自动恢复状态
if($gmember['status'] == '-1' && $gmember['bantime'] <= $_G['timestamp']) {
    $G->member->unban_post($gid, $user->uid, false);
}
$setting = $G->setting->read_all($gid);
//浏览权限
$readaccess = (int)$setting['readaccess'];
//分类选择
$needtypeid = (int)$setting['needtypeid'];
if($needtypeid > 0) {
    $typecount = $_G['loader']->model('group:type')->get_count($gid);
}
//发帖模式
$postaccess = (int)$setting['postaccess'];
if(!$postaccess||($postaccess<1||$postaccess>3)) $postaccess=1;

if($_GET['op'] == 'memberlist') {
    $where = array();
    $where['gid'] = $gid;
    $where['status'] = 1;
    $orderby = array('usertype'=>'ASC','jointime'=>'ASC');
    $offset = 20;
    $start = get_start($_GET['page'], $offset);
    list($total, $list) = $G->member->find('*', $where, $orderby, $start, $offset, TRUE);
    if($total > 0) {
        $multipage = multi($total, $offset, $_GET['page'], url("group/$gid/op/memberlist/page/_PAGE_"));
    }
    $urlpath[] = url_path($group['groupname'],url("group/$gid"));
    $_HEAD['title'] = $group['groupname'].'成员列表';
    $_HEAD['keywords'] = $MOD['meta_keywords'];
    $_HEAD['description'] = $MOD['meta_description'];
    include template('group_memberlist');
    exit;
}

$GT = $_G['loader']->model('group:topic');
//获取数据
$where = array();
$where['gt.gid'] = $gid;
if($digest) {
    $where['gt.digest'] = 1;
}
if($typeid) {
    $where['gt.typeid'] = $typeid;
}
$where['status'] = 1;
$select = 'tpid,gt.gid,subject,uid,username,replies,replytime,gt.dateline,top,digest,closed,pageview,gt.typeid,source,gtt.name as typename';
$orderby = array('top'=>'DESC','replytime'=>'DESC');
$offset = 15;
$start = get_start($_GET['page'],$offset);
list($total, $list) = $GT->find($select, $where, $orderby, $start, $offset, TRUE);
if($total > 0) {
    $multipage = multi($total, $offset, $_GET['page'], url("group/$gid/pr/$pr/page/_PAGE_"));
}

if($_G['in_ajax']) {

    if($total) {
        //$topics = &$list;
        $onclick = "group_topics_subject($gid, {PAGE})";
        $multipage = multi($total, $offset, $_GET['page'], '','', $onclick);
    }

    include template('item_subject_detail_group');
    output();    
}

//获取主题列表字段
if($group['sid'] > 0) {
    $C_S =& $_G['loader']->model('item:subject');
    if(!$subject = $C_S->read($group['sid'])) redirect('item_empty');
    //侧边栏显示主题信息
    $subject_field_table_tr = $C_S->display_sidefield($subject);
    //其他模块和功能的链接
    $links = $_G['hook']->hook('subject_detail_link', $subject, TRUE);
    define('SUB_NAVSCRIPT','group');

    //预览模式
    if($vtid = _cookie('item_style_preview_'.$sid,null,MF_INT_KEY)) {
        if(is_template($vtid,'item')) {
            $subject['templateid'] = $vtid;
            $is_preview = true;
        }
    }

    $category = $C_S->get_category($subject['catid']);
    if(!$subject['templateid'] && $category['config']['templateid']>0) {
        $subject['templateid'] = $category['config']['templateid'];
    }
}

//表情
$smilies = array();
for ($i=1; $i <= 30; $i++) $smilies[$i] = "$i";

//高亮
$active = array();
if($digest) $active['digest'] = ' class="selected"';
if($typeid) $active['typeid'][$typeid] = ' class="selected"';
!$active && $active['all'] = ' class="selected"';

$urlpath[] = url_path($group['groupname']);

$SEO->tags->group_name = $group['groupname'];
//话题类型
if($typeid) $topic_type = $_G['loader']->model('group:type')->read($typeid);
$SEO->tags->topic_type = ($digest?'精华':'') . ($topic_type ? $topic_type['name'] : '');
if($group['tags']) $SEO->tags->group_tags = trim(str_replace('|',',',$group['tags']),',');
$SEO->tags->group_des = trimmed_title(strip_tags(nl2br($group['des'])),100);
//解析seo设置赋值
$_HEAD['title'] = $SEO->pares('group')->title;
$_HEAD['keywords'] = $SEO->keywords;
$_HEAD['description'] = $SEO->description;

if($subject && $subject['templateid']) {
    include template('group_show', 'item', $subject['templateid']);
} else {
    include template('group_show');
}

/* end */