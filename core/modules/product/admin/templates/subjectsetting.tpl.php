<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'post')?>">
    <div class="space">
        <div class="subtitle">商城设置</div>
        <?if($edit_links):?>
        <ul class="cptab">
            <?foreach($edit_links as $val):?>
            <li<?if($val['flag']=='product:subjectsetting')echo' class="selected"';?>><a href="<?=$val['url']?>" onfocus="this.blur()"><?=$val['title']?></a></li>
            <?endforeach;?>
        </ul>
        <?endif;?>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1" valign="top" width="45%"><strong>商品销售佣金比率:</strong>
                    单独为当前商家（主题）每销售一份产品，网站即可获得其销售价的提成（百分比:1-100），例如：10%，即100元的商品，网站可以获得10元的佣金。<br />
                    <span class="font_1">此处设置为-1表示不进行分成，留空则按默认商城模块配置里的分成比率计算。</span>
                </td>
                <td width="*">
                    <input type="text" name="brokerage" value="<?=$setting['brokerage']?>" class="txtbox4" /> %
                </td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><strong>线下支付帐号:</strong>
                    商家线下收款帐号，用于填写银行卡，邮局汇款；留空表示不使用线下支付。
                </td>
                <td>
                    <textarea name="offlinepay" style="height:80px;"><?=_T($setting['offlinepay'])?></textarea>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>买家指定发货时间：</strong>由买家下单时指定发货日期。</td>
                <td><?=form_bool('use_deliverytime',(bool)$setting['use_deliverytime'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>当日发货截止时间:</strong>
                    超过指定时间后，推迟到第二天发货。默认为16点之前。
                    <span class="font_1">买家指定日期发货时使用。</span>
                </td>
                <td>
                    <input type="text" name="onedaydelivery_limit" value="<?=isset($setting['onedaydelivery_limit'])?(int)$setting['onedaydelivery_limit']:16?>" class="txtbox5" /> 点
                    <span class="font_2">(请输入0~23之间的数字)</span>
                </td>
            </tr>
        </table>
    </div>
	<center>
		<input type="hidden" name="sid" value="<?=$sid?>">
		<?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?>
	</center>
</form>
</div>