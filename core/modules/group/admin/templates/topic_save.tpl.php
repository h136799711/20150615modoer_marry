<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'save')?>">
    <div class="space">
        <div class="subtitle">话题编辑</div>
        <?if($edit_links):?>
        <ul class="cptab">
            <?foreach($edit_links as $val):?>
            <li<?if($val['flag']=='discussion:topic')echo' class="selected"';?>><a href="<?=$val['url']?>" onfocus="this.blur()"><?=$val['title']?></a></li>
            <?endforeach;?>
        </ul>
        <?endif;?>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
        	<tr>
                <td class="altbg1">所属小组：</td>
                <td>
                    <span id="group_name"><?=$group['groupname']?></span>
                    <button type="button" id="change_group_btn" class="btn2">更换所属小组</button>
                    <input type="hidden" name="gid" value="<?=$detail['gid']?>">
                </td>
            </tr>
            <tr>
                <td class="altbg1">小组分类：</td>
                <td>
                    <?php $_G['loader']->helper('form','group');?>
                    <select name="typeid">
                        <option value="0">=话题分类=</option>
                        <?=form_group_type($detail['gid'], $detail['typeid']);?>
                    </select>
                    <span class="font_3"><?if($needtypeid==2):?>必选<?endif;?></span>
                </td>
            </tr>
            <tr>
        		<td class="altbg1" width="120">标题：</td>
        		<td width="*"><input type="text" name="subject" value="<?=$detail['subject']?>" class="txtbox" > </td>
        	</tr>
        	<tr>
        		<td class="altbg1">内容：</td>
        		<td><textarea name="content" style="height:100px;width:99%;" ><?=$detail['content']?></textarea></td>
        	</tr>
        </table>
    </div>
	<center>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="tpid" value="<?=$tpid?>" />
		<button type="submint" class="btn">提交</button>
		<button type="button" class="btn" onclick="history.go(-1);">返回</button>
        <input type="hidden" name="forward" value="<?=get_forward()?>" />
	</center>
</form>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('#change_group_btn').click(change_group);       
});

var change_group = function() {

    var _lang = {
        group_empty : '指定的小组不存在。',
        prompt : '请输入小组ID号：'
    };

    var _prompt = function() {
        var gid = window.prompt(_lang.prompt);
        if(!gid||!is_id(gid,'小组ID')) return;
        _ajax(gid);
    };

    var _ajax = function(gid) {
        var url = '<?=U("group/ajax/op/group_info")?>';
        $.post(url.url(), {gid:gid,in_ajax:1}, function(data) {
            if(data.is_message()) {
                myAlert(data);
                return;
            }
            _parse(data);
        });
    };

    var _parse = function(json_str) {
        var json = parse_json(json_str);
        if(json.code==200) {
            _html(json.data);
        } else {
            if(json.code==110003) {alert(_lang.group_empty);return;}
            alert(json.message);
        }
    }

    var _html = function(data) {
        $('#group_name').text(data.groupname);
        $('input[name="gid"]').val(data.gid);
    }

    _prompt();
};
</script>