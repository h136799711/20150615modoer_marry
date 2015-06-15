<?php
!defined('IN_MUDDER') && exit('Access Denied');

$notice_obj = _G('loader')->model('member:notice');

$header_title = '我的消息';
include mobile_template('member_message');