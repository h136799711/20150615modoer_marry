<?php
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'coupon');

if(isset($_GET['id'])) $couponid = (int) $_GET['id'];
if(!$couponid) redirect(lang('global_sql_keyid_invalid','id'));

$CO = $_G['loader']->model(':coupon');
$detail = $CO->read($couponid);
if(!$detail || $detail['status']!=1) redirect('coupon_empty');

if(!$CO->check_valid($couponid, $detail['catid'], $detail['sid'], $detail['endtime'], $detail['status'])) {
	redirect('coupon_status_invalid');
}

if($_GET['op'] == 'sendsms') {
	//if(!$user->isLogin) dialog(lang('global_op_title'), '', 'login');
	$lastsendsms = (int)$_C['lastsendsms'];
	if($_G['timestamp'] - $lastsendsms < 60) {
		redirect('coupon_send_wait');
	}
	if($detail['sms_text'] && $MOD['sendsms'] && check_module('sms')) {
		if($_POST['dosubmit']) {
			check_seccode($_POST['seccode']);
			if($CO->sendsms(_post('mobile'), $detail)) {
				set_cookie('lastsendsms',$_G['timestamp']);
				//coupon.php?act=mobile&do=detail&id=3
				redirect('coupon_send_sms_succeed', url("coupon/mobile/do/detail/id/$couponid"));
			} else {
				redirect('coupon_send_lost');
			}
			output();
		}
		include mobile_template('coupon_sendsms');
		output();
	} else {
		redirect('coupon_send_disabled');
	}
}

$I =& $_G['loader']->model('item:subject');
if(!$subject = $I->read($detail['sid'])) redirect('item_empty');

//更新浏览量
$CO->pageview($couponid);

//使用评论模需要的相关配置信息
$comment_cfg = array (
    'idtype'        => 'coupon',
    'id'            => $couponid,
    'title'         => $detail['subject'],
    'comments'      => $detail['comments'],
    'grade'         => $detail['grade'],
    'enable_post'   => ($MOD['post_comment'] && !$detail['closed_comment']),
    'enable_grade'  => true,
);

if (strposex($_SERVER['HTTP_REFERER'],'sendsms')){
    $header_forward = url("coupon/mobile/do/list/catid/$detail[catid]");
} elseif($_SERVER['HTTP_REFERER']) {
    $header_forward = url("coupon/mobile/do/list");
}
include mobile_template('coupon_detail');