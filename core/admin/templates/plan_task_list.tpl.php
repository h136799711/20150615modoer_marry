<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
    <div class="sub-menu">
        <div class="sub-menu-heading">计划任务</div>
        <a href="<?=cpurl($module,$act)?>" class="sub-menu-item<?if(!$op):?> selected<?endif;?>">已安装</a>
        <a href="<?=cpurl($module,$act,'files')?>" class="sub-menu-item<?if($op=='files'):?> selected<?endif;?>">未安装</a>
    </div>
    <div class="space">
        <?if(!$op):?>
        <table class="maintable" trmouse="Y">
            <tr class="altbg1">
                <td width="350">文件名</td>
                <td width="150">定时设定</td>
                <td width="120">上次执行</td>
                <td width="120">下次执行</td>
                <td width="*">操作</td>
            </tr>
            <?if($tasks) foreach ($tasks as $task):?>
            <tr>
                <?if($task['task']):?>
                <td title="<?=$task['task']->descrption()?>">
                    <?=$task['task']->name()?><br />
                    <?=$task['filename']?>.php
                </td>
                <?else:?>
                <td>
                    <span class="font_2"><i>文件已被删除<br />
                    <?=$task['filename']?>.php</i></span>
                </td>
                <?endif;?>
                <td>
                    <?=$task_model->time_exp_caption($task['time_exp'])?><br />
                    <span class="font_2">已执行 <?=$task['run_count']?> 次</span>
                </td>
                <td><?=$task['lasttime']?date('Y-m-d H:i', $task['lasttime']):'尚未执行'?></td>
                <td><?=$task['nexttime']?date('Y-m-d H:i', $task['nexttime']):'不执行'?></td>
                <td>
                    <a href="<?=cpurl($module,$act,'edit',array('id'=>"{$task['id']}"))?>" data-name="edit_btn">编辑定时</a><br />
                    <a href="<?=cpurl($module,$act,'run',array('filename'=>"{$task['filename']}"))?>">现在执行</a>
                    <a href="<?=cpurl($module,$act,'uninstall',array('filename'=>"{$task['filename']}"))?>" data-name="uninstall_btn">卸载</a>
                </td>
            </tr>
            <?endforeach;?>
            <?if(!$tasks):?>
            <tr>
                <td colspan="5">暂无信息</td>
            </tr>
            <?endif;?>
        </table>
        <?else:?>
        <table class="maintable" trmouse="Y">
            <tr class="altbg1">
                <td width="200">文件名</td>
                <td width="*">任务名称</td>
                <td width="100">操作</td>
            </tr>
            <?if($files)
            foreach ($files as $filename=>$task):
                if($installed && isset($installed[$filename])) continue;
            ?>
            <tr>
                <td><?=$filename?>.php</td>
                <td><?=$task->name()?></td>
                <td><a href="<?=cpurl($module,$act,'install',array('filename'=>$filename))?>">安装</a></td>
            </tr>
            <?endforeach;?>
            <?if(!$files):?>
            <tr>
                <td colspan="5">暂无信息</td>
            </tr>
            <?endif;?>
        </table>
        <?endif;?>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {

    $('a[data-name="edit_btn"]').click(function(e) {
        e.preventDefault();
        var url = $(this).attr('href').url();
        $('<div>').load(url,{in_ajax:1},function() {
            dlgOpen('定时', $(this), 400);
        });
    });

    $('a[data-name="uninstall_btn"]').click(function() {
        return confirm('您确定要卸载吗？');
    });
        
});    
</script>