<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'group');

$op = _input('op', null, MF_TEXT);
$G = $_G['loader']->model(':group');

if(!$user->isLogin) redirect('请先登录。');

switch ($op) {
	case 'add_topic':
        $gid = _get('gid',0,MF_INT_KEY);
        $group = $G->read($gid);
        if(!$group) redirect('group_empty');

        //是否小组成员
        $gmember = $G->member->read($gid, $user->uid);
        //禁言到期，自动恢复状态
        if($gmember['status'] == '-1' && $gmember['bantime'] <= $_G['timestamp']) {
            $G->member->unban_post($gid, $user->uid, false);
        }
        if($gmember['status'] == '0') {
            redirect('您目前正在等待审核，无法进行发言。');
        } elseif ($gmember['status'] == '-1') {
            redirect('您目前正在禁言期（截止 '.date('Y-m-d',$gmember['bantime']).'），无法进行发言。');
        }

        $setting = $G->setting->read_all($gid);
        //浏览权限
        $readaccess = (int)$setting['readaccess'];
        //分类选择
        $needtypeid = (int)$setting['needtypeid'];
        if($needtypeid > 0) {
            $typecount = $_G['loader']->model('group:type')->get_count($gid);
        }

		include mobile_template('group_post_topic');
		break;
	case 'post_topic':
		$TP = $_G['loader']->model('group:topic');
        $post = $TP->get_post($_POST);
        $post['source']=1;//手机模块发布
        $tpid = _post('tpid',null,MF_INT_KEY);
        if(!$tpid) $tpid = null;
        if($MOD['topic_seccode']&&$tpid==null) check_seccode($_POST['seccode']);
        $tpid = $TP->save($post, $tpid);
        if(RETURN_EVENT_ID=='CHECK') {
            redirect('global_op_succeed_check',url("group/mobile/do/topic/id/$tpid"));
        } else {
            if(DEBUG||defined('IN_AJAX')) redirect('global_op_succeed',url("group/mobile/do/topic/id/$tpid"));
            location(url("group/mobile/do/topic/id/$tpid"));
        }
		break;
	case 'reply':
		$tpid = _get('tpid',0,MF_INT_KEY);
		$TP = $_G['loader']->model('group:topic');
		$topic = $TP->read($tpid);
		if(!$topic||$topic['status']!='1') redirect('对不起，您回复的话题不存在。');
		$reuid = _get('reuid', 0, MF_INT_KEY);
		if($reuid > 0) {
			$reuser = $_G['loader']->model(':member')->read($reuid);
		}
		include mobile_template('group_post_reply');
		break;
	case 'post_reply':
		$RP = $_G['loader']->model('group:reply');
    	$post = $RP->get_post($_POST);
        $post['source']=1;//手机模块发布
    	$rpid = _post('rpid',null,MF_INT_KEY);
    	if(!$rpid) $rpid = null;
        if($MOD['reply_seccode']&&$rpid==null) check_seccode($_POST['seccode']);
    	$rpid=$RP->save($post,$rpid);
    	$reply = $RP->read($rpid);
    	if(RETURN_EVENT_ID=='CHECK') {
    		redirect('global_op_succeed_check',url("group/mobile/do/topic/id/$reply[tpid]"));
    	} else {
    		if(DEBUG||defined('IN_AJAX')) redirect('global_op_succeed',url("group/mobile/do/topic/id/$reply[tpid]"));
            location(url("group/mobile/do/topic/id/$reply[tpid]"));
    	}
		break;
    case 'join_group':
        $gid = _input('gid',0,MF_INT_KEY);
        $group = $G->read($gid);
        if(!$group) redirect('group_empty');
        if($G->member->is_blacklist_user($gid)) {
            redirect('抱歉，小组管理员拒绝了您的加入请求。');
        }
        $check = $G->setting->read($gid,'jointype');
        if($check) {
            if($_POST['dosubmit']) {
                $status = $G->member->join(true);//需要审核
                if($status >=1) redirect('group_join_succeed',url("group/mobile/do/group/id/$gid"));
                if($status <=0) redirect('group_join_check_succeed',url("group/mobile/do/group/id/$gid"));
            } else {
                include mobile_template('group_join_apply');
            }
        } else {
            $status = $G->member->join();
            if($status >=1) location(url("group/mobile/do/group/id/$gid"));
            if($status <=0) redirect('group_join_check_succeed',url("group/mobile/do/group/id/$gid"));
            output();   
        }
        break;
	default:
		# code...
		break;
}
?>