<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$M =& $_G['loader']->model(MOD_FLAG.':model');

if(!$_POST['dosubmit']) {

    $subtitle = lang('itemcp_model_add'); 
    $admin->tplname = cptpl('model_add', MOD_FLAG);

} else {

    $t_model = $_POST['t_model'];
    foreach($t_model as $key => $val) {
        if(empty($val) && $key!='usearea') {
            redirect('itemcp_model_post_unmet_from');
        }
    }
    $t_model['tablename'] = 'subject_'.$t_model['tablename'];
    $t_model['disable'] = 0; // use
    $modelid = $M->add($t_model);

    redirect('itemcp_model_post_succeed', cpurl($module, 'field_list', '', array('modelid'=>$modelid)));
}
?>