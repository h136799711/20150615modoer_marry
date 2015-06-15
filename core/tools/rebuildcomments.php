<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2012 Moufersoft
* @website www.modoer.com
*/
_G('loader')->model('tool', FALSE);
class msm_tool_rebuildcomments extends msm_tool {

    protected $name = '重建评论对象的评论数';
    protected $descrption = '用于重建评论对象内评论数量，评论对象包含文章，优惠券，榜单等；注：如您的使用“友言”、“多说”等第三方评论框，请不要执行脚本。';
    protected $acttype = 'rebuild';

    private  $idtype = '';
    private $cm = null;

    public function __construct() {
        parent::__construct();
        $this->cm = $this->loader->model(':comment');
    }

    public function run() {
        $cfg = $this->loader->variable('config','comment');
        if($cfg['comment_interface']!='local') redirect('您目前使用的评论功能不是内置评论功能，不能执行当前脚本，本脚本只能重建内置评论。');
        $ids = _input('ids','',MF_TEXT);
        $idtype = _input('idtype','',MF_TEXT);
        if(is_array($idtype)) $idtype = implode(',', $idtype);
        $this->idtype = $idtype;
        if($ids) {
            $this->rebuild_ids($ids);
        } else {
            $this->rebuild_all();
        }
    }

    public function create_form() {
        $idtypes = array();
        foreach($this->cm->idtypes as $keyname => $idtype) {
            $idtypes[$keyname] = $idtype['name'];
        }
        $this->loader->helper('form');
        $elements = array();
        $elements[] = 
            array(
            'title' => '重建数据类型',
            'des' => '',
            'content' => '<div class="inline">'.form_radio('idtype[]', $idtypes, $keyname).'</div>',
        );
        $elements[] = 
            array(
            'title' => '指定评论对象的ID号',
            'des' => '<p>多个评论对象的ID请使用逗号“,”进行分隔，留空表示重建指定评论对象的全部数据。</p>',
            'content' => form_input('ids', '', 'txtbox'),
        );
        return $elements;
    }

    private function rebuild_ids($ids) {
        if($ids) $ids = explode(',', $ids);
        if(!$ids) {
            redirect('对不起，您填写准备重建数据的评论对象ID。');
        }
        foreach ($ids as $id) {
            $id = (int) $id;
            if(is_numeric($id) && $id > 0) {
                $this->_rebuild($id);
            }
        }
        $this->completed = true;
    }


    private function rebuild_all() {
        $offset =  300;
        $table_name = $this->cm->idtypes[$this->idtype]['table_name'];
        $key_name = $this->cm->idtypes[$this->idtype]['key_name'];
        if(!$table_name || !$key_name) {
            redirect('对不起，找不到你指定的评论对象表。');
        }

        $start = _get('start', 0, MF_INT_KEY);
        $count = _G('db')->from($table_name)->count();
        $list = _G('db')->from($table_name)->select($key_name)->order_by($key_name)->limit($start, $offset)->get();
        if(!$list) {
            $this->completed = true;
        } else {
            $sids = array();
            while ($val=$list->fetch_array()) {
                $this->_rebuild($val[$key_name]);
            }
            $list->free_result();
            $this->params['start'] = $start + $offset;
            $this->params['offset'] = $offset;
            $this->params['idtype'] = $this->idtype;
            $this->message = '正在重建' . $this->cm->idtypes[$this->idtype]['name'] .
                '表评论数据统计...' . ($start) . '-' . ($this->params['start']);
        }
    }

    private function _rebuild($id) {
        return $this->cm->rebuild_comments($this->idtype, $id);
    }

}
?>