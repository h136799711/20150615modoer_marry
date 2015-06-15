<?php 
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');
function tmp_get_tag($tagname) {
    //global $GT;
    if(!$tagname) return '';
    if(!is_array($tagname)) return $tagname;
    return implode(S('tag_split')=='comma'?',':' ',$tagname);
}
?>
<style type="text/css">
.groupitem{margin-bottom:10px;}
.groupitem label{display: block;}
.groupitem input{margin:3px 0; display: block;}
.groupitem span.helper{color: #808080;}
</style>
<form name="myform" id="myform" method="post" action="<?=cpurl($module,$act,'save')?>">
    <div class="groupitem">
        <label>标题：</label>
        <input type="text" name="title" class="txtbox" value="<?=$detail['title']?>" />
    </div>
    <div class="groupitem">
        <label>城市：</label>
        <select name="city_id">
            <?php echo form_city($detail['city_id'])?>
        </select>
    </div>
    <div class="groupitem">
        <label>分类：</label>
        <?php $_G['loader']->helper('form','mylist');?>
        <select name="catid">
            <?php echo form_mylist_category($detail['catid']);?>
        </select>
    </div>
    <div class="groupitem">
        <label>简介:</label>
        <textarea name="intro" class="txtarea"><?=$detail['intro']?></textarea>
    </div>
    <div class="groupitem">
        <label>标签:</label>
        <input type="text" name="tags" class="txtbox" value="<?=tmp_get_tag($detail['tags'])?>" />        
    </div>
    <div class="groupitem">
        <?if($op=='edit'):?>
        <input type="hidden" name="edit" value="yes" />
        <input type="hidden" name="id" value="<?=$id?>" />
        <?endif;?>
        <button type="submit" class="btn" onclick="">提交</button>&nbsp;
        <button type="button" class="btn" onclick="dlgClose();">关闭</button>
    </div>
</form>