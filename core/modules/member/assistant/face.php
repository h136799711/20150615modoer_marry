<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

if($_POST['dosubmit']) {

	$upload = new ms_upload_file('face', 'jpg');
	if(!$upload->upload('temp')) {
		redirect('图片上传失败！');
	}
	$source_file = $upload->path.DS.$upload->filename;

	$face = new mc_member_face();
	$face->upload($user->uid, $source_file);

	redirect('global_op_succeed', url('member/index/ac/face'));

}