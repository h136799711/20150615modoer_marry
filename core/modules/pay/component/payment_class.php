<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class mc_pay_payment extends ms_base {

    public $name = '';

    public $config = array();      //支付模块配置信息
    public $pay = null;            //支付接口使用记录类

    public $notify_url = '';       //支付成功时，接口商发送的通知地址
    public $return_url = '';       //支付完成后的返回地址
    public $charset = '';          //编码
    public $debug = true;          //日志文件记录debug信息

    public $notify_msg = '';       //准备向支付接口通知反馈信息
    public $notify_return = TRUE;  //是否发送通知反馈信息
    public $notify_end = FALSE;    //是否在接口发送通知反馈信息时结束流程（判断完毕后直接跳转callback_url）

    /**
     * 实例化一个支付类
     * @param  string $payment 支付接口标识
     * @param  boolean $return_classname 是否值返回一个类名（用于静态调用）
     * @return mixed
     */
    final public static function factory($payment, $return_classname = false)
    {
        if(!$payment) return false;

        $classname = 'payment_'.$payment;
        if(!class_exists($classname)) {
            $path = MUDDER_CORE.'modules'.DS.'pay'.DS.'component'.DS.'payment'.DS;
            $file = $path.$payment."_class.php";
            if(!is_file($file)) {
                show_error(lang('global_file_not_exist', str_replace(MUDDER_ROOT, '/', $file)));
            }
            include_once $file;
            if(!class_exists($classname)) {
                show_error(lang('global_class_not_found', $classname));
            }            
        }

        return $return_classname ? $classname : new $classname;
    }

    function __construct() {
        parent::__construct();
        $this->config = $this->loader->variable('config','pay');
        $this->charset = $this->global['charset'];
        $this->pay = $this->loader->model(':pay');
    }

    //返回PAY表提供的包涵payid的惟一id
    function get_unid() {}

    //返回支付平台内部的订单ID
    function get_payment_orderid() {}

    //生成支付连接并进入支付页面
    function goto_pay($payid) {}

    //支付平台在支付成功后，向我方发布的异步通知，我方进行检查是否，并处理业务
    function notify_check() {}

    //有些支付平台需要在通知后，再有我方发送已经接收的信息，否则可能会定制向我方发送支付成功的通知
    function notify_return() {
        echo $this->notify_msg;
        if(DEBUG) {
            $this->_log_result("异步通知返回：{$this->notify_msg}", true);
        }
        exit;
    }

    //支付完成返回后的验证寄回，我方进行检查是否，并处理业务
    function return_check() {}

    //错误信息打印
    function halt($code, $message, $url) {
        $content = '<p>';
        $content .= '<b>message:</b>' . lang($message) . '<br /><br />';
        $content .= '<b>code:</b>' . $code . '<br /><br />';
        $content .= '<b>url:</b>' . $url;
        $content .= '</p>';
        show_error($content);
    }

    //跳转
    function goto_url($url) {
        $url = str_replace('&amp;','&', $url);
        $strMsg = "<html><head><meta name=\"Modoer Pay\" content=\"ModoerStudio\"></head>\n";
        $strMsg .= "<body><script language=javascript>\n";
        $strMsg .= "window.location.href='" . $url . "';\n";
        $strMsg .= "</script></body></html>";
        exit($strMsg);
    }

    function do_get($url) {
        $url = str_replace('&amp;', '&', $url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result =  curl_exec($ch);
        //错误记录
        $error = curl_error($ch);
        if($error) {
            $message = "GET操作失败(do_get)\t$error\n$url";
            $this->_log_result($message);
        }
        curl_close($ch);
        return $result;
    }

    function do_post($url, $params) {
        $fields_string = '';
        foreach($params as $key=>$value){
            $fields_string .="{$key}={$value}&";
        }
        rtrim($fields_string,'&');
        if(substr($url, -1)!='?') $url .= '?';
        if(!function_exists('curl_exec')) {
            $this->_log_result('服务器PHP未开启 curl 库.');
            return false;
        }
        $ch = curl_init ($url);
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  false);
        $result = curl_exec($ch);
        //错误记录
        $error = curl_error($ch);
        if($error) {
            $message = "POST操作失败(do_post)\t$error\n$url";
            $this->_log_result($message);
        }
        curl_close ($ch);  
        return $result;
    }


    //安全证书方式GET
    function do_get_cacert($url, $cacert_path) {
        if(!function_exists('curl_exec')) {
            $this->_log_result('服务器PHP未开启 curl 库.');
            return false;
        }
        $url = str_replace('&amp;', '&', $url);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
        curl_setopt($curl, CURLOPT_CAINFO, $cacert_path);//证书地址
        $result = curl_exec($curl);
        //错误记录
        $error = curl_error($curl);
        if($error) {
            $message = "POST操作失败(do_get_cacert)\t$error\n$url";
            $this->_log_result($message);
        }
        curl_close($curl);
        return $result;
    }

    //安全证书方式POST
    function do_post_cacert($url, $params, $cacert_path) {
        if(!function_exists('curl_exec')) {
            $this->_log_result('服务器PHP未开启 curl 库.');
            return false;
        }
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
        curl_setopt($curl, CURLOPT_CAINFO, $cacert_path);//证书地址
        curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        curl_setopt($curl,CURLOPT_POST, true); // post传输数据
        curl_setopt($curl,CURLOPT_POSTFIELDS, $params);// post传输数据
        $result = curl_exec($curl);
        //错误记录
        $error = curl_error($curl);
        if($error) {
            $message = "POST操作失败(do_post_cacert)\t$error\n$url";
            $this->_log_result($message);
        }
        curl_close($curl);
        return $result;
    }

    //日志消息,把返回的参数记录下来
    function _log_result($word, $is_debug = false) {
        if(!$is_debug || $is_debug && !$this->debug) return;
        $content = "\r\nexec date：".strftime("%Y%m%d%H%M%S", $this->global['timestamp'])."\r\n".$word."\r\n";
        $filename = str_replace('msm_', '', get_class($this));
        log_write($filename, $content);
    }

}
