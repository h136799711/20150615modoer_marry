<?php


$html = file_get_contents("http://item.taobao.com/item.htm?id=39293049816");
$match = array();

$return_info = array();
$wangwang_pattern = '/<a class="tb-seller-name" (.*?)>(.*?)<\/a>/is';
//echo $html;
preg_match($wangwang_pattern, $html,$match);

//var_dump($match[2]);
$return_info['wangwang'] = $match[2];
$mainimg_pattern = '/<img id="J_ImgBooth"(.*?)data-src="(.*?)"(.*?)>/is';

preg_match($mainimg_pattern, $html,$match);
$return_info['main_img'] = $match[2];


$title_pattern = '/<h3 class="tb-main-title" data-title="(.*?)"(.*?)>/is';

preg_match($title_pattern, $html,$match);
$return_info['title'] = $match[1];

var_dump($return_info);


