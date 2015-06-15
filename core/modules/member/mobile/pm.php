<?php
!defined('IN_MUDDER') && exit('Access Denied');

$M = $_G['loader']->model('member:message');

$op = _input('op', '', MF_TEXT);
$folder = _input('folder', '', MF_TEXT);

if($op == 'send') {

	if(check_submit('dosubmit')) {

		$result = $M->send($user->uid, $_POST['recv_users'], $_POST['subject'], $_POST['content'], TRUE);
        if(!$result) {
            redirect('An unknown error occurred.');
        }
        redirect('global_op_succeed', url('member/mobile/do/pm/folder/outbox'));

	} else {

		$re_pmid = _get('re_pmid', 0, MF_INT_KEY);
		if($re_pmid > 0) {
			if($message = $M->read($user->uid, $re_pmid)) {
				if(!$message['senduid']) {
					redirect('对不起，不能回复系统短信息。');
				} elseif($user->uid == $message['senduid']) {
					redirect('对不起，不能回复自己。');
				}
				$member = _G('loader')->model('member')->read($message['senduid']);
				if(!$member) {
					redirect('对不起，回复对象不存在。');
				}
				$username = $member['username'];
				$subject = 'Re:'.$message['subject'];
			}
		}

		//发送短信息
		$_HEAD['title'] = "发送短信息";
		$header_title 	= $_HEAD['title'];
		$header_forward = U("member/mobile/do/pm");

		include mobile_template('member_pm_send');
		output();

	}

} elseif($op == 'read') {

	$pmid = _post('pmid', 0, MF_INT_KEY);
	
	$M->read($user->uid, $pmid);
	
	$array = array('code'=>200);
	echo json_encode($array);
	output();

} elseif($op == 'delete') {

	$pmid 	= _post('pmid', 0, MF_INT_KEY);
	$folder = _post('folder', '', MF_TEXT);

	$M->delete($user->uid, $folder, $pmid);

	$array = array('code'=>200);
	echo json_encode($array);
	output();

} else {
	$folder = $folder == 'outbox' ? 'outbox' : 'inbox';
	
	$offset = 10;
	list($total, $list) = $M->find(
		$user->uid, $folder, array('posttime'=>'DESC'), get_start($_GET['page'], $offset), $offset
	);
    
    //清理新邮件数量
    if(!$total && $folder == 'inbox' && $user->newmsgs > 0) {
        $M->clear_new_record($user->uid);
    }

	$sub_param = array('inbox' => lang('member_pm_inbox'), 'outbox' => lang('member_pm_outbox'));
	$multipage = multi($total, $offset, $_GET['page'], url("member/mobile/do/pm/folder/$folder/page/_PAGE_"));

}

$_HEAD['title'] = "我的短信箱";
$header_title 	= $_HEAD['title'];
$header_forward = U("member/mobile/do/message");

include mobile_template('member_pm');