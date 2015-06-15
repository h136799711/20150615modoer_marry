<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act,$op))?>
    <div class="space">
        <div class="subtitle">模块安装</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <?foreach($newmodule as $key => $val) :?>
            <?if(in_array($key,$readonly)):?>
            <tr>
                <td class="altbg1"><strong><?=$key?></strong></td>
                <td><?=$val?></td>
                <input type="hidden" name="newmodule[<?=$key?>]" value="<?=$val?>">
            </tr>
            <?else:?>
            <tr>
                <td width="20%" class="altbg1"><strong><?=$key?></strong></td>
                <td width="*">
                    <input type="text" name="newmodule[<?=$key?>]" value="<?=$val?>" class="txtbox"  />
                </td>
            </tr>            
            <?endif;?>
            <?endforeach;?>
            <?if($need_liccode):?>
            <tr>
                <td width="20%" class="altbg1" valign="top"><strong>商业模块授权码</strong></td>
                <td width="*">
                    <textarea name="newmodule[liccode]" rows="8" cols="50"></textarea>
                </td>
            </tr>
            <?endif;?>
        </table>
        <input type="hidden" name="step" value="2" />
        <input type="hidden" name="newmodule[directory]" value="<?=$_POST['directory']?>" />
        <center>
            <button type="button" class="btn" onclick="history.go(-1);">上一步</button>&nbsp;
            <button type="submit" class="btn" value="modulessubmit">安装模块</button>
        </center>
    </div>
<?=form_end()?>
</div>