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
                <td width="45%" class="altbg1">
                    <strong>微信号：</strong>进入<a href="https://mp.weixin.qq.com" target="_blank">微信公众平台</a> - 账号信息内查看到
                </td>
                <td width="*"><?=form_input('modcfg[account]', $modcfg['account'], 'txtbox')?></td>
            </tr>
            <tr>
                <td class="altbg1">
                    <strong>公众号Token:</strong>进入<a href="https://mp.weixin.qq.com" target="_blank">微信公众平台</a>后，启用高级功能里的“开发模式”，
                    可以填写Token和URL，把您填写的Token复制填写到此处，并始终保持两边一致；URL请填写<?=S('siteurl')?>/weixin.php
                </td>
                <td width="*"><?=form_input('modcfg[token]', $modcfg['token'], 'txtbox')?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>AppID:</strong>在公众平台高级功能页面，可以找到；平台账号如果没认证则没有AppID
                </td>
                <td width="*"><?=form_input('modcfg[appid]', $modcfg['appid'], 'txtbox')?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>AppSecret:</strong>同上</td>
                <td width="*"><?=form_input('modcfg[appsecret]', $modcfg['appsecret'], 'txtbox')?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>欢迎信息:</strong>用户关注公众平台后，或者发送“Hi”给公众平台时，自动回复用户的信息。</td>
                <td width="*"><?=form_textarea('modcfg[default_message]', $modcfg['default_message'])?></td>
            </tr>
            <tr>
                <td class="altbg1">
                    <strong>指令无法时识别时，自动回复还原信息:</strong>会员用户发送的信息，系统无法识别其含义时，自动回复上面的欢迎；不使用本功能时，则不会向用户发送任何信息。
                </td>
                <td width="*"><?=form_bool('modcfg[auto_send_message]', $modcfg['auto_send_message'])?></td>
            </tr>
        </table>
        <center><button type="submit" name="dosubmit" value="yes" class="btn"> 提交 </button></center>
    </div>
</form>
</div>