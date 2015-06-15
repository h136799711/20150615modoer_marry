<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2012 Moufersoft
* @website www.modoer.com
*/
_G('loader')->model('tool', FALSE);
class msm_tool_rebuild_picture_thumb extends msm_tool {

    protected $name = '重新生成主题图片缩略图';
    protected $descrption = '重新生成主题图片数据表内记录的缩略图数据，尺寸没有变化的不会重建。';
    protected $acttype = 'rebuild';

    private $thumb_width = 200;
    private $thumb_height = 150;

    public function __construct()
    {
    	parent::__construct();

    	$w = (int)S('item:pic_width');
    	$h = (int)S('item:pic_height');

    	if($w>0) $this->thumb_width = $w;
    	if($h>0) $this->thumb_height = $h;
    }

    public function run() 
    {
        $this->params['sids'] = _input('sids','',MF_TEXT);
        $this->params['catid'] = _input('catid','',MF_INT_KEY);

        $this->start();
    }

    public function create_form() 
    {
        $this->loader->helper('form');
        $this->loader->helper('form','item');
        $elements = array();
        $elements[] = 
            array(
            'title' => '重建指定主题的缩略图',
            'des' => '输入主题sid号，多个用逗号分隔；本出设置后将忽略其他筛选范围选项。',
            'content' => form_input('sids','','txtbox max'),
        );
        $elements[] = 
            array(
            'title' => '选择主题主分类',
            'des' => '只重建选择分类内的主题图片',
            'content' => '<select name="catid" class="max"><option value="0">全部分类</option>'.form_item_category_main().'</select>',
        );
        $elements[] = 
            array(
            'title' => '缩略图尺寸和模式',
            'des' => '这里读取的是主题模块配置里的尺寸，如需改动，请先到主题模块配置出设置。',
            'content' => '宽：'.$this->thumb_width.'px 高：'.$this->thumb_height.'px，缩略模式：'
            	.(S('picture_createthumb_mod')==1?'等比例缩小':'按尺寸裁剪'),
        );
        return $elements;
    }

    private function start() 
    {
        $offset =  50;
        $start = _get('start', 0, MF_INT_KEY);

        $where = array();
        if($this->params['sids']) {
        	$where['p.sid'] = explode(',',$this->params['sids']);
        } elseif($this->params['catid'] > 0) {
        	$where['s.pid'] = $this->params['catid'];
        }

        $count = _G('db')->join('dbpre_pictures','p.sid','dbpre_subject','s.sid','left join')->where($where)->count();
        $list = _G('db')->join('dbpre_pictures','p.sid','dbpre_subject','s.sid','left join')
        		->select('p.thumb,p.filename,p.width,p.height')
        		->where($where)
        		->order_by('p.picid')
        		->limit($start, $offset)
        		->get();

        if(!$list) {
            $this->completed = true;
        } else {
            $sids = array();
            while ($val = $list->fetch_array()) {
                $this->_thumb($val['thumb'],$val['filename']);
            }
            $list->free_result();
            $this->params['start'] = $start + $offset;
            $this->params['offset'] = $offset;
            $this->message = '正在处理图片...'.($start).'-'.($this->params['start'].'/'.$count);
        }
    }

    private function _thumb($old_thumb, $src_filename) 
    {
    	$old_thumb = MUDDER_ROOT.str_replace('/',DS,$old_thumb);
    	$src_filename = MUDDER_ROOT.str_replace('/',DS,$src_filename);

    	if(!is_file($src_filename)) return -1;
    	if(is_file($old_thumb)) {
    		list($width, $height) = getimagesize($old_thumb);
    		if($width == $this->thumb_width && $height == $this->thumb_height) return 0;
    	}
    	list($width, $height) = getimagesize($src_filename);
    	if($width < $this->thumb_width || $height < $this->thumb_height) {
    		//大图比缩略图还小，则只进行复制
    		return copy($src_filename, $old_thumb);
    	}
    	$img_obj = new ms_image();
    	$img_obj->set_thumb_level(S('picture_createthumb_level'));
    	$img_obj->set_thumb_mod(S('picture_createthumb_mod'));

    	return $img_obj->thumb($src_filename, $old_thumb, $this->thumb_width, $this->thumb_height);
    }

}