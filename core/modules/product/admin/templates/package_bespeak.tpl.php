<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<style type="text/css">.img img {
	max-width: 80px;
	max-height: 60px;
	border: 1px solid #ccc;
	padding: 1px;
	_width: expression(this.width > 80 ? 80: true);
	_height: expression(this.height > 60 ? 60: true);
}

.main_img{
	max-width: 80px;
	max-height: 60px;
	border: 1px solid #ccc;
	padding: 1px;
	_width: expression(this.width > 80 ? 80: true);
	_height: expression(this.height > 60 ? 60: true);
}
.choose_product_wrp {
	  position: absolute;
  width: 640px;
  height: auto;
  top: 100px;
  left: 50%;
  margin-left: -320px;
  border: 1px solid #D8ADAD;
}

.choose_product_wrp tbody{
  max-height: 540px;
  overflow-y: scroll;
}

</style>
<div id="body">
<form method="get" action="<?=SELF ?>">
    <input type="hidden" name="module" value="<?=$module ?>" />
    <input type="hidden" name="act" value="<?=$act ?>" />
    <input type="hidden" name="op" value="<?=$op ?>" />
    <div class="space">
        <div class="subtitle">预约筛选</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
           	
            <tr>
                <td class="altbg1">预约人姓名</td>
                <td><input type="text" name="subject" class="txtbox3" value="<?= $_GET['subject'] ?>" /><button type="submit" value="yes" name="dosubmit" class="btn2">筛选</button></td>
               
            </tr>
            <!--<tr>
                <td class="altbg1">预约时间</td>
                <td colspan="3">
                	<input type="text" name="starttime" class="txtbox3" value="<?=$_GET['starttime'] ?>" />&nbsp;~&nbsp;<input type="text" name="endtime" class="txtbox3" value="<?=$_GET['endtime'] ?>" />&nbsp;(YYYY-MM-DD)</td>
            </tr>--><!--
            <tr>
                <td class="altbg1">结果排序</td>
                <td colspan="3">
                <select name="orderby">
                    <option value="id"<?=$_GET['orderby'] == 'id' ? ' selected="selected"' : '' ?>>默认排序</option>
                    <option value="create_time"<?=$_GET['orderby'] == 'create_time' ? ' selected="selected"' : '' ?>>预约时间</option>
                </select>&nbsp;
                <select name="ordersc">
                    <option value="DESC"<?=$_GET['ordersc'] == 'DESC' ? ' selected="selected"' : '' ?>>递减</option>
                    <option value="ASC"<?=$_GET['ordersc'] == 'ASC' ? ' selected="selected"' : '' ?>>递增</option>
                </select>&nbsp;
                <select name="offset">
                    <option value="20"<?=$_GET['offset'] == '20' ? ' selected="selected"' : '' ?>>每页显示20个</option>
                    <option value="50"<?=$_GET['offset'] == '50' ? ' selected="selected"' : '' ?>>每页显示50个</option>
                    <option value="100"<?=$_GET['offset'] == '100' ? ' selected="selected"' : '' ?>>每页显示100个</option>
                </select>&nbsp;
                <button type="submit" value="yes" name="dosubmit" class="btn2">筛选</button>&nbsp;
                
                </td>
            </tr>-->
        </table>
    </div>
</form>
<?=form_begin(cpurl($module, $act)) ?>
    <div class="space">
        <div class="subtitle">套餐预约管理</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="25">选</td>
                <td width="*">姓名</td>
                <td width="120">联系方式</td>
                <td width="120">预约时间</td>
                <td width="120">预约套餐</td>
                <td width="120">操作</td>
            </tr>
            <?if($total):?>
            <?foreach($list as $val) {?>
            <tr>
                <td><input type="checkbox" name="ids[]" value="<?=$val['id'] ?>" /></td>
                
                <td>
                    <div><?=$val['name'] ?></div>
                </td>
                
                <td>
                    <div><?=$val['phone'] ?></div>
                </td>
                
                <td>
                    <div><?=date('Y-m-d H:i', $val['create_time']) ?></div>
                </td>
                <td>
                    <div>
                    	<a target="_blank" href="<?=url("product/pkg_detail/id/$val[pkgid]")?>">
                    	<?=$val['pkgname'] ?></a>
                    	</div>
                </td>
                
                <td>
                   <button type="button" class="btn js_delete" data-id="<?=$val['id'] ?>">删除</button>
                </td>
            </tr>
            <?}; ?>
            <tr class="altbg1">
                <td colspan="3"><button type="button" onclick="checkbox_checked('ids[]');" class="btn2">全选</button></td>
                <td colspan="6" style="text-align:right;"><?=$multipage ?></td>
            </tr>
            <?else: ?>
            <tr><td colspan="8">暂无信息。</td></tr>
            <?endif ?>
        </table>
    </div>
<?=form_end() ?>


</div>



<script type="text/javascript">

$(function() {
	$(".js_delete.btn").click(function(ev){
		var id = $(ev.target).data("id");
		$.ajax({
			url: '<?= cpurl($module, $act,"delete_bespeak")?>',
			data:{id:id},
			dataType:"json",
			success:function(data){
				console.debug(data);
				
				if(data.status){
					alert(data.info);
					location.reload();
				}else{
					
					alert(data.info);
				}
					
				
			}
			
		});
		
	});
});

</script>


