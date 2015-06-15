<?php
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op');
$TG = $_G['loader']->model(':group');
$TP = $_G['loader']->model('group:topic');

//表情
$smilies = array();
for ($i=1; $i <= 30; $i++) $smilies[$i] = "$i";

switch($op) {
    case 'videourl':

        break;
    case 'add':
        $add = true;
        $gid = _get('gid',0,MF_INT_KEY);
        $group = $TG->read($gid);
        if(!$group) redirect('group_empty');
        $tplname = 'topic_save';
        break;
    case 'post':
        $post = $TP->get_post($_POST);
        $tpid = _post('tpid',null,MF_INT_KEY);
        if(!$tpid) $tpid = null;
        if($MOD['topic_seccode']&&$tpid==null) check_seccode($_POST['seccode']);
        $tpid = $TP->save($post, $tpid);
        if(RETURN_EVENT_ID=='CHECK') {
            redirect('global_op_succeed_check',url("group/topic/id/$tpid"));
        } else {
            if(DEBUG||defined('IN_AJAX')) redirect('global_op_succeed',url("group/topic/id/$tpid"));
            location(url("group/topic/id/$tpid"));
        }
        break;
    case 'edit':
        $tpid = _input('tpid', null, MF_INT_KEY);
        $detail = $TP->read($tpid);
        if(empty($detail)) redirect('group_topic_empty');
        if($detail['uid']!=$user->uid && !$TG->is_owner($detail['gid'])) redirect('global_op_access');

        //分类选择
        $setting = $TG->setting->read_all($detail['gid']);
        $needtypeid = (int)$setting['needtypeid'];
        if($needtypeid > 0) {
            $typecount = $_G['loader']->model('group:type')->get_count($detail['gid']);
        }

        $tplname = 'topic_save';
        break;
    case 'delete':
        $tpid = _input('tpid', null, MF_INT_KEY);
        $detail = $TP->read($tpid);

        if($detail['uid']!=$user->uid && !$TG->is_owner($detail['gid'])) redirect('global_op_access');

        $TP->delete($tpid);
        location(url("group/$detail[gid]"));
        break;
    case 'top':
        $tpid = _input('tpid', null, MF_INT_KEY);
        $detail = $TP->read($tpid);

        if(!$TG->is_owner($detail['gid'])) redirect('global_op_access');
        if(!$detail||$detail['status']<1) redirect('话题不存在或未审核，无法操作。');

        $TG->topic->top($tpid, $detail['top'] ? 0 : 1);
        echo 'OK';
        output();
        break;
    case 'digest':
        $tpid = _input('tpid', null, MF_INT_KEY);
        $detail = $TP->read($tpid);

        if(!$TG->is_owner($detail['gid'])) redirect('global_op_access');
        if(!$detail||$detail['status']<1) redirect('话题不存在或未审核，无法操作。');

        $TG->topic->digest($tpid, $detail['digest'] ? 0 : 1);
        echo 'OK';
        output();
        break;
    case 'close':
        $tpid = _input('tpid', null, MF_INT_KEY);
        $detail = $TP->read($tpid);

        if(!$TG->is_owner($detail['gid'])) redirect('global_op_access');
        if(!$detail||$detail['status']<1) redirect('话题不存在或未审核，无法操作。');

        $TG->topic->close($tpid, $detail['closed'] ? 0 : 1);
        echo 'OK';
        output();
    default:
        redirect('global_op_unkown');
 }
?>