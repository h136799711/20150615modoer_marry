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

    public $total_setp = 2;
    public $next_setp = false;

    private $old_ver = '';
    private $new_ver = '3.3';

    public function __construct($moduleid, $old_ver) {
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
            array('product', 'pictures', 'add', "`pictures` text NOT NULL AFTER  `picture`"),
            array('product_order', 'kdcom', 'add', "`kdcom` varchar(255) NOT NULL DEFAULT 'other' AFTER  `invoice_no`"),
            array('product_field', 'use_delivery_time', 'add', "`use_delivery_time` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `pid`"),
            array('product_field', 'use_dispatch_time', 'add', "`use_dispatch_time` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `use_delivery_time`"),
            array('product_order', 'delivery_time', 'add', "`delivery_time` int(10) unsigned NOT NULL DEFAULT '0' AFTER `amount_changed`"),
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
        $this->db->where_not_equal('picture','');
        $this->db->limit($this->start, $offset);
        $this->db->order_by('pid');
        $r = $this->db->get();
        if(!$r) {
            $this->step++;
            $this->next_setp = true;
            return;
        }
        while ($v = $r->fetch_array()) {
            if($v['pictures']) continue;
            $pictures = array();
            $picture = $v['picture'];
            $pictures[_T(pathinfo($picture, PATHINFO_FILENAME))] = $picture;

            $this->db->from('dbpre_product')
                ->where('pid', $v['pid'])
                ->set('pictures', serialize($pictures))
                ->update();
        }
        $this->start += $offset;
    }

    private function _check_version() {
        if($this->old_ver != '3.2') {
            echo 'Please first upgrade your module(Product Pro) to version 3.2.';
            exit;
        }
        if($this->global['modoer']['version'] < 'MC 3.2') {
            echo 'Please first upgrade your Modoer MC to version 3.2.';
            exit;
        }
    }

}
?>