<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$catid = isset($_GET['catid']) ? (int)$_GET['catid'] : (int)$MOD['pid'];
!$catid and location(url('item/mobile/do/category/p/rand'));

//一批数量
$num = 5;
//实例化主题类
$I =& $_G['loader']->model('item:subject');
$where = array();
$where['city_id'] = array(0,$_CITY['aid']);
$where['pid'] = $catid;
if(!$list = $I->read_random($where, $num, true)) {
    redirect('item_random_empty');
}
$reviewcfg = $_G['loader']->variable('config','review');
if($_G['in_ajax']) {
    $tplname = 'item_list_li';
} else {
    $tplname = 'item_rand';
}
$header_title="随便逛逛";
include mobile_template($tplname);