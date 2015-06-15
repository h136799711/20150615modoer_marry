<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_product_buyattr extends ms_model {
    
    var $table = 'dbpre_product_buyattr';
    var $key = 'id';

    /**
     * 购买属性字符串表达式转换成数组
     * @param  string $str 准备解析的属性字符串；格式： 属性1id：属性1值序号;属性2id：属性2值序号
     * @return array 返回格式：array(属性1id=>属性1值序号,属性2id=>属性2值序号)
     */
    public static function buyattr_strtoarray($str)
    {
        $str = trim($str);
        $buyattr = explode(';', trim($str,';'));
        $result = array();
        foreach ($buyattr as $ba) {
            list($id, $index) = explode(':', $ba);
            $id = (int) $id;
            $index = (int) $index;
            if(!$id && !$index) continue;
            $result[$id] = $index;
        };
        return $result;
    }

    /**
     * 购买属性数组转换成字符串表达式
     * @param  array $array 准备解析的属性数组,格式:array(属性1id=>属性1值序号,属性2id=>属性2值序号)
     * @return string 返回值格式：属性1id：属性1值序号;属性2id：属性2值序号
     */
    public static function buyattr_arraytostr($array)
    {
        $str = '';
        if($array) {
            foreach ($array as $key => $value) {
                $str .= "{$key}:{$value};";
            }
            $str = trim($str,';');          
        }
        return $str;
    }

    function __construct() {
        parent::__construct();
        $this->model_flag = 'product';
        $this->modcfg = $this->variable('config');
    }

    /**
     * 获取指定产品的购买属性列表
     * @param  integer $pid 产品ID
     * @return array      
     */
    function get_product_buyattr($pid)
    {
        $result = array();
        $r = parent::find_all('*', array('pid'=>$pid), 'listorder');
        if(!$r) return $result;
        while ($v = $r->fetch_array()) {
            $v['value'] = explode(',', $v['value']);
            $result[] = $v;
        }
        return $result;
    }

    /**
     * 解析字符串格式的购买属性为可以的数组形式
     * @param  [type] $str [description]
     * @return [type]      [description]
     */
    function parse_post($str)
    {
        $str = trim(_TA($str));
        if(!$str) return '';
        $list = explode("\r\n", $str);
        $attrs = array();
        foreach ($list as $value) {
            $name = $values = null;
            if(strpos($value, '=')) {
                list($name, $values) = explode('=', $value);
                $name = trim($name);
                $values = trim($values);
            }
            if(!$name||!$value) return $this->add_error('购买属性格式不正确。');
            $values = str_replace('，', ',', $values); //中文逗号转成英文逗号
            $values = explode(',', $values); //属性值转成数组
            $attrs[] = array('name'=>$name,'value'=>$values);
        }
        return $attrs;
    }

    /**
     * 添加一个购买属性
     * @param integer $pid  所属产品id
     * @param array $attr 一个购买属性，格式为array('name'=>'xxxxx','value'=>array(1,2,3,4,5,6), 'listorder'=>0)
     * @return integer      属性id
     */
    function add($pid, $attr)
    {
        $post = array();
        $post['pid'] = $pid;
        $post['name'] = $attr['name'];
        $post['value'] = implode(',', $attr['value']);
        $post['listorder'] = (int)$attr['listorder'];
        return parent::save($post);
    }

    /**
     * 编辑一个购买属性
     * @param integer $pid  所属产品id
     * @param array $attr 一个购买属性，格式为array('name'=>'xxxxx','value'=>array(1,2,3,4,5,6), 'listorder'=>0)
     * @return integer      属性id
     */
    function edit($id, $attr)
    {
        $detail = $this->read($id);
        if(!$detail) return $this->add_error('指定的购买属性不存在。');
        $update = array();
        $update['name'] = $attr['name'];
        $update['value'] = implode(',', $attr['value']);
        if(isset($attr['listorder'])) {
            $post['listorder'] = (int)$attr['listorder'];
        }
        return parent::save($update, $detail);
    }

    /**
     * 删除商品商品内的指定属性ID的记录
     * @param  integer $pid 商品id
     * @param  integer $id  属性id
     * @return integer      删除数量
     */
    function delete_ex($pid, $id)
    {
        $where = array(
            'pid' => $pid,
            'id' => $id,
        );
        return $this->db->from($this->table)->where($where)->delete();
    }

    /**
     * 删除指定商品购买属性数据
     * @param  integer $pid 商品ID
     * @return integer      影响数量（删除数量）
     */
    function delete_by_pid($pid)
    {
        return $this->db->from($this->table)->where('pid',$pid)->delete();
    }

}

/**end**/