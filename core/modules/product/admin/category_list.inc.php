<?php
/**
* @author 风格店铺
* @copyright (c)2009-2011 风格店铺
* @website www.cmsky.org
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$C =& $_G['loader']->model(MOD_FLAG.':gcategory');

if(!$_POST['dosubmit']) {

    $catlist = $C->getlist(0);
    $admin->tplname = cptpl('category_list', MOD_FLAG);

} else {

    $C->update($_POST['category']);
    redirect('global_op_succeed', cpurl($module,$act));

}
?>