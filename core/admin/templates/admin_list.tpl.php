<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<div class="sub-menu">
    <div class="sub-menu-heading">后台用户</div>
    <a href="<?=cpurl($module,$act)?>" class="sub-menu-item selected">用户列表</a>
    <a href="<?=cpurl($module,$act,'add')?>" class="sub-menu-item">添加用户</a>
</div>
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="20">删?</td>
                <td width="*">用户名</td>
                <td width="150">E-mail</td>
                <td width="120">最后登录</td>
                <td width="100">登录IP</td>
                <td width="60">登录次数</td>
                <td width="60">有效性</td>
                <td width="80">编辑</td>
            </tr>
            <? if($total) { ?>
            <? while($value = $list->fetch_array()) { ?>
            <tr>
                <td><input type="checkbox" name="adminids[]" value="<?=$value['adminid']?>" <?if(!$admin->is_founder){?>disabled<?}?> /></td>
                <td><?=$value['adminname']?></td>
                <td><?=$value['email']?></td>
                <td><?=date('Y-m-d H:i',$value['logintime'])?></td>
                <td><?=$value['loginip']?></td>
                <td><?=$value['logincount']?></td>
                <td><?=$value['closed']?'<font color="red">否</font>':'<font color="green">是</font>'?></td>
                <td><a href="<?=cpurl($module,$act,'edit',array('adminid'=>$value['adminid']))?>">编辑</a></td>
            </tr>
            <? } ?>
            <? } ?>
        </table>
        <?if($multipage){?><div class="multipage"><?=$multipage?></div><?}?>
        <?if($admin->is_founder) {?>
        <center>
            <input type="hidden" name="op" value="<?=$op?>" />
            <input type="hidden" name="dosubmit" value="yes" />
            <input type="button" value="删除所选" class="btn" onclick="easy_submit('myform', 'delete', 'adminids[]');" />
        </center>
        <?}?>
    </div>
</form>
</div>