<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="./static/javascript/group.js?v=1"></script>
<script type="text/javascript" src="./static/javascript/item.js"></script>
<script type="text/javascript">
var g;
function reload() {
    var obj = document.getElementById('reload');
    var btn = document.getElementById('switch');
    if(obj.innerHTML.match(/^<.+href=.+>/)) {
        g = obj.innerHTML;
        obj.innerHTML = '<input type="file" name="icon" size="20">';
        btn.innerHTML = '取消上传';
    } else {
        obj.innerHTML = g;
        btn.innerHTML = '重新上传';
    }
}
</script>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'save')?>" enctype="multipart/form-data">
    <div class="space">
        <div class="subtitle">编辑小组信息</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr>
                <td class="altbg1" width="120">名称：</td>
                <td><input type="text" class="txtbox" name="groupname" value="<?=$detail['groupname']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1" width="120">分类：</td>
                <td>
                    <div>
                        <input type="txtbox" class="txtbox" name="tags" id="tags" value="<?=$tags?>" readonly />
                        <a href="javascript:"onclick="$('#tags').val('');">清空</a>
                    </div>
                    <div style="width:500px;">
                        <select name="catid" id="catid" onchange="group_load_cattags($(this).val(),'cat_tags');">
                            <?=form_group_category($detail['catid'])?>
                        </select>
                        <span id="cat_tags"></span>
                        <script type="text/javascript">
                            group_load_cattags($('#catid').val(),'cat_tags');
                        </script>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="altbg1" width="120">图标：</td>
                <td>
                    <?if(!$detail['icon']):?>
                    <input type="file" name="icon" size="20" />
                    <?else:?>
                    <span id="reload"><a href="<?=$detail['icon']?>" target="_blank" src="<?=$detail['icon']?>" 
                        onmouseover="tip_start(this);"><?=$detail['icon']?></a></span>&nbsp;
                    [<a href="javascript:reload();" id="switch">重新上传</a>]
                    <?endif;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1" width="120">关联主题：</td>
                <td>
                    <div id="subject_search">
                    <?if($subject):?>
                    <a href="<?=url("item/detail/id/$sid")?>" target="_blank"><?=$subject['name'].($subject['subname']?"($subject[subname])":'')?></a>
                    <?endif;?>
                    </div>
                    <script type="text/javascript">
                        $('#subject_search').item_subject_search({
                            input_class:'txtbox2',
                            btn_class:'btn2',
                            result_css:'item_search_result',
                            <?if($subject):?>
                                sid:<?=$subject['sid']?>,
                                current_ready:true,
                            <?endif;?>
                            hide_keyword:true
                        });
                    </script>
                </td>
            </tr>
            <tr>
                <td class="altbg1">地区：</td>
                <td>
                    <?php $_G['loader']->helper('form');?>
                    <select name="city_id">
                        <?=form_city($detail['city_id'],true)?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1" width="120">简介：</td>
                <td><textarea name="des" style="height:100px;width:600px"><?=$detail['des']?></textarea></td>
            </tr>
            <tr>
                <td class="altbg1" width="120">设置：</td>
                <td>
                    <label><input type="checkbox" name="auth" value="1"<?if($detail['auth']=='1')echo' checked="checked"';?>>官方小组</label>
                    <label><input type="checkbox" name="finer" value="1"<?if($detail['finer'])echo' checked="checked"';?>>推荐小组</label>
                </td>
            </tr>
        </table>
        <center>
            <?if($op=='edit'):?>
            <input type="hidden" name="gid" value="<?=$gid?>" />
            <input type="hidden" name="edit" value="yes" />
            <?endif;?>
            <button type="submit" name="dosubmit" value="yes" class="btn">提交操作</button>
            <button type="button" onclick="history.go(-1);" class="btn">返回</button>
        </center>
    </div>
</form>
</div>