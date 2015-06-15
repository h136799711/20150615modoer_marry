<?php

$A =& $_G['loader']->model('area');
$list = $A->get_list(0,TRUE);
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

$d_city = get_default_city();

$forward = rawurlencode("mobile/index");
include mobile_template('citys');
?>