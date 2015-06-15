<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<?php
    function tmp_get_tag($tagname, $name) {
        global $GT;
        if(!$tagname) return '';
        return substr($GT->field_to_string($tagname),strlen($name)+1);
    }
?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>" name="myform">
	<div class="space">
		<div class="subtitle">分类管理</div>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
				<td width="50">ID</td>
                <td width="80">排序</td>
                <td width="200">名称</td>
				<td width="*">内置标签</td>
				<td width="100">操作</td>
			</tr>
            <?if($total):?>
            <?while($val=$list->fetch_array()) {?>
			<tr>
                <td><?=$val['catid']?></td>
                <td><input type="text" name="category[<?=$val['catid']?>][listorder]" value="<?=$val['listorder']?>" class="txtbox5 width" /></td>
                <td><input type="text" name="category[<?=$val['catid']?>][name]" value="<?=$val['name']?>" class="txtbox3 width" /></td>
                <td><input type="text" name="category[<?=$val['catid']?>][tags]" value="<?=tmp_get_tag($val['tags'],$val['name'])?>" class="txtbox3 width" /></td>
                <td>
                    <a href="javascript:;" onclick="edit_category(<?=$val['catid']?>);">编辑</a>
                    <a href="<?=cpurl($module,$act,'delete',array('catid'=>$val['catid']))?>"
                        onclick="return confirm('您确定要删除吗，本次操作将删除本分类下的所有小组？');">删除</a>
                </td>
            </tr>
            <?}?>
            <?else:?>
            <tr>
                <td colspan="6">暂无信息</td>
            </tr>
            <?endif;?>
		</table>
	</div>
    <div><?=$multipage?></div>
    <center>
        <input type="hidden" name="op" value="update" />
        <input type="hidden" name="dosubmit" value="yes" />
        <button type="button" class="btn" onclick="add_category();">增加分类</button>
        <?if($total):?>
        <button type="button" class="btn" onclick="easy_submit('myform','update',null);">更新操作</button>
        <?endif;?>
    </center>
</form>
</div>
<script type="text/javascript">
function add_category() {
    get_url = "<?=cpurl($module,$act,'add')?>";
    $.post(get_url, { 'in_ajax':1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            dlgOpen('添加分类', result, 350);
        }
    });
}

function edit_category(catid){
    get_url = "<?=cpurl($module,$act,'edit')?>";
    $.post(get_url, { 'catid':catid, 'in_ajax':1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            dlgOpen('编辑分类', result, 350);
        }
    });
}
</script>