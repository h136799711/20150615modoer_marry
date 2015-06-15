<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>">
    <input type="hidden" name="classsort" value="1" />
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?> - 参数设置</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="javascript:;" onclick="tabSelect(1,'config');" onfocus="this.blur()">功能设置</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td width="45%" class="altbg1"><strong>手机网页默认模板:</strong>选择一个手机网页默认风格。</td>
                <td width="*">
                    <select name="modcfg[templateid]">
                        <option value="0">默认风格</option>
                        <?=form_template('mobile', $modcfg['templateid'])?>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="45%" class="altbg1"><strong>自动进入手机模块:</strong>手机打开系统首页时，自动进入手机模块，否则必须通过完整的地址进入(<?=url("mobile","",true)?>)</td>
                <td width="*"><?=form_bool('modcfg[auto_enter]', (bool)$modcfg['auto_enter'])?></td>
            </tr>
              <tr>
                <td class="altbg1"><strong>手机访问时自动切换到手机页面:</strong>当手机访问电脑页面时，如果当前访问的页面存在响应手机浏览页面，则自动跳转到手机浏览页面。</td>
                <td><?=form_bool('modcfg[auto_switch]', (bool)$modcfg['auto_switch'])?></td>
            </tr>
        </table>
        <center><button type="submit" name="dosubmit" value="yes" class="btn"> 提交 </button></center>
    </div>
</form>
</div>