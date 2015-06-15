<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'item_albums');

$A =& $_G['loader']->model('item:album');
$op = _input('op',null,'_T');
$albumid = _input('id',null,MF_INT_KEY);
$picid = _input('picid',null,MF_INT_KEY);
$sid = _input('sid',null,MF_INT_KEY);
$catid = _input('catid',null,MF_INT_KEY);
$urlpath = array();


if($_G['in_ajax'] && $albumid > 0 && $_POST['thumb_start'] > 0) {
    $IP =& $_G['loader']->model('item:picture');
    $start = _post('thumb_start', 1, MF_INT_KEY);
    $start < 1 && $start = 1;
    $where = array();
    $where['albumid'] = $albumid;
    $where['status'] = 1;
    $pic = $IP->db->from($IP->table)->where($where)->order_by('picid','DESC')->limit($start-1,1)->get();
    $pic && $pic=$pic->fetch_array();
    if($pic)
        echo "<li id=\"picid_{$pic['picid']}\"><div class=\"thumb_box\"><a href=\"".url("item/album/picid/{$pic['picid']}")."\"><img src=\"".URLROOT."/{$pic['thumb']}\" /></a></div></li>";
    else
        echo 'NONE';
    output();
}

if($albumid > 0 || $picid > 0) {

    $pic = array();
    $IP =& $_G['loader']->model('item:picture');

    if($picid > 0) {
        $pic = $IP->read($picid);
        if(!$pic['status']) redirect('图片不存在或未审核。');
        $albumid = $pic['albumid'];
    } elseif($albumid > 0) {
        if(check_module('mobile') && is_mobile() && S('mobile:auto_switch')) {
            location(url("item/mobile/do/pictures/albumid/$albumid"));
        }
        //通过albumid进入的算浏览相册一次
        $frist = true;
        $A->pageview($albumid);
    }
    if(!$album = $A->read($albumid)) redirect('item_album_empty');

    $S =& $_G['loader']->model('item:subject');
    if(!$subject = $S->read($album['sid'])) redirect('item_empty');
    $sid = $subject['sid'];
    //城市判断
    if($subject['city_id']>0 && $subject['city_id'] != $_CITY['aid']) {
        $url = get_cityurl($subject['city_id'],"item/album/id/$albumid");
        if(!$_CFG['city_sldomain']) {
            $_CITY = init_city($subject['city_id']);
        } elseif($url) {
            location($url);
        }
    }

    if(!$pic) {
        $pic = $IP->get_album_first_pic($albumid);
        $picid = $pic['picid'];
    }
    if(!$pic) redirect('item_picture_empty');

    $where = array();
    $where['albumid'] = $albumid;
    $where['status'] = 1;

    //本图片在数据库第几张
    $p_num = $IP->db->from($IP->table)->where($where)->where_more('picid',$pic['picid'])->count();
    //获取图片前1后3共计4张图片
    $start = $p_num> 1 ? ($p_num- 2 ) : 0;
    $offset = 4;
    list($p_total, $list) = $IP->find('*', $where, array('picid'=>'DESC'), $start, $offset);
    $i=0;
    $lefturl=$righturl="#";
    if($p_total) while ($val=$list->fetch_array()) {
        $pics[$i++] = $val;
        if($start+$i == $p_num - 1) {
            $lefturl = url("item/album/picid/$val[picid]");
        } else if($start+$i == $p_num + 1) {
            $righturl = url("item/album/picid/$val[picid]");
        }
    }

    $urlpath[] = url_path($subject['name'].($subject['subname']?"($subject[subname])":''), url("item/detail/id/$subject[sid]"));
    $urlpath[] = url_path(lang('item_album_title'), url("item/album/sid/$subject[sid]"));
    $urlpath[] = url_path($album['name'], url("item/album/id/$album[albumid]"));
    $urlpath[] = url_path($pic['title'],'');

    //其他模块和功能的链接
    $subject_field_table_tr = $S->display_sidefield($subject);
    $links = $_G['hook']->hook('subject_detail_link',$subject,TRUE);
    define('SUB_NAVSCRIPT','item/album');

    //SEO
    $_HEAD['keywords'] = $pic['title'].','.$pic['username'].','.$album['name'].','.$subject['name'];
    $_HEAD['description'] = "本图片《{$pic['title']}》是用户{$pic['username']}上传于{$subject['name']}的相册《{$album['name']}》";

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

    //使用评论模需要的相关配置信息
    $comment_cfg = array (
        'idtype'        => 'album',
        'id'            => $albumid,
        'title'         => $pic['title'],
        'comments'      => $album['comments'],
        'enable_post'   => $MOD['album_comment'],
        'enable_grade'  => false,
    );

    if($subject['templateid']) {
        include template('pic', 'item', $subject['templateid']);
    } else {
        include template('item_pic');
    }

} elseif($sid > 0) {

    if($op == 'selectpic') {
        $_G['in_ajax'] = 1;
        $where = array();
        $where['sid'] = (int) $_GET['sid'];
        $page = (int)$_GET['page'];
        $offset = 6;
        $start = get_start($page, $offset);
        $P =& $_G['loader']->model('item:picture');
        list($total, $list) = $P->find('picid,thumb', $where, array('addtime'=>'DESC'), $start, $offset);
        if($total) {
            echo '<table width="100%">';
            $i = 0;
            while($value = $list->fetch_array()) {
                $i++;
                if($i % 3 == 1) echo '<tr>';
                echo '<td width="125"><img src="'.URLROOT.'/'.$value['thumb'].'" width="120" onclick="insert_subject_thumb(\''.$value['thumb'].'\');" style="cursor:pointer;" /></td>';
                if($i % 3 == 0) echo '</tr>';
            }
            if($x = $i % 3) {
                echo str_repeat('<td width="125">&nbsp;</td>', (3 - $x));
                echo '</tr>';
            }
            echo '</table><br /><center>';
            if($page > 1) echo '<a href="javascript:select_subject_thumb('.$where[sid].','.($page-1).');">&lt;&lt;</a>&nbsp;&nbsp;&nbsp;&nbsp;';
            if($start + $offset < $total) echo '<a href="javascript:select_subject_thumb('.$where[sid].','.($page+1).');">&gt;&gt;</a>';
            echo '</center>';
        }
        output();
    }

    $S =& $_G['loader']->model('item:subject');
    if(!$subject = $S->read($sid)) redirect('item_empty');

    $where = array();
    $where['sid'] = $sid;
    list($total, $list) = $A->find('*',$where,array('lastupdate'=>'DESC'),0, 0, 1);

    $urlpath[] = url_path($subject['name'].($subject['subname']?"($subject[subname])":''), url("item/detail/id/$subject[sid]"));
    $urlpath[] = url_path(lang('item_album_title'), url("item/album/sid/$subject[sid]"));

    $subject_field_table_tr = $S->display_sidefield($subject);

    $_HEAD['keywords'] = $MOD['meta_keywords'];
    $_HEAD['description'] = $MOD['meta_description'];

    //其他模块和功能的链接
    $links = $_G['hook']->hook('subject_detail_link',$subject,TRUE);
    define('SUB_NAVSCRIPT','item/album');

    //预览模式
    if($vtid = _cookie('item_style_preview_'.$sid,null,MF_INT_KEY)) {
        if(is_template($vtid,'item')) {
            $subject['templateid'] = $vtid;
            $is_preview = true;
        }
    }
    $category = $S->get_category($subject['catid']);
    if(!$subject['templateid'] && $category['config']['templateid']>0) $subject['templateid'] = $category['config']['templateid'];
    if($subject['templateid']) {
        include template('album', 'item', $subject['templateid']);
    } else {
        include template('item_subject_album');
    }

} else {

    location(url("item/albums"));

}

?>