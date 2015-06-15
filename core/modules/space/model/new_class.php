<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_space_new extends ms_model {

    var $table = 'dbpre_space_new';
    var $key = 'uid';
    var $model_flag = 'space';

    function __construct()
    {
        parent::__construct();
        $this->modcfg = $this->variable('config');
        $this->init_field();
    }
    
	function init_field() {
		$this->add_field('uid,theme,music,cover,photobg,my_name,my_des,other_id,other_name,other_des,other_avatar,wedding_timestamp,wedding_address,wedding_map_point,hotel,domain,is_rsvp');
		$this->add_field_fun('uid,theme,music,other_id,wedding_timestamp,is_rsvp', 'intval');
		$this->add_field_fun('cover,photobg,my_name,my_des,other_name,other_des,other_avatar,wedding_address,wedding_map_point,hotel,domain', '_T');
	}
	
    function save($uid, $post) 
    {
        $this->db->from($this->table);
        $this->db->where('uid', $uid);
        $c = $this->db->count();
        if($c==0){
        	$post['uid'] = $uid;
        	//主题使用排列第一的主题
        	$ST =& $this->loader->model('space:theme');
        	if($theme = $ST->get_first()){
				$post['theme'] = $theme['id'];
			}
		}

        parent::save($post,$c?$uid:NULL);
        return $uid;
    }

/*    function check_post(&$post) {
        if(isset($post['my_name'])) redirect('space_post_spacename_empty');
        if(!$post['spacedescribe']) redirect('space_post_spacedescribe_empty');
        if(strlen($post['spacename']) > 100) redirect(lang('space_post_spacename_len', 100));
        if(strlen($post['spacedescribe']) > 200) redirect(lang('space_post_spacedescribe_len', 200));
        if($post['space_styleid'] > 0) {
            $templates = $this->loader->variable('templates');
            if(!isset($templates['space'][$post['space_styleid']])) redirect('space_post_template_not_found');
        }
    }*/

/*    function pageview($uid,$num=1) {
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
    }*/
}
?>