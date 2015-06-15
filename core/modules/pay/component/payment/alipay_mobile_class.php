<?php
/**
 * 支付宝手机网站支付
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2009 Moufersoft
 * @website www.modoer.com
 */
!defined('IN_MUDDER') && exit('Access Denied');

class payment_alipay_mobile extends mc_pay_payment {

    var $alipay_gateway_new = 'http://wappaygw.alipay.com/service/rest.htm?';
    /**
     * HTTPS形式消息验证地址
     */
    var $https_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';
    /**
     * HTTP形式消息验证地址
     */
    var $http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';


    var $transport = 'http';    //http Or https
    var $sign_type = 'MD5';
    var $security_code  = '';

    var $cacert_path = '';

    var $notify_return = TRUE;
    var $notify_end = FALSE;

    var $out_trade_no = ''; //验证时返回的商家订单ID
    var $trade_no = ''; //验证时返回的支付宝订单ID

    function __construct() {
        parent::__construct();
        //异步通知
        $this->notify_url = _G('cfg','siteurl') . 'api/payment/alipay_mobile/notify.php';
        //支付返回
        $this->return_url = _G('cfg','siteurl') . 'api/payment/alipay_mobile/return.php';
        //中断操作
        $this->merchant_url = _G('cfg','siteurl') . 'api/payment/alipay_mobile/merchant.php';
        //编码
        //$this->charset = _G('charset');
        //if($this->charset == 'gb2312') $this->charset = 'GBK';
        $this->charset = 'UTF-8';
        //MD5安全校验码
        $this->security_code = $this->config['alipay_mobile_key'];
        //ssl证书位置
        $this->cacert_path = MUDDER_ROOT.'api'.DS.'payment'.DS.'alipay_mobile'.DS.'cacert.pem';
    }

    function get_unid() {
        if($this->out_trade_no) return $this->out_trade_no;
        $payid = $_POST['out_trade_no']? $_POST['out_trade_no'] : $_GET['out_trade_no'];
        return trim($payid);
    }

    function get_payment_orderid() {
        if($this->trade_no) return $this->trade_no;
        $orderid = $_POST['trade_no']? $_POST['trade_no'] : $_GET['trade_no'];
        return trim($orderid);
    }

    //跳转到支付平台
    function goto_pay($payid, $unid) {
        if(!$pay = $this->pay->read($payid)) redirect('pay_order_empty');
        $price = $pay['price'];
        $title = $pay['order_name'];
        $goods = $pay['goods'] ? unserialize($pay['goods']) : array();

        //转码
        if(_G('charset') != 'utf-8') {
            $title = charset_convert($title, _G('charset'), 'utf-8');
            foreach($goods as $k=>$v) {
                $goods[$k] = charset_convert($v, _G('charset'), 'utf-8');
            }
        }
    
        $content = $this->create_payurl($title, $price, $unid, $goods);
        if(!$content) redirect('pay_tenpay_url_empty');
        echo '<!DOCTYPE html>';
        echo '<html><head>';
        echo '<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" >';
        echo '<meta charset="'.$this->charset.'" />';
        echo '</head>';
        echo '<body>';
        echo '<h4>正在跳转进入支付网站，请稍后...</h4>';
        echo $content;
        echo '</body></html>';
        exit();
    }

    function check_payurl() {
        $retcode = 0;
        if($retcode < 0) redirect($retmsg);
        return $retcode;
    }

    //生成支付链接
    function create_payurl($title, $price, $unid, $goods=array()) {

        //请求业务参数详细
        $req_data = '<direct_trade_create_req><notify_url>' 
        . $this->notify_url . '</notify_url><call_back_url>' 
        . $this->return_url . '</call_back_url><seller_account_name>' 
        . $this->config['alipay_mobile_id'] . '</seller_account_name><out_trade_no>' 
        . $unid . '</out_trade_no><subject>' 
        . $title . '</subject><total_fee>' 
        . $price . '</total_fee><merchant_url>' 
        . $this->merchant_url . '</merchant_url></direct_trade_create_req>';

        //构造要请求的参数数组
        $parameter = array(
            "service" => "",
            "partner" => trim($this->config['alipay_mobile_partnerid']),
            "sec_id"    => $this->sign_type,
            "format"    => 'xml',
            "v"         => '2.0',
            "req_id"    => $unid,
            "req_data"  => $req_data,
            "_input_charset"    => $this->charset,
        );

        $para_direct = $parameter;
        $para_direct['service'] = 'alipay.wap.trade.create.direct';
        $para_direct = $this->build_request_para($para_direct);

        //远程获取 request_token 
        $html_text = parent::do_post_cacert($this->alipay_gateway_new, $para_direct, $this->cacert_path);

        //URLDECODE返回的信息
        $html_text = urldecode($html_text);
        //解析远程模拟提交后返回的信息
        $para_html_text = $this->_parse_response($html_text);

        if($para_html_text['res_error']) {
            if(_G('charset') != 'utf-8') {
                $para_html_text['res_error'] = charset_convert($para_html_text['res_error'],'utf-8',_G('charset'));
            }
            $this->_log_result($para_html_text['res_error']);
        }

        //获取request_token
        $request_token = $para_html_text['request_token'];
        //业务详细
        $req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';

        //构造要请求的参数数组
        $para_form = $parameter;
        $para_form['service'] = 'alipay.wap.auth.authAndExecute';
        $para_form['req_data'] = $req_data;
        $para_form = $this->build_request_para($para_form);

        //生成表单自动提交
        $content = $this->build_request_form($para_form, 'get', '确认');

        return $content;
    }

    //交易时服务器异步验证
    function notify_check() {
        
        //$this->_log_result("notify:\r\n".$_POST['notify_data']);

        $result = false;
        if(empty($_POST)) { //判断POST来的数组是否为空
            return false;
        }
        $verify_result = $this->_notify_check();
        if($verify_result) {

            $doc = new DOMDocument();   
            $doc->loadXML($_POST['notify_data']);

            if( ! empty($doc->getElementsByTagName( "notify" )->item(0)->nodeValue) ) {
                //商户订单号
                $out_trade_no = $doc->getElementsByTagName( "out_trade_no" )->item(0)->nodeValue;
                //支付宝交易号
                $trade_no = $doc->getElementsByTagName( "trade_no" )->item(0)->nodeValue;
                //交易状态
                $trade_status = $doc->getElementsByTagName( "trade_status" )->item(0)->nodeValue;

                $this->out_trade_no = $out_trade_no;
                $this->trade_no = $trade_no;
                
                if($trade_status == 'TRADE_FINISHED') {
                    //判断该笔订单是否在商户网站中已经做过处理
                    //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                    //如果有做过处理，不执行商户的业务程序
                            
                    //注意：
                    //该种交易状态只在两种情况下出现
                    //1、开通了普通即时到账，买家付款成功后。
                    //2、开通了高级即时到账，从该笔交易成功时间算起，过了签约时的可退款时限（如：三个月以内可退款、一年以内可退款等）后。
            
                    //调试用，写文本函数记录程序运行情况是否正常
                    //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
                    $result = true;
                    $this->notify_msg = "success";     //请不要修改或删除
                } else if ($trade_status == 'TRADE_SUCCESS') {
                    //判断该笔订单是否在商户网站中已经做过处理
                    //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                    //如果有做过处理，不执行商户的业务程序
                            
                    //注意：
                    //该种交易状态只在一种情况下出现——开通了高级即时到账，买家付款成功后。
            
                    //调试用，写文本函数记录程序运行情况是否正常
                    //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
                    $result = true;
                    $this->notify_msg = "success";     //请不要修改或删除
                } else {
                    $this->_log_result("out_trade_no:{$out_trade_no}\ttrade_no:{$trade_no}\ttrade_status:{$trade_status}");
                }
            } else {
                $this->_log_result("notify not found:\n{$_POST['notify_data']}");
            }
        } else {
            $this->notify_msg = "fail";
            $this->_log_result("fail\tout_trade_no:{$out_trade_no}\ttrade_no:{$trade_no}\ttrade_status:{$trade_status}");
        }

        //$this->_log_result("notify result\ttrade_no:{$trade_no}\t". ($result?'TRUE':'FALSE'));
        return $result;
    }

    //交易返回页面时验证
    function return_check() {
        if(empty($_GET)) {//判断GET来的数组是否为空
            return false;
        } else {
            $this->out_trade_no = _get('out_trade_no', '', MF_TEXT);
            $this->trade_no     = _get('trade_no', '', MF_TEXT);
            //生成签名结果
            $isSign = $this->get_sign_veryfy($_GET, $_GET["sign"], true);
            //验证
            //$responsetTxt的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
            //isSign的结果不是true，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
            if ($isSign) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * 建立请求，以表单HTML形式构造（默认）
     * @param $para_temp 请求参数数组
     * @param $method 提交方式。两个值可选：post、get
     * @param $button_name 确认按钮显示文字
     * @return 提交表单HTML文本
     */
    function build_request_form($para_temp, $method, $button_name) {
        //待请求参数数组
        $para = $this->build_request_para($para_temp);
        
        $sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='".$this->alipay_gateway_new
            ."_input_charset=".trim(strtolower($this->charset))."' method='".$method."'>";
        while (list ($key, $val) = each ($para)) {
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }

        //submit按钮控件请不要含有name属性
        $sHtml = $sHtml."<input type='submit' value='".$button_name."'></form>";
        $sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";

        return $sHtml;
    }

    /**
     * 生成要请求给支付宝的参数数组
     * @param $para_temp 请求前的参数数组
     * @return 要请求的参数数组
     */
    function build_request_para($para_temp) {
        //除去待签名参数数组中的空值和签名参数
        $para_filter = $this->_para_filter($para_temp);

        //对待签名参数数组排序
        $para_sort = $this->_arg_sort($para_filter);

        //生成签名结果
        $mysign = $this->_create_sgin($para_sort);
        
        //签名结果与签名方式加入请求提交参数组中
        $para_sort['sign'] = $mysign;
        if($para_sort['service'] != 'alipay.wap.trade.create.direct' 
                && $para_sort['service'] != 'alipay.wap.auth.authAndExecute') {
            $para_sort['sign_type'] = strtoupper(trim($this->sign_type));
        }
        
        return $para_sort;
    }

    function _create_sgin($params) {
        //生成连接
        $prestr = $this->_create_linkstring($params);
        //和MD5安全校验码组合MD5操作
        $mysgin = md5($prestr . $this->security_code);
        return $mysgin;
    }

    /**
     * 对数组排序
     * @param $para 排序前的数组
     * return 排序后的数组
     */
    function _arg_sort($para) {
        ksort($para);
        reset($para);
        return $para;
    }

    /**除去数组中的空值和签名参数
     *$parameter 加密参数组
     *return 去掉空值与签名参数后的新加密参数组
     */
    function _para_filter($parameter) {
        $para = array();
        foreach($parameter as $key => $val) {
            if(in_array($key, array("sign", "sign_type", "act", "api", "m", "offset", "page")) || $val == "") continue;
            else $para[$key] = $parameter[$key];
        }
        return $para;
    }

    //数组拼装成URL参数连接
    function _create_linkstring($array) {
        $arg = $split = "";
        foreach($array as $key=>$val) {
            $arg .= $split . $key . "=" . $val;
            $split = "&";
        }
        return $arg;
    }

    //数组拼装成URL参数连接，参数值进行urlencode编码
    function _create_linkstring_urlencode($para) {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=$key."=".urlencode($val)."&";
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);
        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){
            $arg = stripslashes($arg);
        }
        return $arg;
    }

    /**
     * 解析远程模拟提交后返回的信息
     * @param $str_text 要解析的字符串
     * @return 解析结果
     */
    function _parse_response($str_text) {
        //以“&”字符切割字符串
        $para_split = explode('&',$str_text);
        //把切割后的字符串数组变成变量与数值组合的数组
        foreach ($para_split as $item) {
            //获得第一个=字符的位置
            $nPos = strpos($item,'=');
            //获得字符串长度
            $nLen = strlen($item);
            //获得变量名
            $key = substr($item,0,$nPos);
            //获得数值
            $value = substr($item,$nPos+1,$nLen-$nPos-1);
            //放入数组中
            $para_text[$key] = $value;
        }
        
        if( ! empty ($para_text['res_data'])) {
            //解析加密部分字符串
            /*
            if($this->alipay_config['sign_type'] == '0001') {
                $para_text['res_data'] = rsaDecrypt($para_text['res_data'], $this->alipay_config['private_key_path']);
            }
            */
            //token从res_data中解析出来（也就是说res_data中已经包含token的内容）
            $doc = new DOMDocument();
            $doc->loadXML($para_text['res_data']);
            $para_text['request_token'] = $doc->getElementsByTagName( "request_token" )->item(0)->nodeValue;
        }
        
        return $para_text;
    }


    /**
     * 异步验证
     * @return [type] [description]
     */
    function _notify_check() {
        //对notify_data解密
        $decrypt_post_para = $_POST;

        //notify_id从decrypt_post_para中解析出来（也就是说decrypt_post_para中已经包含notify_id的内容）
        $doc = new DOMDocument();
        $doc->loadXML($decrypt_post_para['notify_data']);
        $notify_id = $doc->getElementsByTagName( "notify_id" )->item(0)->nodeValue;

        //获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
        $responseTxt = 'true';
        if (! empty($notify_id)) {
            $responseTxt = $this->get_response($notify_id);
            $this->_log_result("支付宝远程服务器ATN结果:{$responseTxt}", true);
        }
        
        //生成签名结果
        $isSign = $this->get_sign_veryfy($decrypt_post_para, $_POST["sign"], false);

        //写日志记录
        if ($isSign) {
            $isSignStr = 'true';
        } else {
            $isSignStr = 'false';
        }
        $log_text = "responseTxt=".$responseTxt."\n notify_url_log:isSign=".$isSignStr.",";
        $log_text = $log_text.$this->_create_linkstring($_POST);
        if(!$isSign) $this->_log_result($log_text);

        //验证
        //$responsetTxt的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
        //isSign的结果不是true，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
        if (preg_match("/true$/i",$responseTxt) && $isSign) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * 获取远程服务器ATN结果,验证返回URL
     * @param $notify_id 通知校验ID
     * @return 服务器ATN结果
     * 验证结果集：
     * invalid命令参数不对 出现这个错误，请检测返回处理中partner和key是否为空 
     * true 返回正确信息
     * false 请检查防火墙或者是服务器阻止端口问题以及验证时间是否超过一分钟
     */
    function get_response($notify_id) {
        $transport = strtolower(trim($this->transport));
        $partner = trim($this->config['alipay_mobile_partnerid']);
        $veryfy_url = '';
        if($transport == 'https') {
            $veryfy_url = $this->https_verify_url;
        } else {
            $veryfy_url = $this->http_verify_url;
        }
        $veryfy_url = $veryfy_url."partner=" . $partner . "&notify_id=" . $notify_id;
        $responseTxt = parent::do_get_cacert($veryfy_url, $this->cacert_path);
        
        return $responseTxt;
    }

    /**
     * 获取返回时的签名验证结果
     * @param $para_temp 通知返回来的参数数组
     * @param $sign 返回的签名结果
     * @param $isSort 是否对待签名数组排序
     * @return 签名验证结果
     */
    function get_sign_veryfy($para_temp, $sign, $isSort) {
        //除去待签名参数数组中的空值和签名参数
        $para = $this->_para_filter($para_temp);
        
        //对待签名参数数组排序
        if($isSort) {
            $para = $this->_arg_sort($para);
        } else {
            $para = $this->sort_notify_para($para);
        }
        
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = $this->_create_linkstring($para);
        
        $isSgin = $this->md5_verify($prestr, $sign, $this->security_code);

        return $isSgin;
    }

    /**
     * 异步通知时，对参数做固定排序
     * @param $para 排序前的参数组
     * @return 排序后的参数组
     */
    function sort_notify_para($para) {
        $para_sort['service'] = $para['service'];
        $para_sort['v'] = $para['v'];
        $para_sort['sec_id'] = $para['sec_id'];
        $para_sort['notify_data'] = $para['notify_data'];
        return $para_sort;
    }

    /**
     * 验证签名
     * @param $prestr 需要签名的字符串
     * @param $sign 签名结果
     * @param $key 私钥
     * return 签名结果
     */
    function md5_verify($prestr, $sign, $key) {
        $prestr = $prestr . $key;
        $mysgin = md5($prestr);
        return $mysgin == $sign;
    }

}

/** end **/