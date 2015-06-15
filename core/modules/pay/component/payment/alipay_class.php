<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class payment_alipay extends mc_pay_payment {

    var $gateway = "https://mapi.alipay.com/gateway.do?";
    var $security_code  = '';
    var $sign_type = 'MD5';
    var $mysgin = '';

    var $notify_return = TRUE;
    var $notify_end = FALSE;
    var $paytype = 1; //1:即时到帐,2:担保交易,3:双功能

    var $royalty = ''; //分润参数

    var $out_trade_no = ''; //验证时返回的商家订单ID
    var $trade_no = ''; //验证时返回的支付宝订单ID

    function __construct() {
        parent::__construct();
        $this->notify_url = _G('cfg','siteurl') . 'api/payment/alipay/notify.php';
        $this->return_url = _G('cfg','siteurl') . 'api/payment/alipay/return.php';
        $this->paytype = (int)$this->config['alipay_paytype'];
        if($this->charset == 'gb2312') $this->charset = 'GBK';
        $this->security_code = $this->config['alipay_key'];
    }

    function get_unid() {
        if($this->out_trade_no) return $this->out_trade_no;
        $payid = $_POST['out_trade_no']? $_POST['out_trade_no'] : $_GET['out_trade_no'];
        return _T($payid);
    }

    function get_payment_orderid() {
        if($this->trade_no) return $this->trade_no;
        $orderid = $_POST['trade_no']? $_POST['trade_no'] : $_GET['trade_no'];
        return _T($orderid);
    }

    function goto_pay($payid, $unid) {
        if(!$pay = $this->pay->read($payid)) redirect('pay_order_empty');
        $price = $pay['price'];
        $title = $pay['order_name'];
        $goods = $pay['goods'] ? unserialize($pay['goods']) : array();
        if($pay['royalty']) $this->royalty = $pay['royalty'];
        $content = $this->create_payurl($title, $price, $unid, $goods);
        if(!$content) redirect('pay_tenpay_url_empty');
        echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';
        echo '<html><head>';
        echo '<meta http-equiv="Content-Type" content="text/html; charset='.$this->charset.'">';
        echo '</head>';
        echo '<body onload="javascript:document.alipaysubmit.submit();">';
        echo '<h3>Wait for a moment please.</h3>';
        echo $content;
        echo '</body></html>';
        exit();
    }

    function check_payurl() {
        $retcode = 0;
        if($retcode < 0) redirect($retmsg);
        return $retcode;
    }

    function create_payurl($title, $price, $unid, $goods=array()) {
        $services = array(
            '1' => 'create_direct_pay_by_user',
            '2' => 'create_partner_trade_by_buyer',
            '3' => 'trade_create_by_buyer',
        );
        //构造要请求的参数数组
        $parameter = array(
            "service"         => $services[$this->paytype],
            "payment_type"    => "1",
            //获取配置文件
            "partner"         => $this->config['alipay_partnerid'],
            "seller_email"    => $this->config['alipay_id'],
            "return_url"      => $this->return_url,
            "notify_url"      => $this->notify_url,
            "_input_charset"  => $this->charset,
            "show_url"        => _G('cfg','siteurl'),
            //从订单数据中动态获取到的必填参数
            "out_trade_no"    => $unid,
            "subject"         => $title,
            "body"            => $unid,
            "total_fee"       => $price,
        );

        //增加分润参数
        if($this->royalty && $this->paytype == '1') {
            $parameter["royalty_type"] = "10";
            $parameter["royalty_parameters"] = $this->royalty; //"111@126.com^0.01^分润备注一|222@126.com^0.01^分润备注二";
        }
        //担保交易琥珀双接口
        if($this->paytype == '2' || $this->paytype == '3') {
            if(!$goods) {
                $parameter['price'] = $price;
                $parameter['quantity'] = 1;
            } else {
                $parameter['price'] = $goods['price'];
                $parameter['quantity'] = $goods['num'];
            }
            $parameter['logistics_type'] = 'EXPRESS';
            $parameter['logistics_fee'] = '0';
            $parameter['logistics_payment'] = 'SELLER_PAY';
            $parameter['receive_name'] = '详见网站订单'; //_T($goods['linkman']);
            $parameter['receive_address'] = '详见网站订单';//_T($goods['address']);
            $parameter['receive_zip'] = '详见网站订单';//_T($goods['postcode']);
            $parameter['receive_mobile'] = '详见网站订单';//_T($goods['mobile']);
        }
        $parameter = $this->_para_filter($parameter);
        $this->mysgin = $this->_create_sgin($parameter, FALSE);

        /*
        $arg = $this->_create_linkstring($parameter);
        $url = $this->gateway . $arg . "&sign=" .$this->mysgin . "&sign_type=" . $this->sign_type;
        */
        //生成表单自动提交
        $content = "<form id='alipaysubmit' name='alipaysubmit' action='".$this->gateway."_input_charset=".$this->charset."' method='post'>\r\n";
        foreach($parameter as $key => $val) {
            $content .= "<input type='hidden' name='".$key."' value='".$val."'/>\r\n";
        }
        $content .= "<input type='hidden' name='sign' value='".$this->mysgin."'/>\r\n";
        $content .= "<input type='hidden' name='sign_type' value='".$this->sign_type."'/>\r\n";
        $content .= "</form>";
        return $content;
    }

    //发货
    function send_goods_url($trade_no) {
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service"           => "send_goods_confirm_by_platform",
            "partner"           => trim($this->config['alipay_partnerid']),
            "_input_charset"    => trim(strtolower($this->charset)),
            "trade_no"          => $trade_no, //支付宝交易号
            "logistics_name"    => '详见网站订单物流信息', //物流公司名称
            "invoice_no"        => '详见网站订单物流信息', //物流发货单号
            "transport_type"    => 'EXPRESS', //POST（平邮）、EXPRESS（快递）、EMS（EMS）
        );
        $parameter = $this->_para_filter($parameter);
        $this->mysgin = $this->_create_sgin($parameter, FALSE);
        $parameter['sign'] = $this->mysgin;
        $parameter['sign_type'] = $this->sign_type;

        //发送
        $xml_data = $this->do_post($this->gateway, $parameter);
        //测试保存
        $this->_log_result($xml_data);
        //解析XML
        //$doc = new DOMDocument();
        //$doc->loadXML($xml_data);
        //return $doc;
    }

    //担保交易异步检测
    function notify_check() {
        $result = false;
        $verify_result = $this->_notify_check();
        if($verify_result) {
            $this->out_trade_no = _post('out_trade_no', '', MF_TEXT);
            $this->trade_no = _post('trade_no', '', MF_TEXT);
            /*
                即时到帐返回
                TRADE_FINISHED:交易完成
                TRADE_SUCCESS:交易成功
                担保交易返回/双接口
                WAIT_BUYER_PAY:该判断表示买家已在支付宝交易管理中产生了交易记录，但没有付款
                WAIT_SELLER_SEND_GOODS:该判断表示买家已在支付宝交易管理中产生了交易记录且付款成功，但卖家没有发货
                WAIT_BUYER_CONFIRM_GOODS:该判断表示卖家已经发了货，但买家还没有做确认收货的操作
                TRADE_FINISHED:该判断表示买家已经确认收货，这笔交易完成
            */
            $this->notify_msg = "success";
            $trade_status = trim($_POST['trade_status']);
            if(in_array($trade_status, array(
                'TRADE_FINISHED',
                'TRADE_SUCCESS',
                'WAIT_SELLER_SEND_GOODS',
                'WAIT_BUYER_CONFIRM_GOODS',
                'TRADE_FINISHED'))) {
                $result = true;
                //自动设置为发货
                if($trade_status = 'WAIT_SELLER_SEND_GOODS') {
                    $this->send_goods_url($_POST['trade_no']);
                }
            } else {
                $this->_log_result($unid . ':' . $port_orderid . $_POST['trade_status']);
            }
        } else {
            $this->notify_msg = "fail";
            $this->log_result ($unid . ':' . $port_orderid . ':fail');
        }
        return $result;
    }

    function _create_sgin($params, $utf8urlencode = FALSE) {
        ksort($params);
        reset($params);
        if($this->charset == 'utf-8' && $utf8urlencode) {
            $fun = $this->_create_linkstring_urlencode($params);
        } else {
            $prestr = $this->_create_linkstring($params);
        }
        $mysgin = md5($prestr . $this->security_code);
        return $mysgin;
    }

    function _create_linkstring($array) {
        $arg = $split = "";
        foreach($array as $key=>$val) {
            $arg .= $split . $key . "=" . $val;
            $split = "&";
        }
        return $arg;
    }

    function _create_linkstring_urlencode($array) {
        $arg = '';
        foreach($array as $key => $val) {
            if ($key == "subject" || $key == "body" || $key == "extra_common_param" || $key == "royalty_parameters") {
                $arg .= $key . "=" . urlencode($val) . "&";
            } else {
                $arg .= $key . "=" . $val . "&";
            }
        }
        $arg = substr($arg, 0, count($arg) - 2); //去掉最后一个&字符
        return $arg;
    }

    function _notify_check() {
        //'https://mapi.alipay.com/gateway.do?service=notify_verify&';
        $params = array();
        $params['service'] = 'notify_verify';
        $params['partner'] = $this->config['alipay_partnerid'];
        $params['notify_id'] = $_POST["notify_id"];
        //$veryfy_url = $this->gateway. "service=notify_verify" . "&partner=".$this->config['alipay_partnerid']."&notify_id=".$_POST["notify_id"];
        $veryfy_result = $this->_get_verify($params);
        if(empty($_POST)) { //判断POST来的数组是否为空
            return FALSE;
        } else {
            $post = $this->_para_filter($_POST); //对所有POST返回的参数去空
            $this->mysign = $this->_create_sgin($post); //生成签名结果

            $this->_log_result($this->mysign);

            //写日志记录
            $this->_log_result("veryfy_result=".$veryfy_result."\r\n notify_url_log:sign=".$_POST["sign"]."&mysign=".$this->mysign.",".$this->_create_linkstring($post));

            // mysign与sign不等，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
            if (preg_match("/true$/i", $veryfy_result) && $this->mysign == $_POST["sign"]) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**获取远程服务器ATN结果
     *$url 指定URL路径地址
     *return 服务器ATN结果集
     */
    function _get_verify($params,$time_out = "60") {
        if(function_exists('curl_exec')) {
            return $this->do_post($this->gateway, $params);
        }
        $fields_string = '';
        foreach($params as $key => $value){
            $fields_string .= "{$key}={$value}&";
        }
        rtrim($fields_string,'&');
        $url = $this->gateway . $fields_string;
        $urlarr     = parse_url($url);
        $errno      = "";
        $errstr     = "";
        $transports = "";
        if($urlarr["scheme"] == "https") {
            $transports = "ssl://";
            $urlarr["port"] = "443";
        } else {
            $transports = "tcp://";
            $urlarr["port"] = "80";
        }
        $newurl = $transports . $urlarr['host'];
        $this->_log_result($newurl);
        $fp = @fsockopen($newurl, $urlarr['port'], $errno, $errstr, $time_out);
        if(!$fp) {
            $this->_log_result("ERROR: $errno - $errstr");
            die("ERROR: $errno - $errstr<br />\n");
        } else {
            fputs($fp, "POST ".$urlarr["path"]." HTTP/1.1\r\n");
            fputs($fp, "Host: ".$urlarr["host"]."\r\n");
            fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
            fputs($fp, "Content-length: ".strlen($urlarr["query"])."\r\n");
            fputs($fp, "Connection: close\r\n\r\n");
            fputs($fp, $urlarr["query"] . "\r\n\r\n");
            while(!feof($fp)) {
                $info[] = @fgets($fp, 1024);
            }
            fclose($fp);
            $info = implode(",",$info);
            return $info;
        }
    }

    /**除去数组中的空值和签名参数
     *$parameter 加密参数组
     *return 去掉空值与签名参数后的新加密参数组
     */
    function _para_filter($parameter) {
        $para = array();
        foreach($parameter as $key => $val) {
            if($key == "sign" || $key == "sign_type" || $val == "") continue;
            else $para[$key] = $parameter[$key];
        }
        return $para;
    }
}
?>