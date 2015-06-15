<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">物流管理</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1" trmouse="Y">
			<tr class="altbg1">
                <td width="100">名称</td>
                <td width="*">简介</td>
                <td width="80">邮费</td>
                <td width="60">启用</td>
                <td width="100">操作</td>
			</tr>
            <?if($total):?>
            <?while($val = $list->fetch_array()):?>
            <tr>
                <td><?=$val['shipname']?></td>
                <td><?=$val['shipdesc']?></td>
                <td><?=$val['price']?> 元</td>
                <td><?=$val['enabled']?'是':'否'?></td>
                <td><a href="<?=cpurl($module,$act,'edit',array('shipid'=>$val['shipid']))?>">编辑</a>&nbsp;
                    <a href="<?=cpurl($module,$act,'delete',array('shipid'=>$val['shipid']))?>" 
                        onclick="return confirm('您确定要进行删除操作吗？');">删除</a></td>
            </tr>
            <?endwhile?>
            <?if($multipage):?>
            <tr><td colspan="5">$multipage</td></tr>
            <?endif;?>
            <?else:?>
            <tr>
                <td colspan="5"><div class="messageborder"><span class="msg-ico">暂未设置物流方式</span></div></td>
            </tr>
            <?endif;?>
		</table>
	</div>
	<center>
        <?if($total):?>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="delete" />
        <button type="button" class="btn" onclick="easy_submit('myform','delete','shipids[]')">删除所选</button>&nbsp;
        <?endif;?>
        <button type="button" class="btn" onclick="jslocation('<?=cpurl($module,$act,'add',array('sid'=>$sid))?>')">增加</button>
	</center>
</form>
</div>