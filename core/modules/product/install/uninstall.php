<?php
//广告
$_G['db']->from('dbpre_adv_place')->where('name','商城_首页通栏')->delete();
//自定义字段
$_G['db']->from('dbpre_field')->where('idtype','product')->delete();
?>