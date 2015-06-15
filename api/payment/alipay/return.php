<?php
$root_dir = dirname(dirname(dirname(dirname(__FILE__))));

if(!defined('MUDDER_ROOT')) {
    require $root_dir.'/core/init.php';
}

$_GET['m'] = 'pay';
$_GET['act'] = 'return';
$_GET['api'] = 'alipay';

require MUDDER_MODULE . 'pay/common.php';