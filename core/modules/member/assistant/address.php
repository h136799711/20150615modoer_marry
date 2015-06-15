<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op',null,MF_TEXT);
$ADD =& $_G['loader']->model('member:address');

switch ($op) {
    case 'ajax_add':
        $tplname = 'address_ajax_add';
        break;
    case 'save':
        $id  = _post('id', null, MF_INT);
        $post = $ADD->get_post($_POST['address']);
        $result = $ADD->save($post, $id);
        if($result > 0) {
            if($_G['in_ajax']) {
                redirect('global_op_succeed');
            } else {
                set_cookie('lastmessage', lang('global_op_succeed'));
                location(url("member/index/ac/$ac"));               
            }
        } else {
            if($ADD->has_error()) {
                redirect($ADD->error());
            }
            if($ADD->in_ajax && $ADD->error) {
                redirect(array_shift($ADD->error));
            }
            $address =& $post;
            if($id > 0) $address['id'] = $id;
        }
        break;
    case 'edit':
        $id = _get('id', null, MF_INT_KEY);
        $address = $ADD->read($id);
        if(!$address||$address['uid']!=$user->uid) redirect('对不起，你编辑的信息不存在。');
        break;
    case 'delete':
        $id = _post('id', null, MF_INT_KEY);
        if(!$ADD->delete($id)) {
            redirect($ADD->error());
        }
        echo 'OK';
        output();
        break;
    default:
        $lastmessage = _cookie('lastmessage');
        del_cookie('lastmessage');
        break;
}