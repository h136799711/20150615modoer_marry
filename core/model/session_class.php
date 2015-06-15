<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_session extends ms_base  {

    const CACHE_KEY_EXPIRE = 'comm_session_expire';
    const CACHE_KEY_ONLINE = 'comm_session_online';

    protected $uniq     = '';
    protected $db       = null;
    protected $table    = 'dbpre_session';

    protected $update_time  = 60;       //session 执行更新时间(秒)
    protected $expiry_time  = 900;     //session 超时时间(秒)

    private $_data      = array();      //session 变量
    private $_modify    = false;        //session 变量是否修已修改

    private $_session      = array();

    function __construct()
    {
        parent::__construct();
        $this->_set_uniq();
        $this->_set_db();
        $this->_init();
        $this->delete_expiry(); //自动删除过期记录
    }

    /**
     * 类析构，将有更新的 session 变量存入数据库
     */
    function __destruct()
    {
        if($this->_modify || $this->timestamp - $this->_session['last_time'] > $this->update_time) {
            $this->_update();
        }
    }

    //从session中设置自定义变量
    function __set($key, $value)
    {
        if($value == '') {
            $this->clear($key);
        } else {
            $this->_data[$key] = $value;
        }
        $this->_modify = true;
    }

    //从session中获取自定义变量
    function __get($key)
    {
        if(isset($this->_data[$key])) {
            return $this->_data[$key];
        } else {
            return;
        }
    }

    //获取全部session保存的自定义参数
    function fetch_all()
    {
        return $this->_data;
    }

    //获取session_id
    function get_id()
    {
        return $this->uniq;
    }

    //清空session内自定义变量
    function clear($key='')
    {
        if($key) {
            if(isset($this->_data[$key])) unset($this->_data[$key]);
        } else {
            $this->_data = array();
        }
        $this->_modify = true;
    }

    //设置session_id关联的UID
    function set_uid($uid)
    {
        if($this->_session['uid'] != $uid) {
            $this->db->from($this->table)
                ->where('uniq', $this->uniq)
                ->set('uid', $uid)
                ->update();
        }
    }

    //删除过期session
    function delete_expiry($force = false)
    {
        if(!$force) {
            $interval = 600; //5分钟删除一次
            $task_time = _G('dbcache')->fetch(self::CACHE_KEY_EXPIRE);
            if($this->timestamp - $task_time > $interval || $task_time === false || $task_time > $this->timestamp) {
                //更新定时缓存
                _G('dbcache')->write(self::CACHE_KEY_EXPIRE, $this->timestamp);
            } else {
                return;
            }
        }
        $time = $this->timestamp - $this->expiry_time;
        return $this->db->from($this->table)->where_less('last_time', $time)->delete();
    }

    //获取在线人数，基于session数量进行统计
    function get_online_total()
    {
        $cache = _G('dbcache')->fetch(self::CACHE_KEY_ONLINE);
        if($cache) {
            list($count, $total_time) = explode("\t", $cache);
            //10分钟统计一次
            if(600 > $this->timestamp - $total_time && $this->timestamp > $total_time) return $count;
        }
        //过滤超时的session
        $time = $this->timestamp - $this->expiry_time;
        $count = $this->db->from($this->table)
                    ->where_more('last_time', $time)
                    ->count();
        _G('dbcache')->write(self::CACHE_KEY_ONLINE, "{$count}\t{$this->timestamp}");

        return $count;
    }

    //初始化
    private function _init() {
        $this->_session = $this->db->from($this->table)->where('uniq', $this->uniq)->get_one();
        if(!$this->_session) {
            $this->_create();
        }
        if($this->_session['content']) {
            $this->_data = unserialize($this->_session['content']);
        }
        //session expiry 
        if($this->timestamp - $this->_session['last_time'] > $this->expiry_time) {
            $this->clear();
        }
    }

    //新建一个sessin记录
    private function _create() {
        $post = array();
        $post['uniq'] = $this->uniq;
        $post['uid'] = (int)$this->global['user']->uid;
        $post['ip_address'] = $this->global['ip'];
        $post['content'] = '';
        $post['last_time'] = $this->timestamp;
        $post['last_url']   = request_uri();
        $post['is_mobile'] = is_mobile() ? 1 : 0;
        $post['user_agent'] = $_SERVER['HTTP_USER_AGENT']?$_SERVER['HTTP_USER_AGENT']:'';
        $this->_session = $post;
        $this->db->from($this->table)->set($post)->insert();
    }

    //更新session内容
    private function _update() 
    {
        $post = array();
        $post['uid'] = (int)$this->global['user']->uid;
        $post['ip_address'] = $this->global['ip'];
        $post['last_time']  = $this->timestamp;
        $post['last_url']   = request_uri();
        $post['is_mobile']  = is_mobile() ? 1 : 0;
        $post['user_agent'] = $_SERVER['HTTP_USER_AGENT']?$_SERVER['HTTP_USER_AGENT']:'';
        if($this->_modify) {
            $post['content'] = is_array($this->_data) && count($this->_data) > 0 ? serialize($this->_data) : '';
        }
        $this->db->from($this->table)->set($post)->where('uniq', $this->uniq)->update();
    }

    //设置生成sessin_id
    private function _set_uniq() 
    {
        $hash = _cookie('session_uniq');
        if($hash) {
            list($c_uniq, $c_rand) = explode("\t", authcode($hash, 'DECODE'));
            //检测session_uniq是否有效
            if($c_uniq && $c_uniq == $this->_create_uniq($c_rand)) {
                $this->uniq = $c_uniq;
                return;
            }
        }
        //生成一个sessionid
        $rand = mt_rand(100000000, 999999999);
        $this->uniq = $this->_create_uniq($rand);
        $hash = $this->uniq."\t".$rand;
        set_cookie('session_uniq', authcode($hash,'ENCODE'), 30*24*3600);
    }

    private function _create_uniq($rand)
    {
        return md5($_SERVER['HTTP_USER_AGENT'].$rand._G('cfg','authkey'));
    }

    //设置db库
    private function _set_db() 
    {
        if(!isset($this->global['db'])) {
            $this->global['db'] = new ms_activerecord(_G('dns'));
        }
        //实例化一个模型内部使用的 activerecord 类
        $this->db = new ms_activerecord();
        $this->db->set_db($this->global['db']->get_db());
    }

}