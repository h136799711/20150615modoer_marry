<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'save')?>">
    <div class="space">
        <div class="subtitle">添加/编辑物流方式</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="45%" class="altbg1">名称：<span class="font_1">*</span></td>
                <td width="55%"><input type="text" name="shipname" class="t_input" value="<?=$detail['shipname']?>" validator="{'empty':'N','errmsg':'未完成 物流方式名称 的设置，请返回设置。'}" /></td>
            </tr>
            <tr>
                <td class="altbg1">资费：<span class="font_1">*</span></td>
                <td><input type="text" name="price" class="t_input" value="<?=$detail['price']?>" validator="{'empty':'N','errmsg':'未完成 邮费 的设置，请返回设置。'}" /> 元</td>
            </tr>
            <tr>
                <td class="altbg1">启用：</td>
                <td><input type="radio" name="enabled" value="1" id="radio_enabled_1"<?=$detail['enabled']==1?'checked="checked"':''?> />
                    <label for="radio_enabled_1">是</label>
                    <input type="radio" name="enabled" value="0" id="radio_enabled_0"<?=!$detail['enabled']?'checked="checked"':''?> />
                    <label for="radio_enabled_0">否</label>
                </td>
            </tr>
            <tr>
                <td class="altbg1">简介：</td>
                <td><textarea name="shipdesc" rows="8" cols="40" class="txtarea"><?=$detail['shipdesc']?></textarea></td>
            </tr>
        </table>
        <center>
            <input type="hidden" name="forward" value="<?=get_forward()?>" />
            <input type="hidden" name="do" value="<?=$op?>" />
            <?if($shipid):?>
                <input type="hidden" name="shipid" value="<?=$shipid?>" />
            <?else:?>
                <input type="hidden" name="sid" value="<?=$sid?>" />
            <?endif;?>
            <button type="submit" class="btn" name="dosubmit" value="yes" id="subbtn">提交</button>&nbsp;
            <button type="button" class="btn" onclick="history.go(-1);">返回</button>
        </center>
    </div>
</form>
</div>