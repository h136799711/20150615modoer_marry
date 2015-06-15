<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_comment extends ms_model {

    var $table = 'dbpre_comment';
	var $key = 'cid';
    var $model_flag = 'comment';

    var $typenames = array();
    var $typeurls = array();
    var $idtypes = array();

	function __construct() {
		parent::__construct();
        $this->model_flag = 'comment';
		$this->init_field();
        $this->load_hook();
        $this->modcfg = $this->variable('config');
	}

	function init_field() {
		$this->add_field('idtype,id,extra_id,grade,title,content,username,status');
		$this->add_field_fun('id,extra_id,grade', 'intval');
        $this->add_field_fun('idtype,title,username', '_T');
        $this->add_field_fun('content', '_TA');
	}

    function load_hook() {
        $modules =& $this->global['modules'];
        foreach($modules as $k => $v) {
            $hookfile = MUDDER_MODULE . $v['flag'] . DS . 'inc' . DS . 'comment_hook.php';
            if(!is_file($hookfile)) continue;
            if(!$tmp = read_cache($hookfile)) continue;
            foreach($tmp as $k2 => $v2) {
                $this->idtypes[$k2] = $v2;
            }
        }
    }

    function reply_list($root_cid, $order_by, $start, $num) {
        return $this->db->from($this->table)
            ->where('root_cid', $root_cid)
            ->where('status',1)
            ->order_by($order_by)
            ->limit($start, $num)
            ->get();
    }

    function save($post, $cid = NULL) {
        $edit = $cid != null;
        if($this->modcfg['filter_word']) {
            $W =& $this->loader->model('word');
        }
        if($edit) {
            if(!$detail = $this->read($cid)) redirect('comment_empty');
        } else {
            !$this->in_admin && $this->global['user']->check_access('comment_disable', $this);
            if($this->global['user']->isLogin) {
                $post['uid'] = $this->global['user']->uid;
                $post['username'] = $this->global['user']->username;
            }
            $post['dateline'] = $this->global['timestamp'];
            $post['ip'] = $this->global['ip'];
            $post['status'] = $this->modcfg['check_comment'] ? 0 : 1;
            if($this->modcfg['filter_word']) {
                $W->check($post['content']) && $post['status'] = 0;
            }
        }
        //过滤
        if($this->modcfg['filter_word']) {
            $post['content'] = $W->filter($post['content']);
        }
        $this->check_post($post, $edit);
        if($edit) {
            foreach($detail as $k => $v) {
                if(isset($post[$k]) && $post[$k] == $v) {
                    unset($post[$k]);
                }
            }
            if($post && count($post) > 0) {
                $this->db->from($this->table);
                $this->db->set($post);
                $this->db->where('cid',$cid);
                $this->db->update();
                //更改了状态的需要重新统计主题的评论数量
                if($post['status']) {
                    define('RETURN_EVENT_ID', 'global_op_succeed');
                    $this->_add_total($detail['idtype'], $detail['id']);
                    $detail['uid'] && $this->_add_point($detail['uid']);
                } elseif(isset($post['status']) && ($detail['status'] && !$post['status'])) {
                    define('RETURN_EVENT_ID', 'global_op_succeed_check');
                    $this->_dec_total($detail['idtype'], $detail['id']);
                    $detail['uid'] && $this->_dec_point($detail['uid']);
                } else {
                    define('RETURN_EVENT_ID', $detail['status'] ? 'global_op_succeed' : 'global_op_succeed_check');
                }
            }
        } else {
            $cid = parent::save($post, null, 0, 0);
            //不需要审核
            if($post['status']) {
                $this->_add_total($post['idtype'], $post['id']);
                $post['uid'] && $this->_add_point($post['uid']);
                //会员评论对象
                $this->_notice_post($post);
                define('RETURN_EVENT_ID', 'global_op_succeed');
            } else {
                define('RETURN_EVENT_ID', 'global_op_succeed_check');
            }
        }
        return $cid;
    }

    function reply($reply_cid, $post) {
        $insert = array();
        $insert['title'] = $post['title'];
        $insert['content'] = $post['content'];
        !$this->in_admin && $this->global['user']->check_access('comment_disable', $this);
        if($this->global['user']->isLogin) {
            $insert['uid'] = $this->global['user']->uid;
            $insert['username'] = $this->global['user']->username;
        } else {
            $insert['uid'] = 0;
            $insert['username'] = $post['username'];
        }
        $insert['dateline'] = $this->global['timestamp'];
        $insert['ip'] = $this->global['ip'];
        $insert['status'] = $this->modcfg['check_comment'] ? 0 : 1;

        $comment = $this->read($reply_cid);
        if(!$comment) redirect('回应对象不存在。');
        foreach (array('idtype','id') as $key) {
            $insert[$key] = $comment[$key];
        }

        $W =& $this->loader->model('word');
        if($this->modcfg['filter_word']) {
            $W->check($insert['content']) && $insert['status'] = 0;
            $insert['content'] = $W->filter($insert['content']);
        }

        //回应对象
        $insert['reply_cid'] = $reply_cid;
        $insert['reply_user'] = "$comment[uid]\t$comment[username]";
        //回应根对象
        $insert['root_cid'] = $comment['root_cid'] > 0 ? $comment['root_cid'] : $reply_cid;
        $this->check_post($insert);
        $cid = parent::save($insert, null, 0, 0);
        //不需要审核
        if($insert['status']) {
            //更新回应更对象旗下回应数量
            $this->db->from($this->table)
                ->where('cid', $insert['root_cid'])
                ->set_add('root_subtotal',1)
                ->update();
            //更新评论对象评论数量
            $this->_add_total($insert['idtype'], $insert['id']);
            $insert['uid'] && $this->_add_point($insert['uid']);
            //回复提醒
            $this->_notice_reply($insert);
            define('RETURN_EVENT_ID', 'global_op_succeed');
        } else {
            define('RETURN_EVENT_ID', 'global_op_succeed_check');
        }
        return $cid;
    }

    function checkup($cids) {
        if(is_numeric($cids) && $cids > 0) $cids = array($cids);
        if(!$cids||!is_array($cids)) redirect('global_op_unselect');
        $cids=parent::get_keyids($cids);
        $this->db->select('cid,idtype,id,reply_cid,reply_user,uid,root_cid,dateline');
        $this->db->from($this->table);
        $this->db->where('status',0);
        $this->db->where_in('cid',$cids);
        if(!$r = $this->db->get()) return;
        $upids = $upcids = $upuids = $reply_root = array();
        while($v=$r->fetch_array()) {
            $cid = $v['cid'];
            $upcids[] = $cid;
            $keyid = $v['idtype'].'-'.$v['id'];
            if(isset($upids[$keyid])) {
                $upids[$keyid]++;
            } else {
                $upids[$keyid] = 1;
            }
            //某条评论的回应总量
            if($v['root_cid'] > 0) {
                $reply_root[$v['root_cid']] = isset($reply_root[$v['root_cid']]) ? ($reply_root[$v['root_cid']]+1) : 1;
            }
            if(!$v['uid'] || $v['uid'] < 1) continue;
            if(isset($upuids[$v['uid']])) {
                $upuids[$v['uid']]++;
            } else {
                $upuids[$v['uid']]=1;
            }
            //如果是回复某条评论的提醒
            if($v['reply_cid'] > 0) {
                $this->_notice_reply($v);
            } else {
                $this->_notice_post($v);
            }
        }
        $r->free_result();

        $this->db->from($this->table);
        $this->db->set('status',1);
        $this->db->where_in('cid',$upcids);
        $this->db->update();

        //更新指定评论下的回应总量
        foreach ($reply_root as $cid => $num) {
            $this->db->from($this->table)
                ->set_add('root_subtotal', $num)
                ->where_in('cid', $cid)
                ->update();
        }

        foreach($upids as $idstr => $num) {
            list($idtype, $id) = explode('-', $idstr);
            $this->_add_total($idtype, $id, $num);
        }

        if($upuids) {
            foreach($upuids as $uid => $num) {
                $this->_add_point($uid, $num);
            }
        }
    }

    //删除一条点评
    function delete($cid, $uppoint=true, $allow=true) {
        $total_num = $root_cid = 0;
        $comment = $this->read($cid);
        if(!$comment) redirect('评论信息不存在。');
        if($allow && !$this->in_admin) {
            if($comment['uid'] != $this->global['user']->uid) redirect('global_op_access');
        }
        //如果当前删除项可能是根评论时，删除旗下回应内容
        if(!$comment['root_cid']) {
            $total_num = $this->_delete_reply_root($comment, $uppoint);
        }
        if($comment['status']) {
            //减会员积分
            if($uppoint && $comment['uid'] > 0) $this->_dec_point($comment['uid'], 1);
            //更新评论数量
            $this->_dec_total($comment['idtype'], $comment['id'], 1);
            //如果自己是回应的话，记录根评论cid，等下要更新数量
            if($comment['reply_cid']) {
                $root_cid = $comment['root_cid'];
            }
        }
        //删除自己
        $num = parent::delete($cid);
        //更新更评论的总数
        if($root_cid > 0) {
            $this->db->from($this->table)
                ->set_dec('root_subtotal', 1)
                ->where('cid', $root_cid)
                ->update();
        }
        return $total_num + $num;
    }

    //删除旗下所有回应（根回应删除）
    function _delete_reply_root($comment, $up_total=true, $up_point=true) {
        if($up_point || $up_total) {
            $get = $this->db->from($this->table)
                ->where('root_cid', $comment['cid'])
                ->select('cid,idtype,id,uid,status,reply_cid,root_cid')
                ->get();
            if(!$get) return 0;
            $point = array();
            $total = 0;
            while ($val = $get->fetch_array()) {
                if(!$val['status']) return;
                $total++;
                if($up_point && $v['uid'] > 0) {
                    $point[$v['uid']] = isset($point[$v['uid']]) ? ($point[$v['uid']]+1) : 1;
                }
            }
            //减评论数量
            if($total > 0) {
                $this->_dec_total($comment['idtype'], $comment['id'], $total, false);
            }
            //减会员积分
            if($point) {
                foreach($point as $uid => $num) {
                    $this->_dec_point($uid, $num);
                }
            }
        }
        //删除
        $this->db->from($this->table)->where('root_cid', $comment['cid'])->delete();
        return $this->db->affected_rows();
    }

    function delete_id($idtype, $id=0, $uppoint=false, $allow=false) {
        if(!$idtype) return;
        $where = array();
        $where['idtype'] = $idtype;
        $id && $where['id'] = $id;
        //点评对象被删除时
        $this->db->from($this->table)
            ->where('idtype', $idtype)
            ->where('id', $id)
            ->delete();
        return $this->db->affected_rows();
    }

    function check_post(& $post, $edit = false) {
        if(!$edit && !$post['username']) redirect('comment_post_username_empty');
        if(!$post['title']) redirect('comment_post_title_empty');
        if(!$post['content']) redirect('comment_post_content_empty');
        $this->modcfg['content_min'] = $this->modcfg['content_min']>0 ? $this->modcfg['content_min'] : 10;
        $this->modcfg['content_max'] = $this->modcfg['content_max']>0 ? $this->modcfg['content_max'] : 500;
        if(strlen($post['content']) > $this->modcfg['content_max'] || strlen($post['content']) < $this->modcfg['content_min']) {
            redirect(lang('comment_post_content_charlen',array($this->modcfg['content_min'],$this->modcfg['content_max'])));
        }
    }

    function get_url($idtype, $id) {
        return url(str_replace('_ID_', $id, $this->idtypes[$idtype]['detail_url']));
    }

    function check_idtype($idtype) {
        return isset($this->idtypes[$idtype]);
    }

    function check_id_exists($idtype,$id) {
        if(!$table_name = $this->idtypes[$idtype]['table_name']) return false;
        if(!$key_name = $this->idtypes[$idtype]['key_name']) return false;
        $this->db->from($table_name);
        $this->db->where($key_name,$id);
        return $this->db->count() > 0;
    }

    function check_access($key,$value,$jump) {
        if($this->in_admin) return TRUE;
        if($key=='comment_disable') {
            $value = (int) $value;
            if($value) {
                if(!$jump) return FALSE;
                redirect('comment_access_disable');
            }
        }
        return TRUE;
    }

    function update_comments($idtype, $id, $num, $grade) {
        if(!$table_name = $this->idtypes[$idtype]['table_name']) return;
        if(!$key_name = $this->idtypes[$idtype]['key_name']) return;
        if(!$total_name = $this->idtypes[$idtype]['total_name']) return;
        
        $this->db->from($table_name);
        $this->db->set($total_name, (int)$num);
        if($grade_name = $this->idtypes[$idtype]['grade_name']){
            $this->db->set($grade_name, (int)$grade);
        }
        $this->db->where($key_name, (int)$id);
        $this->db->update();

        return true;
    }

    //重建评论对象的评论数和（评分）
    function rebuild_comments($idtype, $id) {
        if(!$table_name = $this->idtypes[$idtype]['table_name']) return false;
        if(!$key_name = $this->idtypes[$idtype]['key_name']) return false;
        if(!$total_name = $this->idtypes[$idtype]['total_name']) return false;
        $grade_name = $this->idtypes[$idtype]['grade_name'];
        
        $this->db->select('cid', 'count', 'COUNT( ? )');
        if($grade_name) {
            $this->db->select('grade', 'grade', 'ROUND(AVG( ? ))');
        }

        $result = $this->db->from($this->table)
            ->where('idtype', $idtype)
            ->where('id', $id)
            ->where('status', 1)
            ->get_one();

        if(!$result) return false;

        $this->db->from($table_name)
            ->where($key_name, $id)
            ->set($total_name, (int)$result['count']);
        if($grade_name) $this->db->set($grade_name, (int)$result['grade']);
        $this->db->update();
        return $this->db->affected_rows();
    }


    function _filter_words(&$content) {
        return $content;
    }

    function _get_avg_grade($idtype, $id) {
        $this->db->from($this->table);
        $this->db->select('grade', 'grade', 'ROUND(AVG( ? ))');
        $this->db->where('idtype', $idtype);
        $this->db->where('id', $id);
        $this->db->where('status', 1);
        $result = $this->db->get_one();
        return (int) $result['grade'];
    }

    function _add_total($idtype, $id, $num=1, $up_grade = TRUE) {
        if(!$table_name = $this->idtypes[$idtype]['table_name']) return;
        if(!$key_name = $this->idtypes[$idtype]['key_name']) return;
        if(!$total_name = $this->idtypes[$idtype]['total_name']) return;
        $grade = FALSE;
        if($up_grade) {
            if($grade_name = $this->idtypes[$idtype]['grade_name']) {
                $grade = $this->_get_avg_grade($idtype,$id);
            }
        }
        $this->db->from($table_name);
        $this->db->set_add($total_name, $num);
        $grade_name && $this->db->set($grade_name, $grade);
        $this->db->where($key_name, $id);
        $this->db->update();
    }

    function _dec_total($idtype, $id, $num=1, $up_grade = TRUE) {
        if(!$table_name = $this->idtypes[$idtype]['table_name']) return;
        if(!$key_name = $this->idtypes[$idtype]['key_name']) return;
        if(!$total_name = $this->idtypes[$idtype]['total_name']) return;
        $grade = FALSE;
        if($up_grade) {
            if($grade_name = $this->idtypes[$idtype]['grade_name']) {
                $grade = $this->_get_avg_grade($idtype,$id);
            }
        }
        $this->db->from($table_name);
        $this->db->set_dec($total_name, $num);
        $grade_name && $this->db->set($grade_name, $grade);
        $this->db->where($key_name, $id);
        $this->db->update();
    }

    function _add_point($uid, $num=1) {
        if(!$uid) return;
        $P =& $this->loader->model('member:point');
        $P->update_point($uid, 'add_comment', FALSE, $num);
    }

    function _dec_point($uid, $num=1) {
        if(!$uid) return;
        $P =& $this->loader->model('member:point');
        $P->update_point($uid, 'add_comment', TRUE, $num); //删除
    }

    function _ubb(&$content) {
        $searcharray = array('[u]','[/u]','[b]','[/b]','[i]','[/i]','[quote]','[/quote]','[h3]','[/h3]',);
        $replacearray = array('<u>','</u>','<b>','</b>','<i>','</i>','<div class="quote">','</div>','<h3>','</h3>');
        return str_replace($searcharray, $replacearray, $content);
    }

    //提醒评论
    function _notice_post($comment)
    {
        //do nothings....
    }

    //提醒有回复
    function _notice_reply($reply)
    {
        $c_username = '<a href="'.url("space/index/uid/$reply[uid]").'" target="_blank">'.$reply['username'].'</a>';
        $c_url = $this->get_url($reply['idtype'], $reply['id']).'#commentend';
        $note = lang('comment_notice_reply',array($c_username, $c_url));

        list($uid,$username) = explode("\t", $reply['reply_user']);
        if($uid > 0) {
            $N = $this->loader->model('member:notice');
            $N->save($uid,'comment','reply', $note);
        }
    }
}
?>