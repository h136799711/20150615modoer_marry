<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2012 Moufersoft
* @website www.modoer.com
*/
_G('loader')->model('tool', FALSE);
class msm_tool_delpointlog extends msm_tool {

    protected $name = '删除过期用户积分记录';
    protected $descrption = '清理用户的积分更新记录，提高数据查询效率。';
    protected $acttype = 'delete';

    public function run() {
        $pointflow = _get('pointflow', null, MF_TEXT);
        $pointtype = _get('pointtype', null, MF_TEXT);
        $days_ago = _get('days_ago', 0, MF_INT);
        $usernames = _get('usernames', '', MF_TEXT);
        if($pointflow=='all') $pointflow = null;
        if(!$pointtype) redirect('未选择需要删除的积分类型。');

        _G('db')->from('dbpre_point_log');
        if($pointflow) _G('db')->where('point_flow', $pointflow);
        if($pointtype) _G('db')->where('point_type', $pointtype);
        if($days_ago > 0) {
            $datetime = strtotime("-{$days_ago} days", _G('timestamp'));
            _G('db')->where_less('dateline', $datetime);
        }
        if($usernames) {
            $usernames = explode(',', $usernames);
            _G('db')->where('username', $usernames);
        }
        _G('db')->delete();
        
        $this->message = "共删除 " . _G('db')->affected_rows() . " 条积分更新记录。";
        $this->completed = true;
    }

    public function create_form() {
        $this->loader->helper('form');

        $elements = array();
        $elements[] = 
            array(
            'title' => '资金流向',
            'des' => '',
            'content' => '<div class="inline">'.form_radio('pointflow', array('all'=>'全部','in'=>'收入','out'=>'支出'),'all').'</div>',
        );
        $elements[] = 
            array(
            'title' => '删除积分类型',
            'des' => '',
            'content' => '<div class="inline">'.form_check('pointtype[]', $this->create_pointtype_checkbox(), array(), '','&nbsp;').'</div>',
        );
        $elements[] = 
            array(
            'title' => '删除多少天以前的记录',
            'des' => '全部删除，请输入 0',
            'content' => form_input('days_ago', '90', 'txtbox4'),
        );
        $elements[] = 
            array(
            'title' => '删除指定用户积分',
            'des' => '多个用户名，请用逗号“,”分隔',
            'content' => form_input('usernames', '', 'txtbox2'),
        );
        return $elements;
    }

    private function create_pointtype_checkbox() {
        $config = $this->loader->variable('config','member');
        $groups = $config['point_group'] ? unserialize($config['point_group']) : '';
        if(!$groups) return '';
        $radios = array();
        $radios['point'] = display('member:point','point/point');
        $radios['rmb'] = display('member:point','point/rmb');
        foreach($groups as $key => $val) {
            if(!$val['enabled']) continue;
            $radios[$key] = $val['name'];
        }
        return $radios;
    }

}
?>