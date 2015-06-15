<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$_G['charset']?>">
<meta http-equiv="x-ua-compatible" content="ie=edge" />
<title><?=lang('admincp_title')?></title>
<script type="text/javascript" src="./static/javascript/jquery.js?<?=$_G['modoer']['build']?>"></script>
<script type="text/javascript" src="./static/javascript/jquery.plugin.js?<?=$_G['modoer']['build']?>"></script>
<script type="text/javascript" src="./static/javascript/admin.js?<?=$_G['modoer']['build']?>"></script>
<script type="text/javascript" src="./static/javascript/mdialog.js?<?=$_G['modoer']['build']?>"></script>
<link rel="stylesheet" type="text/css" href="./static/images/mdialog.css?<?=$_G['modoer']['build']?>">
<link rel="stylesheet" type="text/css" href="./static/images/admin/admin.css?<?=$_G['modoer']['build']?>">
<link rel="stylesheet" type="text/css" href="./static/images/icons.css?<?=$_G['modoer']['build']?>">
</head>
<body style="overflow:hidden">
<div class="cpbox">
    <div class="cpheader">
        <div class="head-main">
            <div class="head-menu" id="head_menu">
                <div class="head-menu-heading"><h2><?=lang('admincp_caption')?></h2></div>
                <?php include MUDDER_ADMIN . 'cpheader.inc.php';?>
            </div>
            <div class="head-user">
                <a href="#" id="header_user"><img src="./static/images/admin/images/face.jpg"/></a>
            </div>
            <div class="head-links none" id="head_links">
                <div class="head-links-item head-links-user">
                    <p>
                        欢迎使用控制中心！<br />
                        <b><?=$admin->adminname?></b>
                    </p>
                </div>
                <a href="<?=cpurl('modoer','admin','edit',array('adminid'=>$admin->adminid))?>" data-target="cpcontent" class="head-links-item">
                    <i><?=$admin->is_founder?'站长':'城市管理员'?></i>
                    更改密码
                </a>
                <?if(!$admin->is_founder):?>
                <a href="javascript:void(0);" onclick="admincp_select_manage_citys();" class="head-links-item">
                    <i><?=display('modoer:area', "aid/$_CITY[aid]")?></i>
                    切换城市
                </a>
                <?endif;?>
                <a href="index.php" target="_blank" class="head-links-item">
                    <i class="icon"><span class="icomoon icon-arrow-right2"></span></i>
                    网站首页
                </a>
                <a href="<?=SELF?>" class="head-links-item">
                    <i class="icon"><span class="icomoon icon-arrow-right2"></span></i>
                    后台首页
                </a>
                <a href="javascript:void(0);" onclick="admincp_show_map();" class="head-links-item">
                    <i class="icon"><span class="icomoon icon-arrow-right2"></span></i>
                    后台地图
                </a>
                <a href="<?=SELF?>?logout=yes" class="head-links-item">
                    <i class="icon"><span class="icomoon icon-arrow-right2"></span></i>
                    登出后台
                </a>
            </div>
        </div>
    </div>
    <div class="cpbody" id="cpbody">
        <div class="cpleft">
            <div id="left-menu-container">
                <div class="cpbodymenu" id="left_menu"></div>
            </div>
        </div>
        <div class="cpcontent" id="cpcontent" style="position:relative; overflow:hidden;">
            <div class="navpath"></div>
            <iframe id="cpiframe" name="cpiframe" width="100%" height="600" scrolling="auto" frameborder="false" allowtransparency="true"
            src="<?=cpurl('modoer','cphome')?>" style="border: medium none; margin-bottom: 0px;overflow: hidden;"></iframe>
        </div>
    </div>
</div>
<script type="text/javascript">
var IN_ADMIN = true;

$(document).ready(function() {

    $(document).keydown(resetEscAndF5);

    $("#cpiframe").load(admincp_page_resize); 
    $(window).resize(admincp_page_resize);

    $('#head_links > [data-target="cpcontent"]').click(function(e) {
        e.preventDefault();
        $('#cpcontent > iframe').attr('src', $(this).attr('href'));
    });

    $('#head_links').mouseover(function() {
        $(this).show();
    });

    $('#head_links').mouseout(function() {
        $(this).hide();
    });

    $('#header_user').mouseover(function(event) {
        $('#head_links').show();
    });

    $('#head_menu').delegate('a.head-menu-item', 'click', function(e) {
        e.preventDefault();
        open_left_menu($(this).data('key'), $(this).attr('href'));
    });

    $('#left_menu').delegate('a.left-menu-item', 'click', function(e) {
        e.preventDefault();
        $('#cpiframe').attr('src', $(this).attr('href'));
        $('#left_menu').find('a.left-menu-item').removeClass('selected');
        $(this).addClass('selected');
    });

    open_left_menu('home','<?=cpurl('modoer','cpmenu','',array('tab'=>'home'))?>');

});

function admincp_page_resize() {
    var iframe = $("#cpiframe");
    var h = $(window).height() - iframe.offset().top;
    var w = $(window).width() - 160;
    iframe.attr('height', h);
    $('div.cpleft').height($(window).height()-$('div.cpheader').height());
    //console.debug($(window).width()+','+$(window).height());
}

//取菜单
function open_left_menu(key, url) {
    $('#head_menu > a').removeClass('selected');
    url += '&_page_param_rand=' + Math.random();
    $('#left_menu').load(url);
    $('#head_menu > a[data-key="'+key+'"]').addClass('selected');
    if(key=='home') {
        open_right_content('<?=cpurl('modoer','cphome')?>');
    }
}
//打开管理内页
function open_right_content(url) {
    $('#cpiframe').attr('src', url);
}

var admin_cp_menu_dialog = null;
function admincp_show_map() {
    $.post("?module=modoer&act=cpmap", { 'in_ajax':1 }, function(result) {
        if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            admin_cp_menu_dialog = new $.mdialog({ 'title':'后台地图', 'body':result, 'width':800});
        }
    });
}

function admincp_select_manage_citys() {
    $.post("?module=modoer&act=cpmanagecitys", { 'in_ajax':1 }, function(result) {
        if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            admin_cp_menu_dialog = new $.mdialog({ 'title':'管理城市切换', 'body':result, 'width':500});
        }
    });
}

function admincp_click_menu_link(id, url) {
    var tabid = 'head_' + id;
    if(!$('#' + tabid)[0]) {
        tabid = 'head_module';
    }
    $('#' + tabid + ' a').click();
    $('#cpcontent > iframe').attr('src', url);
    admin_cp_menu_dialog.close();
}

</script>
</body>
</html>
