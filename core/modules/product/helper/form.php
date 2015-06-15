<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
function form_product_model($select = '') {
	$loader =& _G('loader');
	$model = $loader->variable('model', 'product', FALSE);
	if(!$model) return;
	foreach($model as $key => $val) {
		$selected = $key == $select ? ' selected' : '';
		$content .= "\t<option value=\"$key\"$selected>$val</option>\r\n";
	}
	return $content;
}

function form_product_category($sid, $catid=null) {
    $loader =& _G('loader');
    $C =& $loader->model('product:category');
    $content = '';
    if(!$r = $C->get_list($sid)) return '';
    foreach($r as $k => $v) {
        $selected = $catid > 0 && $v['catid']==$catid ? ' selected="selected"' : '';
        $content .= "<option value=\"$k\"$selected>$v[name]</option>\n";
    }
    return $content;
}

//参数filter_usemodel_id有2个含义，1：过滤掉已经关联模型的上级分类，2：不过滤参数内制定的模型id的分类
function form_product_gcategory($pid, $select = '', $filter_usemodel_id = false, $out_catid = '') {
    $data = _G('datacall')->datacall_get("gcategory",array('pid'=>$pid),"product");
    if(!$data) return '';
    foreach($data as $key => $val) {
        if(!$val['enabled']) continue;
        if($val['catid'] == $out_catid) continue;
        if($filter_usemodel_id && $val['modelid'] > 0 && $val['modelid'] != $filter_usemodel_id) continue;
        $selected = $val['catid'] == $select ? ' selected' : '';
        $content .= "\t<option value=\"{$val['catid']}\"$selected>{$val[name]}</option>\r\n";
    }
    return $content;
}

function form_product_category_main($select = '') {
    $loader =& _G('loader');
    if(!$category = $loader->variable('gcategory', 'product', FALSE)) {
        return '';
    }
    foreach($category as $key => $val) {
        if($val['pid'] != 0||!$val['enabled']) continue;
        $selected = $key == $select ? ' selected' : '';
        $content .= "\t<option value=\"$key\"$selected>{$val[name]}</option>\r\n";
    }
    return $content;
}
?>