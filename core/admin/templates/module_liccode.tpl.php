<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act,$op))?>
    <div class="space">
        <div class="subtitle">输入授权码</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="20%" class="altbg1"><strong>模块名称:</strong></td>
                <td width="*">
                    <?=$detail['name']?>
                    <a href="http://bbs.modoer.com/plugin.php?id=modoer:mymodule&op=my" target="_blank"><span class="font_1">获取授权码</span></a>
                </td>
            </tr>
            <tr>
                <td width="20%" class="altbg1" valign="top"><strong>授权码:</strong></td>
                <td width="*">
                    <textarea name="liccode" rows="8" cols="50"></textarea>
                </td>
            </tr>
        </table>
        <center>
            <input type="hidden" name="flag" value="<?=$flag?>" />
            <button type="submit" name="dosubmit" class="btn" value="yes"><?=lang('global_submit')?></button>
        </center>
    </div>
</form>
</div>