<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">购买属性管理(<?=$product['subject']?>)</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1" trmouse="Y">
            <tr>
                <td width="25"><input type="checkbox" id="allcheck" onclick="checkbox_checked('ids[]',this);" /></td>
                <td width="60">排序</td>
	            <td width="120">属性名</td>
	            <td width="*">属性值</td>
	            <td width="80">操作</td>
            </tr>
            <?if($list):?>
            <?while($val = $list->fetch_array()):?>
            <tr>
				<td><input type="checkbox" name="ids[]" value="<?=$val['id']?>"></td>
				<td><input type="text" class="txtbox5" size="5" name="listorder[<?=$val['id']?>]" value="<?=$val['listorder']?>" /></td>
				<td data-name="name"><?=$val['name']?></td>
				<td data-name="value"><?=$val['value']?></td>
				<td><a href="#" data-type="edit">编辑</a></td>
			</tr>
			<?endwhile?>
			<tr class="altbg1">
				<td colspan="2"><input type="button" class="btn2" value="全选" onclick="checkbox_checked('ids[]');"></td>
				<td colspan="3"><?=$multipage?></td>
			</tr>
			<?else:?>
			<tr>
				<td colspan="5">暂无信息</td>
			</tr>
			<?endif;?>
		</table>
	</div>
	<center>
		<input type="hidden" name="pid" value="<?=$pid?>">
		<button type="button" class="btn" id="add_btn">增加</button>&nbsp;
        <?if($list):?>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="delete" />
        <button type="button" class="btn" onclick="easy_submit('myform','listorder');">更新排序</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','delete','ids[]');">删除所选</button>&nbsp;
        <?endif;?>
        <button type="button" class="btn" onclick="jslocation('<?=cpurl($module,'product_list','edit',array('pid'=>$pid))?>')">返回</button>&nbsp;
	</center>
</form>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('a[data-type="edit"]').click(function(event) {
        var box = $(this).parents('tr');
        buyattr_edit(box.find('[name="ids[]"]').val(), box.find('[data-name="name"]').text(), box.find('[data-name="value"]').text());
    });

    $('#add_btn').click(function(event) {
        buyattr_add();
    });

    function buyattr_edit(id, name, value) {

        var input = function(caption, name, value) {
            var lbl = $('<label for="">'+caption+'：</label><br />');
            var txt = $('<input type="text" class="txtbox" style="width:95%;" />').attr('name',name).val(value);
            return $('<div></div>').append(lbl).append(txt);
        }

        var button = function(caption, name, value, type, click_callback) {
            if(!name) name = '';
            if(!type) type = 'button';
            var btn = $('<button type="'+type+'" class="btn"></button>').attr({'name':name}).text(caption);
            if(click_callback) btn.click(function(){click_callback()});
            return btn;
        }

        var hidden = function(name, value) {
            return $('<input type="hidden" />').attr('name', name).val(value);
        }

        var form = $('<form></form>').attr({action:"<?=cpurl('product','buyattr','edit')?>",method:'post'});
        form.append(input('属性名','name', name)).append(input('属性值','value', value));
        form.append(button('提交编辑', 'dosubmit', 'yes', 'submit'));
        form.append(button('关闭', '', '', 'button', dlgClose));
        form.append(hidden('pid', <?=$pid?>));
        form.append(hidden('id', id));
        form.bind('submit',function() {
            if(form.find('[name="name"]').val().trim()=='') {
                alert('未填写属性名称。');
            } else if(form.find('[name="value"]').val().trim()=='') {
                alert('未填写属性值。');
            } else {
                return true;
            }
            return false;
        });
        dlgOpen('编辑', form, 400);
    }

    function buyattr_add () {
        var form = $("<form action=\"<?=cpurl('product','buyattr','add')?>\" method=\"post\"></form>");
        form.append("<textarea name=\"buyattr\" rows=\"6\" cols=\"65\" id='post_buyattr'></textarea>");
        form.append("<div class=\"font_1\" style='margin:5px 0;width:100%;'>一行一条购买属性。格式：属性名=属性值1,属性值2,属性值3&nbsp;&nbsp;例如：颜色=红色,蓝色,白色,黑色</div>");
        form.append("<input type=\"submit\" class=\"btn\" name=\"dosubmit\" value=\"提交\">&nbsp;&nbsp;");
        form.append("<input type=\"button\" class=\"btn\" value=\"关闭\" onclick='dlgClose()'>");
        form.append("<input type=\"hidden\" name=\"pid\" value=\"<?=$pid?>\">");
        form.bind('submit',function(){
            if($('#post_buyattr').val()=='') {
                alert('对不起，您尚未填写购买属性。');
                return false;
            }
            return true;
        });
        dlgOpen('增加', form, 500);
    }

});
</script>