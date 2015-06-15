<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
$WD =& $_G['loader']->model('pay:withdraw');
$op = _input('op');

switch ($op) {
    case 'log':

        $status = '';
        $where = array();
        $where['uid'] = $user->uid;
        //$where['status'] = $status;
        $offset = 20;
        $start = get_start($_GET['page'], $offset);
        list($total, $list) = $WD->find('*',$where,array('applytime'=>'DESC'), $start, $offset, true);
        if($total) {
            $multipage = multi($total, $offset, $_GET['page'], url("pay/member/ac/withdraw/status/$status/page/_PAGE_"));
        }
        $tplname = 'withdraw_log';
        break;
    
    default:

        $check_invalid = $_G['loader']->model('member:point')->check_invalid($user->uid,'rmb');
        if($check_invalid) redirect('对不起，您的现金帐号资金异常，暂时无法提现，请联系管理员解决。');

        if(check_submit('paysubmit')) {
            if(!$_POST['password']) {
                redirect('对不起，您未填写支付密码！');
            } elseif(!$user->check_paypw($_POST['password'])) {
                redirect('对不起，您填写的支付密码不正确！');
            }
            $WD->withdraw($_POST['price']);
            redirect('提现申请提交成功！请关注本次提现记录状态。', url("pay/member/ac/withdraw/op/log"));
        } else {
            $myinfo = $WD->get_myinfo();
            if($myinfo['count']>0) {
                $allow_price = $WD->limit_price - $myinfo['price'];
                $allow_count = $WD->allow_count - $myinfo['count'];        
            } else {
                $allow_count = $WD->allow_count;
                $allow_price = $WD->limit_price;
            }
            $allow_price = min($user->rmb, $allow_price);
        }

}
