<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?>&nbsp;模块配置</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="javascript:;" onclick="tabSelect(1,'config');" onfocus="this.blur()">功能设置</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1" width="45%"><strong>行为审核:</strong>前台用户创建小组，发布话题，回复话题时，需要经过后台审核。</td>
                <td>
                    <div><span>创建小组</span><?=form_bool('modcfg[group_check]',$modcfg['group_check'])?></div>
                    <div><span>话题发布</span><?=form_bool('modcfg[topic_check]',$modcfg['topic_check'])?></div>
                    <div><span>话题回复</span><?=form_bool('modcfg[reply_check]',$modcfg['reply_check'])?></div>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>操作验证码:</strong>前台用户创建小组，发布话题，回复话题时，需要填写话验证码。</td>
                <td>
                    <div><span>创建小组</span><?=form_bool('modcfg[group_seccode]',$modcfg['group_seccode'])?></div>
                    <div><span>话题发布</span><?=form_bool('modcfg[topic_seccode]',$modcfg['topic_seccode'])?></div>
                    <div><span>话题回复</span><?=form_bool('modcfg[reply_seccode]',$modcfg['reply_seccode'])?></div>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>小组图标尺寸:</strong>创建小组时，允许上传小组图标，设置图标的固定长宽(正方形)，不得小于100像素</td>
                <td><input type="text" name="modcfg[group_icon_size]" value="<?=$modcfg['group_icon_size']>100?$modcfg['group_icon_size']:100?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>话题内容字数控制:</strong>设置前台发布讨论话题时的字数数量限制。</td>
                <td>
                    <input type="text" name="modcfg[topic_content_min]" value="<?=$modcfg['topic_content_min']>0?$modcfg['topic_content_min']:10?>" class="txtbox5" /> -
                    <input type="text" name="modcfg[topic_content_max]" value="<?=$modcfg['topic_content_max']>0?$modcfg['topic_content_max']:5000?>" class="txtbox5" />
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>回应内容字数控制:</strong>设置前台发在回应话题时的字数数量限制。</td>
                <td>
                    <input type="text" name="modcfg[reply_content_min]" value="<?=$modcfg['reply_content_min']>0?$modcfg['reply_content_min']:10?>" class="txtbox5" /> -
                    <input type="text" name="modcfg[reply_content_max]" value="<?=$modcfg['reply_content_max']>0?$modcfg['reply_content_max']:1000?>" class="txtbox5" />
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>标签数量空值:</strong>新建小组时分类标签最多允许填写数量，最大不能超过10个，默认最大8个</td>
                <td><input type="text" name="modcfg[tag_max_len]" value="<?=$modcfg['tag_max_len']>10?$modcfg['tag_max_len']:8?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>标签分隔符:</strong>标签之间的分隔符号，默认为空格</td>
                <td>
                    <?=form_radio('modcfg[tag_split]',array('0'=>'空格','1'=>'逗号(,)'),(int)$modcfg['tag_split'])?>
                </td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>