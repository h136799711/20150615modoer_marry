<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2012 Moufersoft
* @website www.modoer.com
*/
_G('loader')->model('tool', FALSE);
class msm_tool_repairitemcategory extends msm_tool {

    protected $name = '修复主题分类表数据';
    protected $descrption = '修复在主题筛选时无法正常使用主题分类进行筛选。<b>执行完本脚本后，请再执行“修复异常主题的数据”脚本。</b>';
    protected $acttype = 'repair';

    public function run() {
        $this->repair();
    }

    private function repair() {
        $offset = 50; //_get('offset', 500, MF_INT_KEY);
        $start = _get('start', 0, MF_INT_KEY);
        $q = _G('db')->from('dbpre_category')->limit($start, $offset)->order_by('catid', 'ASC')->get();
        if(!$q) {
            $this->completed = true;
            //更新分类缓存
            _G('loader')->model('item:category')->write_cache();
            return;
        }
        while ($v = $q->fetch_array()) {
            if(!$v['attid'] || ($v['attid'] > 0 && !$this->get_att_exists($v['attid']))) {
                $this->update_attid($v);
            }
        }
        $this->params['start'] = $start + $offset;
        $this->params['offset'] = $offset;
        $this->message = '正在修复主题分类表...'.($start).'-'.($this->params['start']);
    }

    private function get_att_exists($attid) {
        return _G('db')->from('dbpre_att_list')->where('attid', $attid)->where('type','category')->get_one();
    }

    private function update_attid($cate_data) {
        $attid = _G('loader')->model('item:att_list')->save($cate_data['catid'], $cate_data['name'], 'category');
        if($attid) {
            _G('db')->from('dbpre_category')->set('attid',$attid)->where('catid',$cate_data['catid'])->update();
        }
    }
}