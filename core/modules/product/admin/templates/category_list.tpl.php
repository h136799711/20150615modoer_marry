<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<style>
.intro p{margin:0;padding:0;line-height:25px;}
</style>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'update')?>" name="myform" id="myform">
    <div class="space">
        <div class="subtitle">分类管理（关联产品模型的2，3级分类才能添加产品）</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="30">ID</td>
                <td width="40">启用</td>
                <td width="60">排序</td>
                <td width="210">名称</td>
                <td width="210">页面标题</td>
                <td width="*">操作</td>
            </tr>
            <?if(!empty($catlist)) { 
            foreach($catlist as $val) {?>
            <tr>
                <td><?=$val['catid']?></td>
                <td><input type="checkbox" name="category[<?=$val['catid']?>][enabled]" value="1" <?if($val['enabled'])echo' checked="checked"';?> /></td>
                <td><input type="text" class="txtbox5" name="category[<?=$val['catid']?>][listorder]" value="<?=$val['listorder']?>" /></td>
                <td><input type="text" class="txtbox3" name="category[<?=$val['catid']?>][name]" value="<?=$val['name']?>" /></td>
                <td><input type="text" class="txtbox3" name="category[<?=$val['catid']?>][title]" value="<?=$val['title']?>" /></td>
                <td>
                    <a href="<?=cpurl($module,'category','edit',array('catid'=>$val['catid']))?>">编辑</a>&nbsp;
                    <a href="<?=cpurl($module,'category_edit','subcat',array('catid'=>$val['catid']))?>">子分类管理</a>&nbsp;
                    <a href="<?=cpurl($module,'category_edit','delete',array('catid'=>$val['catid']))?>" onclick="return confirm('您确定删除这个主分类吗？请确定这个主分类下无任何子分类存在，否则请先删除子分类。');">删除主分类</a></td>
            </tr>
            <?}?>
            <?} else {?>
            <tr><td colspan="5">暂无信息。</td></tr>
            <?}?>
        </table>
        <center>
            <?if($catlist) {?>
            <input type="hidden" name="dosubmit" value="yes" />
            <input type="hidden" name="op" value="update" />
            <button type="button" class="btn" onclick="easy_submit('myform','update',null)">提交更新</button>
            <?}?>
            <button type="button" class="btn" onclick="jslocation('<?=cpurl($module,'category','add')?>')">增加分类</button>
        </center>
    </div>
</form>
</div>