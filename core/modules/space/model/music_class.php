<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_space_music extends ms_model {

    var $table = 'dbpre_space_music';
    var $key = 'id';
    var $model_flag = 'space';

    function __construct()
    {
        parent::__construct();
        $this->modcfg = $this->variable('config');
        $this->init_field();
    }
    
	function init_field() {
		$this->add_field('listorder,src,name,des,dateline,status');
		$this->add_field_fun('listorder,dateline,status', 'intval');
		$this->add_field_fun('src,name,des', '_T');
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
    
    function delete($ids) {
        $ids = parent::get_keyids($ids);
        $this->_delete(array('id'=>$ids));
    }
    function _delete($where, $up_total = TRUE, $up_point = FALSE) {
        $this->db->from($this->table);
        $this->db->where($where);
        $this->db->select('*');
        if(!$q=$this->db->get()) return;
        
        $ids = array();
        while($v=$q->fetch_array()) {
            //权限判断
            if(!$this->in_admin) redirect('global_op_access');
            $ids[] = $v['id'];
            if(strstr($v['src'],'upload')){
            	@unlink(MUDDER_ROOT.$v['src']);
			}
        }
        if($ids) {
            parent::delete($ids);
        }
    }
    
	function save($post, $id = NULL) {
        $edit = $id != null;
        if($edit) {
            if(!$detail = $this->read($id)) redirect('global_op_empty');
        } 
		if(!$post['status']) $post['status'] = 0;
		$post['dateline'] = time();
        //上传音乐
        $arr = array('mp3');
        if(!empty($_FILES['music']['name'])){
        	$info = pathinfo($_FILES['music']['name']);
        	if(!in_array(strtolower($info['extension']),$arr)){
				redirect('不支持该格式文件。');
			}
        	$this->loader->lib('upload_file', NULL, FALSE);
        	$UP = new ms_upload_file('music', strtolower($info['extension']));
        	$UP->upload('space');
        	$post['src'] = str_replace(DS, '/', $UP->path . '/' . $UP->filename);
            //$UP = new ms_upload_file('music', 'mp3');
            //$xmlfile = $UP->get_filename();
           /* $this->loader->helper('mxml');
            if(!$import_array = mxml::to_array($xmlfile)) redirect('item_model_importfile_invalid');
				$this->loader->lib('upload_file', NULL, FALSE);
	            $img = new ms_upload_image('picture', $this->global['cfg']['picture_ext']);
				$img->set_max_size($this->global['cfg']['picture_upload_size']);
				$img->useSizelimit = false; //不对上传图片进行最大尺寸限制
				$img->set_ext($this->global['cfg']['picture_ext']);
				$img->upload('link');
				$post['logo'] = str_replace(DS, '/', $img->path . '/' . $img->filename);*/
				//die(str_replace(DS, '/', $img->path . '/' . $img->filename));
				//return $post['picture'] = str_replace(DS, '/', $img->path . '/' . $img->filename);
		}else{
			redirect('请选择音乐文件。');
		}
        $id = parent::save($post,$id);
        return $id;
    }
    
    function check_post(& $post, $edit = false) {
        if(!$post['name']) redirect('请填写音乐名称。');
        if(!$post['src']) redirect('请选择音乐文件。');
    }
    
    function update($post) {
        if(!$post || !is_array($post)) redirect('global_op_unselect');
        foreach($post as $id => $val) {
         if(!$val['status']) $val['status'] = 0;
        	//上传音乐
        	$arr = array('mp3');
        	if(!empty($_FILES['m'.$id]['name'])){
	        	$info = pathinfo($_FILES['m'.$id]['name']);
	        	if(!in_array(strtolower($info['extension']),$arr)){
					redirect('不支持该格式文件。');
				}
	        	$this->loader->lib('upload_file', NULL, FALSE);
	        	$UP = new ms_upload_file('m'.$id, strtolower($info['extension']));
	        	$UP->upload('space');
	        	$val['src'] = str_replace(DS, '/', $UP->path . '/' . $UP->filename);
	        	
	        	
        		//删除旧文件
        		$music = $this->read($id); 
        		if(strstr($music['src'],'upload')){
            		@unlink(MUDDER_ROOT.$music['src']);
				}
			}
            parent::save($val,$id,FALSE,TRUE,TRUE);
        }
        $this->write_cache();
    }
}
?>