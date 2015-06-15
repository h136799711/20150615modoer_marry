<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_group extends ms_model {

    var $table = 'dbpre_group';
    var $key = 'gid';

    var $modcfg = '';
    var $model_flag = 'group';

    var $post_topic = array();

    var $topic = null;
    var $member = null;
    var $setting = null;
    var $c_tag = null;

    function __construct() {
        parent::__construct();
        $this->init_field();
        $this->modcfg = $this->variable('config');
        $this->topic = $this->loader->model('group:topic');
        $this->member = $this->loader->model('group:member');
        $this->setting = $this->loader->model('group:setting');
        $this->c_tag = $this->loader->model('group:tag');
    }

    function init_field() {
        $this->add_field('catid,groupname,des,tags,sid,finer,auth,city_id');
        $this->add_field_fun('catid,uid,sid,finer,auth,city_id', 'intval');
        $this->add_field_fun('groupname,des,icon', '_T');
    }

    //通过标签查询
    function find_tag($select,$where,$orderby=array('members'=>'DESC'),$start=0,$offset=20) {
        $where['tagname'] && $this->db->where('gt.tagname',$where['tagname']);
        unset($where['tagname']);
        if($where) foreach ($where as $key => $value) {
            $this->db->where('g.'.$key, $value);
        }
        $this->db->join($this->table,'g.gid','dbpre_group_tag','gt.gid','LEFT JOIN');
        $total = $this->db->count();
        if(!$total) return array(0,'');
        $this->db->sql_roll_back('from,where');
        $this->db->select($select);
        $this->db->limit($start, $offset);
        $list = $this->db->get();
        return array($total, $list);
    }

    //我加入的
    function joined() {
        $this->db->select('g.groupname,gm.*');
        $this->db->join('dbpre_group_member','gm.gid',$this->table,'g.gid','LEFT JOIN');
        $this->db->where('gm.uid', $this->global['user']->uid);
        $this->db->where_more('usertype', '2');
        return $this->db->get();
    }

    //我管理的
    function mylist() {
        $this->db->select('g.*');
        $this->db->join('dbpre_group_member','gm.gid',$this->table,'g.gid','LEFT JOIN');
        $this->db->where('gm.uid', $this->global['user']->uid);
        $this->db->where('usertype', '1');
        return $this->db->get();
    }

    //关联主题的小组
    function find_subject($sid) {
        $this->db->from($this->table);
        $this->db->where('sid', $sid);
        $this->db->where('status', 1);
        $this->db->limit(0,100);
        return $this->db->get();
    }

    //创建小组
    function add() {
        $post = $this->get_post($_POST);
        $post['createtime'] = $this->timestamp;
        if($this->in_admin) {
            $post['uid'] = 0;
            $post['username'] = $this->admin->adminname;
        } else {
            $post['uid'] = $this->global['user']->uid;
            $post['username'] = $this->global['user']->username;
            $post['status'] = $this->modcfg['group_check'] ? 0 : 1;
            $post['members'] = 1;
        }
        $post['icon'] = $this->upload_icon();
        $post = $this->check_post($post);
        $gid = parent::save($post, false, false, false);
        if($gid > 0 && !$this->in_admin) {
            $insert = array();
            $insert['gid'] = $gid;
            $insert['uid'] = $this->global['user']->uid;
            $insert['username'] = $this->global['user']->username;
            $insert['status'] = 1;
            $insert['jointime'] = $this->timestamp;
            $insert['usertype'] = 1;
            $this->db->from('dbpre_group_member')
                ->set($insert)
                ->insert();
        }
        //保存标签表
        $this->c_tag->save($gid, $this->c_tag->field_to_array($post['tags']));
        //正常显示小组后，显示feed数据
        $post['status'] && $this->_feed_add($gid); //添加feed事件
    }

    //编辑
    function edit() {
        $gid = _post('gid',0, MF_INT_KEY);
        $post = $this->get_post($_POST,true);
        $post['icon'] = $this->upload_icon();
        if(!$post['icon']) unset($post['icon']);
        $group = $this->read($gid);
        if(!$group) redirect('group_empty');
        //$post['groupname'] = $group['groupname'];
        $post = $this->check_post($post, $gid);
        parent::save($post, $group, false, false);
        //保存标签表
        if($post['tags']!=$group['tags']) {
            $this->c_tag->save($gid, $this->c_tag->field_to_array($post['tags']));
        }
    }

    //leader=1,owner=2,member=10

    //判断是否是小组成员
    function is_member($gid, $uid = -1) {
        if($uid<0) $uid = $this->global['user']->uid;
        return $this->db->from('dbpre_group_member')
            ->where('gid', $gid)
            ->where('uid', $uid)
            ->get_one();
    }

    //判断是否组长
    function is_owner($gid, $uid = -1) {
        if(is_array($gid)) $gid = $gid['gid'];
        if($uid < 0) $uid = $this->global['user']->uid;
        return $this->db->from('dbpre_group_member')
            ->where('gid', $gid)
            ->where('uid', $uid)
            ->where('usertype', 1)
            ->get_one();
    }

    //图标上传
    function upload_icon() {
        //上传图片部分
        if(!empty($_FILES['icon']['name'])) {
            $this->loader->lib('upload_image', NULL, FALSE);
            $img = new ms_upload_image('icon', $this->global['cfg']['picture_ext']);

            $size = (int) $this->global['group_icon_size'];
            $size < 100 && $size = 100;

            $img->upload('group', $this->global['cfg']['picture_dir_mod'], array('width'=>$size, 'height'=>$size));
            $filepath = str_replace(DS, '/', $img->path . '/' . $img->filename);
            return $filepath;
        }
        return false;
    }

    //创建检测
    function check_post(& $post, $keyid = FALSE) {
        $isedit = $keyid > 0;
        //if(!$post['catid']) redirect('group_save_category_unselect');
        //$cate = $this->loader->model('group:category')->read($post['catid']);
        //if(!$cate) redirect('group_save_category_empty');
        if(!$post['groupname']) redirect('group_save_name_empty');

        $group = $this->exists($post['groupname']);
        if(!$isedit && $group) redirect('group_save_exists');
        if($isedit && $group && $group['gid'] != $keyid) redirect('group_save_exists');

        if(strlen_ex($post['des']) < 20 || strlen_ex($post['des']) > 500) {
            redirect(lang('group_save_des_invalid', array(20, 500)));
        }

        if($group['sid'] != $post['sid'] && $post['sid'] > 0 && !$this->in_admin) {
            if(!$this->loader->model('item:subject')->is_mysubject($post['sid'], $this->global['user']->uid)) {
                redirect('对不起，您选择关联的主题不是您的主题。');
            }
        }

        if(!$post['tags']) redirect('对不起，您未选择分类标签。');
        $tags = $this->c_tag->check_filter($post['tags']);
        if($tags) {
            $post['tags'] = $this->c_tag->array_to_field($tags);
        }

        if($group['status'] == '-1') { //检查重新提交审核的是否更改过内容
            $x = false;
            foreach ($post as $key => $value) {
                if($group[$key] != $value) {
                    $x = true;
                }
            }
            if(!$x) redirect('对不起，您没有更改任何信息，无法重新提交审核。');
            $post['status'] = 0;
        }

        if(!$this->in_admin) {
            unset($post['auth'], $post['finer']);
        } else {
            $post['auth'] = isset($post['auth']) ? (bool)$post['auth'] : 0;
            $post['finer'] = isset($post['finer']) ? (bool)$post['finer'] : 0;
        }
        return $post;
    }

    //审核不通过
    function check_nopass($gid, $message) {
        $message = trim($message);
        if(!$message) redirect('对不起，您未填写审核不通过理由。');
        $group = $this->read($gid);
        if(!$group) redirect('group_empty');
        if($group['status'] > 0) redirect('该小组已经审核通过，无法进行本次操作。');
        $this->db->from($this->table)->where('gid', $gid)->set('status', -1)->update();
        $note = lang('group_notice_status_-1', array($group['groupname'], $message));
        $this->loader->model('member:notice')->save($group['uid'], $this->model_flag, 'group', $note);
    }

    //审核创建小组
    function check_pass($gids) {
        $this->db->from($this->table);
        $this->db->where('gid',$gids);
        $r = $this->db->get();
        if(!$r) return;
        $g = array();
        while ($group = $r->fetch_array()) {
            $g[] = $group['gid'];
            //提醒
            $note = lang('group_notice_status_1', $group['groupname']);
            $this->loader->model('member:notice')->save($group['uid'], $this->model_flag, 'group', $note);
            //feed
            $this->_feed_add($group);
        }
        $this->db->from($this->table);
        $this->db->where('gid', $g);
        $this->db->set('status', 1);
        $this->db->update();
    }

    //删除小组
    function delete($gid, $note = false) {
        if(!$group = $this->read($gid)) {
            redirect('group_empty');
        }
        if(!$this->in_admin) {
            if($group['uid'] != $this->global['user']->uid) redirect('group_access_allow_delete');
            $this->global['user']->check_access('allow_delete', $this);
        }
        $this->topic->delete_gid($gid);
        $this->member->delete_gid($gid);
        parent::delete($gid);
        //删除图标
        if($group['icon'] && is_file(MUDDER_ROOT.$group['icon'])) {
            @unlink(MUDDER_ROOT.$group['icon']);
        }
        if($note) {
            //通知管理员
            $replace = array(
                '{groupname}' => $group['groupname'],
                '{adminname}' => $this->global['admin']->adminname,
            );
            $note = str_replace(array_keys($replace), array_values($replace), lang('group_notice_delete'));
            $this->loader->model('member:notice')->save($group['uid'], $this->model_flag, 'group', $note);
        }
    }

    //查询同名小组
    function exists($name) {
        $this->db->from($this->table);
        $this->db->where('groupname',$name);
        return $this->db->get_one();
    }

    //指定SID是否存在关联小组
    function exists_related_subject($sid) {
        $this->db->from($this->table);
        $this->db->where('sid',$sid);
        return $this->db->count();
    }

    //权限判断
    function check_access($key, $value, $jump) {
        if($this->in_admin) return TRUE;
        if ($key=='allow_create') { //创建小组
            $value = isset($value) ? $value : 1; //默认允许
            if(!$value) {
                if(!$jump) return FALSE;
                if(!$this->global['user']->isLogin) redirect('member_not_login');
                redirect('group_access_allow_create');
            }
        } elseif ($key=='allow_delete') { //删除小组
            $value = (bool) $value;
            if(!$value) {
                if(!$jump) return FALSE;
                redirect('group_access_allow_delete');
            }
        }
        return TRUE;
    }

    //查某个主题拥有的小组数量
    function get_count_subject($sid) {
        $this->db->from($this->table);
        $this->db->where('sid',$sid);
        $this->db->where('status',1);
        return $this->db->count();
    }

    //新建小组的feed
    function _feed_add($group) {
        if(!is_array($group)) {
            $gid = (int)$group;
            $group = $this->read($gid);
        }
        if(!$group) return;

        $FEED =& $this->loader->model('member:feed');
        if(!$FEED->enabled()) return;

        $this->global['fullalways'] = TRUE;

        $feed = array();
        $feed['icon'] = lang('group_feed_add_icon');
        $feed['title_template'] = lang('group_feed_add_title_template');
        $feed['title_data'] = array (
            'username' => '<a href="'.url("space/index/uid/$group[uid]").'">' . $group['username'] . '</a>',
        );
        $feed['body_template'] = lang('group_feed_add_body_template');
        $feed['body_data'] = array (
            'groupname' => '<a href="'.url("group/$group[gid]").'">'.$group['groupname'].'</a>',
        );
        $feed['body_general'] = trimmed_title(strip_tags(preg_replace("/\[.+?\]/is", '', $group['des'])), 150);

        $FEED->save($this->model_flag, 0, $feed['icon'], $group['uid'], $group['groupname'], $feed);
    }

}
?>