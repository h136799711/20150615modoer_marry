<?php
/**
* @author moufer<moufer@163.com>
* @package modoer
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_member_invite extends ms_model {

	public $table = 'dbpre_member_invite';
    public $key = 'id';
    public $model_flag = 'member';
    public $modcfg = null;

    private $message = '';
    private $inviter = '';
    private $count_num = 0;

    function __construct() {
        parent::__construct();
        $this->model_flag = 'member';
        $this->modcfg = $this->variable('config');
        $this->modcfg['invite_reg_maxnum'] = (int)$this->modcfg['invite_reg_maxnum'];
    }

    function getlist($inviter_uid, $start, $offset) {
        $result = array(0,null);
        $this->db->from($this->table);
        $this->db->where('inviter_uid', $inviter_uid);
        if($result[0] = $this->db->count()) {
            $this->db->sql_roll_back('from,where');
            $this->db->order_by('id','DESC');
            $this->db->limit($start, $offset);
            $result[1] = $this->db->get();
        }
        return $result;
    }

    function get_message() {
        return $this->message;
    }

    function get_inviter() {
        return $this->inviter;
    }

    function check_enable($inviter_uid) {
        //未开启邀请注册
        if(!$this->modcfg['invite_reg']) {
            $this->add_error('管理员未开启邀请注册功能。');
            return false;
        }
        //邀请人和被邀请人IP相同
        $this->inviter = $this->loader->model(':member')->read($inviter_uid);
        if(!$this->inviter) {
            $this->add_error('邀请人账号不存在');
            return false;
        }
        if($this->inviter['loginip'] == $this->global['ip']) {
            $this->add_error('不能邀请自己');
            return false;
        }
        //邀请注册人数
        // $num = $this->count_num($inviter_uid);
        // if($this->modcfg['invite_reg_maxnum'] > 0 && $num > $this->modcfg['invite_reg_maxnum']) {
        //     $this->message = '邀请人今日的邀请额度已满';
        //     return false;
        // }
        return true;
    }

    function add($inviter_uid) {
        $this->num = $this->count_num($inviter_uid);
        $add_point = true;
        $inviter = $this->global['user']->read($inviter_uid);
        $post = array();
        $post['inviter_uid'] = $inviter_uid;
        $post['inviter'] = $inviter['username'];
        $post['invitee_uid'] = $this->global['user']->uid;
        $post['invitee'] = $this->global['user']->username;
        $post['dateline'] = $this->global['timestamp'];
        $post['add_point'] = '1';
        if($this->modcfg['invite_reg_maxnum'] > 0) {
            if($this->num > $this->modcfg['invite_reg_maxnum']) {
                $post['add_point'] = '0';
                $add_point = false;
            }
        }
        $this->db->from($this->table)->set($post)->insert();
        //增加积分
        $P = $this->loader->model('member:point');
        //被邀请人总是获得积分
        $P->update_point($this->global['user']->uid, 'invitee');
        //邀请人判断限额
        if($add_point) {
            $P->update_point($inviter_uid, 'inviter');
            if($this->num + 1 > $this->modcfg['invite_reg_maxnum']) {
                //人数满，提醒邀请人
                $this->_notice_max($inviter_uid);
            }
        }
    }

    /*
     计算今日邀请人数
     */
    function count_num($inviter_uid) {
        $starttime = strtotime(date('Y-m-d', $this->global['timestamp']));
        $endtime = $starttime + 24 * 3600 - 1; //1天
        $this->db->from($this->table);
        $this->db->where('inviter_uid', $inviter_uid);
        $this->db->where_between_and('dateline', $starttime, $endtime);
        $this->db->where('add_point', 1);
        return $this->db->count();
    }

    /*
     提醒人数已满
     */
    function _notice_max($inviter_uid) {
        $note = lang('member_reg_invite_maxnum', array( date('Y-m-d',$this->glbal['timestamp']), $this->modcfg['invite_reg_maxnum']) );
        $N = $this->loader->model('member:notice');
        $N->save($inviter_uid,'member','inviter', $note);
    }
}
?>