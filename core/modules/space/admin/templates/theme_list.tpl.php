<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>" name="myform" >
	<div class="space">
		<div class="subtitle">音乐管理</div>
<!--        <ul class="cptab">
            <li<?=$_GET['type']=='logo'?' class="selected"':''?>><a href="<?=cpurl($module,$act,'',array('type'=>'logo'))?>">图片链接</a></li>
            <li<?=$_GET['type']=='char'?' class="selected"':''?>><a href="<?=cpurl($module,$act,'',array('type'=>'char'))?>">文字链接</a></li>
            <li<?=$op=='checklist'?' class="selected"':''?>><a href="<?=cpurl($module,$act,'checklist')?>">未审核<?if($check_count):?>(<?=$check_count?>)<?endif;?></a></li>
        </ul>-->
		<table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
            <td width="25">选</td>
                <td width="60">排序</td>
				<td width="150">封面</td>
				<td width="*">介绍</td>
				<td width="500">css</td>
				<td width="60">状态</td>
				<td width="120">操作</td>
			</tr>
            <?if($total):?>
            <?while($val=$list->fetch_array()) {?>
			<tr >
			<td><input type="checkbox" name="mids[]" value="<?=$val['id']?>" /></td>
				<td><input type="text" class="txtbox5" name="ids[<?=$val['id']?>][listorder]" value="<?=$val['listorder']?>" /></td>
				<td align="center">
				<div style="width: 80px; height:80px; overflow: hidden; border: 2px solid silver;">
					<img src="<?=$val['thumb']?>" height="80" />
				</div>
				
				</td>
				<td>名称：<?=$val['name']?> <br />
				描述：<?=$val['des']?>
				</td>
				<td><?=$val['css']?></td>
				<td><?=$val['status']?'使用':'停止'?></td>
				<td><a href="<?=cpurl($module,$act,'edit',array('id'=>$val['id']))?>">修改</a>&nbsp;<a href="<?=cpurl($module,$act,'delete',array('id'=>$val['id']))?>">删除</a></td>
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
    <button type="button" class="btn" onclick="jslocation('<?=cpurl($module,$act,'add')?>');"  >添加主题</button>&nbsp;
	<?if($total):?>
		<button type="button" class="btn" onclick="easy_submit('myform','listorder',null)">更新排序</button>&nbsp;
    <button type="button" class="btn"  onclick="easy_submit('myform','delete','mids[]');">删除所选</button>&nbsp;
    <?endif;?>
</center>
</form>
 
</div>