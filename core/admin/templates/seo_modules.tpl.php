<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
    <div class="sub-menu">
        <div class="sub-menu-heading">页面SEO管理</div>
        <?php foreach ($seo_modules as $key => $value) :?>
        <a href="<?=cpurl($module,$act,'',array('module_flag'=>$key))?>" class="sub-menu-item<?if($module_flag==$key)echo' selected';?>"><?=$value['name']?></a>
        <?php endforeach;?>
    </div>
    <?=form_begin(cpurl($module,$act,'save'))?>
        <input type="hidden" name="module_flag" value="<?=$seo_module['flag']?>" />
        <div class="space">
            <?=$SEO->display()?>
        </div>
        <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
    <?=form_end()?>
</div>