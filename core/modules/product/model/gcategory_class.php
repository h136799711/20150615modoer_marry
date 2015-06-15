<?php
/**
* @author moufer
* @copyright (c)2009-2011 mouferstudio
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_product_gcategory extends ms_model {

    var $model_flag = 'product';
    var $table = 'dbpre_product_gcategory';
    var $key = 'catid';

    function __construct() {
        parent::__construct();
		$this->init_field();
    }

	function init_field() {
		$this->add_field('name,listorder,modelid,enabled,title,keywords,description');
		$this->add_field_fun('name,title,keywords,description', '_T');
		$this->add_field_fun('listorder,modelid,enabled', 'intval');
	}

    function read($catid) {
        $cat = parent::read($catid);
        return $cat;
    }

    function getlist($pid=0) {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('pid', $pid);
        $this->db->order_by('listorder');
        $row = $this->db->get();

        $result = array();
        if($row) {
            while($value = $row->fetch_array()) {
                $result[$value['catid']] = $value;
            }
        }
        return $result;
    }

    function add($post, $batch = false, $upcache = TRUE) {
        if($batch) {
            // batch
            $names = explode("|", $post['name']);
            if(count($names) > 1) {
                foreach($names as $val) {
                    if(!$val) continue;
                    $post['name'] = $val;
                    $this->add($post, false, false);
                }
                if($upcache) $this->write_cache(true);
                return;
            }
        }

        // $createfield = false;
        $isroot = !isset($post['pid']) || $post['pid'] == 0;
        if($post['pid']) {
            if(!$cate = $this->read($post['pid'])) redirect('productcp_cat_empty');
            $post['level'] = $cate['level'] + 1;
            if($cate['modelid'] > 0) {
                $post['modelid'] = $cate['modelid'];
            }
            if($post['level']>2&&!$post['modelid']) {
                redirect('未选择分类关联模型ID。');
            }
            // if($post['level']=='3') {
            //     $createfield = true;
            // }
            //$this->get_parent_id($post['pid'],1);
        } else {
            $post['level'] = 1;
        }
        $catid = parent::save($post);
        // if($createfield) {
        //     //default fields
        //     $this->create_fields($catid);
        // }
        if($post['pid'] > 0) $this->update_subcat($post['pid']);
        if($upcache) $this->write_cache(true);
        return $catid;
    }

    // 
    function create_fields($catid) {
        $F = $this->loader->model('product:field');
        $F->class_flag = $this->model_flag;
        $ctratefile = MUDDER_MODULE . 'product' . DS . 'model' . DS . 'fields' . DS . 'createfield.php';
        if(is_file($ctratefile)) {
            $create_field = read_cache(MUDDER_MODULE . 'product' . DS . 'model' . DS . 'fields' . DS . 'createfield.php');
            if(empty($create_field)) {
                redirect(lang('productcp_model_not_fount_createfield', 'model/fields/createfield.php'));
            }
            foreach($create_field as $key => $val) {
                $val['idtype'] = 'product';
                $val['id'] = $catid;
                $val['tablename'] = 'product_data';
                $F->add($val, false, false); // 
            }
        }
        $F->write_cache($catid);
    }

    function save($post, $catid=null, $up_cache=true) {
        $remove_pid = false;
        $isedit = $catid > 0;

        if(empty($post['name']) && $_GET['op']!=itembind) redirect('productcp_cat_empty_name');
        //if(empty($catid)) redirect(sprintf(lang('global_sql_keyid_invalid'),'catid'));

		if($catid > 0) {
			$cat = $this->read($catid);
			if(!$cat) redirect('productcp_cat_not_exist');
			if(isset($post['attcat']) && is_array($post['attcat'])) {
                $post['attcat'] = ',' . implode(',', $post['attcat']) . ',';
            } else{
                unset($post['attcat']);
            }
            
            //parent category
            if($post['cate_level2'] > 0) {
                $pid = (int)$post['cate_level2'];
            } elseif($post['cate_level1'] > 0) {
                $pid = (int)$post['cate_level1'];
            } else {
                $pid = $cat['pid'];
            }
            if($cat['pid'] != $pid) {
                $remove_pid = true;
                $post['pid'] = $pid;
            }
            unset($post['cate_level1'],$post['cate_level2']);
            //$cat['pid'] ? $cat['pid'] : 
		} else {
            $pid = $post['pid'] ? $post['pid'] : 0;
        }

        if($pid) {
            $parent = $this->read($pid);
            if(!$parent) redirect('对不起，关联的上级分类不存在，请返回。');
            $post['modelid'] = (int) $post['modelid'];
            $post['level'] = $parent['level'] + 1;
        } else {
            $post['level'] = 1;
            $post['modelid'] = 0;
        }

        parent::save($post, $catid, $up_cache);
		if($pid > 0) {
            //更新子分类聚合
			$this->update_subcat($pid);
            if($remove_pid) $this->update_subcat($cat['pid']);
		}

        if(!$up_cache) $this->write_cache(true);
        //移动上级分类后，更新产品表里的主分类
        if($remove_pid) {
            $catids[] = $catid;
            if($post['level']=='2') {
                //获得子分类列表
                $q = $this->db->from($this->table)->where('pid',$catid)->get();
                if($q) while ($v = $q->fetch_array()) {
                    $catids[] = $v['catid'];
                }
            }
            $this->loader->model(':product')->update_pgcatid($catids, $pid);
        }
    }

    function save_att($catid, $atts,$attmulti) {
        if($atts && is_array($atts)) {
            if($attmulti) foreach ($atts as $key => $value) {
                if(in_array($value, $attmulti)) $atts[$key]="$value|1";
            }
            $attcat = implode(',', $atts);
        } else {
            $attcat = '';
        }
        $this->db->from($this->table);
        $this->db->where('catid',$catid);
        $this->db->set('attcat', $attcat);
        $this->db->update();
        $this->write_cache();
    }

    function delete($catid) {
        $cat = $this->read($catid);
        if(empty($cat)) return;
        //先删除旗下子分类
        $this->db->where('pid', $catid);
        $this->db->from($this->table);
        if($this->db->count() > 0) {
            redirect('productcp_cat_delete_product_exist');
        }
        //第三层特殊处理
        if($cat['level']=='3') {
            //判断是否存在主题
            $rand_p = $this->db->from('dbpre_product')->where('gcatid', $catid)->get_one();
            if($rand_p) redirect('当前分类存在产品数据，暂时无法删除，请先删除本分类下的所有产品。');
            //删除掉自定义字段
            //$this->delete_field($catid);
        }
        parent::delete($catid);
		if($cat['pid'] > 0) {
			$this->update_subcat($cat['pid']);
		}
        $this->write_cache(true);
        return $cat['pid'];
    }

    //删除分类的同时，删除掉分类相关联的自定义字段
    // function delete_fields($catid) {
    //     $F = $this->loader->model('product:field');
    //     $F->class_flag = $this->model_flag;
    //     $F->horizontal = false; //不是纵表
    //     $F->delete_id($catid);
    // }

	//更新父类列表内的可用和不可用的子分类列表
	function update_subcat($catid) {
		$array = $this->_get_subcats($catid);
		$subcats = $nonuse_subcats = '';
		if($array) foreach($array as $val) {
			if($val['enabled']) $subcats .= $val['catid'] . ',';
			if(!$val['enabled']) $nonuse_subcats .= $val['catid'] . ',';
		}
		if($subcats) $subcats = substr($subcats,0,-1);
		if($nonuse_subcats) $nonuse_subcats = substr($nonuse_subcats,0,-1);

		$this->db->from($this->table);
		$this->db->set('subcats', $subcats);
		$this->db->set('nonuse_subcats', $nonuse_subcats);
		$this->db->where('catid', $catid);
		$this->db->update();
	}

	//更新子分类列表
    function update_subcats($post,$pid=null) {
        if(empty($post)) return;
        foreach($post as $catid => $val) {
            foreach ($val as $k => $v) {
                $val[$k] = _T($v);
            }
            $val['name'] = _T($val['name']);
            $val['listorder'] = (int) $val['listorder'];
            $val['enabled'] = (int) $val['enabled'];
            $this->db->from($this->table);
            $this->db->set($val);
            $this->db->where('catid',$catid);
            $this->db->update();
        }
		if($pid) $this->update_subcat($pid);
        $this->write_cache();
    }

    function get_parent_id($catid, $get_level = 1) {
        if(!$rel = $this->variable('gcategory_rel')) return false;
        if(!$rel[$catid]) return false;
        list($pid, $level) = explode(':', $rel[$catid]);
        if($level == $get_level) return $catid;
        if($level-1 == $get_level) return $pid;
        if($get_level < $level-1) {
            list($pid, $level) = explode(':', $rel[$pid]);
        }
        if($level-1 == $get_level) return $pid;
    }

    function update($post) {
        if(!is_array($post)||empty($post)) return;
        foreach($post as $catid => $val) {
            $val['enabled'] = (int) $val['enabled'];
            $this->db->from($this->table);
            $this->db->set($val);
            $this->db->where('catid', $catid);
            $this->db->update();
        }
        $this->write_cache(true);
    }

    function write_cache($update_jscache_flag = true) {

		$js_data = "";
		$js_levle = array(1=>'',2=>'',3=>'');

        $this->db->from($this->table);
        $this->db->order_by(array('level'=>'ASC','listorder'=>'ASC'));

        if($query = $this->db->get()) {

            $i = 0;
            $result = $file = $level2 = $level3 = $rel = false;

            while($val = $query->fetch_array()) {
				$js_data .= $val['catid'] . ':"' . $val['name'] . '",';
                $rel[$val['catid']] = $val['pid'] . ':' . $val['level'];
                if($val['level']=='1') {
					$js_levle[1][]= $val['catid'];
                    $file[$val['catid']][$val['catid']] = $val;
                    $result[$val['catid']] = $val;
                } elseif($val['level']=='2') {
					$js_levle[2][$val['pid']][] = $val['catid'];
                    $file[$val['pid']][$val['catid']] = $val;
                } elseif($val['level']=='3') {
					$js_levle[3][$val['pid']][] = $val['catid'];
                    if($file) foreach($file as $pkey => $pval) {
                        if(isset($pval[$val['pid']])) {
                            $file[$pkey][$val['catid']] = $val;
                        }
                    }
                }
            }

            $query->free_result();

			$js_data = 'data:{' . substr($js_data, 0, -1) . '},level:[';
			if($js_levle[1]) {
				$js_data .= '{0:['.implode(',',$js_levle[1]).']},';
			} else {
				$js_data .= '{0:[0]},';
			}
			if($js_levle[2]) {
				$js_data .= '{';
				foreach($js_levle[2] as $k => $v) $js_data .= $k.':[' . implode(',', $v) . '],';
				$js_data = substr($js_data, 0, -1) . '},';
			} else {
				$js_data .= "{0:[0]},";
			}
			if($js_levle[3]) {
				$js_data .= '{';
				foreach($js_levle[3] as $k => $v) $js_data .= $k.':[' . implode(',', $v) . '],';
				$js_data = substr($js_data, 0, -1) . '}';
			} else {
				$js_data .= '{0:[0]}';
			}

			$js_data = 'var _product_cate = {' . $js_data . ']};';

            $cache = ms_cache::factory();
            $cache->write('product_gcategory', $result);
            $cache->write('product_gcategory_rel', $rel);
            if($file) foreach($file as $key => $val) {
                $cache->write('product_gcategory_' . $key, $val);
            }

            // write_cache('gcategory', arrayeval($result), $this->model_flag);
            // write_cache('gcategory_rel', arrayeval($rel), $this->model_flag);
            // foreach($file as $key => $val) {
            //     write_cache('gcategory_' . $key, arrayeval($val), $this->model_flag);
            // }
            
            write_cache('product_gcategory', $js_data, $this->model_flag, 'js');
            //更新js后，因为浏览器会缓存js文件，所以需要给js做一个更新标识，放在模块配置里面
            if($update_jscache_flag) {
                $C =& $this->loader->model('config');
                $C->save(array('jscache_flag'=>rand(1,1000)), 'product');
            }
        }
    }

    function get_and_write_cache($key) {
        $result = array();
        $this->db->from($this->table);
        $this->db->order_by(array('level'=>'ASC','listorder'=>'ASC'));
        if($query = $this->db->get()) {
            $gcate = $file = $level2 = $level3 = $rel = false;
            while($val = $query->fetch_array()) {
                $rel[$val['catid']] = $val['pid'] . ':' . $val['level'];
                if($val['level']=='1') {
                    $file[$val['catid']][$val['catid']] = $val;
                    $gcate[$val['catid']] = $val;
                } elseif($val['level']=='2') {
                    $file[$val['pid']][$val['catid']] = $val;
                } elseif($val['level']=='3') {
                    if($file) foreach($file as $pkey => $pval) {
                        if(isset($pval[$val['pid']])) {
                            $file[$pkey][$val['catid']] = $val;
                        }
                    }
                }
            }
            $query->free_result();
        }
        //缓存
        $cache = ms_cache::factory();
        $cache->write('product_gcategory', $gcate);
        if(!$key) $result = $gcate;
        $cache->write('product_gcategory_rel', $rel);
        if($key == 'rel') $result = $rel;
        foreach($file as $k => $v) {
            $cache->write('product_gcategory_' . $k, $v);
            if($key == $k) $result = $v;
        }
        //返回值
        return $result;
    }

	function _get_subcats($pid) {
		if(!$pid) return;
		$this->db->from($this->table);
		$this->db->where('pid',$pid);
		$this->db->order_by('listorder');
		if(!$q=$this->db->get()) return;
		$result = array();
		while($v=$q->fetch_array()) {
			$result[] = $v;
		}
		$q->free_result();
		return $result;
	}
}
?>