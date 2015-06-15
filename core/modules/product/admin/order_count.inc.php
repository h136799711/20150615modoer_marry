<?php
/**
* @author 风格店铺
* @copyright (c)2009-2012 风格店铺
* @website www.cmsky.org
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');
$O =& $_G['loader']->model(MOD_FLAG.':order');
$PAYC =& $_G['loader']->variable('config','pay');

$status_name = array();
for($i=1;$i<=6;$i++) {
    $status_name[$i] = strip_tags(lang('product_status_'.$i));
}

if($_GET['dosubmit']) {
    if($_GET['sid'] > 0) {
        $S =& $_G['loader']->model('item:subject');
        $subject = $S->read($_GET['sid'],'*',false);
        if(!$subject) redirect('主题不存在！');
    }
    $timetype = _get('timetype','',MF_TEXT);
    $timetype != 'addtime' && $timetype = 'paytime';
    if(!$_GET['starttime'] || !strtotime($_GET['starttime'])) {
        $starttime = date('Y-m-d', $_G['timestamp'] - 30 * 24 * 3600);
    } else {
        $starttime = $_GET['starttime'];
    }
    if(!$_GET['endtime'] || !strtotime($_GET['endtime'])) {
        $endtime = date('Y-m-d', $_G['timestamp']);
    } else {
        $endtime = $_GET['endtime'];
    }
    $totalprice = $O->totalcount($_GET['sid'], $timetype, $starttime, $endtime);
}
$admin->tplname = cptpl('order_count', MOD_FLAG);
?>