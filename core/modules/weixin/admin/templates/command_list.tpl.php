<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<div class="space">
    <div class="subtitle">操作提示</div>
    <table class="maintable" border="0" cellspacing="0" cellpadding="0">
        <tr><td>微信指令文件存在 core/modules/weixin/component/cmd 文件夹下，请勿删除基础指令文件（help.php 和 welcome.php）</td></tr>
    </table>
</div>
<form method="post" action="<?=cpurl($module,$act)?>&">
    <div class="space">
        <div class="subtitle">对话指令管理</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="200">文件名</td>
                <td width="150">指令标识</td>
                <td width="300">指令作用</td>
                <td width="*">操作</td>
            </tr>
            <?foreach($cmd_files as $cmd):?>
            <tr>
                <?php $name=str_replace('weixin_cmd_','',$cmd);?>
                <td><?=$name?>.php</td>
                <td><?=call_user_func("$cmd::get_mark")?></td>
                <td><?=call_user_func("$cmd::get_name")?></td>
                <td>
                    <?if(in_array($name, $installed_cmds)):?>
                        <?if(in_array($name, $base_cmds)):?>
                        <span class="font_3">基础指令，不能卸载</span>
                        <?else:?>
                        <a href="<?=cpurl($module,$act,'uninstall',array('class'=>$cmd))?>"><span class="font_2">卸载</span></a>
                        <?endif;?>
                    <?else:?>
                        <a href="<?=cpurl($module,$act,'install',array('class'=>$cmd))?>">安装</a>
                    <?endif;?>
                </td>
            </tr>
            <?endforeach;?>
            <?if(empty($cmd_files)):?>
            <tr>
                <td colspan="4">暂无信息</td>
            </tr>
            <?endif;?>
        </table>
    </div>
</form>
</div>