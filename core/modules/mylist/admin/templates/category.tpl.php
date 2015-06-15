<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>" name="myform">
	<div class="space">
		<div class="subtitle">分类管理</div>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
				<td width="60">ID</td>
                <td width="80">排序</td>
                <td width="200">名称</td>
				<td width="*">操作</td>
			</tr>
            <?if($total):?>
            <?while($val=$list->fetch_array()) {?>
			<tr>
                <td><input type="checkbox" name="catids[]" value="<?=$val['catid']?>" /></td>
                <td><input type="text" name="category[<?=$val['catid']?>][listorder]" value="<?=$val['listorder']?>" class="txtbox5 width" /></td>
                <td><input type="text" name="category[<?=$val['catid']?>][name]" value="<?=$val['name']?>" class="txtbox2" /></td>
                <td><a href="<?=cpurl($module,'category','delete',array('catid'=>$val['catid']))?>" onclick="return submit_delete();">删除</a></td>
            </tr>
            <?}?>
            <?else:?>
            <tr>
                <td colspan="7">暂无信息</td>
            </tr>
            <?endif;?>
            <tr class="altbg1">
                <td>增加</td>
                <td><input type="text" name="newcategory[listorder]" class="txtbox5 width" /></td>
                <td colspan="4"><input type="text" name="newcategory[name]" class="txtbox2" />&nbsp;
                <button type="button" class="btn2" onclick="easy_submit('myform','add',null);">添加</button></td>
            </tr>
		</table>
	</div>
    <div><?=$multipage?></div>
    <center>
        <input type="hidden" name="op" value="update" />
        <input type="hidden" name="dosubmit" value="yes" />
        <?if($total):?>
        <button type="button" class="btn" onclick="easy_submit('myform','update',null);">更新操作</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','delete','catids[]');">删除所选</button>&nbsp;
        <?endif;?>
    </center>
</form>
</div>
<script type="text/javascript">
function submit_delete () {
    var ok = '';
    var msg = "\r\n请在输入框内输入“OK”（大写，不包含引号）" 
    ok = prompt('您确定要删除吗，本次操作将删除本分类下的所有榜单！'+msg);
    return ok=='OK';
}    
</script>