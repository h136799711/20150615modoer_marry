<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act,'save'),'post','myform','multipart/form-data')?>
    <div class="space">
        <div class="subtitle">添加/编辑空间主题</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1" width="120">名称:</td>
                <td width="*"><input type="text" name="name" class="txtbox" value="<?=$detail['name']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">排序:</td>
                <td><input type="text" name="listorder" class="txtbox4" value="<?=$detail['listorder']?$detail['listorder']:'0'?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">CSS:</td>
                <td><input type="text" name="css" class="txtbox" value="<?=$detail['css']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">封面:</td>
                <td><input type="text" name="thumb" class="txtbox" value="<?=$detail['thumb']?>" />
                上传：<input type="file" name="picture" />
                </td>
            </tr>
            <tr>
                <td class="altbg1">描述:</td>
                <td><textarea name="des" class="txtbox"><?=$detail['des']?></textarea></td>
            </tr>
            <tr>
                <td class="altbg1">状态:</td>
                <td><?=form_radio('status',array('停用','启用'),isset($detail['status'])?$detail['status']:1)?></td>
            </tr>
        </table>
    </div>
    <center>
        <input type="hidden" name="do" value="<?=$op?>" />
        <?if($op=='edit'):?>
        <input type="hidden" name="id" value="<?=$detail['id']?>" />
        <?endif;?>
        <input type="hidden" name="forward" value="<?=get_forward()?>" />
        <?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?>&nbsp;
        <?=form_button_return(lang('admincp_return'),'btn')?>
    </center>
<?=form_end()?>
</div>