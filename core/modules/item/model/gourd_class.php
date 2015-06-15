<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('item:itembase', FALSE);
class msm_item_gourd extends ms_model {

    var $table = 'dbpre_subjectgourd';
    var $key = 'id';

    function __construct() {
        parent::__construct();
        $this->model_flag = 'item';
        $this->modcfg = $this->variable('config');
        $this->init_cfg();
    }

    function init_cfg($modcfg = null) {
        if($modcfg) $this->modcfg = $modcfg;
        if(!$this->modcfg['gourd_condition'] || !is_numeric($this->modcfg['gourd_condition']) || $this->modcfg['gourd_condition']<1) {
            $this->modcfg['gourd_condition'] = 10;
        }
        if(!$this->modcfg['gourd_total_min'] || !is_numeric($this->modcfg['gourd_total_min']) || $this->modcfg['gourd_total_min']<1) {
            $this->modcfg['gourd_total_min'] = 5;
        }
        if(!$this->modcfg['gourd_total_max'] || !is_numeric($this->modcfg['gourd_total_max']) || $this->modcfg['gourd_total_max']<1) {
            $this->modcfg['gourd_total_max'] = 10;
        }
        if(!$this->modcfg['gourd_point'] || !is_numeric($this->modcfg['gourd_point']) || $this->modcfg['gourd_point']<1) {
            $this->modcfg['gourd_point'] = 10;
        }
        if(!$this->modcfg['gourd_buy_point'] || !is_numeric($this->modcfg['gourd_buy_point']) || $this->modcfg['gourd_buy_point']<1) {
            $this->modcfg['gourd_buy_point'] = 10;
        }
        if(!$this->modcfg['gourd_buy_pointtype']) {
            $this->modcfg['gourd_buy_pointtype'] = 'point1';
        }
        if(!$this->modcfg['gourd_pointtype']) {
            $this->modcfg['gourd_pointtype'] = 'point1';
        }
    }

    //创建一个葫芦
    function create($sid) {
        $set = array();
        $set['sid'] = $sid;
        $set['uid'] = $this->global['user']->uid;
        $set['username'] = $this->global['user']->username;
        $set['status'] = 'grow';
        $set['progress'] = 0;
        $set['total'] = 0;
        $set['num'] = 0;
        $set['createtime'] = $this->global['timestamp'];
        $set['harvesttime'] = 0;
        $set['finishtime'] = 0;
        $set['users'] = '';
        $this->db->from($this->table);
        $this->db->set($set);
        $this->db->insert();

        return $this->db->insert_id();
    }

    //获取最新一个葫芦
    function get_gourd($sid) {
        $this->db->from($this->table);
        $this->db->where('sid', $sid);
        $this->db->order_by('id', 'DESC');
        return $this->db->get_one();
    }

    //更新进度
    function update_progress($sid) {
        if(!$this->modcfg['gourd_enabled']) return;
        $gourd = $this->get_gourd($sid);
        if(!$gourd||$gourd['status'] != 'grow') {
            return;
        }
        $id = $gourd['id'];

        $this->db->from($this->table);
        $this->db->where('id', $id);
        $this->db->set_add('progress', 1);
        $this->db->update();

        $gourd = $this->read($id);
        if($gourd['progress'] >= $this->modcfg['gourd_condition']) {
            //果实成熟
            $total = mt_rand($this->modcfg['gourd_total_min'], $this->modcfg['gourd_total_max']);
            $this->db->from($this->table);
            $this->db->where('id', $id);
            $this->db->set('total', $total);
            $this->db->set('status', 'harvest');
            $this->db->set('harvesttime', $this->global['timestamp']);
            $this->db->update();
            //feed
            $this->fruit_feed($gourd['sid']);
        }
    }

    //购买种子
    function buy_seed($sid) {
        $S = $this->loader->model('item:subject');
        $subject = $S->read($sid);
        if(!$subject) redirect('item_empty');
        $gourd = $this->get_gourd($sid);
        if($gourd['status'] == 'grow' || $gourd['status'] == 'harvest') {
            redirect('已经有葫芦藤种下了，您不能再种了。');
        }
        //购买
        $this->dec_point($sid);
        //播种
        $this->create($sid);
        //feed
        $this->create_feed($subject);
    }

    //采摘
    function pick($id) {
        $gourd = $this->read($id);
        if(!$gourd) redirect('没有可供采摘的葫芦。');
        if($gourd['status']=='grow') redirect('葫芦还在生长，无法采摘。');
        if($gourd['status']=='finish') redirect('葫芦已经全部摘光了。');
        $users = trim($gourd['users']);
        if($users) {
            $userarr = explode("\n", $users);
            foreach ($userarr as $value) {
                list($uid,$username,$time) = explode("\t", $value);
                if($uid == $this->global['user']->uid) redirect('对不起，您之前已经采摘过了。');
            }
        }
        $users = ($users ? ($users."\n") : '') . $this->global['user']->uid . "\t" . $this->global['user']->username . "\t" . $this->global['timestamp'];
        $this->db->from($this->table);
        $this->db->where('id',$id);
        $this->db->set_add('num',1);
        $this->db->set('users', $users);
        if($gourd['num']+1 >= $gourd['total']) {
            //摘完了
            $this->db->set('status', 'finish');
            $this->db->set('finishtime', $this->global['timestamp']);
        }
        $this->db->update();
        //给会员增加果实（积分）
        $this->add_point($gourd['sid']);
        return true;
    }

    //更新用户积分
    function add_point($sid) {
        $pointtype = $this->modcfg['gourd_pointtype'];
        $point = $this->modcfg['gourd_point'];

        $P =& $this->loader->model('member:point');
        $P->update_point2($this->global['user']->uid, $pointtype, $point, "采摘成熟葫芦获得(SID:$sid)");
    }

    //扣除用户积分
    function dec_point($sid) {
        $pointtype = $this->modcfg['gourd_buy_pointtype'];
        $point = $this->modcfg['gourd_buy_point'];
        if($this->global['user']->$pointtype < $point) {
            redirect('对不起，您没有足够的积分购买种子。');
        }
        $P =& $this->loader->model('member:point');
        $P->update_point2($this->global['user']->uid, $pointtype, -$point, "购买葫芦种子(SID:$sid)");
    }

    function update_condition() {
        if(!$this->modcfg['gourd_condition']) return;
        $this->db->from($this->table);
        $this->db->where('status', 'grow');
        $this->db->where_more('progress', $this->modcfg['gourd_condition']);
        $qs = $this->db->get();
        if(!$qs) return;
        while ($val = $qs->fetch_array()) {
            $total = mt_rand($this->modcfg['gourd_total_min'], $this->modcfg['gourd_total_max']);
            $this->db->from($this->table);
            $this->db->where('id', $val['id']);
            $this->db->set('total', $total);
            $this->db->set('status', 'harvest');
            $this->db->set('harvesttime', $this->global['timestamp']);
            $this->db->update();
        }
    }

    function create_feed($subject) {
        $FEED =& $this->loader->model('member:feed');
        if(!$FEED->enabled()) return;
        if(!$subject) return;
        $this->global['fullalways'] = TRUE;

        $param = array();
        $param['flag'] = 'item';
        $param['uid'] = $this->global['user']->uid;
        $param['username'] = $this->global['user']->username;
        $param['icon'] = lang('item_guard_feed_icon');
        $param['sid'] = $subject['sid'];
        $param['city_id'] = $subject['city_id'];

        $feed = array();
        $feed['title_template'] = lang('item_guard_feed_title_template');
        $feed['title_data'] = array (
            'username' => '<a href="'.url("space/index/uid/{$this->global[user]->uid}").'">' . $this->global['user']->username . '</a>',
        );
        $feed['body_template'] = lang('item_guard_feed_body_template');
        $feed['body_data'] = array (
            'subject' => '<a href="'.url("item/detail/id/$subject[sid]").'">' . $subject['name'] . '</a>',
        );
        $feed['body_general'] = '';

        $FEED->save_ex($param, $feed);
    }

    function fruit_feed($sid) {
        $FEED =& $this->loader->model('member:feed');
        if(!$FEED->enabled()) return;
        if(!$subject = $this->loader->model('item:subject')->read($sid)) return;
        $this->global['fullalways'] = TRUE;
        $param = array();

        $param['flag'] = 'item';
        $param['uid'] = $this->global['user']->uid;
        $param['username'] = $this->global['user']->username;
        $param['icon'] = lang('item_fruit_feed_icon');
        $param['sid'] = $sid;
        $param['city_id'] = $subject['city_id'];

        $feed = array();
        $feed['title_template'] = lang('item_fruit_feed_title_template');
        $feed['title_data'] = array (
            'subject' => '<a href="'.url("item/detail/id/$subject[sid]").'">' . $subject['name'] . '</a>',
        );
        $feed['body_template'] = lang('item_fruit_feed_body_template');
        $feed['body_data'] = '';
        $feed['body_general'] = '';
        
        $FEED->save_ex($param, $feed);
    }

}

