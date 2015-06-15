<?php
!defined('IN_MUDDER') && exit('Access Denied');

if(checkSignature()){
	echo $_GET["echostr"];
	exit;
}

function checkSignature() {
    $signature = $_GET["signature"];
    $timestamp = $_GET["timestamp"];
    $nonce = $_GET["nonce"];	
    		
	$token = S('weixin.token');
	$tmpArr = array($token, $timestamp, $nonce);
	sort($tmpArr, SORT_STRING);
	$tmpStr = implode( $tmpArr );
	$tmpStr = sha1( $tmpStr );
	if( $tmpStr == $signature ){
		return true;
	}else{
		return false;
	}
}
?>