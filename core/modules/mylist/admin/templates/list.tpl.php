<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">榜单筛选</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" class="altbg1">城市</td>
                <td width="350">
					<?if($admin->is_founder):?>
                    <select name="city_id">
                    <option value="">==城市==</option>
                    <?=form_city($_GET['city_id']);?>
                    </select>&nbsp;
					<?endif;?>
                </td>
                <td width="100" class="altbg1">分类</td>
                <td width="*">
                    <select name="catid">
                    <option value="">==分类==</option>
                    <?=form_mylist_category($_GET['catid']);?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1">榜单名称</td>
                <td>
                    <input type="text" name="title" class="txtbox3" value="<?=$_GET['title']?>" />
                </td>
                <td class="altbg1">上传会员</td>
                <td>
                    <input type="text" name="username" class="txtbox3" value="<?=$_GET['username']?>" />
                </td>
            </tr>
            <tr>
                <td class="altbg1">更新时间</td>
                <td colspan="3"><input type="text" name="starttime" class="txtbox3" value="<?=$_GET['starttime']?>" />&nbsp;~&nbsp;<input type="text" name="endtime" class="txtbox3" value="<?=$_GET['endtime']?>" />&nbsp;(YYYY-MM-DD)</td>
            </tr>
            <tr>
                <td class="altbg1">结果排序</td>
                <td colspan="3">
                <select name="orderby">
                    <option value="id"<?=$_GET['orderby']=='sid'?' selected="selected"':''?>>默认排序</option>
                    <option value="modifytime"<?=$_GET['orderby']=='modifytime'?' selected="selected"':''?>>更新时间</option>
                    <option value="pageviews"<?=$_GET['orderby']=='pageviews'?' selected="selected"':''?>>人气</option>
                    <option value="favorites"<?=$_GET['orderby']=='favorites'?' selected="selected"':''?>>收藏量</option>
                    <option value="responds"<?=$_GET['orderby']=='responds'?' selected="selected"':''?>>回应量</option>
                </select>&nbsp;
                <select name="ordersc">
                    <option value="DESC"<?=$_GET['ordersc']=='DESC'?' selected="selected"':''?>>递减</option>
                    <option value="ASC"<?=$_GET['ordersc']=='ASC'?' selected="selected"':''?>>递增</option>
                </select>&nbsp;
                <select name="offset">
                    <option value="20"<?=$_GET['offset']=='20'?' selected="selected"':''?>>每页显示20个</option>
                    <option value="50"<?=$_GET['offset']=='50'?' selected="selected"':''?>>每页显示50个</option>
                    <option value="100"<?=$_GET['offset']=='100'?' selected="selected"':''?>>每页显示100个</option>
                </select>&nbsp;
                <button type="submit" value="yes" name="dosubmit" class="btn2">筛选</button>
                </td>
            </tr>
        </table>
    </div>
</form>
<form method="post" action="<?=cpurl($module,$act)?>" name="myform">
    <div class="space">
        <div class="subtitle">筛选结果</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
                <td width="25">删?</td>
                <td width="35">精华</td>
                <td width="180">榜单名</td>
                <td width="*">简介</td>
                <td width="120">榜主</td>
                <td width="50">主题数</td>
                <td width="40">收藏</td>
                <td width="40">鲜花</td>
                <td width="40">回应</td>
                <td width="120">更新时间</td>
                <td width="60">操作</td>
            </tr>
            <?php if($total) { ?>
            <?php while ($val=$list->fetch_array()) { ?>
            <tr>
                <td><input type="checkbox" name="ids[]" value="<?=$val['id']?>" /></td>
                <td><input onclick="mylist_set_digest(this,<?=$val['id']?>)" type="checkbox" value="1"<?if($val['digest'])echo' checked="checked"'?>></td>
                <td><a href="<?=url("mylist/$val[id]")?>" target="_blank"><?=$val['title']?></a><span class="font_2">[<?=template_print('modoer','area',array('aid'=>$val['city_id']))?>]</span></td>
                <td><?=$val['intro']?></td>
                <td><a href="<?=url("splace/index/uid/$val[uid]")?>"><?=$val['username']?></a></td>
                <td><?=$val['num']?></td>
                <td><?=$val['favorites']?></td>
                <td><?=$val['flowers']?></td>
                <td><?=$val['responds']?></td>
                <td><?=date('Y-m-d H:i',$val['modifytime'])?></td>
                <td><a href="javascript:;"onclick="edit_mylist(<?=$val['id']?>)">编辑</a></td>
            </tr>
            <? } ?>
            <tr class="altbg1"><td colspan="12">
                <button type="button" class="btn2" onclick="checkbox_checked('ids[]');">全选</button>&nbsp;
            </td></tr>
            <? } else { ?>
            <tr><td colspan="12">暂无信息。</td></tr>
            <? } ?>
        </table>
        <?if($multipage){?><div class="multipage"><?=$multipage?></div><?}?>
        <center>
            <?php if($total) { ?>
            <input type="hidden" name="dosubmit" value="yes" />
            <input type="hidden" name="op" value="delete" />
            <button type="button" class="btn" onclick="easy_submit('myform','delete','ids[]')">删除所选</button>
            <? } ?>
        </center>
    </div>
</form>
</div>
<script type="text/javascript">
function edit_mylist(id) {
    get_url = "<?=cpurl($module,$act,'edit')?>";
    $.post(get_url, { 'id':id, 'in_ajax':1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            dlgOpen('编辑榜单', result, 430);
        }
    });
}
function mylist_set_digest(obj,id) {
    if (!is_numeric(id)) {
        alert('无效的榜单ID'); 
        return;
    }
    var value = $(obj).attr('checked') ? 1 : 0;
    $.post("<?=cpurl($module,$act,'set_digest')?>", 
    { 'id':id,'value':value, 'in_ajax':1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result != 'OK') {
            alert('操作失败。');
        }
    });
}
</script>