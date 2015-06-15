<?php
/**
* @author 轩
* @copyright (c)2009-2012 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');
$CM =& $_G['loader']->model('comment:comment');

$idtype = _T($_GET['idtype']);
if(!$CM->check_idtype($idtype)) redirect('comment_idype_unkown');
if(!$id = (int) $_GET['id']) redirect(lang('global_sql_keyid_invalid','id'));
$endpage = $_GET['endpage'] > 0;

$where = array();
$where['idtype'] = $idtype;
$where['id'] = $id;
$where['status'] = 1;
$MOD['listorder'] != 'desc' && $MOD['listorder'] = 'asc';
$orderby = array('dateline'=>$MOD['listorder']);
$offset = $MOD['list_num'] > 0 ? $MOD['list_num'] : 10;
$start = $endpage ? -1 : get_start($_GET['page'], $offset);
list($total, $list) = $CM->find($select, $where, $orderby, $start, $offset, TRUE);
if($endpage) $_GET['page'] = ceil($total/$offset);
$multipage = multi($total, $offset, $_GET['page'], url("comment/list/idtype/$idtype/id/$id/page/_PAGE_"), '', "get_comment('$idtype',$id,{PAGE})");
include template('product_comment_list');
?>