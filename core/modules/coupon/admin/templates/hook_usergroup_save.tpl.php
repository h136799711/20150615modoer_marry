<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<tr>
    <td class="altbg1"><strong>允许本组会员打印优惠券:</strong>开启本功能后，本组会员便可以在前台打印主题优惠券</td>
    <td><?=form_bool('access[coupon_print]', $access['coupon_print'])?></td>
</tr>
<tr>
    <td class="altbg1"><strong>允许本组会员发送优惠券短信息:</strong>开启本功能后，本组会员便可以在前台使用发送优惠券短信息的功能（前提是优惠券里具有发送短信息的功能）</td>
    <td><?=form_bool('access[coupon_sendsms]', $access['coupon_sendsms'])?></td>
</tr>