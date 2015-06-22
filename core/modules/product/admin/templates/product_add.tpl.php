<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="./static/javascript/item.js"></script>
<script type="text/javascript" src="./static/javascript/product.js"></script>
<script type="text/javascript" src="./data/cachefiles/product_gcategory.js?r=<?=$MOD['jscache_flag']?>"></script>
<script type="text/javascript">
function do_next() {
    var sid = $("[name='sid']").val();
    if(!sid||sid<0) {
        alert('未选择产品所属主题，请选择。');
        return false;
    }
    var catid=$("[name='gcatid']").val();
    if(!catid || catid <= 1) {
        var allow = false;
        alert('未选择所属商品分类，请选择。');
        return false;
    }
    check_category_allow(sid,catid);
}
function check_category_allow(sid,catid) {
    $.post(Url('product/ajax/do/category/op/add_allow'), {'catid':catid, 'in_ajax':1 }, 
        function(result) {
        if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result=='OK') {
            var copy_pid=$('#copy_pid').val();
            jslocation('<?=cpurl($module,$act,"add")?>&sid='+sid+'&catid='+catid+'&copy_pid='+copy_pid);
        } else {
            alert('数据读取失败，可能网络忙碌，请稍后尝试。');
        }
    });
}
</script>
<div id="body">
    <div class="space">
        <div class="subtitle">添加产品</div>
        <table width="95%" cellspacing="0" cellpadding="0" class="maintable">
            <tr id="tr_subject">
                <td width="100" class="altbg1" align="right"><span class="font_1">*</span>所属主题：</td>
                <td width="*">
					<div id="subject_search">
                        <?if($subject):?>
                        <input type="hidden" name="sid" value="<?=$sid?>">
                        <a href="<?=url("item/detail/id/$sid")?>" terget="_blank"><?=$subject['name'].($subject['subname']?"($subject[subname])":'')?></a>
                        <?endif;?>
                    </div>
                    <?if(!$subject):?>
					<script type="text/javascript">
						$('#subject_search').item_subject_search({
                            catid:0,
							input_class:'txtbox2',
							btn_class:'btn2',
							result_css:'item_search_result',
                            //location:"<?=cpurl($module,$act,'add',array('sid'=>'_SID_'))?>",
							hide_keyword:true
						});
					</script>
                    <?endif;?>
				</td>
            </tr>
			<tr>
				<td  width='*' class='altbg1' align='right'><span class="font_1">*</span>商品分类：</td>
				<td>
                    <div id="product_gcategory"></div>
                    <script type="text/javascript">
                        $('#product_gcategory').mselect({'data':_product_cate,'name':'gcatid','value':'<?=(int)$gcatid?>'});
                    </script>
				</td>
			</tr>
            <tr>
                <td class='altbg1' align='right'>复制产品ID</td>
                <td>
                    <input type="text" id="copy_pid" name="copy_pid" value="" class="txtbox2">
                    <span class="font_2">通过产品ID，复制产品内容</span>
                </td>
            </tr>
        </table>
        <center>
            <button type="button" class="btn" onclick="do_next()" id="next_btn">继续下一步</button>&nbsp;
        </center>
    </div>
</div>
