<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$albumid = _input('albumid',null,MF_INT_KEY);

$A =& $_G['loader']->model('item:album');
if(!$album = $A->read($albumid)) redirect('item_album_empty');

$P =& $_G['loader']->model('item:picture');
$where = array();
$where['albumid'] = $albumid;
list($total, $list) = $P->find('*', $where, array('addtime'=>'DESC'), 0, 0, 1);
if(!$total) redirect('item_picture_empty');

//使用评论模需要的相关配置信息
$comment_cfg = array (
    'idtype'        => 'album',
    'id'            => $albumid,
    'title'         => $album['name'],
    'comments'      => $album['comments'],
    'enable_post'   => S('item:album_comment'),
    'enable_grade'  => false,
);

include mobile_template('item_pictures');