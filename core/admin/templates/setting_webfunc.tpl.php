<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<div class="sub-menu" data-container="div.space > table">
    <div class="sub-menu-heading">网站功能</div>
    <a href="#" class="sub-menu-item selected">主要功能</a>
    <a href="#" class="sub-menu-item">JS数据调用</a>
</div>
<?=form_begin(cpurl($module,$act,$op))?>
    <div class="space">
        <table class="maintable">
            <tr>
                <td class="altbg1"><strong>多城市分站访问模式:</strong>设置多城市分站访问模式</td>
                <td><?=form_radio('setting[city_sldomain]', array('0'=>'常规','1'=>'二级域名','2'=>'城市目录(仅开启目录形式URL改写时有效)'),$config['city_sldomain']?$config['city_sldomain']:0)?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>不需要分站城市目录模式的页面:</strong>不需要进行分站目录模式的页面，
                您可以在文本框内增加不使用城市目录的页面；比如一些模块的内容页，因为他们具有唯一性，
                不需要通过加入城市目录来解决搜索引擎能的问题，例如：新闻内容页(article/detail)，主题内容页(item/detail)等。
                您也可以使用通配符和特定标签来特殊处理，*表示匹配全部，{num}表示匹配数字 
                <br />格式：模块标识/页面名称，每行一条</td>
                <td><?=form_textarea('setting[citypath_without]', $config['citypath_without'], 5,50,'txtarea3')?></td>
            </tr>
            <tr>
                <td width="45%" class="altbg1"><strong>页面 Gzip 压缩:</strong>将页面内容以 gzip 压缩后传输，可以加快传输速度。</td>
                <td width="*"><?=form_bool('setting[gzipcompress]', $config['gzipcompress'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>显示页面信息:</strong>在页面底部，显示数据库查询次数和页面执行时间。<br /><span class="font_1">一部分页面启用了"整页缓存"功能时，本功能将失效。</span></td>
                <td><?=form_bool('setting[scriptinfo]', $config['scriptinfo'])?></td>
            </tr>
            <?if($_G['charset']!='UTF-8'):?>
            <tr>
                <td class="altbg1"><strong>UTF-8格式的URL:</strong>如果您开启伪静态后不能正常进行搜索，或者地图上主题名称出现乱码问题，请开启此功能；没有问题的，请不要打开。</td>
                <td><?=form_bool('setting[utf8url]', $config['utf8url'])?></td>
            </tr>
            <?endif;?>
			<tr>
                <td class="altbg1"><strong>模板后缀:</strong>自定义后缀名，可防模板名被猜测，默认为.htm，必须加上后缀点号。<br /><span class="font_1">请确保模板目录下的各个文件后缀与此处设置保持一致。</span></td>
                <td><input type="text" name="setting[tplext]" value="<?=$config['tplext']?>" class="txtbox4" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>标签分隔符号:</strong>各个模块内使用到标签功能时，用于分割标签的符号，中文建议空格，英文建议逗号。</td>
                <td><?=form_radio('setting[tag_split]',array('space'=>'空格','comma'=>'逗号(,)'),$config['tag_split'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>地图API的地址:</strong>填写根据不同的地图商提供的API功能的JS地址。<br /><span class="font_1">在使用二级域名分站时，有些地图接口需要对各个二级域名申请key，请讲申请到的二级域名api地址填写到地区管理中。</span></td>
                <td><input type="text" name="setting[mapapi]" value="<?=$config['mapapi']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>地图标识:</strong>根据不同的地图标识，系统会载入不同的地图js（前提是地图js存在），可选标识：baidu，google_v3</td>
                <td><input type="text" name="setting[mapflag]" value="<?=$config['mapflag']?>" class="txtbox3" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>地图API的编码:</strong>默认地图不需要设置编码，Google地图需要设置为UTF-8</td>
                <td><input type="text" name="setting[mapapi_charset]" value="<?=$config['mapapi_charset']?>" class="txtbox4" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>地图默认缩放等级:</strong>不同的地图有自己的缩放等级，一般在10-16之前，可自行填写一个数字后前台测试，设置自己满意的值，只能填写数字</td>
                <td><input type="text" name="setting[map_view_level]" value="<?=$config['map_view_level']?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>编辑器UI语言:</strong>选择WEB编辑器操作界面语言包</td>
                <td>
                    <?=form_radio('setting[editor_lang]',array('zh_CN'=>'中文','en'=>'英文'),$config['editor_lang']?$config['editor_lang']:'zh_CN')?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>编辑器上传附件使用相对路径:</strong>在WEB编辑器中，使用上传图片，文件等上传功能时，在上传后使用相对路径来显示图片，文件等。相对路径的好处在于可以针对更改域名时，上传的图片和文件会自动指向新域名，不使用相对路径时，文章里的图片等上传文件将失效，因为全局路径会直接写死域名。</td>
                <td><?=form_bool('setting[editor_relativeurl]', $config['editor_relativeurl'])?><div><span class="font_2">如果你的网站是一级域名或者二级域名(例如:http://demo.modoer.com)，建议打开；如果你的网站是放在二级目录里(例如:http://www.modoer.com/modoer)，请关闭本功能，因为会影响正常网站的图片等上传文件的显示。</span></div></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>二维码生成器地址:</strong>设置一个在线生成二维码图片的文件地址，
                    参数用<span class="font_1">{CONTENT}</span>表示系统传入的内容参数。不填写则使用系统自带的。
                    使用第三方提供的，可降低本地服务器的生成资源。
                </td>
                <td><input type="text" name="setting[qrcode_api_src]" value="<?=$config['qrcode_api_src']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>快递100的API密钥:</strong>提供快递进度查询的API，申请地址http://www.kuaidi100.com/openapi/applyapi.shtml
                </td>
                <td><input type="text" name="setting[kd100_api_key]" value="<?=$config['kd100_api_key']?>" class="txtbox" /></td>
            </tr>
        </table>
        <table class="maintable"style="display:none;">
            <tr>
                <td class="altbg1" class="altbg1" width="45%"><strong>开启JS调用功能:</strong>开启系统的JS远程调用功能。</td>
                <td width="*"><?=form_bool('setting[jstransfer]', $config['jstransfer'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>JS来路限制:</strong>只允许列表中的域名才可以使用JS调用功能，每个域名一行，请勿包含 http:// 或其他非域名内容，留空为不限制来路，即任何网站均可调用.但是多网站调用会加重您的服务器负担。</td>
                <td><textarea name="setting[jsaccess]" rows="6" cols="40" class="txtarea"><?=$config['jsaccess']?></textarea></td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>