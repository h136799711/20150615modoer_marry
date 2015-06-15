<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

//html5 ajax上传
$fn = (isset($_SERVER['HTTP_AJAXUPLOADFILENAME']) ? $_SERVER['HTTP_AJAXUPLOADFILENAME'] : false);
if ($fn) {
    $AU = new ms_ajaxupload('ajaxuploadfilename', 'temp');
    $AU->setMaxSize(S('picture_upload_size'));
    $AU->setFileExt(S('picture_ext'));
    $AU->setImageFile();
    if($AU->startUpload()) {
        //图片尺寸限制
        $IMG = new ms_image();
        $IMG->set_thumb_level(S('picture_createthumb_level'));
        $IMG->set_thumb_mod(1);//S('picture_createthumb_mod')
        $IMG->auto_resize(MUDDER_ROOT . $AU->getFileName(), S('picture_max_width'), S('picture_max_height'));
        $result = array('code' => 200,'filename' => str_replace(DS, '/' , $AU->getFileName()));
        echo json_encode($result);
    } else {
        redirect('没有文件被上传。');
    }
    output();
} else if(!empty($_FILES['picture']['name'])) {

    $_G['loader']->lib('upload_image', NULL, FALSE);
    $img = new ms_upload_image('picture', S('picture_ext'));
    $img->set_max_size(S('picture_upload_size'));
    $img->set_ext(S('picture_ext'));
    $img->upload('temp', null);
    $picture = str_replace(DS, '/', $img->path . '/' . $img->filename);
    if($_POST['in_iframe']) {
        echo fetch_iframe($picture, $_POST['set_domain']!='N');
    } else {
        echo $picture;
    }
    exit;

} elseif(!empty($_FILES['voice_file']['name'])) {

    $_G['loader']->lib('upload_file', NULL, FALSE);
    $UP = new ms_upload_file('voice_file');
    $UP->set_ext('mp3 ogg');
    $UP->upload('temp', null);
    $file = str_replace(DS, '/', $UP->path . '/' . $UP->filename);
    if($_POST['in_iframe']) {
        echo fetch_iframe($file, $_POST['set_domain']!='N');
    } else {
        echo $file;
    }
    exit;

} else {

    redirect('没有文件被上传。');

}
?>