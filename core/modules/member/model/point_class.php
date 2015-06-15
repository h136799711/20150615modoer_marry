<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_member_point extends ms_model {

    var $point = null;
    var $group = null;
    var $allow_del = true; //允许积分扣除到负数

    function __construct() {
        parent::__construct();
        $this->model_flag = 'member';
        $this->modcfg = $this->variable('config');
        $this->_load_point_rule();
    }

    function is_valid_type($type) {
        return isset($this->group[$type]);
    }

    function check_exists($uid) {
        return $this->db->from('dbpre_member_point')
            ->where('uid',$uid)
            ->count() > 0;
    }

    function copy_data($uid) {
        $fields = 'uid,point,rmb,point1,point2,point3,point4,point5,point6';
        $SQL = "insert into ".$this->db->get_table('dbpre_member_point')."($fields) select $fields from ".
            $this->db->get_table('dbpre_members')." where uid=$uid";
        $this->db->exec($SQL);
    }

    function set_point($uid, $points) {
        $this->db->from('dbpre_members');
        $this->db->where('uid', $uid);
        $this->db->set($points);
        $this->db->update();
        //积分备份表记录
        $exists = $this->check_exists($uid);
        $this->db->from('dbpre_member_point');
        $this->db->set($points);
        if($exists) {
            $this->db->where('uid',$uid)->update();            
        } else {
            $this->db->set('uid',$uid)->insert();
        }
    }

    function update_point($uid, $sort, $delete=FALSE, $num = 1, $isusername = FALSE, $merge = NULL) {
        if($uid < 1 || !$uid) return FALSE;
        if(!$member = $this->loader->model(':member')->read($uid)) return FALSE;
        if($this->point && isset($this->point[$sort])) {
            $points = array();
            $points['point'] = (int) $this->point[$sort]['point'];
            if($this->point[$sort]) foreach($this->point[$sort] as $key => $val) {
                $points[$key] = (int)$val;
            }
            if($points) {
                $update = array();
                //判断是否扣到负分
                if($delete && !$this->allow_del) {
                    if($this->global['user']->uid == $uid) {
                        foreach($points as $key => $val) {
                            if($val > 0 && $this->global['user']->$key < $val) {
                                redirect(lang('member_point_less_point_self',$val));
                            }
                        }
                    } else {
                        foreach($points as $key => $val) {
                            if($val > 0 && $this->global['user']->$key < $val) {
                                redirect(lang('member_point_less_point',array($member['username'], $val)));
                            }
                        }
                    }
                }
                $fun = $delete ? 'set_dec' : 'set_add';
                $logposts = array();
                foreach($points as $key => $val) {
                    $value = $val * $num;
                    if(!$value) continue;
                    $update[$key] = array($fun,array($value));
                    //$this->db->$fun($key, $val * $num);
                    //积分更新记录
                    if($value) {
                        $logpost = array();
                        $logpost['uid'] = $member ? $member['uid'] : $this->global['user']->uid;
                        $logpost['username'] = $member ? $member['username'] : $this->global['user']->username;
                        $logpost['flag'] = $sort;
                        $logpost['point_type'] = $key;
                        $logpost['point_flow'] = $delete ? 'out' : 'in';
                        $logpost['point_value'] = $value;
                        $logpost['amount'] = $member ? $member[$key] : $this->global['user']->$key;
                        $logpost['amount'] = $delete ? ($logpost['amount'] - $value) : ($logpost['amount'] + $value);
                        $logpost['remark'] = lang('member_update_point_des', $sort);
                        $logposts[] = $logpost;
                    }
                }
            }
        }
        if(!$update && !$merge) return FALSE;
        if($update && $merge) $newdata = array_merge($update, $merge);
        if($update && !$merge) $newdata = $update;
        if(!$update && $merge) $newdata = $merge;
        if($newdata) {
            $this->db->from('dbpre_members');
            $this->db->set($newdata);
            $this->db->where($isusername ? 'username' : 'uid', $uid);
            //$SQL = $this->db->insert_sql(0, 1);
            $this->db->update();
        }
        if($update) {
            //积分备份表记录
            if($this->check_exists($uid) && $update) {
                $this->db->from('dbpre_member_point')->set($update)->where('uid',$uid)->update();
            } else {
                $this->copy_data($uid);
            }
        }
        //添加积分更新记录
        if($logposts) {
            $log =& $this->loader->model('member:point_log');
            foreach ($logposts as $post) {
                $log->add($post);
            }
        }
        return TRUE;
    }

    //自由扣除，非固定值扣除
    function update_point2($uid, $sort, $point, $des='') {
        if(!$uid || !$point) return FALSE;
        if(!$member = $this->loader->model(':member')->read($uid)) return FALSE;

        $sorts = array();
        if($this->group) $sorts = array_keys($this->group);
        array_push($sorts,'point','rmb');
        if(!in_array($sort, $sorts)) return FALSE;

        $newdata = array();
        if($point > 0) {
            $newdata[$sort] = array('set_add',array($point));
            //$this->db->set_add($sort, $point);
        } else {
            $newdata[$sort] = array('set_dec',array(abs($point)));
            //$this->db->set_dec($sort, abs($point));
        }
        if(!$newdata) return FALSE;

        //积分备份表记录
        if($this->check_exists($uid)) {
            $this->db->from('dbpre_member_point')
                ->set($newdata)
                ->where('uid',$uid)
                ->update();
        } else {
            $this->copy_data($uid);
        }
        //更新积分
        $this->db->where('uid', $uid);
        $this->db->from('dbpre_members');
        $this->db->set($newdata);
        $this->db->update();

        //记录积分变化
        $uid = $member['uid'];
        $username = $member['username'];
        $amount = $member[$sort];
        $log =& $this->loader->model('member:point_log');
        $post['flag'] = '';
        $post['point_type'] = $sort;
        $post['uid'] = $uid;
        $post['username'] = $username;
        if($point > 0) {
            $post['point_flow'] = 'in';
            $post['point_value'] = $point;
        } else {
            $post['point_flow'] = 'out';
            $post['point_value'] = abs($point);
        }
        $post['amount'] = $amount + $point;
        $post['remark'] = lang($des);
        $log->add($post);
        return TRUE;
    }

    //积分兑换
    function exchange($in_value,$in_point,$out_point) {
        if(!$this->global['user']->isLogin) redirect('对不起，您未登录。');
        if(!$in_value = abs((int)$in_value)) redirect('');
        
        if(!$inpoint = $this->group[$in_point]) redirect('对不起，不存在的兑入积分类型');
        if(!$inpoint['enabled']) redirect('对不起，兑入积分类型未使用。');
        if(!$inpoint['in']) redirect('对不起，积分类型不允许兑入。');
        if(!$in_rate = $inpoint['rate']) redirect('对不起，积分类型兑换比率无效。');
        
        if(!$outpoint = $this->group[$out_point]) redirect('对不起，不存在的兑出积分类型');
        if(!$outpoint['enabled']) redirect('对不起，对出积分类型未使用。');
        if(!$outpoint['out']) redirect('对不起，积分类型不允许对出。');
        if(!$out_rate = $outpoint['rate']) redirect('对不起，积分类型兑换比率无效。');

        $needpoint = ceil($in_rate / $out_rate * $in_value);
        if($needpoint > $this->global['user']->$out_point) redirect('对不起，您的积分不足，无法完成本次兑换操作。');
 
        $newdata = array();
        $newdata[$in_point] = array('set_add',array($in_value));
        $newdata[$out_point] = array('set_dec',array($needpoint));

        //积分备份表记录
        if($this->check_exists($this->global['user']->uid)) {
            $this->db->from('dbpre_member_point')
                ->set($newdata)
                ->where('uid', $this->global['user']->uid)
                ->update();
        } else {
            $this->copy_data($this->global['user']->uid);
        }

        $this->db->from('dbpre_members');
        $this->db->set($newdata);
        $this->db->where('uid', $this->global['user']->uid);
        $this->db->update();

        //兑换记录-兑出
        $log =& $this->loader->model('member:point_log');
        $post = array();
        $post['flag'] = 'member:exchange';
        $post['uid'] = $this->global['user']->uid;
        $post['username'] = $this->global['user']->username;
        $post['point_flow'] = 'out';
        $post['point_type'] = $out_point;
        $post['point_value'] = $needpoint;
        $post['amount'] = $this->global['user']->$out_point - $needpoint;
        $post['remark'] = lang('member_point_exchange_des_out');
        $log->add($post);
        //兑入记录
        $post = array();
        $post['flag'] = 'member:exchange';
        $post['uid'] = $this->global['user']->uid;
        $post['username'] = $this->global['user']->username;
        $post['point_flow'] = 'in';
        $post['point_type'] = $in_point;
        $post['point_value'] = $in_value;
        $post['amount'] = $this->global['user']->$in_point + $in_value;
        $post['remark'] = lang('member_point_exchange_des_in');
        $log->add($post);
    }

    function get_bak_point($uid) {
        $this->db->from('dbpre_member_point');
        $this->db->where('uid', $uid);
        return $this->db->get_one();
    }

    function check_invalid($uid, $pointtype) {
        $src = $this->loader->model(':member')->read($uid);
        if(!$src) return FALSE;
        $bak = $this->get_bak_point($uid);
        if(!$bak) {
            $this->copy_data($uid);
            return TRUE;
        }
        if(!isset($src[$pointtype]) || !isset($bak[$pointtype])) return FALSE;
        return $src[$pointtype] != $bak[$pointtype];
    }

    function _load_point_rule() {
        if($config = $this->variable('config')) {
            $this->point = $config['point'] ? unserialize($config['point']) : '';
            $this->group = $config['point_group'] ? unserialize($config['point_group']) : array();
        }
    }
}