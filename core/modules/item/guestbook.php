<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'item');
//实例化点评类
$GB =& $_G['loader']->model(MOD_FLAG.':guestbook');
$S =& $_G['loader']->model('item:subject');
$sid = _get('sid', null, MF_INT_KEY);

$subject = $S->read($sid);
if(empty($subject)) redirect('item_empty');
$fullname = $subject['name'] . ($subject['subname']?"($subject[subname])":'');
$subject_field_table_tr = $S->display_sidefield($subject);
//主题管理员标记
$is_owner = $user->isLogin && $subject['owner'] == $user->username;

$where = array();
$where['sid'] = $sid;
$where['status'] = 1;
$orderby = array('dateline'=>'DESC');
$offset = 20;
$start = get_start($_GET['page'], $offset);
list($total,$list) = $GB->find($select, $where, $orderby, $start, $offset, TRUE);
if($total) $multipage = multi($detail['guestbooks'], $offset, $_GET['page'], url("item/guestbook/sid/$sid/page/_PAGE_"));

$urlpath = array();
$urlpath[] = url_path($fullname, url("item/detail/id/$sid"));
$urlpath[] = url_path('留言', '');

//其他模块和功能的链接
$links = $_G['hook']->hook('subject_detail_link',$subject,TRUE);
define('SUB_NAVSCRIPT','item/guestbook');

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];

if($subject) {
    $scategory = $S->get_category($subject['catid']);
    if(!$subject['templateid'] && $scategory['config']['templateid']>0) {
        $subject['templateid'] = $scategory['config']['templateid'];
    }
}
if($subject && $subject['templateid']) {
    include template('guestbook_list', 'item', $subject['templateid']);
} else {
    include template('item_guestbook_list');
}
?>