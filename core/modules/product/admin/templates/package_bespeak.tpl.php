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
        <div class="subtitle">套餐筛选</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <!--<td width="100" class="altbg1">产品分类</td>
                <td width="300">
                    <select name="gcatid">
                        <option value="">全部</option>
                        <?=form_product_category_main($_GET['gcatid'])?>
                    </select>
                </td>-->
                <!--<td width="100" class="altbg1">所属地区</td>
                <td width="*">
                    <?if($admin->is_founder):?>
                    <select name="city_id">
                        <option value="">不限</option>
                        <?=form_city($_GET['city_id'], TRUE)?>
                    </select>
                    <?else:?>
                    <?=$_CITY['name']?>
                    <?endif;?>
                </td>-->
            </tr>
            <tr>
                <td class="altbg1">套餐ID</td>
                <td><input type="text" name="id" class="txtbox3" value="<?=$_GET['id'] ?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">套餐标题</td>
                <td><input type="text" name="subject" class="txtbox3" value="<?=$_GET['subject'] ?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">发布时间</td>
                <td colspan="3">
                	<input type="text" name="starttime" class="txtbox3" value="<?=$_GET['starttime'] ?>" />&nbsp;~&nbsp;<input type="text" name="endtime" class="txtbox3" value="<?=$_GET['endtime'] ?>" />&nbsp;(YYYY-MM-DD)</td>
            </tr>
            <tr>
                <td class="altbg1">结果排序</td>
                <td colspan="3">
                <select name="orderby">
                    <option value="id"<?=$_GET['orderby'] == 'id' ? ' selected="selected"' : '' ?>>默认排序</option>
                    <option value="create_time"<?=$_GET['orderby'] == 'create_time' ? ' selected="selected"' : '' ?>>发布时间</option>
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
                <button type="button" onclick="window.location='<?=cpurl($module, $act, 'add') ?>';" class="btn2">添加套餐</button>
                </td>
            </tr>
        </table>
    </div>
</form>
<?=form_begin(cpurl($module, $act)) ?>
    <div class="space">
        <div class="subtitle">套餐管理</div>
        <?if($edit_links):?>
        <ul class="cptab">
            <?foreach($edit_links as $val):?>
            <li <?
				if ($val['flag'] == 'product:list')
					echo ' class="selected"';
			?>><a href="<?=$val['url'] ?>" onfocus="this.blur()"><?=$val['title'] ?></a></li>
            <?endforeach; ?>
        </ul>
        <?endif; ?>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="25">选</td>
                <td width="*">名称/所属主题/分类</td>
                <td width="140">价格/积分情况</td>
                <td width="100">提成比例</td>
                <td width="120">添加时间/最后编辑</td>
                <td width="35">上架</td>
                <td width="35">推荐</td>
                <td width="120">操作</td>
            </tr>
            <?if($total):?>
            <?while($val=$list->fetch_array()):?>
            <input type="hidden" name="packages[<?=$val['id'] ?>][id]" value="<?=$val['id'] ?>">
            <tr>
                <td><input type="checkbox" name="ids[]" value="<?=$val['id'] ?>" /></td>
                <td>
                    <div>套餐编号ID:<?=$val['id'] ?> </div>
                    <div><a href="<?=url("product/pkg_detail/id/$val[id]") ?>" target="_blank"><img class="main_img" src="<?=$val['thumb'] ?>" /><?=$val['name'] ?></a></div>
                    <div>
                        <a href="<?=url("item/detail/id/$val[sid]") ?>" target="_blank"><?=$val['subname'] ?></a>
                       <!--[<a href="javascript:void(0)" class="js_addproduct" >加商品</a>]-->
                    </div>
                    <div><?=display("product:gcategory", "catid/$val[gcatid]") ?></div>
                </td>
                <td>
                    <?if($val['start_time']<=strtotime(date('Y-m-d',$_G['timestamp']))&&$val['end_time']>=strtotime(date('Y-m-d',$_G['timestamp']))):?>
                    <div style="color:red;">促销价:&yen;<?=$val['price'] ?></div>
                    <?endif; ?>
                    <div style="color:green;">销售价:&yen;<?=$val['ori_price'] ?></div>
                </td>
                <td>
                    <div><?=$val['brokerage']*100.0 ?>%</div>
                </td>
                <td>
                    <div><?=date('Y-m-d H:i', $val['create_time']) ?></div>
                    <div><?if($val['update_time']):?><?=date('Y-m-d H:i', $val['update_time']) ?><?else: ?><?=date('Y-m-d H:i', $val['update_time']) ?><?endif ?></div>
                </td>
                <td><input type="checkbox" name="packages[<?=$val['id'] ?>][onshelf]" value="1"<?
					if ($val['onshelf'])
						echo ' checked="checked"';
				?> /></td>
                <td><input type="checkbox" name="packages[<?=$val['id'] ?>][finer]" value="1"<?
					if ($val['finer'])
						echo ' checked="checked"';
				?> /></td>
                <td>
                    <a href="<?=cpurl($module, $act, 'edit', array('id' => $val['id'])) ?>">编辑</a>
                    <a href="<?=cpurl($module, 'product_order', 'list', array('pid' => $val['pid'], 'dosubmit' => 'yes')) ?>">订单</a>
                </td>
            </tr>
            <?endwhile; ?>
            <tr class="altbg1">
                <td colspan="3"><button type="button" onclick="checkbox_checked('ids[]');" class="btn2">全选</button></td>
                <td colspan="6" style="text-align:right;"><?=$multipage ?></td>
            </tr>
            <?else: ?>
            <tr><td colspan="8">暂无信息。</td></tr>
            <?endif ?>
        </table>
    </div>
	<?if($total):?>
	<center>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="update" />
		<button type="button" class="btn" onclick="easy_submit('myform','delete','ids[]')">删除所选</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','update',null)">更新修改</button>
	</center>
	<?endif; ?>
<?=form_end() ?>


</div>



<script type="text/javascript">function create_products() {

}

$(function() {

			$(".choose_product").hide();
});
	</script>


