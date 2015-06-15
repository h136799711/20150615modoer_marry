<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>">
    <input type="hidden" name="classsort" value="1" />
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?> - 参数设置</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="javascript:;" onclick="tabSelect(1,'config');" onfocus="this.blur()">功能设置</a></li>
            <li id="btn_config2"><a href="javascript:;" onclick="tabSelect(2,'config');" onfocus="this.blur()">支付接口设置</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td width="45%" class="altbg1"><strong>允许兑换的积分类型和汇率:</strong>设置允许兑换的积分类和充值现金之间的兑换比率</td>
                <td width="*">
                    <?php !is_array($modcfg['cz_type']) && $modcfg['cz_type'] = array($modcfg['cz_type']); ?>
					<input type="checkbox" name="modcfg[cz_type][]"<?if(in_array('rmb',$modcfg['cz_type']))echo' checked="checked"';?> value="rmb" />现金<br />
					<?foreach($pointgroups as $k => $v):?>
					<?if(!$v['enabled']) continue;?>
					<input type="checkbox" name="modcfg[cz_type][]" <?if(in_array($k,$modcfg['cz_type']))echo' checked="checked"';?> value="<?=$k?>" /><?=$v['name']?>&nbsp;&nbsp;兑换比率 <input type="text" name="modcfg[ratio_<?=$k?>]" value="<?=$modcfg['ratio_'.$k]?>" class="txtbox5" /><br />
					<?endforeach;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>货币名称:</strong>设置充值的货币名称，例如：人民币，美元，欧元等</td>
                <td><input type="text" name="modcfg[pricename]" class="txtbox4" value="<?=$modcfg['pricename']?$modcfg['pricename']:'人民币'?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>单次最小充值:</strong>单次最低允许充值多少人民币</td>
                <td><input type="text" name="modcfg[czmin]" class="txtbox4" value="<?=$modcfg['czmin']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>单次最大充值:</strong>单次最多允许可充值多少人民币</td>
                <td><input type="text" name="modcfg[czmax]" class="txtbox4" value="<?=$modcfg['czmax']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>订单自动关闭时间:</strong>会员下单后没有完成支付的订单，一定时间内自动关闭，默认为24小时。</td>
                <td><input type="text" name="modcfg[staletime]" class="txtbox4" value="<?=$modcfg['staletime']>0?$modcfg['staletime']:24?>" />&nbsp;小时</td>
            </tr>
            <tr class="altbg2">
                <td></td><td><b>积分卡充值配置</b></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>是否启用积分卡充值功能:</strong>允许会员在前台助手里使用积分充值功能</td>
                <td><?=form_bool('modcfg[card]',$modcfg['card'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>卡号位数:</strong>积分卡卡号位数，位数区间15-20。</td>
                <td><input type="text" name="modcfg[card_numlen]" class="txtbox4" value="<?=$modcfg['card_numlen']?$modcfg['card_numlen']:15?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>卡号类型:</strong>设置卡号的组成类型</td>
                <td><?=form_radio('modcfg[card_no_type]',array('numeric'=>'纯数字','character'=>'纯字母','mix'=>'数字+字母'),$modcfg['card_no_type'])?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>卡号前缀:</strong>设置每张充值卡卡号的前缀，由数字字母组成，不能超过10个字符。</td>
                <td><input type="text" name="modcfg[card_prefix]" class="txtbox4" value="<?=$modcfg['card_prefix']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>卡密码位数:</strong>积分卡的密码位数，位数区间6-20。</td>
                <td><input type="text" name="modcfg[card_pwnum]" class="txtbox4" value="<?=$modcfg['card_pwnum']?$modcfg['card_pwnum']:6?>" /></td>
            </tr>
            <tr class="altbg2">
                <td></td><td><b>现金提现功能</b></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>启用提现功能:</strong>允许会员在前台助手里提现帐号内的现金到会员支付宝帐号内</td>
                <td><?=form_bool('modcfg[withdraw]',$modcfg['withdraw'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>单笔提现金额:</strong>设置用户单笔提现最小金额和最大金额；默认：1 ~ 500 元；最小值：0.01 元</td>
                <td>
                    <input type="text" name="modcfg[withdraw_min]" class="txtbox4" value="<?=$modcfg['withdraw_min']?$modcfg['withdraw_min']:1?>" />
                    ~
                    <input type="text" name="modcfg[withdraw_max]" class="txtbox4" value="<?=$modcfg['withdraw_max']?$modcfg['withdraw_max']:500?>" />
                    元
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>月累计最大提现额度:</strong>设置用户每月累计提现金额，超出则无法进行提现；默认：1000 元</td>
                <td>
                    <input type="text" name="modcfg[withdraw_limit]" class="txtbox4" value="<?=$modcfg['withdraw_limit']?$modcfg['withdraw_limit']:1000?>" /> 元
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>天累计最大提现次数:</strong>设置用户每天允许提现的次数，超出则无法进行提现；默认：3 次</td>
                <td>
                    <input type="text" name="modcfg[withdraw_count]" class="txtbox4" value="<?=$modcfg['withdraw_count']?$modcfg['withdraw_count']:3?>" /> 次
                </td>
            </tr>
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config2" style="display:none;">
            <tr class="altbg2">
                <td></td><td><?=form_bool('modcfg[alipay]',$modcfg['alipay'])?>&nbsp;<b>支付宝接口设置</b></td>
            </tr>
            <tr>
                <td width="45%" class="altbg1"><strong>支付宝帐号:</strong>
                    到款帐号，请先到 www.alipay 申请支付宝帐号并申请商家服务里的即时到帐接口。<br />
                    <span class="font_1">请确保您的服务器PHP环境已经加载curl和SSL模块，否则将无法正常使用支付宝接口。</span>
                </td>
                <td width="*"><input type="text" name="modcfg[alipay_id]" class="txtbox2" value="<?=$modcfg['alipay_id']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>合作者身份(partnerID):</strong>支付宝商家身份和打款对象ID。</td>
                <td><input type="text" name="modcfg[alipay_partnerid]" class="txtbox" value="<?=$modcfg['alipay_partnerid']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>交易安全校验码(key):</strong>支付宝商家交易所需要的交易安全校验码。</td>
                <td><input type="text" name="modcfg[alipay_key]" class="txtbox" value="<?=$modcfg['alipay_key']?>" /></td>
            </tr>
                <td class="altbg1"><strong>支付宝收款接口:</strong>请选择您最后一次跟支付宝签订的协议里面说明的接口类型。<br />
                    <span class="font_1">注：分润功能仅在即时到帐接口有效。</span>
                </td>
                <td>
                    <?=form_radio('modcfg[alipay_paytype]',array('1'=>'即时到帐接口','2'=>'担保交易接口','3'=>'双功能接口'),($modcfg['alipay_paytype']?$modcfg['alipay_paytype']:1))?>
                </td>
            </tr>
            <tr class="altbg2">
                <td></td><td><?=form_bool('modcfg[alipay_mobile]',$modcfg['alipay_mobile'])?>&nbsp;<b>支付宝手机网站支付接口设置</b></td>
            </tr>
            <tr>
                <td width="45%" class="altbg1"><strong>支付宝企业帐号:</strong>
                    到款帐号，请先到 www.alipay 申请支付宝企业帐号并申请商家服务里的支付宝手机网站支付协议。<br />
                    <span class="font_1">请确保您的服务器PHP环境已经加载curl和SSL模块，否则将无法正常使用支付宝接口。</span>
                </td>
                <td width="*"><input type="text" name="modcfg[alipay_mobile_id]" class="txtbox2" value="<?=$modcfg['alipay_mobile_id']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>合作者身份(partnerID):</strong>支付宝企业帐号商家身份和打款对象ID。</td>
                <td><input type="text" name="modcfg[alipay_mobile_partnerid]" class="txtbox" value="<?=$modcfg['alipay_mobile_partnerid']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>交易安全校验码(key):</strong>支付宝企业帐号商家交易所需要的MD5交易安全校验码。</td>
                <td><input type="text" name="modcfg[alipay_mobile_key]" class="txtbox" value="<?=$modcfg['alipay_mobile_key']?>" /></td>
            </tr>
            <tr class="altbg2">
                <td></td><td><?=form_bool('modcfg[tenpay]',$modcfg['tenpay'])?>&nbsp;<b>财付通接口设置</b></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>财付通帐号:</strong>到款帐号，请先到 mch.tenpay.com 申请财付通商家帐号。</td>
                <td><input type="text" name="modcfg[spid]" class="txtbox2" value="<?=$modcfg['spid']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>财付通商家密匙:</strong>商家的支付密匙，确保核财付通企业后台一致，如遗忘，请到财付通企业后台设置新的，请同步到这里。</td>
                <td><input type="text" name="modcfg[spkey]" class="txtbox" value="<?=$modcfg['spkey']?>" /></td>
            </tr>
            <tr class="altbg2">
                <td></td><td><?=form_bool('modcfg[chinabank]',$modcfg['chinabank'])?>&nbsp;<b>网银在线接口设置</b></td></tr>
            <tr>
                <td class="altbg1"><strong>网银商户号:</strong>到款帐号，请先到 www.chinabank.com.cn 申请商家帐号。</td>
                <td><input type="text" name="modcfg[cb_mid]" class="txtbox2" value="<?=$modcfg['cb_mid']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>网银商家MD5密钥:</strong>商家的MD5密钥，确保和网银后台一致，如遗忘，请到网银后台设置新的，请同步到这里。</td>
                <td><input type="text" name="modcfg[cb_key]" class="txtbox" value="<?=$modcfg['cb_key']?>" /></td>
            </tr>
            <?if(is_file(MOD_ROOT.'component'.DS.'payment'.DS.'paypal_class.php')):?>
            <tr class="altbg2">
                <td></td><td><?=form_bool('modcfg[paypal]',$modcfg['paypal'])?>&nbsp;<b>PayPal支付接口设置</b></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>PayPal帐号:</strong>到款帐号，请先到 www.paypal.com 申请帐号(Email)。</td>
                <td><input type="text" name="modcfg[paypal_email]" class="txtbox2" value="<?=$modcfg['paypal_email']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>支付币种:</strong>PayPal的支付币种，中文站的帐号只能收取人民币(CNY)，输入代号有：CNY,USD等</td>
                <td><input type="text" name="modcfg[paypal_currency]" class="txtbox" value="<?=$modcfg['paypal_currency']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>使用测试帐号(sandbox):</strong>使用测试帐号，可以进行虚拟帐号充值，用于调试，测试帐号注册地址：www.sandbox.paypal.com</td>
                <td><?=form_bool('modcfg[paypal_test]', $modcfg['paypal_test'])?></td>
            </tr>
            <?endif;?>
        </table>
        <center><button type="submit" name="dosubmit" value="yes" class="btn" /> 提交 </button></center>
    </div>
</form>
</div>