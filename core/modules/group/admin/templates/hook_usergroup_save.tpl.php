<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<tr>
    <td class="altbg1"><strong>允许本组会员创建小组:</strong>允许前台会员创建小组</td>
    <td><?=form_bool('access[allow_create]', isset($access['allow_create'])?$access['allow_create']:1)?></td>
</tr>
<tr>
    <td class="altbg1"><strong>允许本组会员删除自己管理的小组:</strong>允许前台会员删除自己管理的小组</td>
    <td><?=form_bool('access[allow_delete]', (bool)$access['allow_delete'])?></td>
</tr>