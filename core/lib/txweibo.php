<?php
/**
 * tentect weibo
 */
!defined('IN_MUDDER') && exit('Access Denied');
class ms_txweibo {

    private $appid     = '';
    private $appkey  = '';
    private $token      = '';
    private $openid      = '';
    private $client     = null;

    public function __construct() {
        if(!class_exists('TXweiboClientV2')) {
            include_once MUDDER_ROOT . 'api' . DS . 'txweibooauth2.php';
        }
        $modcfg = _G('loader')->variable('config','member');
        $this->appid = $modcfg['passport_txweibo_appid'];
        $this->appkey = $modcfg['passport_txweibo_appkey'];

        $token = _G('loader')->model('member:passport')->get_token(_G('user')->uid, 'txweibo');
        $this->token = $token['access_token'];
        $this->expired = $token['expired'];
        $this->openid = $token['psuid'];

        $this->client = new TXweiboClientV2($this->appid, $this->appkey, $this->token, $this->openid);
    }

    private function check_token_expired() {
        return _G('timestamp') < $this->expired;
    }

    public function post_text($content = '', $imagse = array(), $urls = array()) {
        if(!$this->check_token_expired()||!$content) return;
        if(strtoupper(_G('charset')) != 'UTF-8') {
            $content = charset_convert($content, _G('charset'), 'utf-8');
        }
        $result = $this->client->add_t($content);
        if($result['error_code']) {
            //error log
            $result['user'] = $this->global['user']->uid . "\t" . $this->global['user']->username;
            log_write('sina_weibo', $result);
            return false;
        }
        return true;
    }
}
?>