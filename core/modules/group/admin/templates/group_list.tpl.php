<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">小组筛选</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" class="altbg1">小组分类</td>
                <td width="350">
                    <select name="catid">
                    <option value="">==全部==</option>
                    <?=form_group_category($_GET['catid']);?>
                    </select>&nbsp;
                </td>
                <td width="100" class="altbg1">小组ID</td>
                <td width="*">
                    <input type="text" name="gid" class="txtbox3" value="<?=$_GET['gid']?>" />
                </td>
            </tr>
            <tr>
                <td class="altbg1">组长UID</td>
                <td>
                    <input type="text" name="uid" class="txtbox3" value="<?=$_GET['uid']?>" />
                </td>
                <td class="altbg1">组长昵称</td>
                <td>
                    <input type="text" name="username" class="txtbox3" value="<?=$_GET['username']?>" />
                </td>
            </tr>
            <tr>
                <td class="altbg1">创建时间</td>
                <td colspan="3"><input type="text" name="starttime" class="txtbox3" value="<?=$_GET['starttime']?>" />&nbsp;~&nbsp;<input type="text" name="endtime" class="txtbox3" value="<?=$_GET['endtime']?>" />&nbsp;(YYYY-MM-DD)</td>
            </tr>
            <tr>
                <td class="altbg1">结果排序</td>
                <td colspan="3">
                <select name="orderby">
                    <option value="gid"<?=$_GET['orderby']=='gid'?' selected="selected"':''?>>默认排序</option>
                    <option value="dateline"<?=$_GET['orderby']=='createtime'?' selected="selected"':''?>>创建时间</option>
                    <option value="replies"<?=$_GET['orderby']=='topics'?' selected="selected"':''?>>话题数量</option>
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
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">小组列表</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="30"><center>GID</center></td>
                <td>荐?</td>
                <td width="60">图标</td>
                <td width="160">名称</td>
                <td width="*">简介</td>
                <td width="110">创建时间</td>
                <td width="80">会员/话题量</td>
                <td width="120">操作</td>
            </tr>
            <?if($total && $list):?>
            <?while($val=$list->fetch_array()):?>
            <tr>
                <td><center><?=$val['gid']?></center></td>
                <td><input onclick="group_set_finer(this,<?=$val['gid']?>)" type="checkbox" name="group[<?=$val['gid']['finer']?>]"value="1"<?if($val['finer'])echo' checked="checked"'?>></td>
                <td><img src="<?=$val['icon']?$val['icon']:'static/images/z_noimg.gif'?>" width="50" /></td>
                <td>
                    <?if($val['auth']):?><span class="font_1">[官方]</span><?endif;?>
                    <span class="font_2">[<?=display('modoer:area',"aid/$val[city_id]")?>]</span>
                    <a href="<?=url("group/$val[gid]")?>" target="_blank"><?=$val['groupname']?></a>
                    <br />组长：<span id="owner_<?=$val['gid']?>"><?=$val['username']?$val['username']:'<span class="font_2">空缺</span>'?></span>
                        [<a href="javascript:"onclick="group_change_owner(<?=$val['gid']?>);"><?if($val['username']):?>更换<?else:?>增加<?endif;?></a>]
                </td>
                <td><?=trimmed_title($val['des'],'200','...')?></td>
                <td><?=date('Y-m-d H:i',$val['createtime'])?></td>
                <td><?=$val['members']?>/<?=$val['topics']?></td>
                <td>
                    <a href="<?=cpurl($module,'topic','list',array('gid'=>$val['gid']))?>">话题管理</a>
                    <a href="<?=cpurl($module,$act,'edit',array('gid'=>$val['gid']))?>">编辑</a>
                    <a href="<?=cpurl($module,$act,'delete',array('gid'=>$val['gid']))?>" onclick="return confirm('您确定要解散这个小组吗？解散后小组内所有信息将被清空，请慎重抉择！')";>解散</a>
                </td>
            </tr>
            <?endwhile;?>
            <?else:?>
            <tr>
                <td colspan="12">暂无信息。</td>
            </tr>
            <?endif;?>
        </table>
    </div>
    <?if($total):?>
    <div class="multipage"><?=$multipage?></div>
    <?endif;?>
</form>
</div>
<script type="text/javascript">
function group_change_owner(gid) {
    if (!is_numeric(gid)) {
        alert('无效的小组ID'); 
        return;
    }
    var username = window.prompt('请输入需要增加或更换的组长昵称：');
    if(username == null) return;
    username = username.trim();
    if(!username) {
        alert('对不起，您未填写新组长昵称。');
        return;
    }
    $.post("<?=cpurl($module,$act,'change_owner')?>", 
    { 'gid':gid,'username':encodeURIComponent(username), 'in_ajax':1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result == 'OK') {
            $('#owner_'+gid).html(username);
        } else {
            alert('操作失败。');
        }
    });
}
function group_set_finer(obj,gid) {
    if (!is_numeric(gid)) {
        alert('无效的小组ID'); 
        return;
    }
    var value = $(obj).attr('checked') ? 1 : 0;
    $.post("<?=cpurl($module,$act,'set_finer')?>", 
    { 'gid':gid,'value':value, 'in_ajax':1 },
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