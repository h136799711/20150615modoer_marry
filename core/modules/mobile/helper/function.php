<?php
 if(defined('IN_ADMIN')) { 
 if(!defined('IN_OKNJUYGF') || !defined('IN_EWTRYTU') || !defined('IN_LICCODE')) { show_error('File Error.'); 
 exit(0); } 
 } 
 !defined('IN_MUDDER') && exit('Access Denied'); 
 function mobile_template($filename, $templateid = '', $update = false) { 
 if(!$templateid) $templateid = (int)S('mobile:templateid'); 
 return template($filename, 'mobile', $templateid, '', $update); 
 } 
 function mobile_page($count, $offset, $page, $url) { 
 static $page_obj = null; if(!$page_obj) { 
 $page_obj = new mc_mobile_page; 
 } 
 $page_obj->count = $count; 
 $page_obj->offset = $offset; 
 $page_obj->page = $page; 
 $page_obj->url = $url; 
 return $page_obj->create(); 
 } 
 function mobile_location($url) { 
 if(!$url) return; 
 $url = str_replace('&amp;', '&', $url); 
 include mobile_template('jslocation'); 
 exit; 
 }
 ?>