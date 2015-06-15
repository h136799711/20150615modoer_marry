<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2012 Moufersoft
* @website www.modoer.com
*/
_G('loader')->model('tool', FALSE);
class msm_tool_repairarea extends msm_tool {

    protected $name = '修复地区表数据';
    protected $descrption = '修复在主题筛选时无法正常使用地区进行筛选。<b>执行完本脚本后，请再执行“修复异常主题的数据”脚本。</b>';
    protected $acttype = 'repair';

    public function run() {
        $this->repair();
    }

    private function repair() {
        $offset = 50; //_get('offset', 500, MF_INT_KEY);
        $start = _get('start', 0, MF_INT_KEY);
        $q = _G('db')->from('dbpre_area')->limit($start, $offset)->order_by('aid', 'ASC')->get();
        if(!$q) {
            $this->completed = true;
            //更新缓存
            include MUDDER_MODULE . 'modoer' . DS . 'inc' . DS . 'cache.php';
            return;
        }
        while ($v = $q->fetch_array()) {
            if(!$v['attid'] || ($v['attid'] > 0 && !$this->get_att_exists($v['attid']))) {
                $this->update_attid($v);
            }
        }
        $this->params['start'] = $start + $offset;
        $this->params['offset'] = $offset;
        $this->message = '正在修复地区表...'.($start).'-'.($this->params['start']);
    }

    private function get_att_exists($attid) {
        return _G('db')->from('dbpre_att_list')->where('attid', $attid)->where('type','area')->get_one();
    }

    private function update_attid($area_data) {
        $attid = _G('loader')->model('item:att_list')->save($area_data['aid'], $area_data['name'], 'area');
        if($attid) {
            _G('db')->from('dbpre_area')->set('attid',$attid)->where('aid',$area_data['aid'])->update();
        }
    }
}