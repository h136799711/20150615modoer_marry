<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2012 Moufersoft
* @website www.modoer.com
*/
_G('loader')->model('tool', FALSE);
class msm_tool_delinvalidcategory extends msm_tool {

    protected $name = '删除冗余的主题子分类';
    protected $descrption = '清理主题分类里不存在父类的子分类。';
    protected $acttype = 'delete';

    public function run() {
        $level = _get('level', 3);
        (!$level || $level > 3) && $level = 3;
        $dels = array();
        if($q = $this->get_cur_catids($level)) {
            while($v = $q->fetch_array()) {
                if(!$v['pid']) continue;
                if(!$this->check_parent_catid($v['pid'])) {
                    $dels[] = $v['catid'];
                }
            }
            if($dels) {
                _G('db')->from('dbpre_category')->where('catid', $dels)->delete();
            }
            $q->fetch_array();
        }
        $level--;
        if($level <= 0) {
            $this->completed = true;
        } else {
            $this->message = "正在处理" . ($level + 1) . "级分类。";
            $this->params['level'] = $level;
        }
    }

    private function get_cur_catids($level) {
        return _G('db')->from('dbpre_category')->where('level', $level)->get();
    }

    private function check_parent_catid($subcatid) {
        return _G('db')->from('dbpre_category')->where('catid', $subcatid)->count() > 0;
    }

}
?>