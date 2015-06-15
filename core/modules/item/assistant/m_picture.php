<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op');
$P =& $_G['loader']->model(MOD_FLAG.':picture');
$mymenu = 'menu';
switch($op) {
    case 'delete':
        $P->delete($_POST['picids']);
        $url = $_G['in_ajax'] ? '' : get_forward(url('item/member/ac/'.$ac));
        redirect('global_op_succeed_delete', $url);
        break;
    case 'update':
        $P->update($_POST['picture']);
        redirect('global_op_succeed', get_forward(url('item/member/ac/'.$ac)));
        break;
    default:
        // $pid = isset($_GET['pid']) ? (int)$_GET['pid'] : (int)$MOD['pid'];
        // (!$pid || !$P->get_category($pid)) and redirect('item_empty_default_pid');

        // $category = $P->variable('category');
        // $modelid = $category[$pid]['modelid'];
        // $model = $P->variable('model_' . $modelid);
  //       $fields = $P->variable('field_' . $modelid);

  //       $varname = array('catid' => 'cattitle', 'name' => 'title', 'subname' => 'subtitle');
  //       foreach ($fields as $value) {
  //           if($var = $varname[$value['fieldname']]) {
  //               $$var = $value['title'];  
  //           }
  //       }

        //$where['pid'] = (int) $pid;

        //指定picids处理
        if($picids = trim(_get('picids','',MF_TEXT),'_')) {
            $ids = _int_keyid(explode('_', $picids));
            $where['picid'] = $ids;
        }
        //上传用户
        $where['p.uid'] = $user->uid;

        $select = 'p.*,s.city_id,s.name,s.subname,s.pid,s.catid';

        $start = get_start($_GET['page'], $offset = 10);
        list($total, $list) = $P->find($select, $where, array('addtime'=>'DESC'), $start, $offset, true, true);
        $multipage = multi($total, $offset, $_GET['page'], url("item/member/ac/$ac/pid/$pid/picids/$picids/page/_PAGE_"));

        $path_title = lang('item_title_m_picture');
        $tplname = 'pic_list';
}
?>