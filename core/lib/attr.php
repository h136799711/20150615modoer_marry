<?php
/**
* 属性类
* @author moufer<moufer@163.com>
* @copyright (C)2001-2007 Moufersoft
*/
!defined('IN_MUDDER') && exit('Access Denied');

class ms_attr extends ms_base
{
    //类属性数组
    protected $classAttr     = array();

    /**
     * 从确定格式的xml转换成属性类
     * @param  string $xml  xml格式字符串
     * @return ms_attr
     */
    public static function form_xml($xml, $is_file = false)
    {
        _G('loader')->helper('mxml');
        return self::create(mxml::to_array($xml, $is_file));
    }

    /**
     * 把一个数组转换成属性类
     * @param  array $array 数组
     * @return ms_attr
     */
    public static function create($array)
    {
        if(!$array || !is_array($array)) return false;
        $ins = new ms_attr();
        foreach ($array as $key => $value) {
            $ins->set_attr($key, $value);
        }
        return $ins;
    }

    /**
     * 设置属性
     * @param string $key   属性键名
     * @param mixed $value 属性值内容
     */
    public function __set($key, $value)
    {
        $this->set_attr($key, $value);
    }

    /**
     * 获取一个属性值
     * @param  string $key 属性键名
     * @return mixed      如果属性存在，则返回属性值，不存在则返回null
     */
    public function __get($key)
    {
        return $this->get_attr($key);
    }

    /**
     * 设置属性
     * @param string $key   属性键名
     * @param mixed $value 属性值内容
     */
    public function set_attr($key, $value)
    {
        $this->classAttr[$key] = $value;
    }

    /**
     * 判断配置属性否存在，如果设置了key参数，则检测属性名为$key的数组值是否存在
     * @param  string  $key 属性键名
     * @return boolean      属性值是否存在
     */
    public function has_attr($key = '')
    {
        //未指定哦按段那个属性值时，则判断是否存在其中任何一个属性值
        if (! $key) {
            return !empty($this->classAttr);
        }
        return isset($this->classAttr[$key]);
    }

    /**
     * 获取一个属性值
     * @param  string $key 属性键名
     * @return mixed      如果属性存在，则返回属性值，不存在则返回null
     */
    public function get_attr($key)
    {
        if (isset($this->classAttr[$key])) {
            return $this->classAttr[$key];
        }
        return null;
    }

    /**
     * 以数组方式返回全部属性
     * @param  string $type 需要返回的属性组类型  
     * @return mixed       根据$type参数返回指定的类型，包括array(默认),json,object,serialize,xml
     */
    public function fetch_all_attr($type = '')
    {
    	if($type=='json') {
    		return $this->attr_to_json();
    	} elseif($type=='object') {
    		return $this->attr_to_object();
        } elseif($type=='serialize') {
            return $this->classAttr?serialize($this->classAttr):'';
        } elseif($type=='xml') {
            return $this->attr_to_xml();
    	} else {
    		return $this->classAttr;
    	}
    }

	/**
	 * 最后的返回值数组转换成对象
	 * @return stdClass
	 */
	protected function attr_to_object()
	{
		return (object)$this->classAttr;
	}

	/**
	 * 返回值数组转换成json
	 * @return string
	 */
	protected function attr_to_json()
	{
		return json_encode($this->classAttr);
	}

    /**
     * 返回值数组转换成约定的xml格式
     * @return [type] [description]
     */
    protected function attr_to_xml()
    {
        return ms_mxml::from_array($this->classAttr);
    }
	
}