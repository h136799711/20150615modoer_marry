<?php
!defined('IN_MUDDER') && exit('Access Denied');

switch(_input('op')) {
    case 'check_mobile':
        $_G['loader']->helper('validate');
        $mobile = _post('mobile', null, MF_TEXT);
        if(!$mobile) {
            echo lang('member_reg_ajax_mobile_empty'); exit;
        }
        if(!validate::is_mobile($mobile)) {
            echo lang('member_reg_ajax_mobile_invalid'); exit;
        }
        if($user->check_mobile_exists($mobile)) {
            echo lang('member_reg_ajax_mobile_exists'); exit;
        }
        echo 'OK';exit;
        break;
    case 'send_mobile_seccode':
        $MV =& $_G['loader']->model('member:mobile_verify')->set_uniq($user->session_id);
        $mobile = _input('mobile', null, MF_TEXT);
        if($time = $MV->get_resend_time()) {
            echo $time; exit;
        }
        $succeed = $MV->set_mobile($mobile)->send();
        if($succeed) {
            echo 'OK';
        } else {
            echo 'ERROR';
        }
        exit;
        break;
    case 'check_mobile_seccode':
        $MV =& $_G['loader']->model('member:mobile_verify')->set_uniq($user->session_id);
        $serial = _input('serial', null, MF_TEXT);
        $succeed = $MV->set_serial($serial)->checking();
        $verify = $MV->get_status();
        if($succeed) {
            $user->db->from($user->table)->where('uid', $user->uid)->set('mobile', $verify['mobile'])->update();
            $MV->delete();
            echo 'OK';
        } else {
            echo 'ERROR';
        }
        exit;
        break;
    case 'change_paypw':
        if($_POST['dosubmit']) {
            $user->change_pay_password($_POST['old'], $_POST['new'], $_POST['new2']);
            echo 'OK';
        } else {
            redirect('无效提交');
        }
        output();
        break;
    case 'save':
        $profile = _post('profile', null, MF_TEXT);
        $MP =& $_G['loader']->model('member:profile');
        $MP->set_uid($user->uid);
        foreach ($profile as $key => $value) {
            $MP->$key = $value;
        }
        $MP->save();
        redirect('global_op_succeed',url('member/mobile'));
        break;
    default:
        $smscfg = $modcfg = _G('loader')->variable('config','sms');
        $usdmobile = $smscfg['use_api'];
        $header_title = '我的助手';
        include mobile_template('member_myset');
        break;
}
/* end */