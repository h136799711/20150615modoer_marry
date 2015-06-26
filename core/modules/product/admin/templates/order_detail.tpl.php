<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<style>
.clear{clear:both}
*{margin:0;padding:0;}
.con{border:solid 1px #bdbdbd;border-top:none;padding:10px 0;}
.order_form{margin:0 6px;padding:15px 12px;border-bottom:solid 1px #bbdbf1;color:#444;}
.order_form h1{font-size:14px;color:#009de6;margin-bottom:10px;}
.order_form h2{font-size:12px;color:#8c8c8c;margin-bottom:5px;}
.order_form ul{}
.order_form li{width:50%;float:left;line-height:22px;}
.order_form a{color:#06c;}
.order_form a:hover{color:red;}
.order_info{float:left;width:50%;}
.order_info_pic{float:left;width:60px;height:60px;border:solid 1px #bdbdbd;display:block;overflow:hidden;margin-right:10px;}
.order_info_pic:hover{border:solid 1px #333;}
.order_info_text{ height:53px;overflow:hidden;margin:0 15px;line-height:18px;}
.order_info_text a{color:#444;text-decoration:none;}
.order_info_text a:hover{text-decoration:underline;}
.red_common{font-weight:bold;color:#ff5400;}
</style>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>" name="myform">
    <div class="space">
        <div class="subtitle">订单详细信息</div>
        <div class="con">
            <div class="order_form">
                <h1>订单状态</h1>
                <ul>
                    <li><b>订单号：</b><?=$detail['ordersn']?></li>
                    <li><b>订单状态：</b> <?=lang('product_status_'.$detail['status'])?></li>
                    <li><b>订单总价：</b> <span class="red_common">&yen;<?=$detail['order_amount']?></span>
                        (积分抵换:<span class="font_1">&yen;<?=$detail['integral_amount']?></span>)</li>
                    <?if($detail['status']=='5'):?>
                    <li><b>网站佣金：</b><b class="font_4">&yen;<?=$detail['brokerage']?></b></li>
                    <?endif;?>
                </ul>
                <div class="clear"></div>
            </div>
            <div class="order_form">
                <h1>订单详情</h1>
                <h2>订单信息</h2>
                <ul>
                    <li><b>买家名称：</b><?=$detail['buyername']?></li>
                    <li><b>卖家名称：</b><?=$detail['sellername']?></li>
                    <li><b>支付方式：</b><?=$detail['paymentname']?($detail['paymentname']=='cod'?'<span class="font_1">货到付款</span>':lang('pay_name_'.$detail['paymentname'])):'N/A'?></li>
                    <li><b>下单时间：</b><?=date('Y-m-d H:i:s',$detail['addtime'])?></li>
                    <?php if($detail['truebuyer_id'] > 0){ ?>
                    <li class="red_common"><b >新人ID：</b><?=$detail['truebuyer_id']?></li>
                    <li class="red_common"><b>新人登录账号：</b><?=$detail['truebuyer_name']?></li>
                    <?php } ?>
                </ul>
                <div class="clear"></div>
            </div>

            <div class="order_form">
                <h2>收货人及发货信息</h2>
                <ul>
                    <li><b>收货人姓名：</b><?=$detail['username']?></li>
                    <li><b>邮政编码：</b><?=$detail['zipcode']?></li>
                    <li><b>手机号码：</b><?=$detail['mobile']?></li>
                    <li><b>详细地址：</b><?=$detail['address']?></li>
                    <li><b>配送方式：</b><?=$detail['shipname']?></li>
                    <li><b>物流费用：</b><b class="font_1">&yen;<?=$detail['shipfee']?></b></li>
                </ul>
                <div class="clear"></div>
            </div>
            <div class="order_form">
                <h2>商品信息</h2>
                <?php $_QUERY['get__val']=$_G['datacall']->datacall_get('getproduct',array('orderid'=>$orderid,),'product');
                if(is_array($_QUERY['get__val']))foreach($_QUERY['get__val'] as $_val_k => $_val) { ?>
                <div class="order_info" style="margin-bottom:5px;">
                    <a href="<?=url('product/detail/pid/'.$_val['pid'])?>" target="_blank" class="order_info_pic">
                        <img src="<?=URLROOT?>/<? if($_val['goods_image']) { ?><?=$_val['goods_image']?><? } else { ?>static/images/noimg.gif<? } ?>" width="60" height="60"  />
                    </a>
                    <div class="order_info_text">
                        <p><a href="<?=url('product/detail/pid/'.$_val['pid'])?>" target="_blank"><?=$_val['pname']?></a></p>
                        <p class="font_2">
                            <?php
                                $buyattr = $_val['buyattr']?unserialize($_val['buyattr']):array();
                                foreach ($buyattr as $key => $value):
                            ?>
                            <span><?=$value['name']?>：</span><span><?=$value['value']?></span>
                            <?php endforeach;?>
                        </p>
                        <p><b>单价：</b> <span class="red_common">&yen;<?=$_val['price']?></span></p>
                        <p><b>数量：</b><?=$_val['quantity']?></p>
                    </div>
                </div>
                <?php } ?>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <center>
        <button type="button" class="btn" onclick="history.go(-1);">返回</button>
    </center>
</form>
</div>