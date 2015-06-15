<?php
/**
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2013 Moufersoft
 * @website www.modoer.com
 */
!defined('IN_MUDDER') && exit('Access Denied');

$service = new mc_product_service();
$op = _input('op', '', MF_TEXT);
switch($op) {

	default:

		$attcat = _input('attcat', 0, MF_TEXT);
		if($attcat) {
			$attcats = explode(',', $attcat);
			$catids = array();
			foreach ($attcats as $value) {
				if(is_numeric($value)) {
					$catids[] = $value;
				} elseif(strposex($value,'|')) {
					list($tid,) = explode('|', $value);
					$catids[] = (int) $tid;
				}
			}
			if($catids) {
				$service->catids = $catids;
				$cats = _G('loader')->variable('att_cat', 'item');
				$data_cat = array();
				foreach ($cats as $cat) {
					foreach ($catids as $catid) {
						if($cat['catid'] == $catid) $data_cat[] = $cat;
					}
				}
				$data_list = array();
				foreach ($catids as $catid) {
					$tmp = _G('loader')->variable('att_list_'.$catid, 'item');
					$data_list = array_merge($data_list, $tmp);
				}
				$service->data = array(
					'cat' => $data_cat,
					'list' => $data_list,
				);
			}
		}

		echo $service->fetch_all_attr('json');
		output();
		break;

};

/** end **/