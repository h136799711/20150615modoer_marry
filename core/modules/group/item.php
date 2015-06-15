<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'group');

$sid = _get('sid',0,MF_INT_KEY);
$S = $_G['loader']->model('item:subject');
$subject = $S->read($sid);
if(!$subject) redirect('subject_empty');

$total = 0;
$G = $_G['loader']->model(':group');
if($list = $G->find_subject($sid)) {
    $total = $list->num_rows();
}
if( $total === 1) {
    $group = $list->fetch_array();
    location(url("group/$group[gid]"));
}

if(!$subject = $S->read($sid)) redirect('item_empty');
$subject_field_table_tr = $S->display_sidefield($subject);

//其他模块和功能的链接
$links = $_G['hook']->hook('subject_detail_link',$subject,TRUE);
define('SUB_NAVSCRIPT','group');

//预览模式
if($vtid = _cookie('item_style_preview_'.$sid,null,MF_INT_KEY)) {
    if(is_template($vtid,'item')) {
        $subject['templateid'] = $vtid;
        $is_preview = true;
    }
}

$category = $S->get_category($subject['catid']);
if(!$subject['templateid'] && $category['config']['templateid']>0) {
    $subject['templateid'] = $category['config']['templateid'];
}

if($subject['templateid']) {
    include template('group_list', 'item', $subject['templateid']);
} else {
    include template('group_item');
}

?>