<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_product_subjectsetting extends ms_model {

    var $table = 'dbpre_subjectsetting';
    var $key = 'sid';
    var $model_flag = 'product';

    function save($sid,$post) {
        foreach ($post as $key => $value) {
            $this->db->from($this->table);
            $this->db->set('sid',$sid);
            $this->db->set('variable',$key);
            $this->db->set('value', $this->check_post($key, $value));
            $this->db->replace();
        }
    }

    function read($sid,$variable) {
        $this->db->from($this->table);
        $this->db->select('value');
        $this->db->where('sid',$sid);
        $this->db->where('variable',$variable);
        return $this->db->get_value();
    }

    function exists($sid, $variable) {
        $this->db->from('sid',$sid);
        $this->db->where('variable',$variable);
        return $this->db->count() > 0;
    }

    function check_post($variable, $value) {
        if($variable=='brokerage') {
            if(!is_numeric($value) || $value < -1 || $value > 100) {
                redirect('对不起，您设置的分成百分比无效，请重新设置。');
            }
            if(!$value) $value = '';
        }elseif($variable=='offlinepay') {
            if(!$value) $value = '';
        }elseif($variable=='copy_disable') {
            $value = (int)$value;
        }elseif($variable=='use_deliverytime') {
            $value = (int)$value;
        }elseif($variable=='onedaydelivery_limit') {
            $value = (int)$value;
            if($value<0||$value>23) redirect('对不起，您填写的当天发货截止时间格式错误。');
        } else {
            redirect('未知的商城设置参数('.$variable.')。');
        }
        return $value;
    }

}
?>