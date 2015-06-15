<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2012 风格店铺
* @website www.cmsky.org
*/
class display_product {

    //参数 catid,keyname
    function p_name($params) {
        extract($params);
        if(!$pid) return '';
        if(!$keyname) $keyname = 'subject';
        $loader =& _G('loader');
        if($pid > 0) {
            $P =& $loader->model('product:product');
            if(!$detail = $P->read($pid)) return '';
            return $detail[$keyname];
        }
    }

    //参数 modelid
    function model($params) {
        extract($params);
        $loader =& _G('loader');
        if(!$modelid) return '';
        if(!$keyname) $keyname = 'name';
        if(!$model = $loader->variable('model_' . $modelid, 'product')) return 'N/A';
        return $model[$keyname];
    }

    //取得分类的名称或其它
    //参数 catid,keyname
    function gcategory($params) {
        extract($params);
        if(!$keyname) $keyname = 'name';
        if(!$catid) return '';
        $loader =& _G('loader');

        $C =& $loader->model('product:gcategory');
        $root_id = $C->get_parent_id($catid);

        if(!$category = $loader->variable('gcategory_' . $root_id, 'product')) return '';
        return $category[$catid][$keyname];
    }

    //取得一个当前的价格
    function price($params) {
        if(!$params['product'] || !is_array($params['product'])) return lang('product_price_unkown');
        return _G('loader')->model(':product')->myprice($params['product']);
    }

    //取得某个产品自定义数据
    function custom_data($params) 
    {
        $pid = $params['pid'];
        $q = _G('db')->from('dbpre_product_data')->where('pid',$pid)->get();
        if(!$q) return;
        $result = array();
        while ($v=$q->fetch_array()) {
            $result[$v['fieldname']] = $v['value'];
        }
        return $result;
    }

}
?>