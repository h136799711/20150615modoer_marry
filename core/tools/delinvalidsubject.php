<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2012 Moufersoft
* @website www.modoer.com
*/
_G('loader')->model('tool', FALSE);
class msm_tool_delinvalidsubject extends msm_tool {

    protected $name = '删除冗余的关联主题和点评';
    protected $descrption = '清理系统内主题分类已经不存在的关联主题和点评，<b>本脚本为删除操作请先备份数据库再进行</b>。';
    protected $acttype = 'delete';

    public function run() {
        $step = _get('step', '0', MF_INT_KEY);
        if($step == '0') {
            $this->_delete_subject();
            if(!$this->completed) {
                $this->params['step'] = 1;
                $this->message = '正在删除主题...';
            }
        } else {
            $this->_delete_review();
            $this->completed = true;
        }
    }

    private function _delete_subject() {
        if($catids = $this->get_catids()) {
            _G('db')->from('dbpre_subject')
                ->where_not_in('catid', $catids)
                ->delete();
        } else {
            $this->completed = true;
        }
    }

    private function _delete_review() {
        if($catids = $this->get_catids()) {
            _G('db')->from('dbpre_review')
                ->where('idtype','item_subject')->where_not_in('pcatid', $catids)
                ->delete();
        }
    }

    private function get_catids() {
        $q = _G('db')->from('dbpre_category')->get();
        if(!$q) return false;
        $result = array();
        while($v = $q->fetch_array()) {
            $result[] = (int)$v['catid'];
        }
        $q->fetch_array();
        return array_unique($result);
    }

}
?>