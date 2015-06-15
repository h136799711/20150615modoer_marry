<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class hook_member extends ms_base {

    function __construct(&$hook) {
        parent::__construct();
        if(!defined('IN_ADMIN')) {
            $hook->register( array('init','init_end','mobile_index_link','mobile_member_link'), $this );
        } else {
            $hook->register('total', $this);
        }
    }

    //系统初始化
    function init() {
        global $_G;
        $cfg = $this->loader->variable('config','member');
        if($cfg['passport_login'] && $cfg['passport_list']) {
            foreach(explode(',', $cfg['passport_list']) as $passport_name) {
                $_G['passport_apis'][$passport_name] = $cfg['passport_'.$passport_name.'_title'];
            }
            ksort($_G['passport_apis']);
        }
    }

    //系统初始化完毕后，就进行会员初始化
    function init_end() {
    }

    function total() {
        global $_G;
        $result = array();
        $_G['db']->from('dbpre_members');
        $result[] = array(
            'name' => lang('membercp_cphome_member_title'),
            'content' => $_G['db']->count(),
        );
        $_G['db']->from('dbpre_pmsgs');
        $result[] = array(
            'name' => lang('membercp_cphome_pmsg_title'),
            'content' => $_G['db']->count(),
        );

        return $result;
    }

    function mobile_index_link() {
        $result[] = array (
            'flag' => 'member',
            'url' => url('member/mobile'),
            'title'=> '我的助手',
            'icon' => 'assistant',
        );
        return $result;
    }

    function mobile_member_link() {
        $extra = '';
        if (_G('user')->isLogin) {
            $msg_num = _G('user')->newmsg + _G('loader')->model('member:notice')->get_count();
            $extra = '';
            if($msg_num > 0) {
                $extra = "<span class=\"label-comm label-default\">{$msg_num}</span>";
            }
        }
        $result[] = array (
            'flag' => 'member/point',
            'url' => url('member/mobile/do/point'),
            'title'=> '我的积分',
        );
        $result[] = array (
            'flag' => 'member/message',
            'url' => url('member/mobile/do/message'),
            'title'=> '我的消息&nbsp;'.$extra,
        );
        $result[] = array (
            'flag' => 'member/myset',
            'url' => url('member/mobile/do/myset'),
            'title'=> '个人设置',
        );
        return $result;
    }

}
?>