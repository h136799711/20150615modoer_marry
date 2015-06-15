<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<style type="text/css">
.img img { max-width:80px; max-height:60px; border:1px solid #ccc; padding:1px; 
    _width:expression(this.width > 80 ? 80 : true); _height:expression(this.height > 60 ? 60 : true); }
</style>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">产品筛选</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" class="altbg1">产品分类</td>
                <td width="300">
                    <select name="gcatid">
                        <option value="">全部</option>
                        <?=form_product_category_main($_GET['gcatid'])?>
                    </select>
                </td>
                <td width="100" class="altbg1">所属地区</td>
                <td width="*">
                    <?if($admin->is_founder):?>
                    <select name="city_id">
                        <option value="">不限</option>
                        <?=form_city($_GET['city_id'], TRUE)?>
                    </select>
                    <?else:?>
                    <?=$_CITY['name']?>
                    <?endif;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1">产品PID</td>
                <td><input type="text" name="pid" class="txtbox3" value="<?=$_GET['pid']?>" /></td>
                <td class="altbg1">主题SID</td>
                <td><input type="text" name="sid" class="txtbox3" value="<?=$_GET['sid']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">产品标题</td>
                <td><input type="text" name="subject" class="txtbox3" value="<?=$_GET['subject']?>" /></td>
                <td class="altbg1">产品条形码</td>
                <td><input type="text" name="shape_code" class="txtbox3" value="<?=$_GET['shape_code']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">发布时间</td>
                <td colspan="3"><input type="text" name="starttime" class="txtbox3" value="<?=$_GET['starttime']?>" />&nbsp;~&nbsp;<input type="text" name="endtime" class="txtbox3" value="<?=$_GET['endtime']?>" />&nbsp;(YYYY-MM-DD)</td>
            </tr>
            <tr>
                <td class="altbg1">结果排序</td>
                <td colspan="3">
                <select name="orderby">
                    <option value="pid"<?=$_GET['orderby']=='cid'?' selected="selected"':''?>>默认排序</option>
                    <option value="dateline"<?=$_GET['orderby']=='dateline'?' selected="selected"':''?>>发布时间</option>
                    <option value="pageview"<?=$_GET['orderby']=='pageview'?' selected="selected"':''?>>浏览人气</option>
                    <option value="comments"<?=$_GET['orderby']=='comments'?' selected="selected"':''?>>评论数量</option>
                </select>&nbsp;
                <select name="ordersc">
                    <option value="DESC"<?=$_GET['ordersc']=='DESC'?' selected="selected"':''?>>递减</option>
                    <option value="ASC"<?=$_GET['ordersc']=='ASC'?' selected="selected"':''?>>递增</option>
                </select>&nbsp;
                <select name="offset">
                    <option value="20"<?=$_GET['offset']=='20'?' selected="selected"':''?>>每页显示20个</option>
                    <option value="50"<?=$_GET['offset']=='50'?' selected="selected"':''?>>每页显示50个</option>
                    <option value="100"<?=$_GET['offset']=='100'?' selected="selected"':''?>>每页显示100个</option>
                </select>&nbsp;
                <input type="checkbox" name="promote" value="1" <?if($_GET['promote'])echo' checked="checked"'?>/>促销
                <button type="submit" value="yes" name="dosubmit" class="btn2">筛选</button>&nbsp;
                <button type="button" onclick="window.location='<?=cpurl($module,$act,'add')?>';" class="btn2">添加产品</button>
                </td>
            </tr>
        </table>
    </div>
</form>
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle">产品管理</div>
        <?if($edit_links):?>
        <ul class="cptab">
            <?foreach($edit_links as $val):?>
            <li <?if($val['flag']=='product:list')echo' class="selected"';?>><a href="<?=$val['url']?>" onfocus="this.blur()"><?=$val['title']?></a></li>
            <?endforeach;?>
        </ul>
        <?endif;?>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="25">选</td>
                <td width="75">图片</td>
                <td width="*">名称/所属主题/分类</td>
                <td width="140">价格/积分情况</td>
                <td width="140">统计信息</td>
                <td width="120">添加时间/最后编辑</td>
                <td width="35">上架</td>
                <td width="35">推荐</td>
                <td width="120">操作</td>
            </tr>
            <?if($total):?>
            <?while($val=$list->fetch_array()):?>
            <input type="hidden" name="products[<?=$val['pid']?>][pid]" value="<?=$val['pid']?>">
            <tr>
                <td><input type="checkbox" name="pids[]" value="<?=$val['pid']?>" /></td>
                <td class="img"><img src="<?if($val['thumb']):?><?=$val['thumb']?><?else:?>static/images/s_noimg.gif<?endif;?>" /></td>
                <td>
                    <div>商品编号PID:<?=$val['pid']?><?if($val['p_style']=='2'):?><span class="font_2">虚拟物品</span><?endif;?></div>
                    <div><a href="<?=url("product/detail/pid/$val[pid]")?>" target="_blank"><?=$val['subject']?></a></div>
                    <div>
                        <a href="<?=url("item/detail/id/$val[sid]")?>" target="_blank"><?=$val['name'].$val['subname']?></a>
                        [<a href="<?=url_replace(cpurl($module,$act,'list',$_GET),'sid',$val['sid'])?>">筛</a>][<a href="<?=cpurl($module,$act,'add',array('sid'=>$val['sid']))?>">加</a>]
                    </div>
                    <div><?=display("product:gcategory", "catid/$val[gcatid]")?></div>
                </td>
                <td>
                    <?if($val['promote_start']<=strtotime(date('Y-m-d',$_G['timestamp']))&&$val['promote_end']>=strtotime(date('Y-m-d',$_G['timestamp']))):?>
                    <div style="color:red;">促销价:&yen;<?=$val['promote']?></div>
                    <?endif;?>
                    <div style="color:green;">销售价:&yen;<?=$val['price']?></div>
                    <div>赠送积分:<?=$val['giveintegral']?></div>
                    <div>可用积分:<?=$val['integral']?></div>
                </td>
                <td>
                    <div>销售：<?=$val['sales']?> 件</div>
                    <div>库存：<?=$val['stock']?> 件</div>
                    <div>浏览：<?=$val['pageview']?> 次</div>
                    <div>评论：<a href="<?=cpurl('comment','comment_list','list',array('idtype'=>'product','id'=>$val['pid'],'dosubmit'=>'yes'))?>"><?=$val['comments']?></a> 条</div>
                </td>
                <td>
                    <div><?=date('Y-m-d H:i',$val['dateline'])?></div>
                    <div><?if($val['last_update']):?><?=date('Y-m-d H:i',$val['last_update'])?><?else:?><?=date('Y-m-d H:i',$val['dateline'])?><?endif?></div>
                </td>
                <td><input type="checkbox" name="products[<?=$val['pid']?>][is_on_sale]" value="1"<?if($val['is_on_sale'])echo' checked="checked"';?> /></td>
                <td><input type="checkbox" name="products[<?=$val['pid']?>][finer]" value="1"<?if($val['finer'])echo' checked="checked"';?> /></td>
                <td>
                    <a href="<?=cpurl($module,$act,'edit',array('pid'=>$val['pid']))?>">编辑</a>
                    <a href="<?=cpurl($module,'product_order','list',array('pid'=>$val['pid'],'dosubmit'=>'yes'))?>">订单</a>
                </td>
            </tr>
            <?endwhile;?>
            <tr class="altbg1">
                <td colspan="3"><button type="button" onclick="checkbox_checked('pids[]');" class="btn2">全选</button></td>
                <td colspan="6" style="text-align:right;"><?=$multipage?></td>
            </tr>
            <?else:?>
            <tr><td colspan="8">暂无信息。</td></tr>
            <?endif?>
        </table>
    </div>
	<?if($total):?>
	<center>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="update" />
		<button type="button" class="btn" onclick="easy_submit('myform','delete','pids[]')">删除所选</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','update',null)">更新修改</button>
	</center>
	<?endif;?>
<?=form_end()?>
</div>