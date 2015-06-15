<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
    <form method="post" action="<?=cpurl($module,$act,'att')?>">
        <input type="hidden" name="sid" value="<?=$detail['sid']?>" />
        <div class="space">
            <div class="subtitle">设置当前分类(<?=$category['name']?>)产品的晒需属性</div>
            <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
                <thead>
                    <tr>
                        <td>选?</td>
                        <td>属性组名称</td>
                        <td>允许复选</td>
                        <td>说明</td>
                    </tr>
                </thead>
                <? if($list): ?>
                <? while($val = $list->fetch_array()): ?>
                <tr>
                    <td width="30" class="altbg1"><input type="checkbox" name="attcat[]" value="<?=$val['catid']?>"<?if(in_array($val['catid'],$attcat))echo' checked="checked"';?>></td>
                    <td width="200"><?=$val['name']?></td>
                    <td width="100"><input type="checkbox" name="attmulti[]" value="<?=$val['catid']?>"<?if(in_array($val['catid'],$attmulti))echo' checked="checked"';?>></td>
                    <td width="*"><?=$val['des']?></td>
                </tr>
                <? endwhile; ?>
                <? else: ?>
                <tr><td colspan="4">还未添加属性组，请到“主题管理”--“属性组管理”添加属性组后再来做设置。</td></tr>
                <? endif; ?>
            </table>
            <center>
                <input type="hidden" name="do" value="<?=$op?>" />
                <input type="hidden" name="catid" value="<?=$catid?>" />
                <input type="hidden" name="forward" value="<?=get_forward()?>" />
                <button type="button" class="btn" onclick="product_manage_att()">创建属性组</button>
                <?if($list):?><button type="submit" class="btn" name="dosubmit" value="yes">提交</button>&nbsp;<?endif;?>
                <?if($op){?><button type="button" class="btn" onclick="jslocation('<?=cpurl($module,'category_edit','subcat',array('catid'=>$category['pid']))?>')">返回</button>&nbsp;<? } else { ?><button type="button" class="btn" onclick="jslocation('<?=cpurl($module,'category_list')?>')">返回</button>&nbsp;<? } ?>
            </center>
        </div>
    </form>
</div>
<script type="text/javascript">
function product_manage_att() {
    var src = "<?=cpurl('item','att_cat')?>&nofooter=1";
    var content = $('<div></div>');
    var iframe = $("<iframe></iframe>").attr('src',src).attr({
            'frameborder':'no','border':'0','marginwidth':'0','marginheight':'0','scrolling':'auto',
            'allowtransparency':'yes'}).css({"width":"100%","height":"400px"});
    content.append(iframe);
    dlgOpen('属性组设置',content,700);
    if($.browser.msie && $.browser.version.substr(0,1)=='6' ) {
        window.setTimeout(function() {
            iframe.attr('src', src);
        },1200);
    }
}
</script>