<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');
$MC =& $_G['loader']->model('config');
$config = $MC->read_all();

$op = _input('op');
$do = _input('do');

switch($do) {
	case 'forum_test':
		$_G['loader']->helper('modcenter');
		$url = _input('url');
		$result = modcenter::test($url);
		redirect($result);
		break;
	case 'index_module_page':
		$flag = _input('module_flag',null,MF_TEXT);
		if(!check_module($flag)) redirect(lang('global_not_found_module',$flag));
		$file = MUDDER_ROOT . 'core/modules/' . $flag . '/inc/index_hook.php';
		if(!is_file($file)) {
			echo 'EMPTY';
		} else {
			$pages = include $file;
			if(!$pages) {
				echo 'EMPTY';
			} else {
				
			}
		}
		output();
		break;
	default:
		if($_POST['dosubmit']) {
			if(isset($_POST['setting']['mail_stmp_password']) && $_POST['setting']['mail_stmp_password'] == '******') {
				unset($_POST['setting']['mail_stmp_password']);
			}

			foreach(array('jsaccess','ban_ip','citypath_without','rewrite_moduleflag') as $v) {
				if(isset($_POST['setting'][$v])) {
					$_POST['setting'][$v] = preg_replace("/\s*(\r\n|\n\r|\n|\r)\s*/","\r\n",$_POST['setting'][$v]);
				}
			}
			if(isset($_POST['setting']['picture_ext'])) {
				$picture_ext = $_POST['setting']['picture_ext'];
				$exts = explode(' ', $picture_ext);
				if($exts) foreach ($exts as $value) {
					if(preg_match("/(php|inc|asp|jsp|aspx|shtml|vbs|do)/i", $value)) {
						redirect('不被允许的上传格式:' . $value);
					}
				}
			}

			$MC->save($_POST['setting']);
			redirect('global_op_succeed', cpurl($module, $act, $op));
		} else {
			$admin->tplname = cptpl('setting_' . $op);
		}
}
?>