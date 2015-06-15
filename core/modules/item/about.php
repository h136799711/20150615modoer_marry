<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'item');
//实例化点评类
$S =& $_G['loader']->model('item:subject');
$sid = _get('sid', null, MF_INT_KEY);

$subject = $S->read($sid);
if(!$subject||!$subject['status']) redirect('item_empty');

define('SUBJECT_CATID', $subject['pid']);

//判断是否当前内容所属当前城市，不是则跳转
if(check_jump_city($subject['city_id'])) location(url("city:$subject[city_id]/item/about/sid/$id"));
define('SCRIPTNAV', 'item_'.$subject['pid']);

//所属分类
$category = $S->get_category($subject['catid']);
if(!$pid = $category['catid']) {
    redirect('item_cat_empty');
}
$catcfg =& $category['config'];

//关联模型ID
$modelid = $S->category['modelid'];
if(!$model = $S->get_model($modelid)) redirect('item_model_empty');
if($model['usearea']) $area = $S->loader->variable('area');

//主题管理员标记
$is_owner = $user->isLogin && $subject['owner'] == $user->username;
//全名
$fullname = $subject['name'] . ($subject['subname']?"($subject[subname])":'');
//表格生成
$subject_detailfield = $S->display_detailfield($subject);
//获取多行文本字段
$textarea_field = $S->get_textarea_field($modelid);
//前台不显示字段去除
$arrfields = $S->variable('field_' . $modelid);
//字段配置
$fields = array();
foreach($arrfields as $fd) {
    if(!$fd['show_detail'] && $fd['fieldname']=='content') unset($subject[$fd['fieldname']]);
    $fields[$fd['fieldname']] = $fd;
}

//面包屑
$urlpath = array();
$urlpath[] = url_path($fullname, url("item/detail/id/$sid"));
$urlpath[] = url_path('详情', '');

//其他模块和功能的链接
$links = $_G['hook']->hook('subject_detail_link',$subject,TRUE);
define('SUB_NAVSCRIPT','item/about');

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];

if($subject) {
    $scategory = $S->get_category($subject['catid']);
    if(!$subject['templateid'] && $scategory['config']['templateid']>0) {
        $subject['templateid'] = $scategory['config']['templateid'];
    }
}
if($subject && $subject['templateid']) {
    include template('about', 'item', $subject['templateid']);
} else {
    include template('item_subject_about');
}
?>