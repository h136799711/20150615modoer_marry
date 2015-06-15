<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?>&nbsp;模块配置</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="#" onclick="tabSelect(1,'config');" onfocus="this.blur()">功能配置</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1" valign="top" width="45%"><strong>消费积分类型:</strong>购买商品时赠送的消费积分类型，<span class="font_4">请选择一个全新独立并且未被使用过的积分类型</span>。<br /><span class="font_3">您可以打开“会员管理”--“扩展积分管理”，在这里建立、设置新的积分类型。</span></td>
                <td width="*">
                    <select name="modcfg[pointgroup]">
                        <option value="">选择积分类型</option>
                        <?=form_member_pointgroup($modcfg['pointgroup']);?>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="45%" class="altbg1"><strong>消费积分与现金比率:</strong>会员使用积分抵价现金时，1元现金兑换消费计费数量；请填写整数兑换，例如：10<br /><span class="font_1">不填写或小于等于0默认比率是10</span></td>
                <td width="*"><input type="text" name="modcfg[cash_rate]" value="<?=$modcfg['cash_rate']?>" class="txtbox4" /></td>
            </tr>
            <tr>
                <td width="45%" class="altbg1"><strong>赠送消费积分比率（百分比）:</strong>前台商家发布的商品时，根据设置的比率赠送给消费者。
                    <br /><span class="font_1">赠送消费积分 = 销售价格 × 消费积分与现金比率 × 赠送消费积分比率</span>
                    <br /><span class="font_1">留空表示不赠送；设置的值建议在1%-5%；积分是网站发放的，最终承担方是商家</span>
                </td>
                <td width="*"><input type="text" name="modcfg[giveintegral_percent]" value="<?=$modcfg['giveintegral_percent']?>" class="txtbox5" /> %</td>
            </tr>
            <tr>
                <td class="altbg1" valign="top" width="45%"><strong>消费积分赠送数额计算:</strong>会员成功完成购买并完成交易后，可获得相应产品的设置的消费积分；<span class="font_1">按次计算</span>：即一次订单累加算每种产品消费积分（不累加产品的数量）；<span class="font_1">按数量计算</span>：即根据每种产品的购买数量乘以每个产品的消费积分</td>
                <td width="*"><?=form_radio('modcfg[integral_acctype]', array('1'=>'按次计算','2'=>'按数量计算'), isset($modcfg['integral_acctype'])?$modcfg['integral_acctype']:'1')?></td>
            </tr>
            <tr>
                <td width="45%" class="altbg1"><strong>启用商品销售分成:</strong>
                    打开本功能后，商品销售完成后，系统将自动根据分成比率进行计算商家与网站所得，并将商家所得部分打入其网站的现金帐号。
                    <br><span class="font_1">关闭本功能后，商家单独设定的分成比率也将失效，统一不进行成分计算。</span>
                </td>
                <td width="*"><?=form_bool('modcfg[brokerage_enable]', $modcfg['brokerage_enable']?$modcfg['brokerage_enable']:0)?></td>
            </tr>

            <tr>
                <td width="45%" class="altbg1"><strong>商品销售佣金比率（百分比）:</strong>每销售一份产品，网站即可获得其销售价的提成（百分比:1-100），例如：10%，即100元的产品，网站可以获得10元的佣金（如果中间用户使用消费积分抵现金，佣金则从实际支付的现金里计算）。
                    <br /><span class="font_1">留空或者设置为 0 表示不进行分成（不影响对商家单独设定的分成比率）。</span>
                    <br /><span class="font_3">如果要单独为某个商家（主题）设置分成，请在“主题管理-编辑主题-商城设置”内进行设置；</span>
                </td>
                <td width="*"><input type="text" name="modcfg[brokerage]" value="<?=$modcfg['brokerage']?>" class="txtbox5" /> %</td>
            </tr>
            <tr>
                <td class="altbg1"><strong>商品运费纳入佣金计算:</strong>
                    网站在计算销售提成时，加入对运费的计算，即对用户支付的现金进行计算。
                    <br /><span class="font_1">佣金 =（商铺售价+运费-消费积分抵价）× 商品销售佣金比率</span>
                </td>
                <td><?=form_bool('modcfg[brokerage_add_shipfee]',(bool)$modcfg['brokerage_add_shipfee']);?></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top" width="45%"><strong>开启手机短信提醒功能:</strong>前台买家支付成功或确认收货时，发送手机短信给相关商家和买家。 </td>
                <td width="*"><?=form_bool('modcfg[send_sms]', $modcfg['send_sms'])?></td>
            </tr>
            <input type="hidden" name="modcfg[enablebuy]" value="yes">
            <tr>
                <td class="altbg1" valign="top" width="45%"><strong>开启产品发布验证:</strong>发布产品时，必须填写验证码 </td>
                <td width="*"><?=form_bool('modcfg[seccode_product]', $modcfg['seccode_product'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>允许添加产品时使用复制产品内容功能:</strong>前台主题管理员添加产品时，可以通过填写产品ID来获取产品的内容，以便于简化产品添加效率。</td>
                <td><?=form_bool('modcfg[product_copy_enable]',$modcfg['product_copy_enable'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>启用产品发布审核:</strong>发布产品后必须通过后台审核</td>
                <td><?=form_bool('modcfg[check_product]',$modcfg['check_product'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>启用产品点评（评论）功能:</strong>会员可以对产品进行点评。<?if(!check_module('comment')):?><span class="font_4">请先安装评论模块！</span><?endif;?></td>
                <td><?=form_bool('modcfg[post_comment]',$modcfg['post_comment']);?></td>
            </tr>
            <tr>
                <td width="45%" class="altbg1"><strong>图片尺寸:</strong>上传点评对象的图片时，限制期最大尺寸，格式为：宽 x 高；默认：200 x 150</td>
                <td width="*"><input type="text" name="modcfg[thumb_width]" value="<?=$modcfg['thumb_width']?>" class="txtbox5" />&nbsp;x&nbsp;<input type="text" name="modcfg[thumb_height]" value="<?=$modcfg['thumb_height']?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>允许已关联主题的产品使用主题风格:</strong>对已经关联了主题的产品（列表闻内容），采用主题设置的主题风格。</td>
                <td><?=form_bool('modcfg[use_itemtpl]', $modcfg['use_itemtpl'])?></td>
            </tr>
            <tr>
                <td width="45%" class="altbg1"><strong>列表页筛选项内容折叠:</strong>设置列表页筛选项内筛选内容多少数量进行折叠隐藏，留空或0为不进行折叠隐藏</td>
                <td><input type="text" class="txtbox5"  name="modcfg[list_filter_li_collapse_num]" value="<?=$modcfg['list_filter_li_collapse_num']?>" /></td>
            </tr>
            <!--
            <tr>
                <td class="altbg1"><strong>允许主题管理员管理产品评论:</strong>允许管理员对自己主题的产品的评论</td>
                <td><?=form_bool('modcfg[manage_comment]',$modcfg['manage_comment']);?></td>
            </tr>
            -->
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>