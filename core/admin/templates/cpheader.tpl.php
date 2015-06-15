<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$_G['charset']?>">
<script type="text/javascript" src="./static/javascript/jquery.js?<?=$_G['modoer']['build']?>"></script>
<?if($js):?>
<script type="text/javascript" src="./data/cachefiles/config.js?<?=S('js_cache_flag')?>"></script>
<script type="text/javascript" src="./static/javascript/jquery.plugin.js?<?=$_G['modoer']['build']?>"></script>
<script type="text/javascript" src="./static/javascript/common.js?<?=$_G['modoer']['build']?>"></script>
<script type="text/javascript" src="./static/javascript/admin.js?<?=$_G['modoer']['build']?>"></script>
<script type="text/javascript" src="./static/javascript/mdialog.js?<?=$_G['modoer']['build']?>"></script>
<link rel="stylesheet" type="text/css" href="./static/images/mdialog.css?<?=$_G['modoer']['build']?>">
<script type="text/javascript">
var IN_ADMIN = true;
$(document).ready(function() {
	$(document).keydown(resetEscAndF5);
});
</script>
<?endif;?>
<link rel="stylesheet" type="text/css" href="./static/images/icons.css?<?=$_G['modoer']['build']?>">
<link rel="stylesheet" type="text/css" href="./static/images/admin/admin.css?<?=$_G['modoer']['build']?>">
</head>
<body>