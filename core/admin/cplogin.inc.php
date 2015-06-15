<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$_G['charset']?>" />
<meta http-equiv="x-ua-compatible" content="ie=11" />
<title><?=lang('admincp_title')?></title>
<link rel="stylesheet" type="text/css" href="./static/images/admin/login.css" />
<script type="text/javascript">
var siteurl = "<?=$_config['siteurl']?>";
if(self.parent.frames.length != 0) {
	self.parent.location = document.location;
}
function redirect(url) {
	window.location.replace(url);
}
</script>
<script type="text/javascript" src="./data/cachefiles/config.js"></script>
<script type="text/javascript" src="./static/javascript/jquery.js"></script>
<script type="text/javascript" src="./static/javascript/common.js"></script>
</head>
<body>
    <div class="panel">
        <div class="panel-heading">
            <h1><?=lang('admincp_caption')?></h1>
        </div>
        <div class="panel-body">
            <form method="post" action="<?=SELF?>?" name="login">
            <input type="hidden" name="url_forward" value="<?=$url_forward?>" />
            <div class="form-item">
                <label for="admin_name"><?=lang('admincp_tpl_login_user')?></label>
                <?if($admin->isLogin):?>
                <div class="loginname">
                    <input type="hidden" name="admin_name" value="<?=$admin->adminname?>" />
                    <?=$admin->adminname?>
                    <a href="<?=SELF?>?logout=yes"><?=lang('admincp_nav_logout')?></a>
                </div>
                <?else:?>
                <input type="text" name="admin_name" id="admin_name" />
                <?endif;?>
            </div>
            <div class="form-item">
                <label for="admin_pw"><?=lang('admincp_tpl_login_pw')?></label>
                <input type="password" name="admin_pw" id="admin_pw" />
            </div>
            <?if(S('console_seccode')) :?>
            <div class="form-item">
                <label for="seccode">验证码</label>
                <input type="text" name="seccode" onfocus="show_seccode();" />
                <div id="seccode"></div>
                <script>
                $(function() {
                    show_seccode();
                });
                </script>
            </div>
            <?endif;?>
            <div class="form-item">
                <button type="submit" name="loginsubmit" class="button" value="Y"><?=lang('admincp_tpl_login_btn')?></button>
            </div>
            </form>
        </div>
    </div>
    <div class="powered">
        Powered by <a href="http://www.modoer.com/" target="_blank"><b>Modoer</b></a>
        &nbsp;&copy;&nbsp;2007-2014&nbsp;
        <a href="http://www.moufer.cn/" target="_blank">Moufer Studio</a>
    </div>
</body>
</html>