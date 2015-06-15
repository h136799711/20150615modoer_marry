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
    private $new_ver = '3.4';

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
            array('product_cart', 'p_style', 'add', "`p_style` tinyint(1) unsigned NOT NULL DEFAULT '1' AFTER `p_image`"),
            array('product_cart', 'buyattr', 'add', "`buyattr` text NOT NULL AFTER `price`"),
            array('product_ordergoods', 'buyattr', 'add', "`buyattr` text NOT NULL AFTER `quantity`"),
            array('pay', 'callback_url_mobile', 'add', "`callback_url_mobile` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `callback_url`"),
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
        $tables = array(
            array(
                "dbpre_product_buyattr",
                "`id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
                `name` char(60) NOT NULL DEFAULT '',
                `value` text NOT NULL,
                `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`),
                KEY `pid`(`pid`,`listorder`)",
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

    private function _check_version() {
        if($this->old_ver != '3.3') {
            echo 'Please first upgrade your module(Product Pro) to version 3.3.';
            exit;
        }
        if($this->global['modoer']['version'] < 'MC 3.4') {
            echo 'Please first upgrade your Modoer MC to version 3.4.';
            exit;
        }
        if($this->global['modoer']['version'] < '20141023') {
            echo 'Please first upgrade your Modoer MC to version 3.4 <b>Build 20141023</b>';
        }
    }

}

/** end **/