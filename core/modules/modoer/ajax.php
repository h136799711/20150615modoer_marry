<?php
/*
* @author moufer<moufer@163.com>
* @copyright Moufer Studio(www.modoer.com)
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['in_ajax'] = 1;
$_G['fullalways'] = TRUE;
$op = trim($_GET['op']);

// 允许的操作行为
$allow_ops = array( 'get_datacall','check_seccode','getcontent','kuaidi','area' );
// 需要登录的操作
$login_ops = array( );

$op = empty($op) || !in_array($op, $allow_ops) ? '' : $op;

if(!$op) {
    redirect('global_op_unkown');
} elseif(in_array($op, $login_ops) && !$user->isLogin) {
    $_G['forward'] = $_G['web']['referer'] ? $_G['web']['referer'] : $_G['cfg']['siteurl'];
    redirect('member_not_login');
}

switch($op) {
    case 'get_datacall':
        $datacallname = _T(decodeURIComponent($_POST['datacallname']));
        $_G['datacall']->datacallname($datacallname);
        output();
        break;
    case 'check_seccode':
        if(!$_POST['seccode']) { echo lang('global_ajax_seccode_empty'); exit; }
        if(!check_seccode($_POST['seccode'], TRUE, FALSE)) { echo lang('global_ajax_seccode_invalid'); exit; }
        echo lang('global_ajax_seccode_normal'); exit;
        break;
    case 'getcontent':
        if($url = _input('url', null)) {
            if (!check_is_url($url)) redirect('global_ajax_getcontent_url_invalid');
            $GV =& $_G['loader']->lib('video');
            $GV->charset = $_G['charset'];
            if(!check_flash_domain($url)) redirect('global_flash_domain_access');
            if($url = $GV->get_url($url)) {
                echo $url;
            } else {
                redirect('global_ajax_getcontent_video_unkown');
            }
        } else {
            redirect('global_ajax_getcontent_url_empty');
        }
        output();
        break;
    case 'convert':
        $s = _input('s', '', MF_TEXT);
        $d = _input('d', '', MF_TEXT);
        $str = _input('str', '', MF_TEXT);
        if($str) {
            echo charset_convert($str, $s, $d);
        } else {
            echo $str;
        }
        output();
    case 'kuaidi':
        $result = '';
        $com = _input('com', '', MF_TEXT);
        $nu = _input('nu', '', MF_TEXT);
        if($com && $nu) {
            $_G['loader']->lib('kuaidi100', NULL, FALSE);
            $kd = new ms_kuaidi100();
            $result = $kd->get_data($com, $nu);
            if($result[0]=='message') echo $result[1];
            if($result[0]=='url') echo json_encode($result);
        }
        if(!$result) echo 'EMPTY';
        output();
    case 'area':
        $_G['loader']->helper('query');
        $pid = _get('pid', 0, MF_INT_KEY);
        $data = query::area(array('pid'=>$pid));
        if(_G('charset') != 'utf-8') {
            foreach ($data as $key => $value) {
                if(!is_numeric($value)) {
                    $data[$key] = charset_convert($value, _G('charset'), 'utf-8');
                }
            }
        }
        if(!$data) {
            $ret = array(
                'code' => 110003,
                'message' => 'empty',
            );
        } else {
            $ret = array(
                'code' => 200,
                'data' => $data,
            );
        }
        echo json_encode($ret);
        output();
    default:
        redirect('golbal_op_unkown');
}

?>