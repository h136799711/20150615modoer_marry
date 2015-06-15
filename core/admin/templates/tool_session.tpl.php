<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
    <div class="sub-menu">
        <div class="sub-menu-heading">在线访客</div>
    </div>
    <div class="remind">
        <p>最后活动时间和活动页面并不是实时统计的，只在每隔5分钟记录一次。</p>
    </div>
    <div class="space">
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
                <td width="120">会员</td>
                <td width="100">IP</td>
                <td width="60">手机浏览</td>
                <td width="130">最后活动时间</td>
                <td width="*">最后活动页面</td>
            </tr>
            <?php if($total) while ($val=$list->fetch_array()):?>
            <tr>
                <td><?=$val['uid']?$val['username']:'游客'?></td>
                <td><?=$val['ip_address']?></td>
                <td><?=$val['is_mobile']?'Yes':'No'?></td>
                <td><?=newdate($val['last_time'],'w2style')?></td>
                <td><?=$val['last_url']?></td>
            </tr>
            <?php endwhile;?>
        </table>
        <div><?=$multipage?></div>
    </div>
</div>