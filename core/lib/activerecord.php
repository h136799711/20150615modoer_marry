<?php
/**
* 数据库操作
*/
class ms_activerecord
{
	//兼容旧版变量
	public $dns = array();

	protected $db;
	protected $sqlbuild;

	function __construct($dsn = null)
	{
		if ($dsn) {
			$this->db = new ms_mysql($dsn);
			$this->db->connect();
			$this->dns = $this->db->dns;
		}
		$this->new_sqlbuild();
	}

	/**
	 * 对调用本类的函数进行解析，本类不存在的从ms_mysql和ms_sqlbuild中查找
	 * @param  string $method
	 * @param  array $args
	 * @return mixed
	 */
	function __call($method, $args)
	{
		if (! $this->sqlbuild) {
			$this->new_sqlbuild();
		}
		if (method_exists($this, $method)) { //从本类查找
			return call_user_func_array(array($this, $method), $args);
		} elseif (method_exists($this->db, $method)) { //从 ms_mysql 类查找
			return call_user_func_array(array($this->db, $method), $args);
		} elseif (method_exists($this->sqlbuild, $method)) { //从 ms_sqlbuild 类查找
		   return call_user_func_array(array($this->sqlbuild, $method), $args);
		} else {
			//找不到方法，则抛出异常
		   throw new Exception(sprintf(
				'The required method "%s" does not exist for %s', 
				$method, 
				get_class($this))
		   ); 
		}
	}

	/**
	 * 设置一个mysql操作类
	 * @param ms_mysql $db [description]
	 */
	function set_db(ms_mysql $db)
	{
		$this->db = $db;
		$this->dns = $this->db->dns;
	}

	/**
	 * 获取db类
	 * @return ms_mysql
	 */
	function get_db()
	{
		return $this->db;
	}

	/**
	 * 新建一个sqlbuild类
	 */
	function new_sqlbuild()
	{
		$this->sqlbuild = new ms_sqlbuild($this);
	}

	/**
	 * 从组合的sql中获取 mysql_result 类
	 * @param  string  $method  查询模式，可输入：unbuffer
	 * @param  boolean $clear_var
	 * @return mixed
	 */
	function get($method = '', $clear_var = TRUE)
	{
		$SQL    = $this->sqlbuild->get_sql(0);
		$result = $this->db->query($SQL, $method);
		$this->sqlbuild->_cache_sql(); //sql cache 
		if($clear_var) {
			$this->sqlbuild->clear();
		}
		return $result;
	}

	/**
	 * 从数据中获取数据
	 * @param  string|array $select   需要获取的表字段名
	 * @param  string $from     表名
	 * @param  array $where    查询条件
	 * @param  string|array $group_by group by
	 * @param  string|array $order_by order by
	 * @param  string|array $limit    limit
	 * @return array
	 */
	function get_easy($select, $from, $where = null, $group_by = null, $order_by = null, $limit = null)
	{
		$this->select($select);
		$this->from($from);
		if($where) {
			foreach($where as $sk => $sv) {
				$this->where($sk, $sv);
			}
		}
		if($group_by) $this->group_by($group_by);
		if($order_by) $this->order_by($order_by[0], $order_by[1]);
		if($limit) $this->limit($limit[0],$limit[1]);

		return $this->get();
	}

	/**
	 * 获取一条数据
	 * @param  string  $method
	 * @param  boolean $clear_var
	 * @return mixed
	 */
	function get_one($method = '', $clear_var = TRUE)
	{
		if(!$this->limit) $this->sqlbuild->limit(0,1);
		$SQL    = $this->sqlbuild->get_sql(0);
		$result = $this->db->query($SQL, $method);
		$this->sqlbuild->_cache_sql(); //sql cache 
		if($clear_var) {
			$this->sqlbuild->clear();
		}
		if($result) {
			return $result->fetch_array();
		} else {
			return false;
		}
	}

	/**
	 * 获取一个字段数据
	 * @param  boolean $clear_var
	 * @return mixed
	 */
	function get_value($clear_var = TRUE)
	{
		$this->sqlbuild->limit(0,1);
		$SQL    = $this->sqlbuild->get_sql(0);
		$result = $this->db->query($SQL);
		$this->sqlbuild->_cache_sql(); //sql cache 
		if($clear_var) {
			$this->sqlbuild->clear();
		}
		return $result ? $result->result(0) : FALSE;
	}

	/**
	 * 获取查询到的所有结果，并以数组方式返回
	 * @param  string  $keyname   自定义数组键名，通过数据集内指定的字段名填充
	 * @param  string  $value_name   自定义数据值，通过数据集内指定的数据值填充
	 *                               (数组键值不返回SQL里指定的字段值，返回一个指定的字段名称对应的值)
	 * @param  boolean $clear_var [description]
	 * @return array
	 */
	function get_all($key_name = '', $value_name = '', $clear_var = TRUE) {
		$q = $this->get();
		if(!$q) return;
		$value_name_list = trim($value_name_list);
		if($value_name_list) $value_name_list = explode(',',$value_name_list);
		$result = array();
		while ($v = $q->fetch_array()) {
			if($key_name && isset($v[$key_name])) {
				$result[$v[$key_name]] = isset($v[$value_name])?$v[$value_name]:$v;
			} else {
				$result[] = isset($v[$value_name])?$v[$value_name]:$v;
			}
		}
		return $result;
	}

	/**
	 * 返回数据条数
	 * @param  boolean $clear_var
	 * @return int
	 */
	function count($clear_var = TRUE)
	{
		$SQL = $this->sqlbuild->get_sql(1);
		$row = $this->db->query($SQL);
		$this->sqlbuild->_cache_sql();
		if($clear_var) {
			$this->sqlbuild->clear();
		}
		return $row->result(0);
	}

	/**
	 * 执行插入（替换）表操作，返回受影响数据行数
	 * @param  boolean $replace
	 * @param  boolean $clear_var
	 * @return int
	 */
	function insert($replace = FALSE, $clear_var = TRUE)
	{
		$SQL = $this->sqlbuild->insert_sql($replace, 0);
		
		$arows = $this->db->exec($SQL);
		$this->sqlbuild->_cache_sql();
		if($clear_var) {
			$this->sqlbuild->clear();
		}
		return $arows;
	}

	/**
	 * 更新表数据，返回受影响数据行数
	 * @param  boolean $clear_var
	 * @return int
	 */
	function replace($clear_var=TRUE)
	{
		$SQL = $this->sqlbuild->insert_sql(true, 0);
		$arows = $this->db->exec($SQL);
		$this->sqlbuild->_cache_sql();
		if($clear_var) {
			$this->sqlbuild->clear();
		}
		return $arows;
	}

	/**
	 * 更新数据，返回受影响数据行数
	 * @param  boolean $clear_var
	 * @return int
	 */
	function update($clear_var = TRUE)
	{
		$SQL = $this->sqlbuild->insert_sql(0, 1);
		$arows = $this->db->exec($SQL);
		$this->sqlbuild->_cache_sql();
		if($clear_var) {
			$this->sqlbuild->clear();
		}
		return $arows;
	}

	/**
	 * 执行删除操作，返回受影响数据行数
	 * @param  boolean $clear_var
	 * @return int
	 */
	function delete($clear_var=TRUE)
	{
		$SQL = $this->sqlbuild->delete_sql();
		$arows = $this->db->exec($SQL);
		$this->sqlbuild->_cache_sql();
		if($clear_var) {
			$this->sqlbuild->clear();
		}
		return $arows;
	}

	/**
	 * 清空一个表
	 */
	function clear_table() {
		if(!$this->sqlbuild->from) {
			show_error(lang('global_sql_invalid', 'FROM(Clear Table)'));
		}
		$this->db->exec("TRUNCATE TABLE " . $this->sqlbuild->from);
		return $this->affected_rows();
	}

	/**
	 * 删除一个表
	 */
	function drop_table() {
		if(!$this->sqlbuild->from) {
			show_error(lang('global_sql_invalid', 'FROM(Dorp Table)'));
		}
		$this->db->exec("DROP TABLE IF EXISTS " . $this->sqlbuild->from);
	}

}
/** end **/