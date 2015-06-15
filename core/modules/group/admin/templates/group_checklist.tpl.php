<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">小组审核</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
			<tr class="altbg1">
				<td width="25">选</td>
                <td width="55">图标</td>
				<td width="180">小组名称</td>
                <td width="110">创建时间</td>
				<td width="*">简介</td>
                <td width="100">操作</td>
			</tr>
			<?if($total && $list):?>
			<?while($val=$list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="gids[]" value="<?=$val['gid']?>" /></td>
                <td><img src="<?=$val['icon']?$val['icon']:'static/images/z_noimg.gif'?>"width="50" /></td>
				<td>
                    <?=$val['groupname']?><br />
                    组长：<a href="<?=url("space/index/uid/$val[uid]")?>" target="_blank"><?=$val['username']?></a>
                </td>
                <td><?=date('Y-m-d H:i', $val['createtime'])?></td>
				<td><span title="<?=$val['des']?>"><?=trimmed_title($val['des'],'30','...')?></span></td>
                <td><a href="javascript:;"onclick="group_on_pass(<?=$val['gid']?>)">不通过审核</a></td>
            </tr>
			<?endwhile;?>
			<tr class="altbg1">
				<td colspan="7" class="altbg1">
					<button type="button" onclick="checkbox_checked('gids[]');" class="btn2">全选</button>
				</td>
			</tr>
			<?else:?>
			<tr>
				<td colspan="12">暂无信息。</td>
			</tr>
			<?endif;?>
        </table>
    </div>
	<?if($total):?>
    <div class="multipage"><?=$multipage?></div>
	<center>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="delete" />
        <button type="button" class="btn" onclick="easy_submit('myform','checkup','gids[]')">通过审核</button>
        <button type="button" class="btn" onclick="easy_submit('myform','delete','gids[]')">删除所选</button>
	</center>
	<?endif;?>
</form>
</div>
<script type="text/javascript">
function group_on_pass(gid) {
    if (!is_numeric(gid)) {
        alert('无效的小组ID'); 
        return;
    }
    var message = window.prompt('请输入您的审核不通过理由：');
    if(message == null) return;
    message = message.trim();
    if(!message) {
        alert('请输入您的审核不通过理由，本信息将发送给创建者。');
        return;
    }
    $.post("<?=cpurl($module,$act,'nopass')?>", 
    { 'gid':gid,'message':encodeURIComponent(message), 'in_ajax':1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result == 'OK') {
            document_reload();
        } else {
            alert('操作失败。');
        }
    });
}    
</script>