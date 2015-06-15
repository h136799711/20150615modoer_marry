<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
$P =& $_G['loader']->model(':party');
$_G['loader']->helper('form','party');

$op = _input('op');
switch($op) {
case 'add':
    $user->check_access('party_post', $P); //权限验证
    $_G['loader']->lib('editor',null,false);
    $editor = new ms_editor('des');
    $editor->upimage = TRUE;
    $edit_html = $editor->create_html();
    $tplname = 'party_save';
    break;
case 'edit':
    if(!$partyid = _get('id', null, 'intval')) redirect(lang('global_sql_keyid_invalid','partyid'));
    if(!$detail=$P->read($partyid)) redirect('party_empty');
    $party_owner = $user->isLogin && $detail['uid']>0 && $detail[uid] == $user->uid; //活动组织者
    if(!$party_owner) redirect('party_op_access');
    if($detail['sid'] > 0) {
        $S =& $_G['loader']->model('item:subject');
        $subject = $S->read($detail['sid']);
        //if(!$subject) redirect('item_empty');
    }
    $_G['loader']->lib('editor',null,false);
    $editor = new ms_editor('des');
    $editor->upimage = TRUE;
    $editor->content = $detail['des'];
    $edit_html = $editor->create_html();
    $tplname = 'party_save';
    break;
case 'save':
    if(_post('do')=='edit') {
        if(!$partyid = _post('partyid',null,'intval')) redirect(lang('global_sql_keyid_invalid','partyid'));
    } else {
        $partyid = null;
    }
    $post = $P->get_post($_POST);
    $partyid = $P->save($post, $partyid);
    redirect('global_op_succeed',url('party/member/ac/party/op/my'));
    break;
case 'delete':
    if(!$partyid = _input('id', null, 'intval')) redirect(lang('global_sql_keyid_invalid','partyid'));
    $P->delete($partyid, TRUE);
    redirect('global_op_succeed_delete',url('party/member/ac/party/op/my'));
    break;
case 'content':
    if(!$partyid = _input('id', null, 'intval')) redirect(lang('global_sql_keyid_invalid','partyid'));
    if(!$party=$P->read($partyid)) redirect('party_empty');
    $party_owner = $user->isLogin && $party['uid']>0 && $party[uid] == $user->uid; //活动组织者
    if(!$party_owner) redirect('party_op_access');
    $CON =& $_G['loader']->model('party:content');
    if(check_submit('dosubmit')) {
        $CON->save(_post('content'),$partyid);
        redirect('global_op_succeed',url('party/member/ac/party/op/my'));
    } else {
        $content = $CON->read($partyid);
        $_G['loader']->lib('editor',null,false);
        $editor = new ms_editor('content');
        $editor->upimage = TRUE;
        $editor->height = '400px';
        $editor->content = $content['content'];
        $edit_html = $editor->create_html();
        $tplname = 'party_content';
    }
    break;
case 'joins':
    $A =& $_G['loader']->model('party:apply');
    $offset = 20;
    $start = get_start($_GET['page'], $offset = 20);
    list($total, $list) = $A->myjoin($user->uid, $start, $offset);
    if($total) $multipage = multi($total,$offset,$_GET['page'],url('party/member/ac/party/op/joins'));
    $tplname = 'party_list';
    break;
default:
    $op = 'my';
    $where = array();
    $where['uid'] = $user->uid;
    $select = 'partyid,catid,city_id,subject,num,thumb,status,dateline,joinendtime,begintime,endtime,num';
    $offset = 20;
    $start = get_start($_GET['page'], $offset = 20);
    list($total, $list) = $P->find($select,$where,array('flag'=>'ASC','begintime'=>'ASC'), $start, $offset, true);
    if($total) $multipage = multi($total,$offset,$_GET['page'],url('party/member/ac/party'));
    $tplname = 'party_list';
    break;
}
?>