<?php
!defined('IN_MUDDER') && exit('Access Denied');
class model_update extends ms_model {

    public $flag = 'product';
    public $moduleid = 0;

    public $setp = 0;
    public $start = 0;
    public $index = 0;
    public $params = array();
    public $progress = 0;

    public $total_setp = 3;
    public $next_setp = false;

    private $old_ver = '';
    private $new_ver = '3.1';

    public function __construct($moduleid,$old_ver) {
        parent::__construct();
        $this->loader->helper('sql');
        $this->moduleid = $moduleid;
        $this->old_ver = $old_ver;
        $this->_check_version();
        $this->step = (int) _get('step');
        $this->step = $this->step < 1 || !$this->step ? 1 : $this->step;
        $this->start = (int) _get('start');
        $this->start = $this->start < 0 || !$this->start ? 0 : $this->start;
        $this->index = (int) _get('index');
        $this->index = $this->index < 0 || !$this->index ? 0 : $this->index;
    }

     public function updating() {
        $method = '_step_' . $this->step;
        if(method_exists($this, $method)) {
            $this->$method();
        } else {
            echo sprintf('The required method "%s" does not exist for %s.', $method, get_class($this));
            exit;
        }
        return $this;
    }

    public function completed() {
        $this->params['moduleid'] = $this->moduleid;
        $this->params['step'] = $this->step;
        if($this->next_setp) {
            $this->start = $this->index = 0;
        }
        $this->params['start'] = $this->start;
        $this->params['index'] = $this->index;
        $this->progress = round($this->step / $this->total_setp, 2);
        if($this->progress>1) $this->progress = 1;
        return $this->step > $this->total_setp;
    }

    private function _step_1() {
        $array = array(
            array('product_order', 'integral_amount', 'add', "integral_amount DECIMAL(9,2) UNSIGNED NOT NULL DEFAULT '0.00' AFTER order_amount"),
            array('product_order', 'brokerage', 'add', "brokerage DECIMAL(9,2) UNSIGNED NOT NULL DEFAULT '0.00' AFTER integral_pointtype"),
            array('product_order', 'amount_changed', 'add', "amount_changed SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0' AFTER remark"),
        );
        if($detail = $array[$this->index]) {
            sql_alter_field($detail[0], $detail[1], $detail[2], $detail[3]);
        }
        $this->index++;
        $total = count($array);
        if($this->index >= $total) {
            $this->next_setp = true;
            $this->step++;
        }
    }

    private function _step_2() {
        $offset = 500;
        $this->db->from('dbpre_product');
        $this->db->limit($this->start, $offset);
        $this->db->order_by('pid');
        $r = $this->db->get();
        if(!$r) {
            $this->step++;
            $this->next_setp = true;
            return;
        }
        while ($v = $r->fetch_array()) {
            $tags = array();
            $tags[] = display('product:gcategory',"catid/$v[pgcatid]");
            $cate = $this->loader->model('product:gcategory')->read($v['gcatid']);
            if($cate['catid'] != $v['pgcatid']) {
                $tags[] = display('product:gcategory',"catid/$cate[catid]");
            }
            $tags[] = display('product:gcategory',"catid/$v[gcatid]");
            $atts = $this->_get_attname($v['pid']);
            if($atts) $tags = array_merge($tags, $atts);
            $tags = array_unique($tags);
            $this->db->from('dbpre_product')
                ->where('pid', $v['pid'])
                ->set('tag_keyword', '|'.implode('|', $tags).'|')
                ->update();
        }
        $this->start += $offset;
    }

    private function _step_3() {
        $table = $this->db->get_table('dbpre_product_order');
        $this->db->exec("UPDATE $table SET `integral_amount` = `integral` / 10");
        $this->step++;
        $this->next_setp = true;
    }

    private function _check_version() {
        if($this->old_ver != '3.0') {
            echo 'Please first upgrade your module(Product Pro) to version 3.0.';
            exit;
        }
        if($this->global['modoer']['version'] < 'MC 3.0.1') {
            echo 'Please first upgrade your Modoer MC to version 3.0.1.';
            exit;
        }
    }

    private function _get_attname($pid) {
        $this->db->join('dbpre_productatt','a.attid','dbpre_att_list','b.attid','LEFT JOIN');
        $this->db->where('pid',$pid);
        $q = $this->db->get();
        if(!$q) return;
        $result = array();
        while ($v = $q->fetch_array()) {
            $result[] = $v['name'];
        }
        return $result;
    }

}
?>