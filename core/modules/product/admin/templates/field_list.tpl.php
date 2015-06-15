<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'update')?>">
    <div class="space">
        <div class="subtitle">字段管理</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="60">排序</td>
                <td width="90">字段标题</td>
                <td width="*">字段名</td>
                <td width="100">字段类型</td>
                <td width="40"><center>显示</center></td>
                <td width="40"><center>后台</center></td>
                <td width="100">操作</td>
            </tr>
            <?if($result) { foreach($result as $val) {?>
            <tr>
                <td><input type="text" name="neworder[<?=$val['fieldid']?>]" value="<?=$val['listorder']?>" class="txtbox5" /></td>
                <td><?=$val['title']?></td>
                <td><?=$val['fieldname']?></td>
                <td><?=$F->fieldtypes[$val['type']]?></td>
                <td><center><?=$val['show_detail']?'√':'×'?></center></td>
                <td><center><?=$val['isadminfield']?'√':'×'?></center></td>
                <td>
                    <a href="<?=cpurl($module,'field_edit','edit',array('fieldid'=>$val['fieldid']))?>">编辑</a>
                    <?if(!$val['iscore']) { $disable = $val['disable'] ? 'enable' : 'disable'; ?>
                    <a href="<?=cpurl($module,'field_edit',$disable,array('modelid'=>$modelid,'fieldid'=>$val['fieldid']))?>"><?=$val['disable']?'启用':'禁用'?></a>
                    <a href="<?=cpurl($module,'field_edit','delete',array('modelid'=>$modelid,'fieldid'=>$val['fieldid']))?>" onclick="return confirm('您确定要进行删除操作吗？');">删除</a>
                    <?}?>
                </td>
            </tr>
            <?}?>
            <?} else {?>
            <tr><td colspan="9">暂无信息。</td></tr>
            <?}?>
        </table>
        <center>
            <input type="hidden" name="modelid" value="<?=$modelid?>">
            <?if($result) {?>
            <button type="submit" class="btn" name="dosubmit" value="yes">更新排序</button>&nbsp;
            <?}?>
            <button type="button" class="btn" onclick="jslocation('<?=cpurl($module,'field_edit','add',array('modelid'=>$modelid))?>')">新增字段</button>&nbsp;
            <button type="button" class="btn" onclick="jslocation('<?=cpurl($module,'model_list')?>')" /><?=lang('global_return')?></button>
        </center>
    </div>
</form>
</div>