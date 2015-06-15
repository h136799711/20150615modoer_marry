<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2013 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op', '', MF_TEXT);

switch($op) {

    case 'add_allow':
        $catid = _input('catid', 0, MF_INT_KEY);
        if(!$catid) redirect(lang('global_sql_keyid_invalid', 'catid'));
        $GC = $_G['loader']->model('product:gcategory');
        $cat = $GC->read($catid);
        if(empty($cat)) redirect('对不起，您选择的产品分类不存在。');
        if(!$cat['enabled']) redirect('对不起，您选择的产品分类已停用。');
        if(!$cat['modelid']) redirect('对不起，您选择的分类不允许添加产品。'.($cat['level']<3?'您可选择旗下三级分类。':''));
        echo 'OK';
        output();
        break;

    default:

        redirect('global_op_unknow');

}

/** end **/