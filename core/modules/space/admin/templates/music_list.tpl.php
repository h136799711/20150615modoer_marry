<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>" name="myform" enctype="multipart/form-data">
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
				<td width="150">名称</td>
                <td width="410">文件</td>
				<td width="*">描述</td>
				<td width="60">状态</td>
			</tr>
            <?if($total):?>
            <?while($val=$list->fetch_array()) {?>
			<tr >
                <td><input type="checkbox" name="mids[]" value="<?=$val['id']?>" /></td>
                <td><input type="text" name="ids[<?=$val['id']?>][listorder]" value="<?=$val['listorder']?>" class="txtbox5 width" /></td>
                <td><input type="text" name="ids[<?=$val['id']?>][name]" value="<?=$val['name']?>" class="txtbox5 width" /></td>
                <td><input type="text" name="ids[<?=$val['id']?>][src]" value="<?=$val['src']?>" class="txtbox3" />上传：<input type="file" name="m<?=$val['id']?>" style="width: 150px;" /></td>
                <td><input type="text" name="ids[<?=$val['id']?>][des]" value="<?=$val['des']?>" class="txtbox5 width" /></td>
                <td><input type="checkbox" name="ids[<?=$val['id']?>][status]" value="1" <?=$val['status']?'checked="checked"':''?> ></td>
            </tr>
            <?}?>
            <tr class="altbg1">
				<td colspan="20" class="altbg1">
					<button type="button" onclick="checkbox_checked('mids[]');" class="btn2">全选</button>&nbsp;
					<?if($total):?>
				    <button type="button" class="btn2" onclick="easy_submit('myform','update',null);">更新</button>&nbsp;
				    <button type="button" class="btn2"  onclick="easy_submit('myform','delete','mids[]');">删除所选</button>&nbsp;
				    <?endif;?>
				</td>
			</tr>
            <?else:?>
            <tr>
                <td colspan="6">暂无信息</td>
            </tr>
            <?endif;?>
            	
		</table>
	</div>
    <div><?=$multipage?></div>
        <input type="hidden" name="op" value="update" />
        <input type="hidden" name="dosubmit" value="yes" />
</form>
<form method="post" action="<?=cpurl($module,$act,'save')?>" name="musicform" enctype="multipart/form-data">
	<table class="maintable" border="0" cellspacing="0" cellpadding="0">
	    <tr>
	    	<td  width="25">&nbsp;</td>
	    	 <td  width="60"><input type="text" name="listorder" class="txtbox5 width" value="0" /></td>
	    	 <td width="150"><input type="text" name="name" class="txtbox5 width" value="" /></td>
	    	 <td  width="410"><input type="file" name="music" accept="audio/mp3" style="width: 300px;" /></td>
	    	 <td width="*"><input type="text" name="des" class="txtbox5 width" value="" />
	    	 </td>
	    	 <td width="60"><?=form_radio('status',array('停用','启用'),1)?></td>
	    </tr>
	 </table>
        <input type="hidden" name="dosubmit" value="yes" />
 </form>
 
<center>
    <button type="button" class="btn" onclick="easy_submit('musicform');" />增加链接</button>&nbsp;
    
</center>
</div>