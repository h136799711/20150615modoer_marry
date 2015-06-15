<?php
/**
* @author moufer<moufer@163.com>
* @package modoer
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_member_verify extends ms_model {

    public $table = 'dbpre_member_verify';
    public $key = 'id';
    public $model_flag = 'member';

    function __construct() {
        parent::__construct();
    }

    function __set($key, $value) {
        if($key == 'message') {
            $this->set_message($value);
        } else {
            $this->key = $value;
        }
    }

    //通过序列获取内容
    public function find_by_hash($hash) {
        return $this->db->from($this->table)->where('hash', $hash)->get_one();
    }

    //通过验证码和行为标识获取数据内容
    public function get_verify_data($verify_code, $action_flag) {
        $data = $this->db->from($this->table)
            ->where('verify_code',$verify_code)
            ->where('action_flag',$action_flag)
            ->get_one();
        if(!$data) return;

        if($data['expriy_date'] < $this->timestamp) {
            parent::delete($data['id']); //过期删除
            return -1; //过期
        }
        if($data['extra']) $data['extra'] = unserialize($data['extra']);
        return $data;
    }

    public function create($verify) {
        //发现已存在的，进行删除
        $localdata = $this->find_by_hash($verify->hash);
        if($localdata) {
            parent::delete($localdata['id']);
        }

        $data = array();
        $data['hash']           = $verify->hash;
        $data['verify_code']    = $verify->verify_code;
        $data['action_flag']    = $verify->action_flag;
        $data['expriy_date']    = $this->timestamp + $verify->expriy_date;
        $data['uniq']           = $verify->uniq;
        $data['uid']            = $verify->uid;
        $data['sender']         = $verify->sender;
        $data['sender_id']      = $verify->sender_id;
        $data['send_time']      = $this->timestamp;
        $data['extra']          = empty($verify->extra_item) ? '' : serialize($verify->extra_item);

        $id = parent::save($data);
        return $id;
    }

    //获取上一次发送间隔
    public function get_send_interval($hash) {
        $data = $this->find_by_hash($verify->hash);
        if(!$data) return null;
        return $this->timestamp - $data['send_time'];
    }
}
?>