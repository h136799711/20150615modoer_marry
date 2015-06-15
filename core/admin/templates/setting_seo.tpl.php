<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<div class="sub-menu" data-container="div.space > table.maintable">
    <div class="sub-menu-heading">搜索引擎优化</div>
    <a href="#" class="sub-menu-item selected">伪静态设置</a>
    <a href="#" class="sub-menu-item">页面优化</a>
</div>
<?=form_begin(cpurl($module,$act,$op))?>
    <div class="space">
        <table class="maintable">
            <tr>
                <td class="altbg1">
                    <strong>启用 URL 单一入口:</strong>
                    <span class="font_1">本功能在未启用“URL改写”功能时使用，及使用原始动态脚本URL。</span><br />
                    开启本功能后，系统所有页面地址都将从index.php文件进入。
                </td>
                <td>
                    <?=form_bool('setting[single_index]', (bool)$config['single_index'])?>
                </td>
            </tr>
            <tr>
                <td width="50%" class="altbg1"><strong>启用 URL 改写(URL Rewrite):</strong>URL 改写可以提高搜索引擎抓取，但会轻微增加服务器负担。
                <br /><span class="font_1">URL 改写，必须在模板中使用 url 模板标签，<a href="http://www.modoer.com/article.php?aid=38" target="_blank">详细使用请参考相关教程</a></span></td>
                <td width="*">
                    <?=form_bool('setting[rewrite]', $config['rewrite'])?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>URL 改写方式:</strong>伪静态：以网页文件以HTML为后缀；目录形式：模拟多层文件目录形式以及复杂URL表现实现。
                <br /><span class="font_1">建议IIS6用户只使用 伪静态 方式</span></td>
                <td>
                    <?=form_radio('setting[rewrite_mod]', array('html'=>'伪静态','pathinfo'=>'目录形式'), $config['rewrite_mod'])?>
                    ( 加后缀 <?=form_input('setting[rewrite_mod_pathinfo_ext]', $config['rewrite_mod_pathinfo_ext'], 'txtbox5')?> 例如 html )
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>URL 来路与设置的模式不一致时自动 301 跳转:</strong>如果URL来路的模式与当前设定的URL改写不一致时（例如：设置了伪静态模式URL，但来路URL是目录形式的），是否使用301跳转的到正确的URL，适用于建站中途更改URL改写方式的用户(搜索引擎已经收录之前设置的URL时)
                <br /><span class="font_1">此功能需要rewrite配置文件同时加载URL改写的两种规则，否则系统是无法获得URL来路，仅建议Apache用户使用，同时没有必要不要使用。</span></td>
                <td>
                    <?=form_bool('setting[rewrite_location]', $config['rewrite_location'])?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>隐藏 URL 中的 index.php :</strong><span class="font_1">本功能需要对 Web 服务器支持Rewrite功能，</span>同时需要伪静态规则加载到服务器中。</td>
                <td>
                    <?=form_bool('setting[rewrite_hide_index]', $config['rewrite_hide_index'])?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>启用 Rewrite 中文兼容:</strong>如果Web服务器不支持 Rewrite 中文，请开启。</td>
                <td>
                    <?=form_bool('setting[rewritecompatible]', $config['rewritecompatible'])?>
                </td>
            </tr>
        </table>

        <table class="maintable" style="display:none;">
            <tr>
                <td width="50%" class="altbg1"><strong>标题附加字:</strong>网页标题通常是搜索引擎关注的重点，本附加字设置将出现在标题中网站名称的后面，如果有多个关键字，建议用 "|"、","(不含引号) 等符号分隔</td>
                <td width="50%"><input type="text" name="setting[subname]" value="<?=$config['subname']?>" class="txtbox" />
                <br /><span class="font_2">网站标题不要太长，最好不要超过30个字符，搜索引擎对过长标题无爱。</span></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>标题分隔符:</strong>标题和标题附加字之间的分隔符</td>
                <td><input type="text" name="setting[titlesplit]" value="<?=$config['titlesplit']?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>使用SEO标签智能处理不存在标签内容:</strong>当用户自定义SEO标签时（标题，关键字，描述），如果遇到设置的标签不存在时，自动隐藏标签后面相关的文字，直到分隔符出现(,-|_)。</td>
                <td><?=form_bool('setting[auto_seo_tag]', (bool)$config['auto_seo_tag'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>Meta Keywords:</strong>Keywords 项出现在页面头部的 Meta 标签中，用于记录本页面的关键字，多个关键字间请用半角逗号 "," 隔开</td>
                <td><input type="text" name="setting[meta_keywords]" value="<?=$config['meta_keywords']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>Meta Description:</strong>Description 出现在页面头部的 Meta 标签中，用于记录本页面的概要与描述</td>
                <td><input type="text" name="setting[meta_description]" value="<?=$config['meta_description']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><strong>其它头部信息:</strong>如需在 &lt;head&gt;&lt;/head&gt; 中添加其它的 HTML 代码，可以使用本设置，否则请留空；请填写HTML代码，不要填写纯文字，否则会破环网页布局。</td>
                <td><textarea name="setting[headhtml]" rows="5" cols="40" class="txtarea"><?=$config['headhtml']?></textarea></td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>