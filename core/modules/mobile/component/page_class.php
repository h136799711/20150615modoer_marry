<?php
/**
* 手机web分页类
*/
class mc_mobile_page extends ms_base
{
	const MODE_DEFAULT	= 'default';	//普通模式（上一页 下一页）
	const MODE_APPEND  	= 'append';		//增加模式（下一页）
	const MODE_NAV  	= 'nav';		//导航模式（上一页 [页码选择] 下一页）

	public $count		= 0;
	public $offset 		= 10;
	public $page 		= 1;
	public $url			= '';
	public $anchor		= '';

	public $page_num	= 0;	//本页实际显示数量(在不知道数据总数时)	

	public $box_class	= 'pagination';	//分页框架样式
	public $a_class		= 'btn btn-mycolor';		//页码样式

	public $mode		= '';	//使用的模式

	function __construct($page=1, $offset=10, $count=0, $url = '')
	{
		parent::__construct();
		$this->page   = $page;
		$this->offset = $offset;
		$this->count  = $count;
		$this->url   = $url;
	}

	//生成html
	function create($mode = self::MODE_DEFAULT) 
	{
		$url 		= $this->_url_pares();
		$this->mode = $mode;
		$content 	= '';

		//开始数量
		$start 		= ($this->page - 1) * $this->offset;

		//上一页
	    if($start > 0 && $this->mode != self::MODE_APPEND) {
	    	$content .= '<a href="'.str_replace('{PAGE}', $this->page - 1, $url).'" class="J_forward '.$this->a_class.'" data-name="page_forward">'.lang('global_forward_page').'</a>';
	    }

	    //分页导航
	    if($this->count > $this->offset && $this->mode == self::MODE_NAV) {

	    }
	    
	    //下一页
	    if($this->count > $start + $this->offset || (!$this->count && $this->page_num > $this->offset)) {
			$content .= '<a href="'.str_replace('{PAGE}', $this->page + 1, $url).'" class="J_next '.$this->a_class.'" data-name="page_next">'.lang('global_next_page').'</a>';
	    }
	    
	    //组合
	    if($content) {
	    	$boxclass = $this->box_class.' page-mode-'.$this->mode;
	    	return '<div data-name="pagination" class="'.$boxclass.'">'.$content.'</div>';
	    }
	    
	    return '';
	}

	//页面URL处理
	private function _url_pares()
	{
		$mpurl = $this->url;
		$anchor = $this->anchor ? "#{$this->anchor}" : '';

        if(!strpos($mpurl, '_PAGE_')) {
            $mpurl .= strposex($mpurl, '?') ? '&amp;' : '?';
            $mpurl = $mpurl.'page={PAGE}'.$anchor;
        } else {
            $mpurl = str_replace('_PAGE_', '{PAGE}', $mpurl).$anchor;
        }

        return $mpurl;
	}

}