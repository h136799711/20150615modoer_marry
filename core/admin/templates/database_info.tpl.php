<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
    <div class="sub-menu" data-container="div.space>table">
        <div class="sub-menu-heading">数据库管理</div>
        <a href="<?=cpurl($module,$act,'maintenance')?>" data-type="url" class="sub-menu-item">数据表维护</a>
        <a href="#" data-name="modoer" class="sub-menu-item selected">Modoer数据表</a>
        <a href="#" data-name="other" class="sub-menu-item">其他数据表</a>
    </div>
    <div class="space">
        <table class="maintable" data-name="modoer" trmouse="Y">
            <tr class="altbg1">
                <td width="20%">数据表名称</td>
                <td width="20%">创建时间</td>
                <td width="20%">最后更新时间</td>
                <td width="10%">记录数</td>
                <td width="10%">数据</td>
                <td width="10%">索引</td>
                <td width="10%">碎片</td>
            </tr>
            <? if($mudder_table_info) { foreach($mudder_table_info as $info) { ?>
            <tr>
                <td><?=$info['Name']?></td>
                <td><?=$info['Create_time']?></td>
                <td><?=$info['Check_time']?></td>
                <td><?=$info['Rows']?></td>
                <td><?=size_unit($info['Data_length'])?></td>
                <td><?=size_unit($info['Index_length'])?></td>
                <td><?=size_unit($info['Data_free'])?></td>
            </tr>
            <? } ?>
            <tr class="altbg2">
                <td><strong>合计:</strong>&nbsp;<?=count($mudder_table_info)?>个表</td>
                <td></td>
                <td></td>
                <td><?=$mudder_rows_total?></td>
                <td><?=size_unit($mudder_Data_length)?></td>
                <td><?=size_unit($mudder_Index_length)?></td>
                <td><?=size_unit($mudder_Data_free)?></td>
            </tr>
            <? } ?>
        </table>
        <table class="maintable none" data-name="other" trmouse="Y">
            <tr class="altbg1">
                <td width="20%">数据表名称</td>
                <td width="20%">创建时间</td>
                <td width="20%">最后更新时间</td>
                <td width="10%">记录数</td>
                <td width="10%">数据</td>
                <td width="10%">索引</td>
                <td width="10%">碎片</td>
            </tr>
            <? if($other_table_info) { foreach($other_table_info as $info) { ?>
            <tr>
                <td><?=$info['Name']?></td>
                <td><?=$info['Create_time']?></td>
                <td><?=$info['Check_time']?></td>
                <td><?=$info['Rows']?></td>
                <td><?=size_unit($info['Data_length'])?></td>
                <td><?=size_unit($info['Index_length'])?></td>
                <td><?=size_unit($info['Data_free'])?></td>
            </tr>
            <? } ?>
            <tr class="altbg2">
                <td><strong>合计:</strong>&nbsp;<?=count($other_table_info)?>个表</td>
                <td></td>
                <td></td>
                <td><?=$other_rows_total?></td>
                <td><?=size_unit($other_Data_length)?></td>
                <td><?=size_unit($other_Index_length)?></td>
                <td><?=size_unit($other_Data_free)?></td>
            </tr>
            <? } else { ?>
            <tr >
                <td colspan="7"><div class="panel-body center">暂无信息</div></td>
            </tr>
            <? } ?>
        </table>
    </div>
</div>