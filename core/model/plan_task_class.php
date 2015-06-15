<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2011 Moufersoft
* @website www.modoer.com
*/
class msm_plan_task extends ms_model
{
    const CACHE_KEY = 'comm_task_nexttime'; 

    public $table = 'dbpre_plan_task';
    public $key = 'id';
    /**
     * 计划任务脚本存放文件夹
     * @var string
     */
    private $task_dir = 'core/tasks/';

    /**
     * 判断下次执行时间的缓存标记名
     * @var string
     */

    public function __construct() 
    {
        parent::__construct();
        $this->task_dir = str_replace('/', DS, $this->task_dir);
    }

    /**
     * 自动执行脚本
     * @param  string $filename 手动指定一个任务脚本立刻执行
     * @return boolean           手动指定时，返回直接结果
     */
    public function run($filename='')
    {
        $tasks = array();
        if($filename) {
            $data = $this->read_by_filename($filename);
            if(!$data) return $this->add_error('指定执行的脚本未安装。');
            $tasks[] = $data;
        } else {
            //获取执行脚本记录，当前时间已大于预定执行时间时
            $tasks = $this->db->from($this->table)
                ->where_between_and('nexttime', 0, $this->timestamp)
                ->get_all();
        }
        if($tasks) {
            foreach ($tasks as $task) {
                $succeed = $this->run_task($task['filename']);
                //执行成功，则更新记录本次执行时间，写入下次更新时间
                $this->_update_time($task);
            }
        }
        //更新dbcache
        $this->_update_nexttime_cache();
        //如果是指定执行某个脚本，则返回当前执行结果
        if($filename) return $succeed;
    }

    /**
     * 立刻执行一个脚本
     * @param  string $filename 脚本文件名，不包含后缀
     * @return boolean             是否成功执行
     */
    public function run_task($filename)
    {
        $task_obj = $this->factory($filename);
        if(!$task_obj) return false;
        if(!$task_obj->run()) {
            $this->add_error($task_obj);
            //执行失败，记录
            log_write('plan_task', "fail.\ttime:".date('Y-m-d H:i:s',$this->timestamp)."\tscript:{$filename}\terror:".$this->error()."");
            return false;
        }
        return true;
    }

    /**
     * 获取指定的脚本信息
     * @param  string $filename 脚本文件名，不包含后缀
     * @return array           脚本信息素组
     */
    public function read_by_filename($filename)
    {
        return $this->db->from($this->table)->where('filename', $filename)->get_one();
    }

    /**
     * 获取已安装计划任务脚本数据集
     * @return array
     */
    public function get_list()
    {
        $q = $this->db->from($this->table)->get();
        if(!$q) return;
        $result = array();
        while ($v = $q->fetch_array()) {
            $result[$v['filename']] = $v;
        }
        return $result;
    }

    /**
     * 获取全部脚本文件（实例化）
     * @return array
     */
    public function load_files()
    {
        $result = array();
        $directory = MUDDER_ROOT . $this->task_dir;
        if(!is_dir($directory)) return false;
        $iterator = new DirectoryIterator($directory);
        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isFile() && pathinfo($fileinfo->getFilename(), PATHINFO_EXTENSION) == 'php') {
                $filename = $fileinfo->getBasename('.php');
                $classname = 'msm_task_' . $filename;
                if(!class_exists($classname)) include_once $fileinfo->getRealPath();
                if(get_parent_class($classname)=='ms_task') {
                    $result[$filename] = new $classname();
                }
            }
        }
        return $result;
    }

    /**
     * 实例化一个计划任务脚本类
     * @param  string $filename 脚本文件名（不包含文件后缀）
     * @return ms_task
     */
    public function factory($filename) 
    {
        $filename = _F($filename);
        if(!$filename) return $this->add_error('未提供执行脚本。');
        $filepath = MUDDER_ROOT.$this->task_dir.$filename.'.php';
        if(!is_file($filepath)) return $this->add_error('脚本文件不存在。');
        include_once $filepath;
        $classname = 'msm_task_'.$filename;
        if(!class_exists($classname)) return $this->add_error('文件不是一个计划任务脚本文件。');
        return new $classname();
    }

    /**
     * 安装一个脚本
     * @param  string $filename
     * @return num           返回脚本安装记录表主键
     */
    public function install($filename) 
    {
        $task_obj = $this->factory($filename);
        if(!$task_obj) return false;
        if($this->is_installed($filename)) return $this->add_error('该计划任务脚本已经安装了。');
        $post = array();
        $post['filename']   = $filename;
        $post['time_exp']   = $task_obj->get_time_exp();
        $post['setting']    = '';
        $post['nexttime']   = $this->get_nexttime($post['time_exp']);
        $post['run_count']  = 0;
        $id = parent::save($post);
        //更新dbcache
        $this->_update_nexttime_cache();
        return $id;
    }

    /**
     * 卸载一个脚本
     * @param  string $filename
     * @return num           数据库影响行数
     */
    public function uninstall($filename)
    {
        $this->db->from($this->table)->where('filename',$filename)->delete();
        $arows = $this->db->affected_rows();
        //更新dbcache
        $this->_update_nexttime_cache();
        return $arows;
    }

    /**
     * 判断是否已经安装
     * @param  string  $filename
     * @return boolean
     */
    public function is_installed($filename) 
    {
        return $this->db->from($this->table)->where('filename',$filename)->count() > 0;
    }


    /**
     * 解析时间表达式字符串，转换为代码可识别的时间数组
     * @param  [type] $time_exp_str 时间表达式字符串
     * @return array|boolean|null   定时时间数组，返回false表示解析失败
     */
    public function parse_time_exp($time_exp_str)
    {
        if(!$time_exp_str = trim($time_exp_str)) return false;

        $result = array();
        foreach (explode('|', $time_exp_str) as $value) {
            list($time_unit, $time_value) = explode('=', trim($value));
            $time_unit = trim($time_unit);
            $time_value = trim($time_value);
            if(!in_array($time_unit, array('weekday','day','hour','minute'))) {
                return $this->add_error('无效的时间参数:'.$time_unit);
            }
            if($time_value=='*') continue;
            if(!is_numeric($time_value) || $time_value < 0) return $this->add_error("无效的时间值：{$time_unit}={$time_value}");
            if($time_unit=='weekday' && $time_value > 7) return $this->add_error("星期不能大于7。");
            if($time_unit=='day' && $time_value > 31) return $this->add_error("日期不能大于31。");
            if($time_unit=='hour' && $time_value > 23) return $this->add_error("小时不能大于23。");

            //星期和日期的时间值不能是空或0
            if(!$time_value && ($time_unit=='weekday' || $time_unit=='day')) continue;

            $result[$time_unit] = $time_value;
        }
        //指定了星期或日期时。小时必须要有，没有则设置为0点
        if(($result['weekday'] || $result['day']) && !isset($result['hour'])) $result['hour'] = 0;
        //如果分钟大于59，却有指定小时的时候，报错
        if($result['minute'] > 59 && isset($result['hour'])) {
            return $this->add_error("您指定了执行的小时单位后，分钟单位不能大于59。");
        }
    
        return $result;
    }

    /**
     * 把时间表达式数组转换成字符串型号
     * @param  array $time_exp 数组型时间表达式
     * @return string           
     */
    public function to_time_exp_str($time_exp)
    {
        if(!$time_exp) return '';
        $result = '';
        foreach ($time_exp as $key => $value) {
            $result .= "$key=$value|";
        }
        return trim($result, '|');
    }

    /**
     * 解析时间表达式，返回书面文字
     * @param  string $time_exp 时间表达式
     * @return string           可阅读文字
     */
    public function time_exp_caption($time_exp_str)
    {
        if(!is_array($time_exp_str)) {
            //解析时间表达式
            $time_exp = $this->parse_time_exp($time_exp_str);
        } else {
            $time_exp = $time_exp_str;
        }

        if(!$time_exp['minute'] && !$time_exp['hour'] && !$time_exp['day'] && !$time_exp['weekday']) {
            return lang('admincp_plan_task_time_exp_empty');
        }
        $week_lngs = lang('global_week_day');
        $title = '';
        if($time_exp['weekday'] > 0) {  //按星期计算
            $title = lang('admincp_plan_task_time_exp_weekday', $week_lngs[$time_exp['weekday']]);
        } elseif($time_exp['day'] > 0) {
            $title = lang('admincp_plan_task_time_exp_day',$time_exp['day']);
        }
        if(is_numeric($time_exp['hour']) && $time_exp['hour'] >= 0) {
            $lang = $title ? 'admincp_plan_task_time_exp_hour1' : 'admincp_plan_task_time_exp_hour2';
            $title .=  lang($lang, $time_exp['hour']);
        }
        if(is_numeric($time_exp['minute']) && $time_exp['minute'] > 0) {
            $lang = $title ? 'admincp_plan_task_time_exp_minute1' : 'admincp_plan_task_time_exp_minute2';
            $title .=  lang($lang, $time_exp['minute']);
        }
        return $title?$title:lang('admincp_plan_task_time_exp_empty');
    }

    /**
     * 通过指定时间表达式，计算下次运行时间
     * @param  string|array $time_exp_str 时间表达式
     * @param  int $lasttime     最后一次运行时间（unix时间戳），留空表示现在
     * @return int               下一次运行时间（unix时间戳)，返回0表示不执行
     */
    public function get_nexttime($time_exp_str, $lasttime = '')
    {
        if(!is_array($time_exp_str)) {
            //解析时间表达式
            $time_exp = $this->parse_time_exp($time_exp_str);
        } else {
            $time_exp = $time_exp_str;
        }

        if(!$time_exp) return 0;

        !$lasttime && $lasttime=$this->timestamp;
        $today = strtotime(date('Y-m-d',$lasttime));
        $nexttime = $today;

        if($time_exp['weekday'] > 0) {  //按星期计算
            //今天星期几
            $n = date('N', $today);
            //本周还没有到指定星期时
            if($n < $time_exp['weekday']) {
                $add_day = $time_exp['weekday'] - $n;
            } else {
                //本周已过，计算下周
                $add_day = 7 - $n + $time_exp['weekday'];
            }
            $nexttime = strtotime("+{$add_day} day", $today);
        } elseif($time_exp['day'] > 0) {
            //今天几号
            $j = date('j', $today);
            //本月还没有到指定号数
            if($j < $time_exp['day']) {
                $add_day = $time_exp['day'] - $j;
                $nexttime = strtotime("+{$add_day} day", $today);
            } else {
                //本月已过号数，计算下个月
                $nexttime = strtotime("+1 month", $today);
                $dec_day = $j - $time_exp['day'];
                $nexttime = strtotime("-{$dec_day} day", $nexttime);
            }
        }
        if(is_numeric($time_exp['hour']) && $time_exp['hour'] >= 0) {
            $nexttime = strtotime("+{$time_exp['hour']} hours", $nexttime);
            //今日执行时间已过，则明日执行（因为设置了星期或月号，不会再计算今天，所以这里只考虑每天的小时）
            if($nexttime < $lasttime) {
                $nexttime = strtotime("+1 day", $nexttime);
            }
        }
        if(is_numeric($time_exp['minute']) && $time_exp['minute'] > 0) {
            if($today == $nexttime) {
                $nexttime = $lasttime;
            }
            do {
                $nexttime = strtotime("+{$time_exp['minute']} minutes", $nexttime);
            } while ($nexttime <= $lasttime);
        }

        if($today == $nexttime) return 0;
        return $nexttime;
    }

    /**
     * 更新脚本的执行时间
     * @param  array $task_data 脚本数据项
     * @return int            数据库影响行数
     */
    private function _update_time($task_data)
    {
        $set = array();
        $set['lasttime'] = $this->timestamp;
        $set['nexttime'] = $this->get_nexttime($task_data['time_exp'], $this->timestamp);
        $set['run_count'] = array('set_add', array(1));
        $this->db->from($this->table)->set($set)->where('id',$task_data['id'])->update();
        return $this->db->affected_rows();
    }

    /**
     * 记录dbcache里的计划任务下次执行时间
     * @return [type] [description]
     */
    private function _update_nexttime_cache()
    {

        $nexttime = $this->db->select('nexttime')
            ->from($this->table)
            //->where_more('nexttime', $this->timestamp)
            ->order_by('nexttime','ASC')
            ->get_value();
        _G('dbcache')->write(self::CACHE_KEY, $nexttime < $this->timestamp ? 0 : $nexttime);
    }

}

/** end **/