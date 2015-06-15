<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2012 Moufersoft
* @website www.modoer.com
*/
_G('loader')->model('tool', FALSE);
class msm_tool_delinvalidattlist extends msm_tool {

    protected $name = '删除冗余的地区和主题分类属性值';
    protected $descrption = '清理地区以及主题分类关联的modoer_att_list关联的属性值。';
    protected $acttype = 'delete';

    public function run() {
        $step = _get('step', 1);
        (!$step || $step <2) && $step = 1;

        if($step > 2) {
            $this->completed = true;
            return;
        }

        $tables = array('1'=>'area', '2'=>'category');
        $fun = '_delete_' . $tables[$step];
        $rows = $this->$fun();

        if($step > 2) {
            $this->completed = true;
        } else {
            $this->message = "($step/2)处理表" . ($tables[$step]) . "..." . ($rows?"已清理{$rows}条数据...":'');
            $this->params['step'] = $step+1;
        }
    }

    private function _delete_area() {
        $SQL="DELETE FROM modoer_att_list WHERE type='area' AND attid NOT IN(SELECT attid FROM modoer_area)";
        _G('db')->exec($SQL);
        return _G('db')->affected_rows();
    }

    private function _delete_category() {
        $SQL="DELETE FROM modoer_att_list WHERE type='category' AND attid NOT IN(SELECT attid FROM modoer_category)";
        _G('db')->exec($SQL);
        return _G('db')->affected_rows();
    }

}
?>