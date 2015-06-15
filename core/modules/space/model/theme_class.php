<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_space_theme extends ms_model {

    var $table = 'dbpre_space_theme';
    var $key = 'id';
    var $model_flag = 'space';

    function __construct()
    {
        parent::__construct();
        $this->modcfg = $this->variable('config');
        $this->init_field();
    }
    
	function init_field() {
		$this->add_field('listorder,thumb,pic1,pic2,pic3,css,name,des,content,dateline,status');
		$this->add_field_fun('listorder,dateline,status', 'intval');
		$this->add_field_fun('thumb,pic1,pic2,pic3,css,name,des,content', '_T');
	}

	function get_first(){
        $this->db->from($this->table);
        $this->db->where('status', 1);
		$this->db->order_by('listorder','ASC');
		return $this->db->get_one();
	}
	
	function find($select="*", $where=null, $orderby=null, $start=0, $offset=10, $total=FALSE) {
	    $this->db->from($this->table);
		$where && $this->db->where($where);
        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) {
                return $result;
            }
            $this->db->sql_roll_back('from,where');
        }
		$this->db->select($select?$select:'*');
        if($orderby) $this->db->order_by($orderby);
        if($start < 0) {
            if(!$result[0]) {
                $start = 0;
            } else {
                $start = (ceil($result[0]/$offset) - abs($start)) * $offset; //按 负数页数 换算读取位置
            }
        }
        $this->db->limit($start, $offset);
        $result[1] = $this->db->get();

        return $result;
    }
    
    function save($post, $id = NULL) {
        $edit = $id != null;
        if($edit) {
            if(!$detail = $this->read($id)) redirect('global_op_empty');
        }
         if(!$post['status']) $post['status'] = 0;
         $post['dateline'] = time();
         
        if(!$post['name']) redirect('请填写主题名称。');
        if(!$post['css']) redirect('请填写css文件位置。');
        else{
			if(!file_exists(MUDDER_ROOT.$post['css'])){
				 redirect('css文件位置错误。');
			}
		}
		
        //上传图片
        if(!empty($_FILES['picture']['name'])){
				$this->loader->lib('upload_image', NULL, FALSE);
	            $img = new ms_upload_image('picture', $this->global['cfg']['picture_ext']);
				$img->set_max_size($this->global['cfg']['picture_upload_size']);
				$img->useSizelimit = false; //不对上传图片进行最大尺寸限制
				$img->set_ext($this->global['cfg']['picture_ext']);
				$img->upload('space');
				$post['thumb'] = str_replace(DS, '/', $img->path . '/' . $img->filename);
				//die(str_replace(DS, '/', $img->path . '/' . $img->filename));
				//return $post['picture'] = str_replace(DS, '/', $img->path . '/' . $img->filename);
				//删除旧文件
				if(strstr($detail['thumb'],'upload')){
            		@unlink(MUDDER_ROOT.$detail['thumb']);
				}
			}elseif(!$detail['thumb'] && !$post['thumb'] ){
				redirect('请选择封面。');
		}
        $id = parent::save($post,$id);
        return $id;
    }

    function check_post(&$post) {
        if(!$post['name']) redirect('请填写主题名称。');
        if(!$post['css']) redirect('请填写css文件位置。');
        else{
			if(file_exists($post['css'])){
				 redirect('css文件位置错误。');
			}
		}
        if(!$post['thumb']) redirect('请选择封面。');
    }

        function delete($ids) {
        $ids = parent::get_keyids($ids);
        $this->_delete(array('id'=>$ids));
    }
	
	 function _delete($where) {
        $this->db->from($this->table);
        $this->db->where($where);
        $this->db->select('*');
        if(!$q=$this->db->get()) return;
        
        $ids = array();
        while($v=$q->fetch_array()) {
            //权限判断
            if(!$this->in_admin) redirect('global_op_access');
            $ids[] = $v['id'];
            if(strstr($v['thumb'],'upload')){
            	@unlink(MUDDER_ROOT.$v['thumb']);
			}
        }
        if($ids) {
            parent::delete($ids);
           /* $this->_delete_fields($artids);
            $this->_delete_comments($artids);
            $this->_delete_subjectlinks($artids);*/
        }
    }
	 // 排序
    function listorder($post) {
        if(!$post && !is_array($post)) redirect('global_op_unselect');
        foreach($post as $id => $val) {
            $listorder = (int) $val['listorder'];
            $this->db->from($this->table);
            $this->db->set('listorder',$listorder);
            $this->db->where('id',$id);
            $this->db->update();
        }
    }
}
?>