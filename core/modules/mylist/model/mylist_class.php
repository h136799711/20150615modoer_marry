<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_mylist extends ms_model {

    var $table = 'dbpre_mylist';
	var $key = 'id';
    var $model_flag = 'mylist';

	function __construct() {
		parent::__construct();
        $this->model_flag = 'mylist';
		$this->init_field();
        $this->modcfg = $this->variable('config');
	}

	function init_field() {
		$this->add_field('city_id,catid,title,tags,intro');
		$this->add_field_fun('city_id,catid', 'intval');
        $this->add_field_fun('title', '_T');
        $this->add_field_fun('intro', '_TA');
	}

    function read($id) {
        $data = parent::read($id);
        if($data['tags']) {
            $data['tags'] = unserialize($data['tags']);
        }
        return $data;
    }

    function save($post, $id = NULL) {
        $edit = $id != null;
        if($edit) {
            if(!$detail = $this->read($id)) redirect('mylist_empty');
        } else {
            $post['uid'] = $this->global['user']->uid;
            $post['username'] = $this->global['user']->username;
            $post['createtime'] = $post['modifytime'] = $this->timestamp;
        }

        $W =& $this->loader->model('word'); //过滤字串和检测敏感字类
        $post['title'] = $W->filter($post['title']);
        $post['intro'] = $W->filter($post['intro']);

        //tag标签处理
        if($post['tags']) {
            $T = $this->loader->model('mylist:tag');
            $tags = $T->add($post['tags']);
            $post['tags'] = serialize($tags); //序列化保存
        }
        //vp($post);
        //exit;
        //录入/更新
        $id = parent::save($post, $id);

        $TD = $this->loader->model('mylist:tag_data');
        if($tags) {
            $TD->save($tags, $id);
        } else {
            $TD->delete_mylist($id);
        }

        if($edit && $detail['city_id'] != $post['city_id']) {
            $TD->update_city_id($id, $post['city_id']);
        }
        return $id;
    }

    function check_post($post, $edit = false) {
        if(!$post['catid']||!is_numeric($post['catid'])) redirect('mylist_post_catid_empty');
        if(!$post['title']) redirect('mylist_post_title_empty');
        if(!$post['intro']) redirect('mylist_post_intro_empty');
        if($post['city_id']) {
            $city = $this->loader->model('area')->read((int)$post['city_id']);
            if(!$city || $city['level']!='1') redirect('您未选择或选择的连接城市无效。');
        }
        $cate = $this->loader->model('mylist:category')->read((int)$post['catid']);
        if(!$cate) redirect('mylist_catid_empty');
    }

    function delete($id) {
        $mylist = $this->read($id);
        if(!$mylist) redirect('mylist_empty');
        if(!$this->in_admin && $mylist['uid']!=$this->global['user']->uid) {
            redirect('mylist_manage_access_denied');
        }
        //标签数据
        $this->loader->model('mylist:tag_data')->delete_mylist($id);
        //榜单主题
        $this->loader->model('mylist:item')->delete_mylist($id);
        //榜单评论
        if(check_module('comment')) {
            $this->loader->model(':comment')->delete_id('mylist', $id);
        }
        //收藏
        $this->loader->model('mylist:favorite')->delete_mylist($id);
        //鲜花
        $this->loader->model('mylist:flower')->delete_mylist($id);
        //删除自己
        parent::delete($id);
    }

    function delete_catids($catid) {
        if(!$this->in_admin) redirect('mylist_manage_access_denied');

        $q = $this->db->from($this->table)->where('catid', $catid)->get();
        if(!$q) return;

        $item = $this->loader->model('mylist:item');
        $tag_data = $this->loader->model('mylist:tag_data');
        if(check_module('comment')) {
            $comment = $this->loader->model(':comment');
        }
        //收藏
        $fav = $this->loader->model('mylist:favorite');
        //鲜花
        $flower = $this->loader->model('mylist:flower');
        while ($v = $q->fetch_array()) {
            $item->delete_mylist($v['id']);
            $tag_data->delete_mylist($v['id']);
            if($comment) {
                $comment->delete_id('mylist', $v['id']);
            }
            $fav->delete($id,$v['uid']);
            $flower->delete($id,$v['uid']);
        }
        $this->db->from($this->table)->where('catid', $catid)->delete();
        return $this->db->affected_rows();
    }

    function set_digest($mylist_id, $digest)
    {
        $mylist = $this->read($mylist_id);
        if(!$mylist) redirect('mylist_empty');
        $digest == $digest ? '1' : '0';
        if($mylist['digest'] == $digest) return;
        $ar = parent::_update_value($mylist_id, 'digest', (int)$digest);
        //更新用户精华榜单积分
        $this->loader->model('member:point')->update_point($mylist['uid'], 'set_digest', !$finer);
        if($digest) {
            //给用户发送提醒
            $this->_notice_digest($mylist);
        }
    }

    function update_num($mylist_id, $num) {
        return parent::_update_number($mylist_id, 'num', $num);
    }

    function update_favorites($mylist_id, $num) {
        return parent::_update_number($mylist_id, 'favorites', $num);
    }

    function update_flowers($mylist_id, $num) {
        return parent::_update_number($mylist_id, 'flowers', $num);
    }

    function update_responds($mylist_id, $num) {
        return parent::_update_number($mylist_id, 'responds', $num);
    }

    function update_pv($mylist_id, $num) {
        return parent::_update_number($mylist_id, 'pageviews', $num);
    }

    function update_modiyftime($mylist_id) {
        return parent::_update_value($mylist_id, 'modifytime', $this->timestamp);
    }

    function update_thumb($mylist_id, $thumb) {
        return parent::_update_value($mylist_id, 'thumb', $thumb);
    }

    function _notice_digest($mylist)
    {
        $c_url = '<a href="'.url("mylist/{$mylist['id']}").'" target="_blank">'.$mylist['title'].'</a>';
        $note = lang('mylist_notice_set_digest',array($c_url));

        $N = $this->loader->model('member:notice');
        $N->save($mylist['uid'], 'mylist', 'digest', $note);
    }

}
?>