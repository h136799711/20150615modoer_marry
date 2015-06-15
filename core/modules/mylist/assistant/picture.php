<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
$op = $_POST['op'] ? $_POST['op'] : $_GET['op'];
$P =& $_G['loader']->model(':party');
$A =& $_G['loader']->model('party:apply');
$PP =& $_G['loader']->model('party:picture');

switch($op) {
case 'save':
    if(!$partyid = _post('partyid', null, 'intval')) redirect(lang('global_sql_keyid_invalid','partyid'));
    $post = $PP->get_post($_POST);
    $PP->save($post);
    redirect(RETURN_EVENT_ID, url("party/member/ac/picture/id/$partyid"));
    break;
case 'upload':
    if(!$partyid = _get('id', null, 'intval')) redirect(lang('global_sql_keyid_invalid','partyid'));
    if(!$party=$P->read($partyid)) redirect('party_empty');
    $tplname = 'picture_save';
    break;
case 'update':
    $PP->update($_POST['pictures']);
    redirect('global_op_succeed', get_forward(url("party/member/ac/picture/id/$partyid")));
    break;
case 'delete':
    $PP->delete($_POST['picids']);
    redirect('global_op_succeed_delete', get_forward(url("party/member/ac/picture/id/$partyid")));
default:
    $op = 'list';
    if(!$partyid = _get('id', null, 'intval')) redirect(lang('global_sql_keyid_invalid','partyid'));
    if(!$party=$P->read($partyid)) redirect('party_empty');
    $party_owner = $user->isLogin && $party['uid']>0 && $party[uid] == $user->uid; //活动组织者
    $access = $party_owner || $A->check_join_exists($partyid, $user->uid);
    if(!$access) redirect('party_op_access');
    $where = array();
    if($party_owner) {
        $where['partyid'] = $partyid;
    } else {
        $where['uid'] = $user->uid;
    }
    $offset = 10;
    $start = get_start($_GET['page'], $offset);
    list($total, $list) = $PP->find('picid,uid,title,username,dateline,status,pic,thumb', $where, array('dateline'=>'DESC'), $start, $offset, TRUE);
    if($total) {
        $multipage = multi($total, $offset, $_GET['page'], url("party/member/ac/picture/id/$partyid/page/_PAGE_"));
    }
    $tplname = 'picture_list';
    break;
}
?>