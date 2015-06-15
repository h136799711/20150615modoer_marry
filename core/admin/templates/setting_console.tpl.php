<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<div class="sub-menu">
    <div class="sub-menu-heading">后台设置</div>
</div>
<?=form_begin(cpurl($module,$act,$op))?>
    <div class="space">
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1"><strong>后台登陆验证码:</strong>防止后台密码被猜测</td>
                <td><?=form_bool('setting[console_seccode]', $config['console_seccode'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>开启后台首页数据统计:</strong>在后台登录后首页显示的相关数据统计功能</td>
                <td><?=form_bool('setting[console_total]', $config['console_total'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>城市管理员后台不显示系统公告:</strong>非创始人登录后台系统时，后台首页不显示Modoer提供的系统补丁公告</td>
                <td><?=form_bool('setting[console_hide_system_notice]', $config['console_hide_system_notice'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>城市管理员后台不显示系统信息:</strong>非创始人登录后台系统时，后台首页不显示Modoer和系统信息</td>
                <td><?=form_bool('setting[console_hide_systeminfo]', $config['console_hide_systeminfo'])?></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>指定快捷菜单的菜单组:</strong>设置后台快捷菜单，可实现后台自定义设置快捷菜单功能；<br /><span class="font_2">不支持多层分类和模板 url 标签形式链接</span></td>
                <td width="*">
                <select name="setting[console_menuid]">
                    <option value="">==选择菜单组==</option>
                    <?=form_menu_main($config['console_menuid'])?>
                </select>
                </td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>