<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<style>
.intro p{margin:0;padding:0;line-height:25px;}
</style>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'add')?>">
<input type="hidden" name="newcat[pid]" value="<?=$_GET['catid']?>" />
    <div class="space">
        <div class="subtitle">分类管理（关联产品模型的2，3级分类才能添加产品）</div>
        <ul class="cptab">
            <li class="selected"><a href="<?=cpurl($module,$act,'subcat',array('catid'=>$catid))?>" onfocus="this.blur()">子分类管理</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="45%"><strong>子分类名称：</strong>向<span class="font_1"><?=$t_cat['name']?></span>添加一个子分类。一次性添加多个分类，请使用"|"分隔。</td>
                <td width="40%">
                    <input type="text" class="txtbox2" name="newcat[name]" />
                </td>
                <td class="altbg1" width="*" rowspan="2"><center><button type="submit" name="dosubmit" value="yes" class="btn2">添加子分类</button></center></td>
            </tr>
            <?if($t_cat['level'] >= 1):?>
            <tr>
                <td class="altbg1"><strong>关联产品模型ID：</strong>关联某个产品模型，用于产品添加时填写自定义字段；没有关联产品模型的分类不能添加产品.<br />
                    <?if($t_cat['level']=='1'):?>
                    <span class="font_1">二级分类可不选择产品模型则表示本类不允许添加商品；</span>
                    <?else:?>
                    <span class="font_1">三级分类则必须选择关联模型。</span>
                    <?endif;?>
                </td>
                <td>
                    <select name="newcat[modelid]">
                        <?if($t_cat['level']=='1'):?>
                        <option value="0" selected="selected">==不关联模型==</option>
                        <?else:?>
                        <option value="0" selected="selected">==选择产品模型==</option>
                        <?endif;?>
                        <?=form_product_model($t_cat['modelid']);?>
                    </select>
                </td>
            </tr>
            <?endif;?>
        </table>
    </div>
</form>
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">子分类列表[<?=$t_cat['name']?>]</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="30">选?</td>
                <td width="40">ID</td>
                <td width="50">模型ID</td>
                <td width="40">启用</td>
                <td width="60">排序</td>
                <td width="210">名称</td>
                <td width="210">页面标题</td>
                <td width="*">操作</td>
            </tr>
            <?if($result) {
            foreach($result as $val) { ?>
            <tr>
                <td><input type="checkbox" name="catids[]" value="<?=$val['catid']?>" /></td>
                <td><?=$val['catid']?></td>
                <td><?=$val['modelid']?$val['modelid']:'N/A'?></td>
                <td><input type="checkbox" name="t_cat[<?=$val['catid']?>][enabled]" value="1" <?if($val['enabled'])echo' checked="checked"';?> /></td>
                <td><input type="text" class="txtbox5" name="t_cat[<?=$val['catid']?>][listorder]" value="<?=$val['listorder']?>" /></td>
                <td><input type="text" class="txtbox3" name="t_cat[<?=$val['catid']?>][name]" value="<?=$val['name']?>" /></td>
                <td><input type="text" class="txtbox3" name="t_cat[<?=$val['catid']?>][title]" value="<?=$val['title']?>" /></td>
                <td>
                    <a href="<?=cpurl($module,'category','edit',array('catid'=>$val['catid']))?>">编辑</a>&nbsp;
                    <?if($val['level']>=1&&$val['level']<=2):?>
                    <a href="<?=cpurl($module,$act,'subcat',array('catid'=>$val['catid']))?>">下级分类</a>&nbsp;
                    <?endif;?>
                    <?if($val['modelid'] > 0):?>
                    <a href="<?=cpurl($module,'category','att',array('catid'=>$val['catid']))?>">筛选属性管理</a>
                    <?endif;?>
                    <a href="<?=cpurl($module,$act,'delete',array('catid'=>$val['catid']))?>" onclick="return confirm('您确定要进行删除操作吗？');">删除</a>
                </td>
            </tr>
            <?}?>
            <tr class="altbg1"><td colspan="10">
            <button type="button" onclick="checkbox_checked('catids[]');" class="btn2">全选</button></td></tr>
            <?} else {?>
            <tr><td colspan="10">暂无信息。</td></tr>
            <?}?>
        </table>
        <center>
            <input type="hidden" name="catid" value="<?=$_GET['catid']?>" />
            <input type="hidden" name="dosubmit" value="yes" />
            <input type="hidden" name="op" value="subcat" />
            <?if($result){?>
            <button type="submit" class="btn">更新提交</button>&nbsp;
            <?}?>
            <?if($t_cat['level']==1):?><button type="button" class="btn" onclick="jslocation('<?=cpurl($module,'category_list')?>')" />返回上级分类</button><?endif;?>
            <?if($t_cat['level']==2):?><button type="button" class="btn" onclick="jslocation('<?=cpurl($module,'category_edit','subcat',array('catid'=>$t_cat['pid']))?>')" />返回上级分类</button><?endif;?>
        </center>
    </div>
</form>
</div>