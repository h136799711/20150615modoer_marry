<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript">
$(function(){
    $(".maintable tr").each(function(i) { this.style.backgroundColor = ['#fff','#f2fbff'][i%2]} );
})
</script>
<div id="body">
    <div class="sub-menu" data-container="div.space>table.maintable tr">
        <div class="sub-menu-heading">系统工具箱</div>
        <a href="#" data-name="*" class="sub-menu-item selected">全部脚本</a>
        <a href="#" data-name="delete" class="sub-menu-item">清理类</a>
        <a href="#" data-name="repair" class="sub-menu-item">修复类</a>
        <a href="#" data-name="rebuild" class="sub-menu-item">重建类</a>
        <a href="#" data-name="other" class="sub-menu-item">其他类</a>
    </div>
    <div class="space">
        <table class="maintable" trmouse="Y">
            <?foreach($tools as $key => $tool):?>
            <tr data-name="<?=$tool->get_type()?>">
                <td width="*">
                    <div class="font_3"><?=$tool->get_name()?></div>
                    <div class="font_2"><?=$tool->get_descrption()?></div>
                </td>
                <td width="160">
                    <center>
                        <button type="button" class="btn" data-toolname="<?=$key?>" data-type="run_btn">执行</button>
                    </center>
                </td>
            </tr>
            <?endforeach;?>
        </table>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    var form_url='<?=cpurl($module,$act,'create_form',array('tool'=>'__toolname__'))?>';
    var run_url='<?=cpurl($module,$act,'run',array('tool'=>'__toolname__'))?>';

    var run_script = function(data, toolname) {
        if(data == 'RUN') {
            jslocation(run_url.replace('__toolname__',toolname));
            return;
        }
        var content = '<div class="dialog">';
        content += '<form method="get" action="<?=SELF?>" name="myform">';
        content += '<input type="hidden" name="module" value="<?=$module?>" />';
        content += '<input type="hidden" name="act" value="<?=$act?>" />';
        content += '<input type="hidden" name="op" value="run" />';
        content += '<input type="hidden" name="tool" value="'+toolname+'" />';
        content += data;
        content += '<div class="form-submit"><button type="submit" class="btn">开始执行</button>'
            +'<button type="button" class="btn unimportant" data-type="close"">关闭</button></div>';
        content += "</form></div>";
        dlgOpen('系统工具箱', content, 500);
    }

    $('button[data-type="run_btn"]').click(function() {
        var toolname = $(this).data('toolname');
        $.post(form_url.replace('__toolname__', toolname).url(), { tool:toolname, in_ajax:1 }, function(data) {
            if(data == null) {
                alert('信息读取失败，可能网络忙碌，请稍后尝试。');
                return;
            } else if(is_message(data)) {
                myAlert(data);
                return;
            } else {
                run_script(data, toolname);
            }
        });
    });

    $('ul.cptab').delegate('li >a', 'click', function(e) {
        e.preventDefault();
        $('ul.cptab > li').removeClass('selected');
        $(this).parent().addClass('selected');
        var type = $(this).data('type');
        $('table.maintable tr').each(function() {
            if(type=='all'||$(this).data('type')==type) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});
</script>