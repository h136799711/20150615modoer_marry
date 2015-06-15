<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$P =& $_G['loader']->model(MOD_FLAG . ':picture');

//上传权限验证
$user->check_access('item_pictures', $P);

$sid = _input('sid', 0, MF_INT_KEY);
$subject = $P->check_post_before($sid);
if(check_submit('dosubmit')) {
	if(is_array($_POST['pictures'])) {
		foreach ($_POST['pictures'] as $picture) {
			$post = array();
			$post['sid'] = _post('sid', 0, MF_INT_KEY);
			$post['albumid'] = _post('albumid', 0, MF_INT_KEY);
			$post['local_picture'] = $picture;
			$P->save($post, TRUE);
		}
		redirect('上传成功！', url("item/mobile/do/detail/id/$sid"));
	} else {
		redirect('对不起，您未上传图片。');
	}
} else {
	$pid = $subject['pid'];
	$category = $P->variable('category');
	$modelid = $category[$pid]['modelid'];
	$catcfg = $category[$pid]['config'];
	$model = $P->variable('model_' . $modelid);
}


include mobile_template('item_upload_pic');
?>