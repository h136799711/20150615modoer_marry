<?php
!defined('IN_MUDDER') && exit('Access Denied');

//手机助手菜单获取
$links = $_G['hook']->hook('mobile_member_link',null,TRUE);
$header_title = '我的助手';
include mobile_template('member_index');