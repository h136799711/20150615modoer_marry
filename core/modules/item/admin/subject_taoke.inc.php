<?php
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');
$op = _input('op');
$_G['loader']->helper('form,taoke', 'item');
$C = & $_G['loader']->model('config');
switch ($op) {
    case 'store_add':
        if ($_GET['dosubmit']) {
            if ($taobaodata = taoke_item_shops()) list($total, $data) = $taobaodata;
            if ($total) {
                $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module, $act, $op, $_GET));
            }
        }
        $admin->tplname = cptpl('subject_taoke_store_add', MOD_FLAG);
        break;

    case 'store_detail':
        $user_id = _input('user_id', null, MF_INT_KEY);
        $detail = taoke_item_shop_detail($user_id);
        $admin->tplname = cptpl('subject_taoke_store_detail', MOD_FLAG);
        include MUDDER_CORE . $admin->tplname;
        output();
        break;

    case 'store_linkfield':
        if (!$catid = _post('catid', null)) redirect('item_taobaoke_add_category_empty');
        $catid = explode(',', $catid);
        $cid = 0;
        foreach ($catid as $id) if ($id > 0) $cid = $id;
        $IB = & $_G['loader']->model('item:itembase');
        $model = $IB->get_model($cid, true);
        $fields = $_G['loader']->variable('field_' . $model['modelid'], 'item');
        $use_fields = array();
        foreach ($fields as $k => $v) {
            if (in_array($v['fieldname'], array(
                'aid',
                'catid',
                'status',
                'templateid',
                'level',
                'finer',
                'card_msg',
                'mappoint'
            ))) continue;
            if (in_array($v['type'], array(
                'area',
                'level',
                'template',
                'att',
                'subject',
                'option',
                'taoke_product'
            ))) continue;
            $use_fields[$k] = $v;
        }
        $admin->tplname = cptpl('subject_taoke_store_linkfield', MOD_FLAG);
        break;

    case 'store_recode':
        $catid = _input('catid', null, MF_INT_KEY);
        $fields = _input('fields', null);
        $links = taoke_check_field($catid, $fields);
        $C->save(array(
            'taoke_catid' => $catid,
            'taoke_link_fields' => serialize($links)
        ) , MOD_FLAG);
        echo fetch_iframe('');
        break;

    case 'store_start':
        if (!$detail = taoke_item_save_store()) redirect(lang('item_taobaoke_addlost') . '[user_id:' . $_POST['user_id'] . ']');
        redirect('item_taoke_add_succeed');
        break;

    default:
        redirect('global_op_unkown');
    }
?>
