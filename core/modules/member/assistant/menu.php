<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
$_G['assistant_menu'] = array();
$_G['manage_menu'] = array();
foreach(array_keys($_G['modules']) as $flag) {
    $hookfile = MUDDER_MODULE . $flag . DS . 'inc' . DS . 'menu_hook.php';
    if(is_file($hookfile)) {
        include $hookfile;
    }
}

//主题管理菜单
$_G['subject_owner'] = FALSE;
if($user->uid > 0) {
    $S =& $_G['loader']->model('item:subject');
    $_G['mysubjects'] = $S->mysubject($user->uid);
    $_G['subject_owner'] = count($_G['mysubjects']) > 0;
	if($_G['subject_owner']) {
		$manage_subject = (int) $_C['manage_subject'];
		if(!in_array($manage_subject, $_G['mysubjects'])) {
			$manage_subject = $_G['mysubjects'][0];
		}
		$_G['manage_subject'] = $S->read($manage_subject,'*',false);
		unset($manage_subject);
	}
}

//菜单处理
if(isset($_G['assistant_menu']['manage'])) {
    $_G['manage_menu']['manage'] = $_G['assistant_menu']['manage'];
    unset($_G['assistant_menu']['manage']);
}
foreach (array('assistant_menu'=>'我的助手','manage_menu'=>'主题管理') as $_kname => $_title) {
    $menu = array();
    foreach ($_G[$_kname] as $key => $value) {
        if(isset($value['title'])) {
            $menu[$key]['title'] = $value['title'];
            unset($value['title']);
        } else {
            $menu[$key]['title'] = $_title;
        }
        $menu[$key]['menus'] = $value;
    }
    $_G[$_kname] = $menu;
}
unset($menu);
?>
