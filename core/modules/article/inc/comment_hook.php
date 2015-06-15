<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

return array (
    'article' => array(
        'name' => lang('article_hook_comment_name'),
        'table_name' => 'dbpre_articles',
        'key_name' => 'articleid',
        'title_name' => 'subject',
        'grade_name' => 'grade',
        'total_name' => 'comments',
        'close_name' => 'closed_comment',
        'detail_url' => 'article/detail/id/_ID_',
    ),
);
?>