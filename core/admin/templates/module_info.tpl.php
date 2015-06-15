<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act,$op))?>
    <div class="space">
        <div class="subtitle">模块信息</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="20%" class="altbg1">模块名</td>
                <td width="*">
                    <input type="text" name="moduleinfo[name]" value="<?=$moduleinfo['name']?>" class="txtbox2" />
                </td>
            </tr>
            <tr>
                <td  class="altbg1">版本号</td>
                <td style="padding:5px;"><?=$moduleinfo['version']?>(<?=$moduleinfo['releasetime']?>)</td>
            </tr>
            <tr>
                <td  class="altbg1">开发人员</td>
                <td style="padding:5px;"><?=$moduleinfo['author']?>(<?=$moduleinfo['email']?>)</td>
            </tr>
            <tr>
                <td  class="altbg1">版权所有</td>
                <td style="padding:5px;"><?=$moduleinfo['copyright']?>(<?=$moduleinfo['siteurl']?>)</td>
            </tr>
            <?if($moduleinfo['liccode']):?>
            <tr>
                <td  class="altbg1">授权信息</td>
                <td style="padding:5px;">
                    域名：<?=$licinfo['d']?>；
                    有效期：<?=date('Y-m-d', $licinfo['s'])?> ~ <?=$licinfo['e']>=3650?'永久':date('Y-m-d',$licinfo['s']+$licinfo['e']*86400)?>
                </td>
            </tr>
            <?endif;?>
        </table>
        <center>
            <input type="hidden" name="moduleinfo[flag]" value="<?=$moduleinfo['flag']?>" />
            <input type="hidden" name="moduleinfo[moduleid]" value="<?=$moduleinfo['moduleid']?>" />
            <button type="submit" name="dosubmit" class="btn" value="yes"><?=lang('global_submit')?></button>
        </center>
    </div>
</form>
</div>