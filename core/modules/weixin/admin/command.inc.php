<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$cmd_obj = new mc_weixin_cmd_manage();
$cmd_files = $cmd_obj->get_cmd_list();

$installed_cmds = S('weixin:cmds');
$installed_cmds = $installed_cmds ? array_unique(explode(',', $installed_cmds)) : array();
//基础指令
$base_cmds = array('welcome','help');

$op = _input('op',null,MF_TEXT);
switch($op) {
    case 'install':
    	$class = _input('class','',MF_TEXT);
    	$name = str_replace('weixin_cmd_', '', $class);
    	$filename = MUDDER_MODULE.'weixin'.DS.'component'.DS.'cmd'.DS. $name.'.php';
    	if(!is_file($filename)) redirect('安装的指令文件不存在。'.$filename);
    	if(!in_array($name, $installed_cmds)) {
    		$installed_cmds[] = $name;
    		$post = array('cmds'=>implode(',', $installed_cmds));
    		$_G['loader']->model('config')->save($post ,'weixin');
    		location(cpurl($module,$act));
    	}
    	break;
    case 'uninstall':
    	$class = _input('class','',MF_TEXT);
    	$name = str_replace('weixin_cmd_', '', $class);
    	if(in_array($name,$base_cmds)) {
    		redirect('对不起，您不能删除基础指令。');
    	}
    	$i = array_search($name, $installed_cmds);
    	if(is_numeric($i)) {
    		unset($installed_cmds[$i]);
    		$post = array('cmds'=>implode(',', $installed_cmds));
    		$_G['loader']->model('config')->save($post ,'weixin');
    		location(cpurl($module,$act));
    	}
    	break;
    default:
        $admin->tplname = cptpl('command_list', MOD_FLAG);
    	break;
}