<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$A =& $_G['loader']->model(MOD_FLAG.':album');
$P =& $_G['loader']->model(MOD_FLAG.':picture');

$op = _input('op', '', MF_TEXT);

switch ($op) {
    
    case 'create_album':
        $sid = _input('sid',0,MF_INT_KEY);
        $name = _input('name',null,MF_TEXT);
        if($_G['charset']=='gb2312' && $name) {
            $name = charset_convert($name,'utf-8','gbk');
        }
        $albumid = $A->create_normal($sid, $name, '', true);
        echo $albumid;
        output();
        break;

    case 'post_multi':
        //albumid,sid,title,comments,url
        if($_POST['output_charset']&&$_POST['output_charset']!=$_G['charset']) {
            $_G['output_charset'] = $_POST['output_charset'];
        }
        $post = $P->get_post($_POST);
        $post['title'] = basename($_POST['Filename'],'.'.pathinfo($_POST['Filename'],PATHINFO_EXTENSION));
        if($_G['charset'] != 'utf-8') {
            $_G['loader']->lib('chinese', NULL, FALSE);
            $CHS = new ms_chinese('utf-8', $_G['charset']);
            $post['title'] = $CHS->Convert($post['title']);
        }
        $post['comments'] = '';
        $post['url'] = '';
        echo $P->save($post, true);
        output();
        break;

    default:
        if(!$_POST['dosubmit']) {

            //上传权限验证
            $user->check_access('item_pictures', $P);

            $_G['loader']->helper('form');
            $_G['loader']->helper('form','item');
            if($_GET['op'] == 'multi') {
                if(!$MOD['multi_upload_pic']) redirect('item_upload_multi_off');
                $multi_num = $MOD['multi_upload_pic_num'] > 1 && $MOD['multi_upload_pic_num'] <= 20 ? $MOD['multi_upload_pic_num'] : 5;
            }

            if($albumid = abs(_get('albumid',0,'intval'))) {
                if(!$album = $A->read($albumid)) redirect('item_album_empty');
                $_GET['sid'] = (int)$album['sid'];
            }

            if($sid = (int)$_GET['sid']) {
                //redirect(lang('global_sql_keyid_invalid', 'sid'));
                $subject = $P->check_post_before($sid);
                $pid = $subject['pid'];

                $category = $P->variable('category');
                $modelid = $category[$pid]['modelid'];
                $catcfg = $category[$pid]['config'];
                $model = $P->variable('model_' . $modelid);
            }

            //新建相册权限验证
            $access_create_album = $user->check_access('item_create_album', $A, false);

            $tplname = 'pic_upload';

        } else {

            $post = $P->get_post($_POST, FALSE);
            $result = $P->save($post, $_POST['multi'] == 'yes');
            if(_post('do') == 'review_upload') {
                echo fetch_iframe($result);
                output();
            }

            redirect(RETURN_EVENT_ID, get_forward(url('item/member/ac/m_picture'),1));
        }
}
?>