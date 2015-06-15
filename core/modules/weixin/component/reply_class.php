<?php
/**
* 微信信息被动回复
*/
class mc_weixin_reply
{
    private $acc_obj = null;

    public $ToUserName;
    public $FromUserName;
    public $MsgType;

    static function factory($type)
    {
        $classname = 'weixin_reply_'.$type;
        if (class_exists($classname)) {
            return new $classname();
        }
        $msg = array(
            'msg' => "微信被动回复类 {$classname} 不存在",
            'url' => $_SERVER['REQUEST_URI'],
        );
        log_write('weixin_err', $msg);
        return;
    }

    function __construct() 
    {
        $this->acc_obj = mc_weixin_account::instant();
    }

    function set_user($ToUserName, $FromUserName)
    {
        $this->ToUserName = $ToUserName;
        $this->FromUserName = $FromUserName;
    }

    function send()
    {
        $content  = "<xml>\n";
        $content .= "<ToUserName><![CDATA[{$this->ToUserName}]]></ToUserName>\n";
        $content .= "<FromUserName><![CDATA[{$this->FromUserName}]]></FromUserName>\n";
        $content .= "<CreateTime>"._G('timestamp')."</CreateTime>\n";
        $content .= "<MsgType><![CDATA[{$this->MsgType}]]></MsgType>\n";
        $content .= $this->_xml();
        $content .= "</xml>";
        if(DEBUG) log_write('weixin_debug',$content);
        echo $content;
    }

    protected function _xml()
    {
        return '';
    }
}

/**
* 回复文本消息
*/
class weixin_reply_text extends mc_weixin_reply
{
    public $MsgType = 'text';

    private $Content = '';

    function set_content($content)
    {
        if(strtolower(_G('charset')) != 'utf-8') {
            $content = charset_convert( $content, _G('charset'), 'utf-8' );
        }
        $this->Content = $content;
    }

    protected function _xml()
    {
        return "<Content><![CDATA[{$this->Content}]]></Content>\n";
    }
}

/**
* 回复图文消息
*/
class weixin_reply_news extends mc_weixin_reply
{
    public $MsgType = 'news';

    private $max_article_count = 10;    //最多回复10条
    private $articles = array();

    //增加一条图文信息
    public function add_article($Title, $Description = '', $PicUrl = '', $Url = '')
    {
        if(count($this->articles) >= $this->max_article_count) {
            return;
        }
        //第一个参数是一个数组，表示参数是一个符合article格式的数据
        if(is_array($Title)) {
            $article = $Title;
        } else {
            $article = array(
                'Title' => $Title,
                'Description' => $Description,
                'PicUrl' => $PicUrl,
                'Url' => $Url,
            );
        }
        if(strtolower(_G('charset')) != 'utf-8') {
            $article['Title'] = charset_convert( $article['Title'], _G('charset'), 'utf-8' );
            $article['Description'] = charset_convert( $article['Description'], _G('charset'), 'utf-8' );
        }
        $this->articles[] = $article;
    }

    protected function _xml()
    {
        $ArticleCount = count($this->articles);
        if(!$ArticleCount) return;

        $content = "<ArticleCount>".$ArticleCount."</ArticleCount>\n";
        $content .= "<Articles>\n";
        foreach ($this->articles as $item) {
            $content .= "<item>\n";
            foreach ($item as $key => $value) {
                $content .= "<{$key}><![CDATA[{$value}]]></{$key}>\n";
            }
            $content .= "</item>\n";
        }
        $content .= "</Articles>\n";

        return $content;
    }
}
/** end **/