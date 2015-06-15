<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_dbcache extends ms_model {

    /**
     * 定时删除过期缓存的缓存键名
     */
    const CACHE_KEY_EXPIRE = 'comm_dbcache_expire';

	public $table = 'dbpre_dbcache';
    public $key = 'cache_key';

    /**
     * 缓存数据集合
     * @var array
     */
    protected $data = array();
    /**
     * 更新或新增的key数组
     * @var array
     */
    protected $write_keys = array();
    /**
     * 准备删除的key数组
     * @var array
     */
    protected $delete_keys = array();
    /**
     * 不存在的缓存key数组
     * @var array
     */
    private $not_found_keys = array();

    /**
     * 删除过期缓存的时间间隔，默认600秒
     * @var integer
     */
    private $expire_task_interval = 3600;

    /**
     * 析构函数，更新数据表数据
     */
    function __destruct()
    {
        //log_write('dbcache','mark1');
        $this->_clear_expire();
        $this->_update();
        $this->_delete();
        //vp($this->write_keys);
        //vp($this->not_found_keys);
    }

    /**
     * 获取一个缓存数据
     * @param  string  $key        缓存键名
     * @param  boolean $fetch_full 是否获取数据项全部信息（更新时间，生命期），反之仅返回缓存值
     * @return [type]              [description]
     */
    function fetch($key, $fetch_full = false)
    {
        if(!isset($this->data[$key])) {
            //之前已经访问过，不存在的变量，不用再读取数据库
            if(in_array($key, $this->not_found_keys)) {
                return false;
            }
            $data = $this->read($key);
            if($data) {
                $this->data[$key] = $data;
                return $fetch_full?$data:$data['cache_value'];
            }
        } else {
            return $fetch_full?$this->data[$key]:$this->data[$key]['cache_value'];
        }
        $this->not_found_keys[] = $key;
        return false;
    }

    /**
     * 获取内存中存在的全部缓存数据
     * @return array [description]
     */
    function fetch_data_all()
    {
        return $this->data;
    }

    /**
     * 写入一个缓存数据（数据并没有直接写入数据库，类析构时写入）
     * @param  string  $key         缓存键名
     * @param  string  $value       缓存数据
     * @param  integer $expire_time 生命周期，单位：秒，缺省为永久保留
     */
    function write($key, $value, $expire_time = 0)
    {
        if($expire_time > 0) $expire_time += $this->timestamp;
        $this->data[$key] = array(
            'cache_value' => $value,
            'expire_time' => $expire_time,
        );
        if(!in_array($key, $this->write_keys)) $this->write_keys[] = $key;
        //新写入缓存，如果这个缓存键之前存放在not_found_keys变量时，此时就要从not_found_keys删除
        $i = array_search($key, $this->not_found_keys);
        if($i !== false) unset($this->not_found_keys[$i]);
    }

    /**
     * 删除一个缓存（数据并没有马上在数据库中删除，类析构时删除）
     * @param  string $key 缓存键名
     */
    function delete($key)
    {
        unset($this->data[$key]);
        if(!in_array($key, $this->delete_keys)) $this->delete_keys[] = $key;
    }

    /**
     * 查询一个或多个缓存
     * @param  string $keys 缓存键名。多个缓存键名用逗号分隔
     * @return array
     */
    function find($keys)
    {
        //多数据读取
        if(!is_array($keys)) $keys = explode(',',trim($keys));
        $result = array();
        $n_keys = $keys;
        //已经在内存中的字段不再到数据库读取
        if($this->data) {
            foreach ($keys as $i => $key) {
                if(isset($this->data[$key])) {
                    $result[$key] = $this->data[$key];
                    unset($n_keys[$i]);
                    continue;
                }
            }
        }
        //读取数据库
        $q = $this->db->from($this->table)->where('cache_key', $n_keys)->get();
        if(!$q) return $result;
        //新读取的数据放入内存中
        while ($v =  $q->fetch_array()) {
            $this->data[$v['cache_key']] = $v;
            $result[$v['cache_key']] = $v;
            $i = array_search($v['cache_key'], $this->not_found_keys);
            if($i !== false) unset($this->not_found_keys[$i]);
        }
        $q->free_result();
        foreach ($keys as $key) {
            if(!isset($this->data[$key])) $this->not_found_keys[] = $key;
        }
        return $result;
    }

    /**
     * 更新缓存数据
     * @return int 数据库影响行数
     */
    function _update()
    {
        if(!$this->write_keys) return;
        $arows = 0;
        foreach ($this->write_keys as $key) {
            $this->data[$key]['update_time'] = $this->timestamp;
            $arows += $this->db->from($this->table)
                ->set('cache_key', $key)->set($this->data[$key])
                ->replace();
        }
        return $arows;
    }

    /**
     * 删除缓存记录
     * @return int 数据库影响行数
     */
    function _delete()
    {
        if(!$this->delete_keys) return;
        return $this->db->from($this->table)->where('cache_key', $this->delete_keys)->delete();
    }

    /**
     * 删除已过期的数据
     * @return int 数据库影响行数
     */
    function _clear_expire($force = false)
    {
        if(!$force) {
            //检车是否到了固定更新间隔
            $time = $this->fetch(self::CACHE_KEY_EXPIRE);
            if($time === false || $this->expire_task_interval < $this->timestamp - $time || $time > $this->timestamp) {
                $this->write(self::CACHE_KEY_EXPIRE, $this->timestamp);
            } else {
                return;
            }
        }
        return $this->db->from($this->table)
            ->where_less('expire_time', $this->timestamp)
            ->delete();
    }
    
}
/** end **/