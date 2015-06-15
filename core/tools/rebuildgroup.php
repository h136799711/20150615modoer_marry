<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2012 Moufersoft
* @website www.modoer.com
*/
_G('loader')->model('tool', FALSE);
class msm_tool_rebuildgroup extends msm_tool {

    protected $name = '重建小组统计数据';
    protected $descrption = '包括重新统计小组会员数量，话题数量，回帖数量。';
    protected $acttype = 'rebuild';
    private  $type = '';

    public function run() {
        $gids = _input('gids','',MF_TEXT);
        $type = _input('type','',MF_TEXT);
        if(is_array($type)) $type = implode(',', $type);
        $this->type = $type;
        if($gids) {
            $this->rebuild_gids($gids);
        } else {
            $this->rebuild_all();
        }
    }

    public function create_form() {
        $this->loader->helper('form');
        $elements = array();
        $elements[] = 
            array(
            'title' => '指定小组ID号',
            'des' => '<p>多个小组ID请使用逗号“,”进行分隔；留空表示重建全部小组数据。</p>',
            'content' => form_input('gids', '', 'txtbox'),
        );
        $elements[] = 
            array(
            'title' => '重建数据类型',
            'des' => '',
            'content' => '<div class="inline">'
                .form_check('type[]', array('members'=>'会员数量','topics'=>'话题数量','replies'=>'回帖数量'), 
                    array('members','topics'), '','&nbsp;')
                .'</div>',
        );
        return $elements;
    }

    private function rebuild_gids($gids) {
        $gids = explode(',', $gids);
        if(!$gids) {
            redirect('对不起，您指定的账号不存在。');
        }
        foreach ($gids as $gid) {
            $gid = trim($gid);
            if(is_numeric($gid) && $gid > 0) {
                $this->_rebuild($gid);
            }
        }
        $this->completed = true;
    }

    private function rebuild_all() {
        $offset =  500;
        $start = _get('start', 0, MF_INT_KEY);
        $count = _G('db')->from('dbpre_group')->count();
        $list = _G('db')->from('dbpre_group')->select('gid')->order_by('gid')->limit($start, $offset)->get();
        if(!$list) {
            $this->completed = true;
        } else {
            $sids = array();
            while ($val=$list->fetch_array()) {
                $this->_rebuild($val['gid']);
            }
            $list->free_result();
            $this->params['start'] = $start + $offset;
            $this->params['offset'] = $offset;
            $this->params['type'] = $this->type;
            $this->message = '正在重建小组表统计数据...'.($start).'-'.($this->params['start']);
        }
    }

    private function _rebuild($gid) {

        $type = explode(',', $this->type);

        $set = array();
        if(!$type || in_array('members',$type)) $set['members'] = (int)$this->_get_members($gid);
        if(!$type || in_array('topics',$type)) $set['topics'] = (int)$this->_get_topics($gid);
        if(!$type || in_array('replies',$type)) $set['replies'] = (int)$this->_get_replies($gid);

        $db =& _G('db');
        $db->from('dbpre_group');
        $db->where('gid', $gid);
        $db->set($set);
        $db->update();
    }

    private function _get_members($gid) {
        $db =& _G('db');
        $db->from('dbpre_group_member');
        $db->where('gid',$gid);
        $db->where_not_equal('status',0);
        return $db->count();
    }

    private function _get_topics($gid) {
        $db =& _G('db');
        $db->from('dbpre_group_topic');
        $db->where('gid', $gid);
        $db->where('status', 1);
        return $db->count();
    }

    private function _get_replies($gid) {
        $db =& _G('db');
        $db->join('dbpre_group_topic','gt.tpid', 'dbpre_group_reply','gr.tpid');
        $db->where('gt.gid',$gid);
        $db->where('gr.status',1);
        return $db->count();
    }

}
?>