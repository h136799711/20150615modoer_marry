<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
class msm_group_member extends ms_model {

    var $table = 'dbpre_group_member';
	var $key = 'id';
    var $model_flag = 'group';

    var $typenames = array();
    var $typeurls = array();
    var $idtypes = array();

	function __construct() {
		parent::__construct();
        $this->model_flag = 'group';
        $this->modcfg = $this->variable('config');
	}

    function _group() {
        return $this->loader->model(":group");
    }

    function read($gid, $uid) {
        $where = array(
            'gid' => $gid,
            'uid' => $uid
        );
        $this->db->from($this->table);
        $this->db->where($where);
        return $this->db->get_one();
    }

    function is_blacklist_user($gid,$uid=-1) {
        if($uid<1) $uid = $this->global['user']->uid;
        $blacklist = $this->loader->model("group:setting")->read($gid,'blacklist');
        if(!$blacklist) return false;
        $list = explode(',', $blacklist);
        if($uid == $this->global['user']->uid) {
            $username = $this->global['user']->username;
        } else {
            $member = $this->loader->model(':member')->read($uid);
            $username = $member['username'];
        }
        return in_array($username, $list);
    }

    //申请加入小组
    function join($check = false) {
        if($check) {
            $message = _post('message', '', MF_TEXT);
            if(!$message||strlen_ex($message)<3) redirect('对不起，您未填写申请验证说明。');
        } else {
            $message = '';
        }
        $gid = _input('gid', null, MF_INT_KEY);
        $group = $this->loader->model(':group')->read($gid);
        if(!$group) redirect('group_empty');
        $group['status'] < 1 && redirect('小组目前无法加入。');
        $m = $this->db->from('dbpre_group_member')
            ->where('gid', $gid)->where('uid', $this->global['user']->uid)
            ->get_one();
        if($m) redirect('您已经是本小组会员（或等待管理员审核进入）！');
        $insert = array();
        $insert['gid'] = $gid;
        $insert['uid'] = $this->global['user']->uid;
        $insert['username'] = $this->global['user']->username;
        $insert['status'] = $check?0:1;
        $insert['jointime'] = $this->timestamp;
        $insert['usertype'] = 10;
        $insert['applydes'] = $message;
        $this->db->from('dbpre_group_member')
            ->set($insert)
            ->insert();
        if($insert['status'] > 0) {
            $this->db->from('dbpre_group')->where('gid', $gid)->set_add('members',1)->update();
        }
        if($check) { //通知管理员审核
            $replace = array(
                '{groupname}' => $group['groupname'],
                '{checkurl}' => url("group/member/ac/memberlist/gid/$gid/status/0"),
            );
            $note = str_replace(array_keys($replace), array_values($replace), lang('group_notice_join_check'));
            $this->loader->model('member:notice')->save($group['uid'], $this->model_flag, 'group', $note);
        }
        return $insert['status'];
    }

    //更换组长
    function change_owner($gid, $username) {
        $group = $this->loader->model(':group')->read($gid);
        if(!$group) redirect('group_empty');
        $member = $this->loader->model(':member')->read($username, 1);
        if(!$member) redirect('member_empty');
        if($group['uid']>0) {
            //先对原组长降级为普通会员
            $this->db->from($this->table)
                ->where(array('gid'=>$gid,'uid'=>$group['uid']))
                ->set('usertype',10)
                ->update();
        }
        $exists = $this->read($gid, $member['uid']);
        if($exists['id']>0) {
            $this->db->from($this->table)->where('id',$exists['id'])->set('usertype',1)->update();
        } else {
            $set = array(
                'gid' => $gid,
                'uid' => $member['uid'],
                'username' => $member['username'],
                'status' => '1',
                'jointime' => $this->timestamp,
                'usertype' => 1,
            );
            $this->db->from($this->table)->set($set)->insert();
        }
        $set = array(
            'uid'=>$member['uid'],
            'username'=>$member['username'],
        );
        $this->db->from('dbpre_group');
        $this->db->set($set);
        !$exists && $this->db->set_add('members',1);
        $this->db->where('gid',$gid);
        $this->db->update();
        //通知用户
        $CN = $this->loader->model('member:notice');
        $replace = array(
            '{adminname}' => $this->global['admin']->adminname,
            '{groupname}' => $group['groupname'],
        );
        //提醒新组长
        $note = str_replace(array_keys($replace), array_values($replace), lang('group_notice_change_owner_add'));
        $CN->save($member['uid'], $this->model_flag, 'group', $note);
        //提醒原组长
        if($group['uid']>0) {
            $note = str_replace(array_keys($replace), array_values($replace), lang('group_notice_change_owner_cancel'));
            $CN->save($group['uid'], $this->model_flag, 'group', $note);            
        }
    }

    //审核会员加入申请
    function checkup($group, $uid, $status) {
        if($status!='-1'&&$status!='1') {
            redirect('对不起，您提交的审核状态无效。');
        }
        if(!is_array($group) && !is_numeric($group)) {
            $group = $this->_group()->read($group);
        }
        if(!$group||!is_array($group)) redirect('group_empty');
        $member = $this->read($group['gid'], $uid);
        if(!$member) redirect('对不起，您操作的信息不存在。');
        if($member['status']!='0') redirect('对不起，您审核的信息已经处理，无法二次处理。');
        if($status == '-1') {
            parent::delete($member['id']);
        } else {
            $this->db->from($this->table);
            $this->db->set('status',1);
            $this->db->where('id',$member['id']);
            $this->db->update();
        }
        //通知用户
        $replace = array(
            '{groupname}' => "<a href=\"".url("group/{$group['gid']}")."\">{$group['groupname']}</a>",
            '{owner}' => $this->global['user']->username,
        );
        $note = str_replace(array_keys($replace), array_values($replace), lang('group_notice_join_check_'.$status));
        $this->loader->model('member:notice')->save($uid, $this->model_flag, 'group', $note);
    }

    //解散小组时删除
    function delete_gid($gid) {
        $this->db->from($this->table);
        $this->db->where('gid', $gid);
        $this->db->delete();
    }

    function delete($gid, $uid) {
        $member = $this->read($gid, $uid);
        if(!$member) redirect('对不起，会员不是当前小组成员。');
        if($member['status'] != '0') {
            $this->db->from('dbpre_group')->set_dec('members',1)->update();
        }
        parent::delete($member['id']);
    }

    //退出小组
    function quit($gid,$uid=-1) {
        if($uid < 0) $uid = $this->global['user']->uid;
        $this->db->from('dbpre_group_member');
        $this->db->where('gid', $gid);
        $this->db->where('uid', $uid);
        $m = $this->db->get_one();
        if(!$m) redirect('对不起，您没有加入过这个小组。');
        $m['usertype']=='1' && redirect('对不起，您是小组组长，不能退出小组。');
        $m['status']<'0' && redirect('对不起，您当前为禁止发言状态，暂时不能退出。');
        if($m['status'] > 0) {
            $this->db->from('dbpre_group')->where('gid', $gid)->set_dec('members',1)->update();
        }
        $this->db->from('dbpre_group_member')->where('id', $m['id'])->delete();
    }

    function ban_post($gid, $uid, $time, $message) {
        $group = $this->loader->model(":group")->read($gid);
        if(!$group) redirect('group_empty');
        $message = trim($message);
        if(strlen_ex($message)<3) redirect('对不起，您未填写禁言理由。');
        $bantime = strtotime($time);
        if(!$bantime || $bantime < $this->timestamp) redirect('对不起，您设置的禁言时间无效。');
        $bantime += 24*3600-1;
        $this->db->from($this->table);
        $this->db->where(array('gid'=>$gid,'uid'=>$uid));
        $this->db->set('status', -1); //禁言
        $this->db->set('bantime', $bantime);
        $this->db->update();
        //通知用户
        $replace = array(
            '{groupname}' => $group['groupname'],
            '{usertype}' => '管理员',
            '{username}' => $this->global['user']->username,
            '{message}' => $message,
            '{bantime}' => date('Y-m-d', $bantime),
        );
        $note = str_replace(array_keys($replace), array_values($replace), lang('group_notice_ban_post'));
        $this->loader->model('member:notice')->save($uid, $this->model_flag, 'group', $note);
    }

    function unban_post($gid, $uid, $show_msg = true) {
        $member = $this->read($gid, $uid);
        if($member['status'] != '-1') {
            if(!$show_msg) return;
            redirect('会员不在禁言状态，无法处理。');
        }
        $this->db->from($this->table);
        $this->db->where('id', $member['id']);
        $this->db->set('status', 1);
        $this->db->set('bantime', 0);
        $this->db->update();
    }

}
?>