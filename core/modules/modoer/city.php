<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'city');

$forward = _get('forward', null, MF_TEXT);
$name = _input('name', '', MF_TEXT);
$city_id = _input('city_id', null, MF_INT_KEY );
if(!$city_id && $name) {
    $city = get_city_for_doman($name);
    $city_id = (int) $city['aid'];
}
if($city_id > 0) {

    $citys = $_G['loader']->variable('area');
    if(!$city = $citys[$city_id]) redirect('global_area_city_id_invalid');
    if(!$city['enabled']) redirect('global_area_city_disabled');

    init_city($city_id);
    if($forward) $forward = rawurldecode($forward);
    $forward = str_replace('!', '/', $forward);
    $url = $forward ? url($forward) : url('index');
//    exit;
    location($url);

} else {

    $sitename = $_CFG['sitename'];
    if($d_city = get_default_city()) {
        if(!$_CITY['aid']) {
            init_city($d_city['aid']);
        }
    } else {
        show_error('global_area_city_empty');
    }
    
    $A =& $_G['loader']->model('area');
    $list = $A->get_list(0,TRUE);
    if(!$forward) $forward = null;

    $total = 0;
    if($list) foreach($list as $k=>$v) {
        foreach($v as $_k => $_v) {
            if(!$_v['enabled']) {
                unset($list[$k][$_k]);
            } else {
                $total++;
            }
        }
        if(empty($list[$k])) unset($list[$k]);
    }
    ksort($list);
    include template('modoer_city');
}
?>