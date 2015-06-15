<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<?if(DEBUG==FALSE && $url_forward) {?>
<script type="text/javascript">
window.onload = function() {
    setTimeout(do_location, 2000);
}
function do_location() {
    document.location.href = '<?=$url_forward?>';
}
</script>
<? } ?>
<style type="text/css">
.redirect {
	position: relative;
	width: 80%;
	margin:50px auto 0;
	padding:30px;
	background-color: #FFF;
	border:1px solid #ddd;
	border-radius: 5px;
	box-shadow:0px 1px 5px #ddd;
}
.redirect-ico {
	position: absolute;
	width:100px;
	text-align: center;
}
.redirect-body {
	margin-left: 100px;
	padding-top: 5px;
}
.redirect-msg {
	line-height: 20px;
	font-size:16px;
	margin-bottom:20px;
}
.redirect-body > .forward {
	display: inline-block;
	font-size:14px;
}
.redirect-nav {
	margin-top:20px;
}
.redirect-nav > a { 
	display: inline-block;
	margin-bottom :10px;
	padding-left: 15px;
	background: url('./static/images/admin/images/arrow.gif') 0px 2px no-repeat;
}
.redirect-nav > a:last-child {
	margin-bottom:0;
}
</style>
<script type="text/javascript" src="./static/javascript/jquery.js"></script>
<div id="body">
	<div class="redirect">
		<div class="redirect-ico">
			<img src="./static/images/admin/images/information.gif" />
		</div>
		<div class="redirect-body">
			<p class="redirect-msg"><?=$message?></p>
			<?if($url_forward):?>
				<a class="forward" href="<?=$url_forward?>"><?=lang('global_message_des')?></a>
			<?endif;?>
			<?if($navs):?>
				<div class="redirect-nav">
					<?foreach($navs as $nav):?>
					<a href="<?=$nav['url']?>"><?=lang($nav['name'])?></a><br />
					<?endforeach;?>
				</div>
			<?endif;?>
		</div>
	</div>
</div>