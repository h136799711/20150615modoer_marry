<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2012 Moufersoft
* @website www.modoer.com
*/
_G('loader')->model('tool', FALSE);
class msm_tool_repairalbum extends msm_tool {

    protected $name = '修复相册显示异常';
    protected $descrption = '修复相册在前台相册列表页显示异常的问题。';
    protected $acttype = 'repair';

    public function run() {
        $offset = 500; //_get('offset', 500, MF_INT_KEY);
        $start = _get('start', 0, MF_INT_KEY);
        $count = _G('db')->from('dbpre_album')->count();
        $list = _G('db')->join('dbpre_album','a.sid','dbpre_subject','s.sid','LEFT JOIN')->select('a.albumid, s.city_id')->order_by('a.albumid')->limit($start, $offset)->get();
        if(!$list) {
            $this->completed = true;
        } else {
            $sids = array();
            while ($val=$list->fetch_array()) {
                _G('db')->from('dbpre_album')->set('city_id',$val['city_id'])->where('albumid',$val['albumid'])->update();
            }
            $list->free_result();
            $this->params['start'] = $start + $offset;
            $this->params['offset'] = $offset;
            $this->message = '正在修复相册...'.($start).'-'.($this->params['start']);
        }
    }
}
?>