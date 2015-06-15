<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<div class="sub-menu">
    <div class="sub-menu-heading">上传设置</div>
</div>
<?=form_begin(cpurl($module,$act,$op))?>
    <div class="space">
        <table class="maintable">
            <tr>
                <td class="altbg1" width="45%"><strong>图片文件存放方式:</strong>设置图片文件在文件夹存放方式，默认按月存放。</td>
                <td width="*">
                    <?php !$config['picture_dir_mod'] && $config['picture_dir_mod']='MONTH'; ?>
                    <?=form_radio('setting[picture_dir_mod]',array('MONTH'=>'月','WEEK'=>'周','DAY'=>'日'),$config['picture_dir_mod'])?>
                </td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>默认上传尺寸限制:</strong>系统默认图片最大上传尺寸，单位：KB</td>
                <td width="*"><input type="text" name="setting[picture_upload_size]" value="<?=$config['picture_upload_size']?>" class="txtbox4" /> KB</td>
            </tr>
            <tr>
                <td class="altbg1"><strong>默认允许图片类型:</strong>不同类型请用【空格】分割，默认为：jpg jpeg png gif</td>
                <td><input type="text" name="setting[picture_ext]" value="<?=$config['picture_ext']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>限制图片最大尺寸:</strong>自动缩小用户上传的图片大于当前设置的最大尺寸，默认：800*600；</td>
                <td width="*"><?=form_input('setting[picture_max_width]',$config['picture_max_width']?$config['picture_max_width']:800,'txtbox5')?>&nbsp;*&nbsp;<?=form_input('setting[picture_max_height]',$config['picture_max_height']?$config['picture_max_height']:600,'txtbox5')?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>默认图片水印功能:</strong>在默认上传图片时，给图片加上水印，支持PNG类型水印，水印图片存放在./static/images/watermark.png，您可替换水印文件以实现不同的水印效果，水印功能需要GD库支持。</td>
                <td><?=form_bool('setting[watermark]',$config['watermark'])?></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>文字水印字符:</strong>填写水印文字，用于使用文字形式水印的时候。使用中文，必须要上传支持的字体simsun.ttc，请上传Modoer文件夹 static/images/fonts，simsun.ttc字体可以在Windows系统内搜索到，<a href="http://www.google.com.hk/search?hl=zh-CN&source=hp&q=simsun.ttc" target="_blank">也可以通过搜索引擎下载到</a>。<span class="font_1">不宜过多文字，文字长度超过图片长度时，将无法打上水印</span></td>
                <td width="*"><input type="text" name="setting[watermark_text]" value="<?=$config['watermark_text']?>" class="txtbox2" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>默认水印位置:</strong>设置水印在图片上的位置，默认是在右下角</td>
                <td><?=form_radio('setting[watermark_postion]', array(0=>'随机',1=>'左上角',2=>'右上角',3=>'左下角',4=>'右下角',5=>'居中',6=>'底部文字'), $config['watermark_postion'],'','&nbsp;')?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>加水印图片大小限制:</strong>针对大于指定尺寸的图片添加水印，小于指定大小的图片不增加水印。</td>
                <td width="*"><?=form_input('setting[watermark_limitsize_width]',$config['watermark_limitsize_width']?$config['watermark_limitsize_width']:200,'txtbox5')?>
                    &nbsp;*&nbsp;<?=form_input('setting[watermark_limitsize_height]',$config['watermark_limitsize_height']?$config['watermark_limitsize_height']:100,'txtbox5')?></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>缩略图生成质量:</strong>设置缩略图的生成质量，值越大，质量越高，同时占用空间也大，默认是80，最大100；<span class="font_1">不建议设置为100，可能缩略图的文件大小会大于原图。</span></td>
                <td width="*"><input type="text" name="setting[picture_createthumb_level]" value="<?=$config['picture_createthumb_level']?>" class="txtbox4" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>缩略图生成模式:</strong>按尺寸裁剪：对图片缩小并裁剪，超出部分裁剪，确保图片大小与设置的高宽相等；等比例缩小：宽和高只取其中1项，宽优先选择。建议(默认)选择按尺寸裁剪。</td>
                <td><?=form_radio('setting[picture_createthumb_mod]', array('按尺寸裁剪','等比例缩小'), $config['picture_createthumb_mod'],'','&nbsp;')?></td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>