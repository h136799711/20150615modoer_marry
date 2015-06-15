<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
$op = $_POST['op'] ? $_POST['op'] : $_GET['op'];
$P =& $_G['loader']->model(':party');
$A =& $_G['loader']->model('party:apply');

switch($op) {
case 'save':
    if(!$partyid = _post('partyid', null, 'intval')) redirect(lang('global_sql_keyid_invalid','partyid'));
    $post = $A->get_post($_POST);
    $A->save($post);
    redirect('global_op_succeed', url("party/detail/id/$partyid"));
    break;
case 'apply':
    $op = 'apply';
    if(!$partyid = _get('id', null, 'intval')) redirect(lang('global_sql_keyid_invalid','partyid'));
    if(!$detail=$P->read($partyid)) redirect('party_empty');
    //报名费用判断
    if($applyprice = $P->get_applyprice($detail)) {
        list($price, $pt) = $applyprice;
        if(!num_empty($price) && $user->$pt < $price) {
            redirect('对不起，您的账号当前没有足够的' . display('member:point',"point/$pt") . '支付报名费用。');
        }
    }

    if($A->check_join_exists($partyid, $user->uid)) redirect('party_apply_joined');
    $tplname = 'apply_save';
    break;
case 'delete':
    $A->delete(_post('applyids'));
    redirect('global_op_succeed', url("party/member/ac/party"));
    break;
default:
    $op = 'list';
    if(!$partyid = _get('id', null, 'intval')) redirect(lang('global_sql_keyid_invalid','partyid'));
    if(!$party=$P->read($partyid)) redirect('party_empty');
    $party_owner = $user->isLogin && $party['uid']>0 && $party[uid] == $user->uid; //活动组织者
    if(!$party_owner) redirect('party_op_access');
    $offset = 20;
    $start = get_start($_GET['page'], $offset);
    list($total, $list) = $A->find('*', array('partyid'=>$partyid), array('dateline'=>'DESC'), $start, $offset, TRUE);
    if($total) $multipage = multi($total, $offset, $_GET['page'], url('party/member/ac/party'));
    $tplname = 'apply_list';
    break;
}
?>