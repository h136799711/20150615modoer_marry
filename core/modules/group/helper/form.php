<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
function form_group_category($select = '') {
	$loader =& _G('loader');
    if(!$category =& $loader->variable('category', 'group')) return;
    $list = array();
	foreach($category as $val) {
        $list[$val['catid']] = $val['name'];
    }
    $loader->helper('form');
    return form_option($list,$select);
}

function form_group_type($gid, $typeid=null) {
    $loader =& _G('loader');
    $C =& $loader->model('group:type');
    $content = '';
    if(!$r = $C->get_list($gid)) return '';
    while($v = $r->fetch_array()) {
        $selected = $typeid > 0 && $v['typeid']==$typeid ? ' selected="selected"' : '';
        $content .= "<option value=\"{$v['typeid']}\"$selected>$v[name]</option>\n";
    }
    $r->free_result();
    return $content;
}
?>