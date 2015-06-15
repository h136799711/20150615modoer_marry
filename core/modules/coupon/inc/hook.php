<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class hook_coupon extends ms_base {

    function __construct(&$hook) {
        parent::__construct();
        $hook->register(
            array('subject_detail_link','mobile_index_link'), 
            $this
        );
    }

	function subject_detail_link(&$params) {
		extract($params);
		$IB =& $this->loader->model('item:itembase');
		$model = $IB->get_model($pid,true);
		$title = '优惠券';
		return array (
			'flag' => 'coupon',
			'url' => url('coupon/item/sid/'.$sid),
			'title'=> $title,
		);
	}

	function mobile_index_link() {
		$title = '优惠券';
		$result[] = array (
			'flag' => 'coupon',
			'url' => url('coupon/mobile/do/list'),
			'title'=> '优惠券',
			'icon' => 'coupon',
		);
		return $result;
	}

}
?>