<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'',array('pid'=>$pid))?>">
    <div class="space">
        <div class="subtitle">主题回收站</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="25">选?</td>
                <td width="*">主题名称</td>
				<td width="120">分类名称</td>
				<td width="120">提交时间</td>
                <td width="100">登记会员</td>
				<td width="100">操作</td>
            </tr>
			<?if($total):?>
			<?while($val=$list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="sids[]" value="<?=$val['sid']?>" /></td>
                <td><?=$val['name']?>&nbsp;<?=$val['subname']?><span class="font_2">[<?=template_print('modoer','area',array('aid'=>$val['city_id']))?>]</span></td>
				<td><?=display('item:category',"catid/$val[pid]")?>&gt;<?=display('item:category',"catid/$val[catid]")?></td>
				<td><?=date('Y-m-d H:i', $val['addtime'])?></td>
                <td><?=$val['creator']?$val['creator']:'<span class="font_2">[后台添加]</span>'?></td>
				<td><a href="<?=cpurl($module,'subject_edit','',array('pid'=>$pid, 'sid'=>$val['sid']))?>">编辑</a></td>
            </tr>
			<?endwhile;?>
			<tr>
				<td colspan="6" class="altbg1">
					<button type="button" onclick="checkbox_checked('sids[]');" class="btn2">全选</button>
					<input type="checkbox" name="delete_point" id="delete_point" value="1" />
                    <label for="delete_point">删除主题同时减少登记者积分</label>
				</td>
			</tr>
			<?else:?>
			<tr>
				<td colspan="6">暂无信息。</td>
			</tr>
			<?endif;?>
        </table>
    </div>
	<?if($total):?>
    <div class="multipage"><?=$multipage?></div>
	<center>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="restore" />
		<button type="button" class="btn" onclick="easy_submit('myform','restore','sids[]')">恢复所选</button>&nbsp;
		<button type="button" class="btn" onclick="easy_submit('myform','delete','sids[]')">删除所选</button>
	</center>
	<?endif;?>
</form>
</div>