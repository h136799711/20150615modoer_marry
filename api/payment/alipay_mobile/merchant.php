<?php
$root_dir = dirname(dirname(dirname(dirname(__FILE__))));

if(!defined('MUDDER_ROOT')) {
    require $root_dir.'/core/init.php';
}

location(S('siteurl').'index.php?m=mobile');