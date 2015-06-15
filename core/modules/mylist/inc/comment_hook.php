<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

return array (
    'mylist' => array(
        'name' => lang('mylist_hook_comment_name'),
        'table_name' => 'dbpre_mylist',
        'key_name' => 'id',
        'title_name' => 'title',
        //'grade_name' => 'grade',
        'total_name' => 'responds',
        'detail_url' => 'mylist/_ID_',
    ),
);
?>