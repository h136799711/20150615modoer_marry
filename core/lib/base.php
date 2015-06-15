<?php
/**
* Modoer基类
* @author moufer<moufer@163.com>
* @copyright modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class ms_base 
{

    //错误代号
    protected $erron    = 0;
    //错误文字信息
    protected $errmsg   = '';

    //全局变量数组
    public $global      = null;
    //当前系统unix时间戳
    public $timestamp   = 0;
    //IP地址
    public $ip          = '127.0.0.1';
    //类加载器
    public $loader      = null;
    //函数钩子管理器
    public $hook        = null;

    //后台模式
    public $in_admin    = false;
    //AJAX模式
    public $in_ajax     = false;
    //API模式
    public $in_api      = false;
    //JS模式
    public $in_js       = false;
    //手机Web模式
    public $in_mobile   = false;

    function __construct() 
    {
        global $_G;

        //应用全局变量
        $this->global       =& $_G; 

        //loader载入类
        $this->loader       = $_G['loader'];

        //当前时间
        $this->timestamp    =& $_G['timestamp']; 
        //当前访客ID
        $this->ip           =& $_G['ip'];

        //Hook类
        $this->hook         = $_G['hook'];

        //执行页面的各种状态
        $this->in_mobile    = defined('IN_MOBILE');
        $this->in_admin     = defined('IN_ADMIN');
        $this->in_api       = defined('IN_API');
        $this->in_ajax      = isset($_G['in_ajax']) && $_G['in_ajax'];
        $this->in_js        = isset($_G['in_js']) && $_G['in_js'];
    }

    //新家一条错误记录
    function add_error($errmsg, $erron = 110000, $show_error = false) 
    {
        //同一个基类的对象
        if(is_object($errmsg)) {
            if(is_subclass_of($errmsg, 'ms_base')) {
                $this->errmsg   = $errmsg->error();
                $this->erron    = $errmsg->erron();
            }
        } elseif(is_string($errmsg)) {
            $this->errmsg = lang($errmsg);
            $this->erron = $erron;
        }
        //是否直接在页面显示错误信息
        if($show_error) {
            redirect($this->errmsg);
        }
        return false;
    }

    //是否存在错误记录
    function has_error() 
    {
        return $this->errmsg != '';
    }

    //返回一条错误记录代号
    function erron() 
    {
        return $this->erron;
    }

    //返回一条错误记录信息
    function error() 
    {
        return $this->errmsg;
    }

    //返回json api格式
    function error_json()
    {
        $ret = array(
            'code' => $this->erron(),
            'message' => $this->error(),
        );
        if($this->global['charset'] != 'utf-8') {
            $ret['message'] = charset_convert($ret['message'], $this->global['charset'], 'utf-8');
        }
        return json_encode($ret);
    }

    //清除错误信息
    function error_clear()
    {
        $this->errmsg   = '';
        $this->code     = 0;
    }
}

/** end **/