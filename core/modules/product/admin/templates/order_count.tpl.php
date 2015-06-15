<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="./static/javascript/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="./static/javascript/item.js"></script>
<style>
.maintable button,.maintable input,.maintable select{vertical-align: middle;}
</style>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">筛选条件</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="120">搜索商家</td>
                <td>
                    <div id="subject_search">
					<?if($subject):?>
					<a href="<?=url("item/detail/id/$subject[sid]")?>" target="_blank"><?=$subject['name'].($subject['subname']?"($subject[subname])":'')?></a>
					<?endif;?>
					</div>
					<script type="text/javascript">
						$('#subject_search').item_subject_search({
							input_class:'txtbox3',
							btn_class:'btn2',
							result_css:'item_search_result',
							<?if($subject):?>
								sid:<?=$subject['sid']?>,
								current_ready:true,
							<?endif;?>
							hide_keyword:true
						});
					</script>
                </td>
                <td class="altbg1">时间类型</td>
                <td>
                    <input type="radio" name="timetype" value="paytime" id="timetype_paytime"<?if($timetype=='paytime')echo' checked="true"';?>>
                    <label for="timetype_paytime">付款时间</label>
                    <input type="radio" name="timetype" value="addtime" id="timetype_addtime"<?if($timetype=='addtime')echo' checked="true"';?>>
                    <label for="timetype_addtime">下单时间</label>
                </td>
            </tr>
            <tr>
                <td class="altbg1"  width="120">开始时间</td>
                <td width="350"><input type="text" name="starttime" class="txtbox3" value="<?=$starttime?>" onfocus="WdatePicker({doubleCalendar:true,maxDate:'%y-%M-{%d-1}'})" /></td>
                <td class="altbg1"  width="120">结束时间</td>
                <td width="*">
                    <input type="text" name="endtime" class="txtbox3" value="<?=$endtime?>" onfocus="WdatePicker({doubleCalendar:true,maxDate:'%y-%M-%d'})" />&nbsp;
                    <button type="submit" value="yes" name="dosubmit" class="btn2">筛选</button>
                </td>
            </tr>
        </table>
    </div>
</form>
<?if($totalprice):?>
<div class="space">
    <div class="subtitle">商品销售额统计&nbsp;<?if($subject):?><?=$subject['name']?><?endif;?> (<?if($tm1 && $tm2):?><?=$tm1?> 至 <?=$tm2?><?else:?>所有<?endif;?>) 销售统计</div>
    <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
        <?if(!$totalprice):?>
        <tr>
            <td>没有任何记录.</td>
        </tr>
        <?else:?>
        <?foreach ($totalprice as $key => $value):?>
        <tr>
            <td width="300" class="altbg1"><?=$status_name[$key]?>数据:</td>
            <td>
                <?=$status_name[$key]?> <?=$value['totalorder']?> 个订单，总计：<?=$value['totalprice']?> 元
                <?if($key==5):?>收取佣金：<?=$value['brokerage']?> 元<?endif;?>
            </td>
        </tr>
        <?endforeach;?>
        <?endif;?>
        <!--
        <tr>
            <td width="400" class="altbg1"><?if($subject):?><?=$subject['name']?><?endif;?><?if($tm1 && $tm2):?><?=$tm1?> 至 <?=$tm2?> <?else:?>所有<?endif;?>的订单销售总额：</td>
            <td width="*">RMB：<?=$totalprice?> 元</td>
        </tr>
        -->
    </table>
</div>
<?else:?>
<div class="space">
    <div class="subtitle">商品销售额统计</div>
    <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
        <tr>
            <td>您还未进行统计筛选或者暂无任何销售记录。</td>
        </tr>
    </table>
</div>
<?endif;?>
</div>