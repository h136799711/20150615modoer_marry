<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op');
$P = $_G['loader']->model('product:gcategory');
$_G['loader']->helper('form',MOD_FLAG);

switch ($op) {
    case 'load_subcategory':
        $pid = _input('pid',0,MF_INT_KEY);
        $pcate = $P->read($pid);
        if(!$pcate) redirect('product_category_empty');
        $catid = _input('catid',0,MF_INT_KEY);
        $cate = $P->read($catid);
        if(!$cate) redirect('product_category_empty');
        $q = $_G['db']->from($P->table)
            ->where('pid',$pid)->where_not_equal('catid',$catid)
            ->get();
        if(!$q) {
            echo 'EMPTY';
            output();
        }
        $result = array();
        while ($v = $q->fetch_array()) {
            if($_G['charset'] != 'utf-8') {
                $v['name'] = charset_convert($v['name'],$_G['charset'],'utf-8');
            }
            $result[$v['catid']] = $v['name'];
        }
        $q->free_result();
        echo json_encode($result);
        break;
    case 'att':
        $catid = _input('catid', 0, MF_INT_KEY);
        $category = $P->read($catid);
        if(!$category || $category['level'] < 2) redirect('对不起，您选择的分类无效或不是第三级分类');
        if($_POST['dosubmit']) {
            $P->save_att($catid, $_POST['attcat'], $_POST['attmulti']);
            redirect('global_op_succeed', get_forward(cpurl($module,$act,'list',array('catid'=>$category['pid']))));
        } else {
            $attmulti=array();
            $attcat = $category['attcat'] ? explode(',', $category['attcat']) : array();
            foreach ($attcat as $key=>$value) {
                if(preg_match("/^[0-9]+\|[0-9]{1}$/", $value)) {
                    list($_catid,$is_multi) = explode('|', $value);
                    $attcat[$key]=$_catid;
                    $is_multi && $attmulti[]=$_catid;
                } elseif(is_numeric($value)) {
                    $attcat[$key]=$value;
                }
            }
            $AT = $_G['loader']->model('item:att_cat');
            list(,$list) = $AT->read_all();
            $admin->tplname = cptpl('category_att', MOD_FLAG);
        }
        break;
    case 'batch_add':
        $newcat = $_POST['newcat'];
        empty($newcat['name']) && redirect('productcp_cat_add_subcat_empty_name');
        empty($newcat['pid']) && redirect('productcp_cat_add_subcat_empty_pcatid');
        if(isset($newcat['modelid'])&&!$newcat['modelid']) redirect('未选择关联模型ID');
        $P->add($newcat, true);
        redirect('global_op_succeed', cpurl($module,$act,'subcat',array('catid'=>$newcat['pid'])));
    case 'add':
        $pid = 0;
        $admin->tplname = cptpl('category_save', MOD_FLAG);
        break;
    case 'edit':
        $catid = _input('catid',0,MF_INT_KEY);
        $category = $P->read($catid);
        if(!$category) redirect('对不起，您选择的分类无效不存在。');
        if($category['pid']>0) {
            $pcate = $P->read($category['pid']);
        }
        $admin->tplname = cptpl('category_save', MOD_FLAG);
        break;
    case 'save':
        $catid = _post('catid',0,MF_INT_KEY);
        $t_cat = $_POST['t_cat'];
        if(empty($t_cat['name'])) {
            redirect('productcp_cat_empty_name');
        }
        if($_POST['do'] == 'edit') {
            if($catid <= 0) redirect('catid参数无效！');
        } else {
            $catid = null;
        }
        $catid = $P->save($t_cat, $catid);
        redirect('global_op_succeed', get_forward(cpurl($module,'category',''),1));
        break;
    case 'update':
        $P->update($_POST['category']);
        redirect('global_op_succeed', get_forward(cpurl($module,'category')));
        break;
    default:
        $catlist = $P->getlist(0);
        $admin->tplname = cptpl('category_list', MOD_FLAG);
        break;
}

/* end */