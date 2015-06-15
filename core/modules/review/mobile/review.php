<?php
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op', null, MF_TEXT);
$R =& $_G['loader']->model(':review');
$OBJ =& $_G['loader']->model('item:subject');

switch ($op) {
	case 'add':
		//点评数量权限验证
		$user->check_access('review_num', $R);
		$sid = _get('sid', null, MF_INT_KEY);
		if(!$object = $OBJ->read($sid)) redirect('review_object_empty');
		//登录页面,权限判断
		$login_url = "member/mobile/do/login/in_dlg/$_G[in_dlg]/forward/" . base64_encode(url("review/mobile/do/review/op/add/sid/$sid"));
		$review_access = $OBJ->review_access($object ? $object : null);
		if($review_access['code'] != 1) {
			$R->redirect_access($review_access, $login_url,"javascript:history.go ( -1 );");
		}
		if($object) {
			$subject = $OBJ->get_subject($object);
			$config = $OBJ->get_review_config($object);
			$pid = $OBJ->get_obj_pid($object);
			$rogid = $config['review_opt_gid'];
			//判断是否允许游客点评
			if(!$user->isLogin && !$config['guest_review']) {
					location(url($forward));
			}
			$review_opts = $R->variable('opt_' . $rogid);
		}
		if($_G['in_dlg']) {
			mobile_location(url("review/mobile/do/review/op/add/sid/$sid"));
		}
		include mobile_template('review_save');
		exit;
		break;

	case 'save':
		$post = $R->get_post($_POST['review'], FALSE);
		$post['source'] = 1; //来自手机web模块
		$rid = $R->save($post);
		redirect(RETURN_EVENT_ID, get_forward(url('item/mobile/do/detail/id/'.$post['id']),1));
		break;
	
	default:

		if(!$user->isLogin && !in_array($ac, $guestacs)) {
			$forward = $_G['web']['reuri'] ? ($_G['web']['url'] . $_G['web']['reuri']) : url('meber/mobile');
			location(url('member/mobile/do/login/forward/'.base64_encode($forward)));
		}

		$op = 'my';
		$where['uid'] = $user->uid;
		$where['idtype'] = 'item_subject';
		$select = '*';
		$reviewcfg = $_G['loader']->variable('config','review');
		$start = get_start($_GET['page'], $offset = 10);
		list($total, $list) = $R->find($select, $where, array('posttime'=>'DESC'), $start, $offset, TRUE);
		$multipage = mobile_page($total, $offset, $_GET['page'], url("review/mobile/do/review/op/$op/page/_PAGE_"));
		//ajax
		if($_G['in_ajax']) {
			include mobile_template('review_my_li');
			output();
		}
		include mobile_template('review_my');
		break;
}