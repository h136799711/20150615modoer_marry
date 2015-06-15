<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<div class="sub-menu">
    <div class="sub-menu-heading">扩展积分管理</div>
    <a href="<?=cpurl($module,$act,'group')?>" class="sub-menu-item selected">积分字段设置</a>
    <a href="<?=cpurl($module,$act)?>" class="sub-menu-item">积分更新策略设置</a>
    <a href="<?=cpurl($module,$act,'log')?>" class="sub-menu-item">积分变更记录</a>
</div>
<div class="remind">
    <p>等级积分是系统内置关联会员等级的不是扩展字段；</p>
    <p>兑换比率为单项积分对应一个单位标准积分的值，例如 point1 的比率为 1.5(相当于 1.5 个单位标准积分)、point2 的比率为 3(相当于 3 个单位标准积分)、point3 的比率为 15(相当于 15 个单位标准积分)，则 point3 的 1 分相当于 point2 的 5 分或 point1 的 10 分。一旦设置兑换比率，则用户将可以在个人中心自行兑换各项设置了兑换比率的积分，如不希望实行积分自由兑换，请将其兑换比率设置为 0</p>
</div>
<?=form_begin(cpurl($module,$act,$op))?>
    <div class="space">
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
                <td width="40">启用</td>
                <td width="100">字段名称</td>
                <td width="150">积分名称</td>
                <td width="150">积分单位</td>
                <td width="50">兑入</td>
                <td width="50">兑出</td>
                <td width="*">兑换比例</td>
            </tr>
            <?foreach(array('point1','point2','point3','point4','point5','point6') as $key) {?>
            <tr>
                <td><input type="checkbox" name="point_group[<?=$key?>][enabled]" value="1"<?if($point_group[$key]['enabled'])echo' checked="checked"'?> /></td>
                <td><?=$key?></td>
                <td><input type="text" name="point_group[<?=$key?>][name]" value="<?=$point_group[$key]['name']?>" class="txtbox4" /></td>
                <td><input type="text" name="point_group[<?=$key?>][unit]" value="<?=$point_group[$key]['unit']?>" class="txtbox4" /></td>
                <td><input type="checkbox" name="point_group[<?=$key?>][in]" value="1"<?if($point_group[$key]['in'])echo' checked="checked"'?> /></td>
                <td><input type="checkbox" name="point_group[<?=$key?>][out]" value="1"<?if($point_group[$key]['out'])echo' checked="checked"'?> /></td>
                <td><input type="text" name="point_group[<?=$key?>][rate]" value="<?=$point_group[$key]['rate']?>" class="txtbox5" /></td>
            </tr>
            <?}?>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>