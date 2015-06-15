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

    public $total_setp = 4;
    public $next_setp = false;

    private $old_ver = '';
    private $new_ver = '3.2';

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
        $tables = array(
            array(
                "dbpre_product_field",
                "pid mediumint(8) unsigned NOT NULL DEFAULT '0',
                cod_price decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
                freight_price_snail decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
                freight_price_exp decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
                freight_price_ems decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
                PRIMARY KEY (pid)",
            ),
        );
        if($table = $tables[$this->start]) {
            sql_create_table(str_replace('dbpre_','', $table[0]), $table[1]);
            $this->start++;
        } else {
            if($this->start >= count($tables)) {
                $this->step++;
                $this->next_setp = true;
            }
        }
    }


    private function _step_2() {
        $array = array(
            array('product', 'shape_code', 'add', "`shape_code` CHAR(15) NOT NULL DEFAULT '' AFTER `subject`"),
            array('product', 'brief_code', 'add', "`brief_code` CHAR(15) NOT NULL DEFAULT '' AFTER `shape_code`"),
            array('product', 'is_cod', 'add', "`is_cod` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `is_on_sale`"),
            array('product', 'is_freight', 'add', "`is_freight` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' AFTER  `is_cod`"),
            array('product', 'freight', 'add', "freight tinyint(1) unsigned NOT NULL DEFAULT '1' AFTER `is_freight`"),
            array('product_order', 'is_cod', 'add', "`is_cod` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER  `is_serial`"),
            array('product_order', 'is_offline_pay', 'add', "`is_offline_pay` ENUM('null','owner','admin') NOT NULL DEFAULT 'null' AFTER `is_cod`"),
            array('product_order', 'offline_pay_role', 'add', "`offline_pay_role` varchar(20) NOT NULL DEFAULT '' AFTER `is_offline_pay`"),
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

    private function _step_3() {
        $this->db->from('dbpre_product');
        $this->db->set('is_freight', 0);
        $this->db->where('free_shipping',1);
        $this->db->update();
        $this->step++;
        $this->next_setp = true;
    }

    //add index
    private function _step_4() {
        $fields = array(
            array('dbpre_product', 'shape_code', 'shape_code(shape_code)'),
            array('dbpre_product', 'brief_code', 'brief_code(brief_code)'),
            array('dbpre_productatt', 'pid', 'pid(pid)'),
        );
        if($field = $fields[$this->start]) {
            $field[0] = str_replace('dbpre_','', $field[0]);
            if(sql_exists_table($field[0])) {
                if($field[1]=='PRIMARY KEY')
                    sql_alter_pk($field[0], 'add', $field[2]);
                else
                    sql_alter_index($field[0], 'add', $field[1], $field[2]);
                $s = "√";
            } else {
                $s = "×";
            }
            $this->start++;
        } else {
            if($this->start >= count($tables)) {
                $this->step++;
                $this->next_setp = true;
            }
        }
    }

    private function _check_version() {
        if($this->old_ver != '3.1') {
            echo 'Please first upgrade your module(Product Pro) to version 3.1.';
            exit;
        }
        if($this->global['modoer']['version'] < 'MC 3.1') {
            echo 'Please first upgrade your Modoer MC to version 3.1.';
            exit;
        }
    }

}
?>