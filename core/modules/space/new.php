<?php
/**
* @author moufer<moufer@163.com>
* @pageage space
* @copyright Moufer Studio(www.modoer.com)
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'space_index');

$SN =& $_G['loader']->model('space:new');
$do = trim(_T($_GET['do']));
$pageoffset = 6;

if(in_array($do,array('set_domain','sendsms','get_invite','invite','set_story','update','upload_img'))){//json
	define('IN_JSON_AJAX', TRUE);
}
switch($do){
	case 'set_domain':
	if($user->isLogin){
				$uid = $user->uid;
	}else{
		location(url('index'));
	}
	//设置域名
	
	//保存
	$post = $SN->get_post($_POST);
	$SN->save($user->uid,$post);
	exit(json_encode(array('status'=>1001)));
	break;
	case 'sendsms':
	$phones = json_decode($_POST['data'],TRUE);
	//发送手机号
	$message = '短信内容';
	$_G['loader']->model('sms:factory',false);
    $sms = msm_sms_factory::create();
    $msm_cfg = $_G['loader']->variable('config','sms');
    if(!$sms || !$msm_cfg['use_api']) redirect('手机短信发送接口不可用，请联系网站管理员。');
	foreach($phones as $key => $mobile){
        $sms->send($mobile,$message);
	}
	exit(json_encode(array('status'=>1001)));
	break;
	case 'get_invite':
	$start = 0;
	$offset = 0;
	if($user->isLogin){
    	$start = get_start($_GET['page'],$pageoffset);
    	$offset = $pageoffset;
		$uid = $user->uid;
	}else{
		location(url('index'));
	}
	$SA =& $_G['loader']->model('space:attend');
	
	list($satotal , $salist) = $SA->find('*',array('uid'=>$uid),'dateline ASC', $start, $offset);
	$invites = array();
	 if($salist) while ($val = $salist->fetch_array()) {
	 	$val['is_part'] = $val['is_part']?'是':'否';
		$invites[] = $val;
	}
	//$nomore = count($invites) < $offset ? 1 : 0;
	exit(json_encode(array('status'=>1001,'data'=>$invites)));
	break;
	case 'invite':
	$uid = _post('uid',0,MF_INT_KEY);
	$M =& $_G['loader']->model('member:member');
	if(!$uid || ! $M->read($uid)) {
		 redirect(lang('global_sql_invalid_field','uid'));
	}
	$SA =& $_G['loader']->model('space:attend');
	$psot=$SA->get_post($_POST);
	$SA->save($psot);
	exit(json_encode(array('status'=>1001)));
	break;
	case 'set_story':
	if($user->isLogin){
			$uid = $user->uid;
	}else{
		location(url('index'));
	}
	$SS =& $_G['loader']->model('space:story');
	$data = json_decode($_POST['data'],TRUE);
	//保留删除
	$storyids = array();
	foreach($data as $key => $value){
		if($value['id']){
			$storyids[] = $value['id'];
		}
	}
	$SS->delete_not_in($uid,$storyids);
	
	foreach($data as $key => $value){
		if(!$value['id']) {$value['uid'] = $uid; $value['id']=null;}
		//$post = $SS->get_post($value);
		$SS->save($value,$value['id']);
	}
	
	list($sstotal , $sslist) = $SS->find('*',array('uid'=>$uid),'dateline ASC', 0, 0);
	$imgs = array();
	 if($sslist) while ($val = $sslist->fetch_array()) {
		$imgs[] = $val;
	}
	
	exit(json_encode(array('status'=>1001,'data'=>array('image'=>$imgs))));
	break;
	case 'get_story':
	$start = 0;
	$offset = 0;
	if($_GET['user_id']){
    	$start = get_start($_GET['page'],$pageoffset);
    	$offset = $pageoffset;
    	$uid = $_GET['user_id'];
	}else{
		if($user->isLogin){
				$uid = $user->uid;
		}else{
			location(url('index'));
		}
	}
	$SS =& $_G['loader']->model('space:story');
	
	list($sstotal , $sslist) = $SS->find('*',array('uid'=>$uid),'dateline ASC', $start, $offset);
	$imgs = array();
	 if($sslist) while ($val = $sslist->fetch_array()) {
		$imgs[] = $val;
	}
	$nomore = count($imgs) < $offset ? 1 : 0;
	exit(json_encode(array('status'=>1001,'data'=>array('image'=>$imgs),'nomore'=>$nomore)));
	break;
	case 'upload_img':
	
	$_G['loader']->lib('upload_image', NULL, FALSE);
    $img = new ms_upload_image('file', $_CFG['picture_ext']);
	$img->set_max_size($_CFG['picture_upload_size']);
	$img->useSizelimit = false; //不对上传图片进行最大尺寸限制
	$img->set_ext($_CFG['picture_ext']);
	if(!$img->upload('space')) {
		redirect('图片上传失败！');
	}
	$source_file = str_replace(DS, '/', $img->path . '/' . $img->filename);
	exit(json_encode(array('data'=>array('path'=>$source_file))));
	break;
	case 'update':
	if($user->isLogin){
				$uid = $user->uid;
		}else{
			location(url('index'));
		}
	if(isset($_POST['wedding_timestamp'])){
		$_POST['wedding_timestamp'] = strtotime(str_replace('.','-',$_POST['wedding_timestamp']));
	}
	$post = $SN->get_post($_POST);
	$SN->save($user->uid,$post);
	exit('{"status":1001}');
	break;
	default:
	$uid = _get('uid',0,MF_INT_KEY);
	if(!$uid) {
		if($user->isLogin){
			$uid = $user->uid;
		}else{
			location(url('index'));
		}
	}
	$setmode = FALSE;
	if($user->isLogin && $uid == $user->uid){
		$setmode = TRUE;
	}
	
	$newspace 	= $SN->read($uid);
	if(!$user->isLogin && !$newspace){
		redirect('该新人空间未开通。',url('index'));
	}elseif($setmode && !$newspace){
		if($do == 'start'){
			$newspace = array('theme'=>'','music'=>'','cover'=>'','photobg'=>'','my_name'=>'','my_des'=>'','other_id'=>0,'other_name'=>'','other_des'=>'','other_avatar'=>'','wedding_timestamp'=>'','wedding_address'=>'','wedding_map_point'=>'','hotel'=>'','domain'=>'','is_rsvp'=>0);
			$SN->save($user->uid,$newspace);
		}
	}
	$member = $_G['loader']->model(':member')->read($uid);
	if(!$member) redirect('index');
	
	//故事
	$SS =& $_G['loader']->model('space:story');
	list($sstotal,$sslist) = $SS->find('*',array('uid'=>$uid),'dateline ASC', 0, $pageoffset,1);
	//音乐
	$SM =& $_G['loader']->model('space:music');
	list($smtotal,$smlist) = $SM->find('*',array('status'=>1),'listorder ASC', 0, 0);
	if($newspace['music'])  $music = $SM->read($newspace['music']);
	//主题
	$ST =& $_G['loader']->model('space:theme');
	list($sttotal,$stlist) = $ST->find('*',array('status'=>1),'listorder ASC', 0, 0);
	if($newspace['theme']) { $theme = $ST->read($newspace['theme']);}
	else{$theme = $ST->get_first();}
	
	//载入模型的内容页模板
	include template('space_new');
	break;
}

?>