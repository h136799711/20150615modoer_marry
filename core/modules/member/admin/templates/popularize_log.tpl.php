<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<div class="sub-menu" data-container="div.space > table">
    <div class="sub-menu-heading">推广记录</div>
</div>
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="200">推广人</td>
                <td width="200">注册用户</td>
                <td width="150">注册时间</td>
                <td width="*">已奖励</td>
            </tr>
            <? if($total):?>
            <? while($val=$list->fetch_array()):?>
            <tr>
                <td><a href="<?=url("space/index/uid/$val[inviter_uid]")?>" target="_blank"><?=$val['inviter']?></a></td>
                <td><a href="<?=url("space/index/uid/$val[invitee_uid]")?>" target="_blank"><?=$val['invitee']?></a></td>
                <td><?=date('Y-m-d H:i',$val['dateline'])?></td>
                <td><?=$val['add_point']?'是':'否'?></td>
            </tr>
            <? endwhile; ?>
            <? else: ?>
            <tr>
                <td colspan="6">暂无信息。</td>
            </tr>
            <? endif; ?>
        </table>
        <div><?=$multipage?></div>
    </div>
</form>
</div>