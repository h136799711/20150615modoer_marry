<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2012 Moufersoft
* @website www.modoer.com
*/
_G('loader')->model('tool', FALSE);
class msm_tool_repairproduct extends msm_tool {

    protected $name = '修复异常商城产品分类丢失';
    protected $descrption = '修复商城产品主分类和子分类关联不正确的问题。';
    protected $acttype = 'repair';

    public function run() {
        $offset = _get('offset', 100, MF_INT_KEY);
        $start = _get('start', 0, MF_INT_KEY);
        //$count = _G('db')->from('dbpre_product')->count();
        $list = _G('db')->from('dbpre_product')->select('pid,pgcatid,gcatid')->order_by('pid')->limit($start, $offset)->get();
        if(!$list) {
            $this->completed = true;
        } else {
            $P =& _G('loader')->model(':product');
            while ($val=$list->fetch_array()) {
                $this->repair_category($val);
            }
            $list->free_result();
            $this->params['start'] = $start + $offset;
            $this->params['offset'] = $offset;
            $this->message = '正在修复主题...'.($start).'-'.($this->params['start']);
        }
    }

    public function create_form() {
        $this->loader->helper('form');
        $elements = array();
        $elements[] = 
            array(
            'title' => '单次操作产品数量',
            'des' => '由于每一个产品处理步骤较多，不宜超过500个',
            'content' => form_input('offset', '300', 'txtbox4'),
        );
        return $elements;
    }

    private function repair_category($product) {
        $gcatid = $product['gcatid'];
        if(!$gcatid) return;
        $cate = _G('db')->from('dbpre_product_gcategory')->where('catid',$gcatid)->get_one();
        if($cate['level'] == '3') {
            $cate = _G('db')->from('dbpre_product_gcategory')->where('catid', $cate['pid'])->get_one();
            $pgcatid = $cate['pid'];
        } else {
            $pgcatid = $cate['pid'];
        }
        if($pgcatid != $product['pgcatid']) {
            _G('db')->from('dbpre_product')
                ->where('pid',$product['pid'])
                ->set('pgcatid', $pgcatid)
                ->update();
        }
    }

}
?>