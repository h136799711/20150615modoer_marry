<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2011 Moufersoft
* @website www.modoer.com
*/
abstract class ms_myiterator extends ms_base implements Iterator {

    protected $_list = array();
    private $_valid = FALSE;

    public function __construct() {
        parent::__construct();
        $this->load_list();
    }

    //此函数为子类给 $this->list 赋值
    protected function load_list() {}

    public function next() {
        $this->_valid = (next($this->_list) === FALSE) ? FALSE : TRUE;
    }

    public function rewind() {
        $this->_valid = (reset($this->_list) === FALSE) ? FALSE : TRUE;
    }

    public function valid() {
        return $this->_valid;
    }

    public function current() {
        return current($this->_list);
    }

    public function key() {
        return key($this->_list);
    }

    public function count() {
        return count($this->_list);
    }

}