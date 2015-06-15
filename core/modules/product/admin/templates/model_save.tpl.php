<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle"><?=$subtitle?></div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="45%" class="altbg1"><strong>模型名称：</strong></td>
                <td width="55%"><input type="text" name="t_model[name]" class="txtbox2" value="<?=$t_model['name']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>附加数据表:</strong></td>
                <td>
                    <?if($disabled){?>
                        dbpre_<?=$t_model['tablename']?>
                    <? }else{?>
                        dbpre_product_data
                    <?}?>
                </td>
            </tr>
        </table>
        <center>
            <?if($modelid>0){?><input type="hidden" name="modelid" value="<?=$modelid?>" /><?}?>
            <button type="submit" name="dosubmit" value="yes" class="btn"> 提 交 </button>&nbsp;
            <button type="button" class="btn" onclick="history.go(-1);"> 返 回 </button>
        </center>
    </div>
</form>
</div>