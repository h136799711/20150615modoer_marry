<?php
$att_cats = $_G['loader']->variable('att_cat','item');
foreach ($attcats as $att_catid) {
if($att_cats[$att_catid]) {?>
<div style="margin:5px 0;">
    <label style="display:block;font-weight:bold;color:#808080;"><?=$att_cats[$att_catid]['name']?>:</label>
    <?php $multi=in_array($att_catid,$attmulti);?>
    <select id="att_<?=$att_catid?>" name="att[]" <?if($multi):?>multiple="true"<?endif?> style="width:250px;">
        <?php $getval=$_G['datacall']->datacall_get('getatt',array('catid'=>$att_catid,),'product');
        if(is_array($getval))foreach($getval as $val_k => $val):?>
        <option value="<?=$att_catid?>_<?=$val['attid']?>"<?if($productatts[$att_catid.'_'.$val['attid']])echo' selected="selected"'?>><?=$val['name']?></option>
        <?php endforeach; ?>
    </select>
    <div class="clear"></div>
	<?if($multi):?>
    <script type="text/javascript">$('#att_<?=$att_catid?>').mchecklist({width:'60%',height:60,line_num:2});</script>
    <?endif?>
</div>
<?php }} ?>