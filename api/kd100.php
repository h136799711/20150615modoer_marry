<?php
@define('IN_MUDDER', TRUE);
define('MUDDER_ROOT', substr(dirname(__FILE__), 0, -3));

$kuaidi_list = array(
	'anxindakuaixi'=>'安信达快递',
	'debangwuliu'=>'德邦物流',
	'coe'=>'中国东方(COE)',
	'ems' => 'EMS',
	'fedex'=>'fedex（国外）',
	'guotongkuaidi'=>'国通快递',
	'huitongkuaidi'=>'汇通快运',
	'quanfengkuaidi'=>'全峰快递',
	'jietekuaidi'=>'捷特快递',
	'jinyuekuaidi'=>'晋越快递',
	'jinguangsudikuaijian'=>'京广速递',
	'lianbangkuaidi'=>'联邦快递',
	'shentong'=>'申通快递',
	'shunfeng'=>'顺丰速递',
	'tiantian'=>'天天快递',
	'tnt'=>'TNT',
	'ups'=>'UPS',
	'yuantong'=>'圆通速递',
	'yunda'=>'韵达快运',
	'yuntongkuaidi'=>'运通快递',
	'zhongtong'=>'中通速递',
	'zhaijisong'=>'宅急送',
	'zhongtiekuaiyun'=>'中铁快运',
);

if($_GET['act'] == 'comlist') {
	include MUDDER_ROOT . 'core/helper/mxml.php';
	echo mxml::from_array($kuaidi_list);
	exit();
}

$htmlapi_list = array('ems','shentong','shunfeng');

$cfg = include MUDDER_ROOT.'./data/cachefiles/modoer_config.php';
if(!$cfg['kd100_api_key']&&!$cfg['dk100_api_key']) {
	echo '系统未设置快递查询KEY。';
	exit();
}

$typeCom = $_POST["com"];//快递公司
$typeNu = $_POST["nu"];  //快递单号

if(!$typeCom && !in_array($typeCom, $kuaidi_list)) {
	echo '未指定快递公司或不快递公司不提供快递进度查询。';
	exit();
}
if(!$typeNu) {
	echo '未指定快递运单号。';
	exit();
}

//echo $typeCom.'<br/>' ;
//echo $typeNu ;

$AppKey=$cfg['kd100_api_key']?$cfg['kd100_api_key']:$cfg['dk100_api_key'];
$url ='http://api.kuaidi100.com/api?id='.$AppKey.'&com='.$typeCom.'&nu='.$typeNu.'&show=2&muti=1&order=asc';
//请勿删除变量$powered 的信息，否者本站将不再为你提供快递接口服务。
$powered = '查询数据由：<a href="http://kuaidi100.com" target="_blank">KuaiDi100.Com （快递100）</a> 网站提供 ';
//echo $url;

//优先使用curl模式发送数据
if (function_exists('curl_init') == 1){
  $curl = curl_init();
  curl_setopt ($curl, CURLOPT_URL, $url);
  curl_setopt ($curl, CURLOPT_HEADER,0);
  curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt ($curl, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
  curl_setopt ($curl, CURLOPT_TIMEOUT,5);
  $get_content = curl_exec($curl);
  curl_close ($curl);
} else {
  include(MUDDER_ROOT . "/core/lib/snoopy.php");
  $snoopy = new ms_snoopy();
  $snoopy->referer = 'http://www.google.com/';//伪装来源
  $snoopy->fetch($url);
  $get_content = $snoopy->results;
}
print_r($get_content . '<br/>' . $powered);
exit();
?>