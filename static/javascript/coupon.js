
function send_coupon_sms(couponid) {
    if (!is_numeric(couponid)) {alert('无效的couponid'); return;}
    $.post(Url('coupon/detail/id/'+couponid+'/do/sendsms'), 
    { couponid:couponid,in_ajax:1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            dlgOpen('发送优惠短信息', result, 400);
        }
    });
}