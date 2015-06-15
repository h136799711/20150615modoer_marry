<?php
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');
$modmenus = array(
    array(
        'title' => '会员功能设置',
        'member|模块设置|config',
        'member|会员任务管理|task',
        'member|扩展积分管理|point|group',
        'member|推广注册记录|popularize',
    ),
    array(
        'title' => '会员管理',
        'member|用户列表|members',
        'member|用户组管理|usergroup',
    ),
    array(
        'title' => '其他功能',
        //'member|通行证反向整合|passport',
        'member|短信息通知|batchpm',
    ),
    array(
        'title' => '个人空间',
        'space|模块设置|config',
        'space|音乐管理|music',
        'space|主题管理|theme',
    ),
);
?>