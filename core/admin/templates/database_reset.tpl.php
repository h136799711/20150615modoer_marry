<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<div class="sub-menu">
    <div class="sub-menu-heading">数据库管理</div>
    <a href="<?=cpurl($module,$act,'backup')?>" class="sub-menu-item">数据备份</a>
    <a href="<?=cpurl($module,$act,'reset')?>" class="sub-menu-item selected">数据恢复</a>
</div>
<div class="remind">
    <p>1. 导入的数据必须是用 Modoer 备份的文件；</p>
    <p>2. 导入的数据文件内容必须全部是当前 Modoer 所使用的数据表，如果文件内的表前缀和当前系统不同，将不允许导入；</p>
    <p>3. 只允许从卷号1的文件开始恢复数据。</p>
</div>
<form method="post" action="<?=cpurl($module,$act,'reset')?>">
    <div class="space">
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="20">[<a href="javascript:;" onclick="allchecked();">选</a>]</td>
                <td width="*">文件名</td>
                <td width="130">备份时间</td>
                <td width="130">修改时间</td>
                <td width="120">版本</td>
                <td width="50">卷号</td>
                <td width="100">文件大小</td>
                <td width="60">操作</td>
            </tr>
            <? if($list) { foreach($list as $dbfile) { ?>
            <tr>
                <td><input type="checkbox" name="backfiles[]" value="<?=$dbfile['filename']?>" /></td>
                <td><a href="<?='./data/backupdata/'.$dbfile['filename']?>" target="_blank" title="鼠标点右键“目标另存为”可下载到本地"><?=$dbfile['filename']?></a></td>
                <td><?=$dbfile['bktime']?></td>
                <td><?=$dbfile['mtime']?></td>
                <td><?=$dbfile['version']?></td>
                <td><?=$dbfile['volume']?></td>
                <td><?=$dbfile['filesize']?></td>
                <td>
                    <? if($dbfile['volume'] == 1) { ?>
                    <a href="<?=cpurl($module,$act,'reset',array('filename'=>$dbfile['filename'],'doreset'=>'confirm'))?>" onclick="return confirm('您确定要导入备份数据吗？')">导入</a>
                    <? } else { ?>
                    -
                    <? } ?>
                </td>
            </tr>
            <?}}?>
            <? if(!$list) {?><tr><td colspan="8">没有备份文件。</td></tr><? } ?>
        </table>
        <center><input type="submit" name="dosubmit" value=" 删除所选 " class="btn" /></center>
    </div>
</form>
</div>