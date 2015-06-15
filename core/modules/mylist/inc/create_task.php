<?php
/**
 * 榜单类任务
 *
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2009 Moufersoft
 * @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class task_mylist_create {

    var $flag = 'mylist:create';
    var $title = 'mylist_task_create_title';
    var $copyright = 'MouferStudio';
    var $version = '1.0';

    var $info = array();
    var $install = false;
    var $ttid = 0;

    function __construct() {
        $this->title = lang($this->title);
    }

    function form($cfg) {
        $result = array();
        $result[] = array(
            'title' => '创建榜单数量',
            'des' => '任务要求会员创建榜单，并在榜单内添加至少1个主题',
            'content' => form_input('config[num]',$cfg['num']>0?$cfg['num']:1,'txtbox4'),
        );
        return $result; 
    }

    function form_post($cfg) { 
        if(!$cfg['num'] || !is_numeric($cfg['num']) || $cfg['num'] < 1) redirect('完成条件错误：未填写一个有效的数量。');
        return true; 
    }

    function progress(& $task_detail) {
        $db = _G('db');
        $taskid = $task_detail['taskid'];
        $db->from('dbpre_mytask');
        $db->where('uid',_G('user')->uid);
        $db->where('taskid',$taskid);
        $mytask = $db->get_one();
        if(!$mytask) return 0;

        $cfg = @unserialize($task_detail['config']);
        if(!$cfg) return 0;
        if(!$cfg['num'] || $cfg['num'] < 1) $cfg['num'] = 1;

        $db->from('dbpre_mylist');
        $db->where('uid',_G('user')->uid);
        $db->where_more('num', 1);
        $db->where('status', 1);
        $db->where_more('createtime', $mytask['applytime']);
        if(!$count = $db->count()) return 0;
        $min = min($cfg['num'], $count);
        return round($min / $cfg['num'] * 100);
    }

    function apply($taskid) {}

    function delete($taskid) {}

    function install() {}

    function unstall() {}
}
?>