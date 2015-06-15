<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?>&nbsp;模块配置</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="javascript:;" onclick="tabSelect(1,'config');" onfocus="this.blur()">功能设置</a></li>
            <li id="btn_config2"><a href="javascript:;" onclick="tabSelect(2,'config');" onfocus="this.blur()">界面设置</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1"><strong>关闭全部评论:</strong>一次性关闭所有评论，可用于特殊情况，特殊时期。</td>
                <td><?=form_bool('modcfg[disable_comment]',$modcfg['disable_comment']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>关闭所有显示的评论:</strong>关闭所有显示的评论信息。</td>
                <td><?=form_bool('modcfg[hidden_comment]',$modcfg['hidden_comment']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>是否开启游客评论:</strong>允许游客进行点评</td>
                <td><?=form_bool('modcfg[guest_comment]',$modcfg['guest_comment']);?></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top" width="45%">
                <strong>使用评论系统接口:</strong>
                    第三方社会化评论系统即是独立于网站的专业的评论系统，具有比较完善的评论功能，支持微博转发和平台分析，
                    同时可以在一定程度上防御垃圾信息，但管理平台不在网站系统内，需要进入第三方评论系统后台进行管理。</td>
                <td width="*"><?=form_radio('modcfg[comment_interface]',$radio_interface, $modcfg['comment_interface'])?></td>
            </tr>
            <?php if(iterator_count($ITFN)>0):?>
            <?php foreach ($ITFN as $interface):?>
            <?php $info = $interface->get_interface_info();?>
            <?php $elements = $interface->form_elements();?>
            <?php if($elements):?>
            <tr class="<?="J_inter J_{$info['flag']}"?> altbg2 none">
                <td></td><td><b><?=$info['name']." {$info['url']}"?></b></td>
            </tr>
            <?php foreach ($elements as $item):?>
            <tr class="<?="J_inter J_{$info['flag']}"?> none">
                <td class="altbg1">
                    <strong><?=$item['title']?>:</strong>
                    <?=$item['des']?>
                </td>
                <td>
                    <?=$item['content']?>
                </td>
            </tr>
            <?php endforeach;?>
            <?php endif;?>
            <?php endforeach;?>
            <?php endif;?>
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config2" style="display:none;">
            <tr>
                <td class="altbg1" valign="top" width="45%"><strong>评论单页显示数量:</strong>设置页面的评论显示最大数量</td>
                <td width="*"><?=form_radio('modcfg[list_num]',array('5'=>'5条','10'=>'10条','20'=>'20条','40'=>'40条','50'=>'50条'),$modcfg['list_num'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>评论排序方式:</strong>设置评论显示顺序</td>
                <td><?=form_radio('modcfg[addtime]',array('asc'=>'最早评论在前','desc'=>'最新评论在前'),$modcfg['addtime']?$modcfg['addtime']:'asc')?></td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>
<?php if(iterator_count($ITFN)>0):?>
<script type="text/javascript">
$(document).ready(function() {

    $('[name="modcfg[comment_interface]"]').each(function() {
        $(this).click(function() {
            interface_radio_click();
        });
    });

    interface_radio_click();

});

function interface_radio_click() {
    var radio = $('[name="modcfg[comment_interface]"]:checked');
    var inter = radio.val();
    if(inter==0) inter='local';
    $('tr.J_inter').hide();
    $('tr.J_'+inter).show();
}
<?php endif;?>
</script>