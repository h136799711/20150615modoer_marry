<?php
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

if($_GET['op'] == 'switch') {

    $siteurl = $_CFG['siteurl'];
    if(substr($siteurl,-1)=='/') $siteurl = substr($siteurl, 0, -1);

    $cityid = _get('cityid', 0, MF_INT_KEY);
    if(!in_array($cityid,$admin->mycitys)) redirect('您没有权限管理城市：'.display('modoer:area', "aid/$cityid"));
    $city = $_G['loader']->model('area')->read($cityid);
    if(!$city) redirect('您准备切换的城市不存在，请返回。');

    init_city($cityid);
    redirect('正在为您切换到城市:'.display('modoer:area', "aid/$cityid").'，请稍等...', $siteurl . SELF);
}

$content = '<div class="cpmanagecitys">';
if(!$_G['admin']->is_founder) {
    $content .= '<ul>';
    foreach ($admin->mycitys as $cityid) {
        if($cityid<1) continue;
        if($cityid == $_CITY['aid']) {
            $content .= "<li><b style='color:red;'>".display('modoer:area',"aid/$cityid")."</b></li>";
        } else {
            $content .= "<li><a href='".cpurl("modoer","cpmanagecitys","switch",array('cityid'=>$cityid))."'>".display('modoer:area',"aid/$cityid")."</a></li>"; 
        }
    }
    $content .= '</ul><div class="clear"></div>';
}

$content .= '</div>';
echo $content;
output();
?>