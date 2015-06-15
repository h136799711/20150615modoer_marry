<?php
/**
* 获取商品详细内容
*/
class mc_product_detail extends mc_product_middle
{
	
	protected function init()
	{
		$this->model = $this->loader->model(':product');
	}

	protected function work()
	{
		$id = $this->get_param('id', 0, MF_INT_KEY);
		$detail = $this->model->read($id);

		if(!$detail) return $this->add_error('product_empty');
		if(!$detail || !$detail['status']) return $this->add_error('product_empty');
		if(!$detail['is_on_sale']) return $this->add_error('抱歉，该产品已经下架，进入此商家店铺挑选其他产品。');

		if($detail['pictures']) {
			$detail['pictures'] = unserialize($detail['pictures']);
		}
		if($detail['tag_keyword']) {
			$detail['tag_keyword'] = explode('|',trim($detail['tag_keyword'],'|'));
		}

		//主题的属性组数据
		$atts = $this->get_atts($id);
		$this->result['atts'] = $atts;

		//关联商户
		$S = $this->loader->model('item:subject');
		$subject = $S->read($detail['sid']);
		$this->result['subject'] = $subject;

		//商品分类
		$category = $this->loader->model('product:gcategory')->read($detail['gcatid']);
		$this->result['category'] = $category;

		//我的价格
		$this->result['myprice'] = $this->model->myprice($detail);

		//更新浏览量
		if($this->get_param('up_pv')) {
			$this->model->pageview($pid);
		}

		$this->result['data'] = $detail;
	}

	protected function get_atts($id)
	{
	    $r = _G('db')->join('dbpre_productatt','at.attid','dbpre_att_list','al.attid')
	    	->where('at.pid', $pid)
	    	->select('at.*,al.name')
	    	->get();
	    if(!$r) return array(array(), array());

	    while ($v = $r->fetch_array()) {
	        $result[$v['att_catid']][$v['attid']] = $v['name'];
	    }

	    $q = _G('db')->from('dbpre_att_cat')->where('catid',array_keys($result))->get();
	    while ($v=$q->fetch_array()) {
	        $attcats[$v['catid']] = $v['name'];
	    }

	    return array($attcats, $result);
	}

}