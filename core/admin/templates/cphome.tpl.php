<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');?>
<script type="text/javascript" src="./static/javascript/mdialog.js"></script>
<div id="body">
    <?if($_G['admin']->is_founder):?>
    <div class="space" id="message" style="display:none;">
        <div class="subtitle"><?=lang('admincp_cphome_system_msg_title')?></div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <?if($install_exists):?>
            <tr><td><span class="font_4"><?=lang('admincp_cphome_system_msg_install_exists')?></span></td></tr>
            <?endif;?>
            <?if(DEBUG):?>
            <tr><td>
            <span class="font_4"><?=lang('admincp_cphome_system_msg_debug_1')?></span><br />
            <span class="font_2"><?=lang('admincp_cphome_system_msg_debug_2')?></span>
            </td></tr>
            <?endif;?>
            <?if($_G['modify_template']):?>
            <tr><td>
            <span class="font_4"><?=lang('admincp_cphome_system_msg_template_1')?></span><br />
            <span class="font_2"><?=lang('admincp_cphome_system_msg_template_2')?></span>
            </td></tr>
            <?endif;?>
            <?if(!$server['gd']):?>
            <tr><td><span class="font_4"><?=lang('admincp_cphome_system_msg_gd')?></span></td></tr>
            <?endif;?>
            <?if(!check_browser()):?>
            <tr><td>
            <span class="font_4"><?=lang('admincp_cphome_system_msg_browser_1')?></span><br />
            <span class="font_2"><?=lang('admincp_cphome_system_msg_browser_2')?></span>
            </td></tr>
            <?endif;?>
        </table>
    </div>
    <?endif;?>
    <?if($sessions):?>
    <div class="space">
        <div class="subtitle"><?=lang('admincp_cphome_cpuser_title')?></div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
                <td width="30%"><?=lang('admincp_cphome_cpuser_name')?></td>
                <td width="40%"><?=lang('admincp_cphome_cpuser_ipaddress')?></td>
                <td width="30%"><?=lang('admincp_cphome_cpuser_lasttime')?></td>
            </tr>
            <?php while($val = $sessions->fetch_array()) { ?>
            <?php if($val['adminname']==$admin->adminname){?><tr style="font-weight:bold;"><?}else{?><tr><?}?>
                <td><?=$val['adminname']?></td>
                <td><?=$val['ipaddress']?></td>
                <td><?=date('Y-m-d H:i:s', $val['lasttime'])?></td>
            </tr>
            <? } ?>
        </table>
    </div>
    <?endif;?>
    <? if(!$licinfo || $licinfo < 0 || !$licinfo['l'] || !$_G['siteinfo']['d']): ?>
    <div class="space">
        <div class="subtitle"><?=lang('admincp_cphome_cpuser_lic_enter')?></div>
        <form method="post" action="<?=cpurl('modoer','license','post')?>">
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr><td><p class="font_4" style="line-height:150%;"><?=lang('admincp_cphome_cpuser_lic_invalid', MUDDER_DOMAIN)?></p></td></tr>
			<tr><td><textarea name="AF7B41" style="width:99%;height:50px;"></textarea></td></tr>
            <tr>
                <td>
                    <button type="submit" class="btn" name="dosubmit"><?=lang('admincp_cphome_cpuser_lic_submit')?></button>&nbsp;
                    <button type="button" class="btn" onclick="window.open('http://bbs.modoer.com/plugin.php?id=modoer:mymodule&op=license');"><?=lang('admincp_cphome_cpuser_lic_get')?></button>
                    <button type="button" class="btn" onclick="window.open('http://shop.modoer.com');"><?=lang('admincp_cphome_cpuser_lic_buy')?></button>
                </td>
            </tr>
        </table>
        </form>
    </div>
    <?endif;?>
    <?if($_G['admin']->is_founder||!$_CFG['console_hide_system_notice']):?>
    <div class="space">
        <div class="subtitle">System Notice</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td><iframe src="http://www.modoer.com/notice.php" height="150" width="100%" frameborder="0" scrolling="no"></iframe></td>
            </tr>
        </table>
    </div>
    <?endif;?>
    <?if($_G['cfg']['console_total']):?>
    <div class="space">
        <div class="subtitle"><?=lang('admincp_cphome_cpuser_data_total')?></div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
          <?if($system):foreach($system as $key => $val):?>
          <?if($key%3==0):?><tr><?endif;?>
            <td width="100" class="altbg1"><?=$val['name']?></td>
            <td width="200"><?=$val['content']?></td>
          <?if($key%3==2):?></tr><?endif;?>
          <?endforeach;?>
          <?php
            $ix=ceil(($key+1)/3) * 3 - ($key+1);
            for($i=0;$i<$ix;$i++) {
                echo '<td width="100" class="altbg1">&nbsp;</td>';
                echo '<td width="200">&nbsp;</td>';
            }
            echo '</tr>';
          ?>
          <?else:?>
            <tr><td><?=lang('admincp_cphome_cpuser_data_nothing')?></td></tr>
          <?endif;?>
        </table>
    </div>
    <?endif;?>
    <?if($_G['admin']->is_founder||!$_CFG['console_hide_systeminfo']):?>
    <?if($licinfo['l']):?>
    <div class="space">
        <div class="subtitle"><?=lang('admincp_cphome_cpuser_lic_info_title')?></div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="200" class="altbg1"><?=lang('admincp_cphome_cpuser_lic_domain')?>:</td>
                <td width="*"><?=$_G['siteinfo']['d']?></td>
                <td width="200" class="altbg1"><?=lang('admincp_cphome_cpuser_lic_version')?>:</td>
                <td width="*"><?=lang('admincp_sell_version_name_'.$_G['siteinfo']['v'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><?=lang('admincp_cphome_cpuser_lic_lifetime')?>:</td>
                <td><?=date('Y-m-d',$_G['siteinfo']['s']+$_G['siteinfo']['e']*3600*24)?></td>
                <td class="altbg1"><?=lang('admincp_cphome_cpuser_lic_upguardtime')?>:</td>
                <td><?=date('Y-m-d',$_G['siteinfo']['u'])?></td>
            </tr>
        </table>
    </div>
    <?endif;?>
    <div class="space">
        <div class="subtitle"><?=lang('admincp_cphome_cpuser_system_info_title')?></div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr id="trNewversion" style="display:none;"><td colspan="2">---</td></tr>
            <tr><td class="altbg1"><?=lang('admincp_cphome_cpuser_system_info_version')?>:</td>
                <td><?=$_G['modoer']['version']?> Build <?=$_G['modoer']['build']?>
                [&nbsp;<a href="http://www.modoer.com/checkver.php?v=<?=urlencode($_G['modoer']['version'].'.'.$_G['modoer']['build'])?>" target="_blank"><?=lang('admincp_cphome_cpuser_system_info_newserverurl')?></a>&nbsp;]
                <span id="mo_user"></span></td>
            </tr>
            <tr><td class="altbg1"><?=lang('admincp_cphome_cpuser_system_info_charset')?>:</td><td><?=$_G['charset']?></td></tr>
            <tr><td class="altbg1"><?=lang('admincp_cphome_cpuser_system_info_server')?>:</td><td><?=$server['software']?></td></tr>
            <tr><td class="altbg1"><?=lang('admincp_cphome_cpuser_system_info_php')?>:</td>
                <td><?=$server['phpver']?>&nbsp;<?if(substr($server['phpver'],0,1)<5)echo'(<span class="font_1">'.lang('admincp_cphome_cpuser_system_info_php_msg').'</span>)'?></td>
            </tr>
            <tr><td class="altbg1"><?=lang('admincp_cphome_cpuser_system_info_mysql')?></td>
                <td><?=$server['mysqlver']?>&nbsp;<?if(substr($server['mysqlver'],0,1)<5)echo'(<span class="font_1">'.lang('admincp_cphome_cpuser_system_info_mysql_msg').'</span>)'?></td></tr>
            <tr><td width="200" class="altbg1"><?=lang('admincp_cphome_cpuser_system_info_time')?>:</td><td><?=$server['time']?></td></tr>
            <tr><td class="altbg1"><?=lang('admincp_cphome_cpuser_system_info_upfile')?>:</td><td><?=$server['upfile']?></td></tr>
            <tr><td class="altbg1"><?=lang('admincp_cphome_cpuser_system_info_register_globals')?>:</td><td><?=ini_get('register_globals') ? 'On' : 'Off'?>(<?=lang('admincp_cphome_cpuser_system_info_register_globals_msg')?>)</td></tr>
            <tr><td class="altbg1"><?=lang('admincp_cphome_cpuser_system_info_safe_mode')?>:</td><td><?=ini_get('safe_mode') ? 'On' : 'Off'?></td></tr>
            <tr><td class="altbg1"><?=lang('admincp_cphome_cpuser_system_info_gd')?>:</td><td><?=$server['gd']?></td></tr>
            <tr><td class="altbg1"><?=lang('admincp_cphome_cpuser_system_info_magic_quotes_gpc')?>:</td><td><?=ini_get('magic_quotes_gpc') ? 'On' : 'Off'?></td></tr>
            <tr><td class="altbg1"><?=lang('admincp_cphome_cpuser_system_info_urlopen')?>:</td><td><?php echo ini_get("allow_url_fopen") ? 'On' : 'Off'?>(<?=lang('admincp_cphome_cpuser_system_info_urlopen_msg')?>)</td></tr>
            <tr><td class="altbg1"><?=lang('admincp_cphome_cpuser_system_info_ram')?>:</td><td><?=$server['memory']?> KB</td></tr>
        </table>
    </div>
    <?endif;?>
</div>
<script type="text/javascript">
$(document).ready(function() {
    if($("#message table tr").length > 0) {
        $('#message').css('display','');
    }
});
</script>
<?php
function check_browser() {
    $agent = $_SERVER['HTTP_USER_AGENT'];
    if(strpos($agent,"MSIE 9.0"))  
      return FALSE;
    else if(strpos($agent,"MSIE 8.0"))  
      return FALSE;
    else if(strpos($agent,"MSIE 7.0")) 
      return FALSE;
    else if(strpos($agent,"MSIE 6.0")) 
      return FALSE;
    else
      return TRUE;
}
?>