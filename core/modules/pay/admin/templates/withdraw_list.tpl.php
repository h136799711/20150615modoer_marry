<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript">
function withdraw_succeed(id) {
    if(!window.confirm("您确定已经向支付宝帐号(" + $('#alipay_'+id).text() + ")成功打款("+$('#price_'+id).text()+" 元)了吗？")) return;
    $.post("<?=cpurl($module,$act,'update')?>", { status:'succeed', id:id, in_ajax:1, empty:getRandom() },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if (result == 'succeed'){
            alert('状态更新完毕！');
            document_reload();
        } else {
            alert('发生错误了！');
            if(result) alert(result);
        }
    });
}
function withdraw_cancel(id) {
    var des = prompt('请填写提现失败的理由：','');
    if(!des) return;
    $.post("<?=cpurl($module,$act,'update')?>", { status:'cancel', id:id, in_ajax:1, status_des:des, empty:getRandom() }, 
        function(result) {
        if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if (result == 'succeed'){
            alert('状态更新完毕！');
            document_reload();
        } else {
            alert('发生错误了！');
            if(result) alert(result);
        }
    });
}
</script>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
	<div class="space">
		<div class="subtitle">提现申请管理</div>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
			<tr class="altbg2">
                <th colspan="10">
                    <ul class="subtab">
                        <li <?=$status=='wait'?' class="current"':''?>><a href="<?=cpurl($module,$act,'',array('status'=>'wait'))?>">申请中</a></li>
                        <li <?=$status=='succeed'?' class="current"':''?>><a href="<?=cpurl($module,$act,'',array('status'=>'succeed'))?>">已打款</a></li>
                        <li <?=$status=='cancel'?' class="current"':''?>><a href="<?=cpurl($module,$act,'',array('status'=>'cancel'))?>">已取消提现</a></li>
                    </ul>
                </th>
            </tr>
            <tr class="altbg1">
                <td width="60">流水号</td>
				<td width="100">提现金额(元)</td>
                <td width="120">会员</td>
				<td width="140">提现帐号</td>
                <td width="140">申请时间</td>
				<td width="80">状态</td>
                <?if($status=='cancel'):?>
                <td width="*">处理时间/意见</td>
                <?elseif($status=='wait'):?>
                <td width="*">操作</td>
                <?endif;?>
			</tr>
			<?if($total>0){while($row=$list->fetch_array()) {?>
			<tr>
                <td><?=$row['id']?></td>
                <td><span id="price_<?=$row['id']?>"><?=$row['price']?></span></td>
                <td><a href="<?=cpurl("member","members","edit",array('uid'=>$row[uid]))?>"><?=$row['username']?></a></td>
                <td><?=$row['realname']?><br /><span id="alipay_<?=$row['id']?>"><?=$row['accounts']?></span></td>
                <td><?=date('Y-m-d H:i:s',$row['applytime'])?></td>
                <td><?=lang('pay_withdraw_status_'.$row['status'])?></td>
                <?if($status=='cancel'):?>
                <td><?=date('Y-m-d H:i:s',$row['optime'])?><div class="font_2"><?=$row['status_des']?></div></td>
                <?elseif($status=='wait'):?>
                <td>
                    <a href="javascript:void(0);" onclick="withdraw_succeed(<?=$row[id]?>);return false;">打款成功</a> | <a 
                    href="javascript:void(0);" onclick="withdraw_cancel(<?=$row[id]?>);return false;">失败/取消</a>
                </td>
                <?endif;?>
            </tr>
            <?} $list->free_result();?>
            <?} else {?>
			<tr><td colspan="10">暂无信息。</td></tr>
            <?}?>
		</table>
    </div>
    <?if($multipage){?><div class="multipage"><?=$multipage?></div><?}?>
    <center>
    </center>
</form>
</div>