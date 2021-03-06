<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_space extends ms_model {

    var $table = 'dbpre_spaces';
    var $key = 'uid';
    var $model_flag = 'space';

    function __construct()
    {
        parent::__construct();
        $this->modcfg = $this->variable('config');
        $this->field = array('space_styleid','spacename','spacedescribe');
        $this->field_fun = array(
            'space_styleid' => 'intval',
            'spacename' => '_T',
            'spacedescribe' => '_T',
        );
    }

    function create($uid, $username) 
    {
        $this->db->from($this->table);
        $this->db->where('uid', $uid);
        if($this->db->count() >= 1) return;

        $templateid = $this->modcfg['templateid'] > 0 ? $this->modcfg['templateid'] : 0;
        $spacename = $this->modcfg['spacename'] ? $this->modcfg['spacename'] : lang('space_spacename_str');
        $spacedescribe = $this->modcfg['spacedescribe'] ? $this->modcfg['spacedescribe'] : lang('space_spacedescribe_str');
        $post = array();
        $post['uid'] = $uid;
        $post['space_styleid'] = $templateid;
        $post['spacename'] = $this->_parse_des($username, $spacename);
        $post['spacedescribe'] = $this->_parse_des($username, $spacedescribe);
        $post['pageview'] = 0;

        parent::save($post);

        return $uid;
    }

    function check_post(&$post) {
        if(!$post['spacename']) redirect('space_post_spacename_empty');
        if(!$post['spacedescribe']) redirect('space_post_spacedescribe_empty');
        if(strlen($post['spacename']) > 100) redirect(lang('space_post_spacename_len', 100));
        if(strlen($post['spacedescribe']) > 200) redirect(lang('space_post_spacedescribe_len', 200));
        if($post['space_styleid'] > 0) {
            $templates = $this->loader->variable('templates');
            if(!isset($templates['space'][$post['space_styleid']])) redirect('space_post_template_not_found');
        }
    }

    function pageview($uid,$num=1) {
        if(!$uid || $this->global['user']->uid == $uid) return;
        $this->db->from($this->table);
        $this->db->set_add('pageview', $num);
        $this->db->where('uid',$uid);
        $this->db->update();
    }

    function _parse_des($username, $str) {
        $s = array('{username}', '{sitename}');
        $r = array($username, $this->global['cfg']['sitename']);
        return str_replace($s, $r, $str);
    }
}
?>