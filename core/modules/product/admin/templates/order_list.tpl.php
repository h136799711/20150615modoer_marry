<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="./static/javascript/My97DatePicker/WdatePicker.js"></script>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">订单筛选</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1">产品PID</td>
                <td><input type="text" name="pid" class="txtbox3" value="<?=$_GET['pid']?>" /></td>
                <td class="altbg1">主题SID</td>
                <td><input type="text" name="sid" class="txtbox3" value="<?=$_GET['sid']?>" /></td>
            </tr>
            <tr>
                <td width="80" class="altbg1">订单号</td>
                <td width="250"><input type="text" name="ordersn" class="txtbox3" value="<?=$_GET['ordersn']?>" /></td>
                <td width="50" class="altbg1">支付方式</td>
                <td width="*">
                    <?php 
                        $_G['loader']->helper('form','pay');
                        $pmts = array('balance','alipay','tenpay','chinabank','paypal');
                    ?>
                    <select name="paymentname">
                        <option value="">==全部==</option>
                        <option value="cod"<?if($_GET['paymentname']=='cod')echo' selected="selected"'?>>货到付款</option>
                        <option value="offline"<?if($_GET['paymentname']=='offline')echo' selected="selected"'?>>线下支付</option>
                        <?foreach ($pmts as $key):?>
                            <option value="<?=$key?>"<?if($_GET['paymentname']==$key)echo' selected="selected"'?>><?=lang("pay_name_$key")?></option>
                        <?endforeach?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1">买家</td>
                <td colspan="3"><input type="text" name="buyername" class="txtbox3" value="<?=$_GET['buyername']?>" /></td>
            </tr>
            <tr>
                <td width="80" class="altbg1">下单时间</td>
                <td width="*" colspan="3"><input type="text" name="starttime" class="txtbox3" value="<?=$_GET['starttime']?>" onfocus="WdatePicker({doubleCalendar:true,maxDate:'%y-%M-{%d-1}',dateFmt:'yyyyMMdd'})" /> – <input type="text" name="endtime" class="txtbox3" value="<?=$_GET['endtime']?>" onfocus="WdatePicker({doubleCalendar:true,maxDate:'%y-%M-%d',dateFmt:'yyyyMMdd'})" />&nbsp;&nbsp<button type="submit" value="yes" name="dosubmit" class="btn2">筛选</button></td>
            </tr>
        </table>
    </div>
</form>
<form method="post" action="<?=cpurl($module,$act)?>" name="myform">
    <div class="space">
        <div class="subtitle">产品订单列表</div>
        <ul class="cptab">
            <?php $allp = $_GET; unset($allp['status']) ?>
            <li <?if(!$status){?>class="selected"<? } ?>><a href="<?=cpurl($module,$act,'list',$allp)?>">所有订单</a></li>
            <?foreach($status_name as $k => $v) :?>
            <?php $allp = $_GET; $allp['status'] = $k; ?>
            <li<?if($status==$k)echo' class="selected"';?>><a href="<?=cpurl($module,$act,'list',$allp)?>"><?=$v?></a></li>
            <?endforeach;?>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
                <td width="15%">订单号</td>
                <td width="15%">店铺名称</td>
                <td width="15%">下单时间</td>
                <td width="10%">买家名称</td>
                <td width="15%">订单总价</td>
                <td width="11%">支付方式/商户订单号</td>
                <td width="10%">订单状态</td>
                <td width="10%">操作</td>
            </tr>
            <?if($total):?>
            <?foreach($mylist as$val):?>
            <tr>
                <td>
                    <div><?=$val['ordersn']?></div>
                </td>
                <td><a href="<?=url('product/shop/sid/'.$val['sid'])?>" target="_blank"><?=$val['name']?></a></td>
                <td><?=date('Y-m-d H:i:s',$val['addtime'])?></td>
                <td><?=$val['buyername']?></td>
                <td><?=$val['order_amount']?></td>
                <td>
                    <?if($val['paymentname']=='cod'):?>
                    <span class="font_1">货到付款</span>
                    <?elseif($val['paymentname']=='offline'):?>
                    <span class="font_3">线下支付(<?=$val['is_offline_pay']=='admin'?'网站收款':'商家收款'?>)</span>
                    <?elseif($val['paymentname']):?>
                    <?=lang('pay_name_'.$val['paymentname'])?>
                    <?else:?>
                    N/A
                    <?endif;?>
                    <div><?=$paylist[$val['orderid']]?></div>
                </td>
                <td><?=lang('product_status_'.$val['status'])?></td>
                <td>
                    <a href="<?=cpurl($module,$act,'view',array('orderid'=>$val['orderid']))?>">查看</a>
                    <?if($val['status']=='1'):?>
                    <a href="javascript:;" onclick="submit_pay_offline('<?=$val['ordersn']?>','<?=$val['orderid']?>');">已线下支付</a>
                    <?endif;?>
                </td>
            </tr>
            <?endforeach;?>
            <?else:?>
            <tr><td colspan="8">暂无信息。</td></tr>
            <?endif?>
        </table>
        <?if($multipage){?><div class="multipage"><?=$multipage?></div><?}?>
    </div>
</form>
</div>
<script type="text/javascript">
function submit_pay_offline (ordersn, orderid) {
    var ok = '';
    var msg = "\r\n请在输入框内输入“OK”（大写，不包含引号）" 
    ok = prompt('您确定您已经收到了订单 '+ordersn+' 的支付款项吗？'+"\r\n"+'如果货款是由商家收取的，请不要在后台确认线下支付，请让商家在前台确认订单收款。'+msg);
    if(ok!='OK') return false;
    var frm = $('#dfsdf');
    if(frm[0]) frm.remove();
    frm = $("<form method=\"post\" action=\"<?=cpurl($module,$act,'pay_offline')?>\"></form>");
    var hie1 = $("<input type=\"hidden\" name=\"ordersn\">").val(ordersn);
    frm.append(hie1);
    var hie2 = $("<input type=\"hidden\" name=\"orderid\">").val(orderid);
    frm.append(hie2);
    $(document.body).append(frm);
    frm[0].submit();
}
</script>