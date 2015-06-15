<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'group');

$op = _input('op',null,MF_TEXT);
if($op == 'reload') {
	$rpid = _input('rpid',null,MF_INT_KEY);
	$reply = $_G['loader']->model('group:reply')->read($rpid);
	if($reply) {
		$_G['loader']->helper('msubb');
	}
	echo msubb::pares($reply['content'], $reply);
	output();
}

$tpid=_get('id',null,MF_INT_KEY);
$GT = $_G['loader']->model('group:topic');
$topic = $GT->read($tpid);
if(empty($topic)) redirect('group_topic_empty');
if($topic['status'] < 1 && $topic['uid'] != $user->uid) redirect('group_topic_not_audit');

//手机模块适应
if(check_module('mobile') && is_mobile() && S('mobile:auto_switch')) {
    location(U("group/mobile/do/topic/id/$tpid"));
}

$gid = $topic['gid'];
$G = $_G['loader']->model(':group');
if(!$group = $G->read($gid)) redirect('group_empty');
if($group['status'] < 1) redirect('当前小组没有完成审核，或已经删除，您无法访问。', url("group/$gid"));

//是否小组成员
$gmember = $G->member->read($gid, $user->uid);
//禁言到期，自动恢复状态
if($gmember['status'] == '-1' && $gmember['bantime'] <= $_G['timestamp']) {
    $G->member->unban_post($gid, $user->uid, false);
}
$setting = $G->setting->read_all($gid);
//浏览权限
$readaccess = (int)$setting['readaccess'];
if($readaccess && !$gmember['status']) redirect('您没有权限访问小组，请先申请加入该小组。');
//发帖模式
$postaccess = (int)$setting['postaccess'];
if(!$postaccess||($postaccess<1||$postaccess>3)) $postaccess=1;
//分类选择
$needtypeid = (int)$setting['needtypeid'];

//获取话题回应
$where = array();
$where['tpid'] = $tpid;
$where['status'] = 1;
$orderby = 'dateline';
$select = '*';
$offset = 30;
$start = get_start($_GET['page'], $offset);

$RP = $_G['loader']->model('group:reply');
list($total, $list) = $RP->find($select, $where, $orderby, $start, $offset, true);
if($total > 0) {
    $multipage = multi($total, $offset, $_GET['page'], url("group/topic/id/$tpid/page/_PAGE_"));
}

//增加点击率
if($topic['uid'] != $user->uid) {
    $GT->pageview($tpid);
}

//面包屑
$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("group/index"));
$urlpath[] = url_path($group['groupname'], url("group/$gid"));
if($tpid>0) $urlpath[] = url_path($topic['subject'], url("group/topic/id/$tpid"));

//表情
$smilies = array();
for ($i=1; $i <= 30; $i++) $smilies[$i] = "$i";

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

//页面SEO标签直
$SEO->tags->topic_subject = $topic['subject'];
$SEO->tags->topic_author = $topic['username'];
$SEO->tags->group_name = $group['groupname'];
$description = preg_replace("/(\[\/.*?\/\])/i", '', $topic['content']);
$SEO->tags->topic_content = trimmed_title(preg_replace("/\s*(\r\n|\n\r|\n|\r)\s*/", "。", strip_tags($description)),100,'...');
//解析seo设置,赋值
$_HEAD['title'] = $SEO->pares('topic')->title;
$_HEAD['keywords'] = $SEO->keywords;
$_HEAD['description'] = $SEO->description;

$_G['loader']->helper('msubb');

if($subject && $subject['templateid']) {
    include template('group_topic', 'item', $subject['templateid']);
} else {
    include template('group_topic');
}

/*end */