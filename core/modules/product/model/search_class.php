<?php
/**
* 商品搜索
*/
class msm_product_search extends msm_product
{

	//接受的参数
	public $params = array(
		'catid'		=> '',
		'keyword'	=> '',
		'orderby'	=> 'sales',
		'sort'		=> 'desc',
		'filter'	=> '0',
		'att'		=> '',
		'num'		=> 10,
		'page'		=> 1,
	);

	//排序字段
	public $orderby_options = array('sales', 'price', 'comments', 'last_update');

	//默认单页数量
	public $default_num = 10;

	//查询结果
	protected $total = 0;	//数量
	protected $data = null;	//结果集
	protected $atts = array(); //选择的属性组数组
	protected $attcats = array(); //分类下管理的属性组
	protected $category = array(); //当前分类数据

	public function set_param($key, $value)
	{
		$this->params[$key] = $value;
	}

	/**
	 * 搜索商品
	 * @return boolean 查询操作是否成功
	 */
	public function search()
	{
		//参数验证
		if(!$this->check_params()) return false;

		//获取查询参数
		if(!$where = $this->get_where()) return false;

		//排序赋值
		$orderby = array('p.'.$this->params['orderby'] => $this->params['sort']);

		//获取数量
		$start = get_start($this->params['page'], $this->params['num']);

		//属性组处理
		$atts = array();
		if($att = $this->params['att']) {
		    $att = explode('_', $att);
		    foreach($att as $att_v) {
		        list($att_catid, $att_id) = explode('.', $att_v);
		        if(!$att_catid || !$att_id) continue;
		        $atts[$att_catid] = $att_id;
		    }
		}
		$this->atts = $atts;

		//获取数量
		list($total, $list) = parent::find('p.*', $where, $orderby, $start, $this->params['num'], true, 's.name,s.subname', $atts);

		//数量
		$this->total = $total;

		//数据数组
		if($total > 0 && $list) {
			$this->data = array();
			while ($v = $list->fetch()) {
				$this->data[] = $v;
			}
		}

		return true;
	}

	/**
	 * 获得搜索参数数组
	 * @return array
	 */
	public function params()
	{
		return $this->params;
	}

	/**
	 * 获取查询结果数量
	 * @return int
	 */
	public function total()
	{
		return $this->total;
	}

	/**
	 * 获取查询结果数据集合
	 * @return array
	 */
	public function data()
	{
		return $this->data;
	}

	/**
	 * 获取选择的属性组数组
	 * @return array 
	 */
	public function atts()
	{
		return $this->atts;
	}

	/**
	 * 获取选择的分类可筛选的属性组
	 * @return array 
	 */
	public function attcats()
	{
		return $this->attcats;
	}

	/**
	 * 输入的查询参数验证
	 * @return boolean 验证成功返回true,反之false
	 */
	protected function check_params() 
	{
		if($this->params['catid'] > 0) {
			if(!$this->params['pid'] = $this->gcategory_model->get_parent_id($this->params['catid'])) {
			    return $this->add_error('product_cat_empty');
			}			
		}

		//排序处理
		if(!in_array($this->params['orderby'], $this->orderby_options)) $this->params['orderby'] = $this->orderby_options[0];
		$this->params['sort'] = strtoupper($this->params['sort']) == 'ASC' ? 'ASC' : 'DESC';

		//分页参数
		$this->params['num'] = $this->params['num'] > 0 ? $this->params['num'] : $this->default_num;
		$this->params['page'] = $this->params['page'] > 0 ? $this->params['page'] : 1;

		return true;
	}

	/**
	 * 生成查询条件where
	 * @return array
	 */
	protected function get_where()
	{
		$where = array();
		$where['p.status'] = 1;
		$where['p.is_on_sale'] = 1;

		//店内搜索
		$sid = $this->params['sid'];
		if($sid > 0) {
			$where['p.sid'] = $sid;
			$s_catid = $this->params['s_catid'];
			if($s_catid > 0) {
				$where['p.catid'] = $s_catid;
			}			
		}

		$catid = $this->params['catid'];
		if($catid > 0) {
			if(!$pid = $this->get_pid($catid)) {
			    return $this->add_error('product_cat_empty');
			}

			//分类分级变量1主2子
			$category = $this->loader->variable('gcategory_' . $this->params['pid'], 'product');
			//判断子分类是否禁用
			if(!$category[$catid]['enabled']) return $this->add_error('product_cat_disabled');
			$this->category = $category[$catid];
			//分类等级
			$category_level = $category[$catid]['level'];
			$subcats = $category[$catid]['subcats'];
			//
			$w_gcatid = "";
			if($catid != $pid) {
				//第三级
				if($category_level > 2) {
			        $attcats = explode(',', trim($category[$catid]['attcat'], ','));
			        $where['p.gcatid'] = $catid;
			        $w_gcatid = "gcatid='$catid'";
				} else {
			    	$attcats = explode(',', trim($category[$catid]['attcat'], ','));
			    	$where['p.gcatid'] = $subcats ? array_merge(array($catid), explode(',',trim($subcats, ','))) : array($catid);
			        $w_gcatid = "gcatid IN (".implode(',', $where['p.gcatid']).")";
				}
				//属性组
			    if($attcats) {
			        foreach ($attcats as $key => $value) {
			            if(preg_match("/^[0-9]+\|[0-9]{1}$/", $value)) {
			                list($_catid, $is_multi) = explode('|', $value);
			                $attcats[$key] = $_catid;
			            } elseif(is_numeric($value)) {
			                $attcats[$key] = $value;
			            }
			        }
			    }
			    $this->attcats = $attcats;
			} else {
				//一级分类
				$catelist = _G('loader')->variable('gcategory_'.$pid, 'product');
			    $ids = array();
			    foreach($catelist as $key=>$val) {
			        $ids[] = $key;
			    }
			    $where['p.pgcatid'] = $pid;
			    $w_gcatid = "pgcatid='$pid'";
			}			
		}
		$this->w_gcatid = $w_gcatid;

		//获取当前城市的产品数据
		if(!$sid) {
			$where['p.city_id'] = array(0, (int)_G('city','aid'));
		}
		
		//关键字
		if($this->params['keyword']) {
			$where['subject'] = array('where_like',array("%{$this->params['keyword']}%"));
		}

		//是否筛选促销
		if($this->params['filter']) $where['p.promote'] = array('where_more',array("0.01"));

		return $where;
	}

	protected function get_where_gcategory()
	{

	}
}