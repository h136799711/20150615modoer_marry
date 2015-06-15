<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

define("MF_DB_J",'JOIN');
define("MF_DB_LJ",'LEFT JOIN');
define("MF_DB_RJ",'RIGHT JOIN');

class ms_sqlbuild
{

    //所支持的组合子句集合
    protected $var_fields     = array('set', 'select', 'from', 'index', 'where', 'group_by', 
        'having', 'order_by', 'limit');
    
    public $table          = '';
    public $where          = '';
    public $set            = '';
    public $select         = '';
    public $from           = '';
    public $index          = '';
    public $group_by       = '';
    public $having         = '';
    public $order_by       = '';
    public $limit          = '';
    
    //缓存
    public $cache_table    = '';
    public $cache_where    = '';
    public $cache_select   = '';
    public $cache_from     = '';
    public $cache_index    = '';
    public $cache_group_by = '';
    public $cache_having   = '';
    public $cache_order_by = '';
    public $cache_limit    = '';

    //db操作类
    protected $db               = null;
    //db类是否继承自activerecord类
    protected $is_activerecord = true;
    
    /**
     * 获取操作的DB类
     * @param [type] $db [description]
     */
    function __construct($db)
    {
        $this->db = $db;
        //判断这个类是否实例化自 ms_activerecord
        $this->is_activerecord = get_class($this->db) == 'ms_activerecord';
    }

    // 设置SQL中的from子句
    function from($tablename, $asname = null, $sql = null)
    {
        $this->from = $this->db->get_table($tablename).($asname ? " $asname" : '');
        if($sql) $this->from .= ' '.$sql;

        return $this->get_class();
    }

    // 关联2个表
    function join($table1,$key1, $table2,$key2, $jointype = 'JOIN')
    {
        if(!preg_match("/^([a-z0-9]+)\.([a-z0-9_]+)$/i", $key1, $match1)) {
            redirect('无效的关联表字段。'.$key1);
        }
        if(!preg_match("/^([a-z0-9]+)\.([a-z0-9_]+)$/i", $key2, $match2)) {
            redirect('无效的关联表字段。'.$key2);
        }

        $table1  = $this->db->get_table($table1);
        $table2  = $this->db->get_table($table2);

        $asname1 = $match1[1];
        $asname2 = $match2[1];

        $key1    = $this->db->_ck_field($match1[2]);
        $key2    = $this->db->_ck_field($match2[2]);

        $this->from = sprintf("%s %s %s %s %s ON (%s.%s = %s.%s)", 
            $table1, $asname1, $jointype, $table2, $asname2, $asname1, $key1, $asname2, $key2);

        return $this->get_class();
    }

    // 关联第3,4..个表
    function join_together($together_key, $table, $key, $jointype = 'JOIN')
    {
        if(!preg_match("/^([a-z0-9]+)\.([a-z0-9_]+)$/i", $together_key, $match1)) {
            redirect('无效的关联表字段。'.$together_key);
        }
        if(!preg_match("/^([a-z0-9]+)\.([a-z0-9_]+)$/i", $key, $match2)) {
            redirect('无效的关联表字段。'.$key);
        }

        $together_asname = $match1[1];
        $asname          = $match2[1];

        $table           = $this->db->get_table($table);
        $together_key    = $this->db->_ck_field($match1[2]);
        $key             = $this->db->_ck_field($match2[2]);

        $this->from .= sprintf(" %s %s %s ON (%s.%s = %s.%s)", 
            $jointype, $table, $asname, $together_asname, $together_key, $asname, $key);
        return $this->get_class();
    }

    function use_index($index_name)
    {
        if(!preg_match("/^([a-z0-9_]+)$/i", $index_name)) {
            redirect('无效的索引名称。'.$index_name);
        }
        $this->index = " ($index_name)";

        return $this->get_class();
    }

    // 设置查询字段
    function select($fields, $asname = NULL, $fun = NULL)
    {
        $split = $this->select ? ',' : '';
        if(is_string($fields)) {
            if($fun) {
                $fields = str_replace('?', $this->db->_ck_field($fields), $fun);
            } else {
                $fields = $this->db->_ck_field($fields);
            }
            $select = $fields.($asname ? (' AS '.$this->db->_ck_field($asname)) : '');
            $this->select .= $split.$select;
        } elseif(is_array($fields)) {
            foreach ($fields as $key) {
                $this->select .= $split.$this->db->_ck_field($fields);
                $split = ',';
            }
        }

        return $this->get_class();
    }

    //字段有函数
    function select_param($fun, $fields) {
        if(!is_array($fields)) $fields = array($fields);
        foreach ($fields as $k => $field) {
            $fields[$k] = $this->db->_escape($field);
        }
        $select = vsprintf($fun, $fields);
        $this->select .= ($this->select ? ',' : '').$select;

        return $this->get_class();
    }
    
    // 设置更新字段或插入字段数据
    function set($key, $value=null) {
        if(is_array($key)) {
            foreach($key as $k => $v) {
                if(is_array($v) && count($v)==2 && is_array($v[1])) {
                    $fun = $v[0];
                    $args = array_merge(array($k), $v[1]);
                    call_user_func_array(array(&$this, $fun), $args);
                } else {
                    $this->set($k, $v);
                }
            }
        } else {
            $this->db->_ck_field($key);
            $this->set[$key] = $this->db->_escape($value);
        }

        return $this->get_class();
    }

    // 设置更新2个字段值相同
    function set_equal($key1,$key2) 
    {
        $this->db->_ck_field($key1);
        $this->db->_ck_field($key2);
        $this->set[$key1] = $key2;
        return $this->get_class();
    }
    
    // 设置更新字段累加
    function set_add($key, $value=1) 
    {
        if(!$value) return;
        $this->db->_ck_field($key);
        $this->set[$key] = $key.'+'.$this->db->_escape($value);

        return $this->get_class();
    }

    // 设置更新字段减少
    function set_dec($key, $value=1) 
    {
        $this->db->_ck_field($key);
        $this->set[$key] = $key.'-'.$this->db->_escape($value);

        return $this->get_class();
    }

    // 直接使用SQL表示set的值
    function set_src($key, $value) 
    {
        $this->set[$key] = $key.'-'.$value;

        return $this->get_class();
    }
    
    // 设置查询字段
    function where($key, $value = '', $split = 'AND')
    {
        if(!$key) return $this->get_class();
        if(is_array($key)) {
            foreach ($key as $k => $v) {
                if(is_array($v) && count($v)==2 && is_array($v[1])) {
                    $fun = $v[0];
                    $args = array_merge(array($k), $v[1]);
                    call_user_func_array(array(&$this, $fun), $args);
                } else {
                    $this->where($k, $v, $split);
                }
            }
        } elseif($key=='{sql}') {
            $this->_exp_where('sql', $value, $split);
        } elseif(is_array($value)) {
            $this->where_in($key, $value, $split);
        } else {
            $where = $this->db->_ck_field($key)." = ".$this->db->_escape($value);
            $this->where .= ($this->where ? " $split " : '').$where;    
        }

        return $this->get_class();
    }

    // 查询字段表达式，用户复杂的where子句
    function where_exp($exp, $key, $value, $split='AND')
    {
        if(is_array($value)) {
            foreach($value as $_k => $val) {
                $value[$_k] = $this->db->_escape($val);
            }
            $value = implode(",", $value);
        }
        $where = sprintf(str_replace(" ? "," %s ", $exp), $this->db->_ck_field($key), $value);
        $this->where .= ($this->where ? " $split " : '').$where;

        return $this->get_class();
    }

    // 设置查询子句形式 field != 'value'
    function where_not_equal($key, $value, $split='AND')
    {
        $where = $this->db->_ck_field($key)." != ".$this->db->_escape($value);
        $this->where .= ($this->where ? " $split " : '').$where;

        return $this->get_class();
    }

    // 设置查询子句形式 field >= 'value' 或 field > 'value'
    function where_more($key, $value, $equal=1, $split='AND')
    {
        $mark = $equal ? '>=' : '>';
        $where = $this->db->_ck_field($key).$mark.$this->db->_escape($value);
        $this->where .= ($this->where ? " $split " : '').$where;

        return $this->get_class();
    }

    // 设置查询子句形式 field <= 'value' 或 field < 'value'
    function where_less($key, $value, $equal=1, $split='AND')
    {
        $mark = $equal ? '<=' : '<';
        $where = $this->db->_ck_field($key).$mark.$this->db->_escape($value);
        $this->where .= ($this->where ? " $split " : '').$where;

        return $this->get_class();
    }

    // 设置查询子句形式 in
    function where_in($key, $values, $split='AND')
    {
        if(count($values) == 0) return;
        if(count($values) == 1) {
            $this->where($key, $values[0], $split);
            return;
        }
        foreach($values as $_key => $_val) {
            $values[$_key] = $this->db->_escape($_val);
        }
        $where = $this->db->_ck_field($key)." IN (". implode(",", $values) .")";
        $this->where .= ($this->where ? " $split " : '').$where;

        return $this->get_class();
    }

    // 设置查询子句形式 not in
    function where_not_in($key, $values, $split='AND')
    {
        foreach($values as $_key => $_val) {
            $values[$key] = $this->db->_escape($_val);
        }
        $where = $this->db->_ck_field($key)." NOT IN (". implode(",", $values) .")";
        $this->where .= ($this->where ? " $split " : '').$where;

        return $this->get_class();
    }
    
    // 设置查询子句形式 field between 100 and 200
    function where_between_and($key, $begin, $end, $split='AND')
    {
        $where = $this->db->_ck_field($key)." BETWEEN ". $this->db->_escape($begin) ." AND ".$this->db->_escape($end);
        $this->where .= ($this->where ? " $split " : '').$where;

        return $this->get_class();
    }

    // 设置查询子句形式 field like '100%'
    function where_like($key, $value, $split='AND', $kh = '')
    {
        $where = $this->db->_ck_field($key)." LIKE ".$this->db->_escape($value);
        $this->where .= ($this->where ? " $split " : '').($kh=='('?'(':'').$where.($kh==')'?')':'');

        return $this->get_class();
    }
    
    // 设置查询子句形式 or
    function where_or($key, $value)
    {
        if(is_array($value)) {
            $this->where_in($key, $value, 'OR');
        } else {
            $where = $this->db->_ck_field($key)." = ".$this->db->_escape($value);
            $this->where .= ($this->where ? ' OR ' : '').$where;
        }

        return $this->get_class();
    }

    // 设置查询子句形式 CONCAT(field,field2) like '100%'
    function where_concat_like($keys, $value, $split='AND', $kh='')
    {
        if(is_string($keys)) $keys = explode(',', $keys);
        foreach($keys as $k=>$v) {
            $keys[$k] = $this->db->_ck_field(trim($v));
        }
        $where = "CONCAT(".implode(',',$keys).") LIKE ".$this->db->_escape($value);
        $this->where .= ($this->where ? " $split " : '').($kh=='('?'(':'').$where.($kh==')'?')':'');

        return $this->get_class();
    }

    // 设置exist子查询
    function where_exist($sql, $split='AND', $kh = '')
    {
        $where = 'exists(' .$this->db->repace_table($sql).')';
        $this->where .= ($this->where ? " $split " : '').($kh=='('?'(':'').$where.($kh==')'?')':'');

        return $this->get_class();
    }

    // 设置exist子查询
    function where_in_select($key, $sql, $split='AND', $kh = '')
    {
        $where = $key.' IN(' .$this->db->repace_table($sql).')';
        $this->where .= ($this->where ? " $split " : '').($kh=='('?'(':'').$where.($kh==')'?')':'');

        return $this->get_class();
    }

    // 设置group_by子句
    function group_by($groups) 
    {
        if(is_array($groups)) {
            foreach($groups as $key => $val) {
                $groups[$key] = $this->db->_ck_field($val);
            }
            $groupby = implode(',', $groups);
        } elseif(is_string($groups)) {
            $groupby = $this->db->_ck_field($groups);
        }
        $this->group_by .= ($this->group_by ? ',' : '').$groupby;

        return $this->get_class();
    }

    //设置having子句
    function having($exp) 
    {
        $this->having = $exp;

        return $this->get_class();
    }
    
    // 设置order_by子句
    function order_by($field, $type='ASC') 
    {
        if($field=='NULL') {
            $this->order_by = "NULL";
            return $this->get_class();
        } elseif(is_string($field)) {
            if(strpos($field, ' ')) {
                list($field, $type) = explode(' ', $field);
            }
            $type = strtoupper($type);
            $orderby = $this->db->_ck_field($field).($type=='DESC' ? (' '.$type) : '');
        } elseif(is_array($field)) {
            $split = '';
            foreach ($field as $key => $val) {
                $val = strtoupper($val);
                $orderby .= $split.$this->db->_ck_field($key).($val=='DESC' ? (' '.$val) : '');
                $split = ',';
            }
        }
        $this->order_by .= ($this->order_by ? ',' : '').$orderby;

        return $this->get_class();
    }

    // 设置 limit 子句
    function limit($start, $offset) 
    {
        $start = (int) $start;
        $offset = (int) $offset;

        if(!$start && !$offset) {
            return $this->get_class();
        }

        if(!$start) {
            $this->limit = "$offset";
        }
        if (!$offset) {
            $offset = 10;
        }
        $this->limit = "$start, $offset";

        return $this->get_class();
    }

    /**
     * SQL代码回滚，将在缓存的SQL重新设置成当前有效的SQL，支持回滚部分参数
     * @param  string $vars 需要回滚的表但是字段，例如from,where
     */
    function sql_roll_back($vars = null) 
    {
        $arr = $vars ? (is_array($vars) ? $vars : explode(',',$vars)) : $this->var_fields;
        foreach($arr as $v) {
            $n = 'cache_'.$v;
            $this->$v = $this->$n;
        }

        return $this->get_class();
    }

    //组合一个查询sql
    function get_sql($get_count_sql = false) 
    {
        foreach($this->var_fields as $v) {
            $$v = $v;
        }
        if(!$this->$from) show_error(lang('global_sql_invalid', "FROM(Get)"));
        $SQL = "SELECT ".($get_count_sql ? "COUNT(*)":($this->$select ? $this->$select : "*")) .
            " FROM ".$this->$from .
            ($this->$index ? " USE INDEX ".$this->index : "") .
            ($this->$where ? " WHERE ".$this->where : "") .
            ($this->$group_by ? " GROUP BY ".$this->$group_by : "") .
            ($this->$having ? " HAVING ".$this->$having : "") .
            ($this->$order_by ? " ORDER BY ".$this->$order_by : "") .
            ($get_count_sql ? '' : ($this->$limit ? " LIMIT ".$this->$limit : ""));
        
        return $SQL;
    }

    //组合一个插入sql
    function insert_sql($replace = FALSE, $update = FALSE) 
    {
        foreach($this->var_fields as $v) {
            $$v = $v;
        }
        if(empty($this->$from)) {
            show_error(lang('global_sql_invalid', 'FROM(insert)'));
        }
        if(empty($this->$set)) {
            show_error(lang('global_sql_invalid', 'SET(insert)'));
        }

        $split = $setsql = '';
        foreach($this->$set as $key => $val) {
            $setsql .= $split.$key.'='.$val;
            $split = ',';
        }

        if($update) {
            $SQL = "UPDATE ".$this->$from." SET ".$setsql.
                ($this->$where ? " WHERE ".$this->$where : "") .
                ($this->$order_by ? " ORDER BY ".$this->$order_by : "");
        } else {
            $SQL = ($replace ? "REPLACE " : "INSERT ").$this->$from." SET ".$setsql;
        }

        return $SQL;
    }

    //组合成一个删除sql
    function delete_sql()
    {
        foreach($this->var_fields as $v) {
            $$v = $v;
        }
        if(!$this->$from) show_error(lang('global_sql_invalid', "FROM(delete)"));
        $SQL = "DELETE FROM ".$this->$from.($this->$where ? " WHERE ".$this->$where : "");
        return $SQL;
    }

    /**
     * 清理当前的sql表达式内容
     * @param  string $name [description]
     */
    function clear($name = 'ALL') {
        if($name == 'ALL') {
            foreach($this->var_fields as $v) {
                $this->$v = '';
            }
        } elseif(isset($name[$this->var_fields])) {
            $this->$name = '';
        }
    }

    /**
     * 特殊含义的sql解析 
     *
     * @param string $exp
     * @param string $value
     * @param string $split
     * @return string
     */
    function _exp_where($exp,$value,$split)
    {
        $pattern_arr = array("/ union /i", "/ select /i", "/ update /i", "/ outfile /i");
        $where = '';
        switch($exp) {
        case 'sql':
            foreach($pattern_arr as $p) if(preg_match($p,$value)) return;
            $where = $value;
            break;
        }
        if(!$where) return;
        $this->where .= ($this->where ? " $split " : '').$where; 
    }

    //缓存SQL各子句数据
    function _cache_sql($clear=FALSE) {
        foreach($this->var_fields as $v) {
            $n = 'cache_'.$v;
            $this->$n = $this->$v;
            if($clear) $this->$v = '';
        }
    }

    /**
     * 返回可用于函数链操作的类
     * @return [type] [description]
     */
    protected function get_class()
    {
        return $this->is_activerecord ? $this->db : $this;
    }

}