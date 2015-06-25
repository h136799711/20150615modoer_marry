<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="./static/javascript/validator.js"></script>
<script type="text/javascript" src="./static/javascript/item.js"></script>
<script type="text/javascript" src="./static/javascript/product.js"></script>
<script type="text/javascript" src="./static/javascript/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="./data/cachefiles/product_gcategory.js?r=<?=$MOD['jscache_flag'] ?>"></script>
<style type="text/css">.img img {
	max-width: 80px;
	max-height: 60px;
	border: 1px solid #ccc;
	padding: 1px;
	_width: expression(this.width > 80 ? 80: true);
	_height: expression(this.height > 60 ? 60: true);
}

.added_ele
{
	float:left; 
  width: 86px;
  text-align: center;
}
.added_ele .js_del{
	  color: #5C54D7;
  cursor: pointer;
  margin-top: 5px;
  display:block;
}
.added_ele img,
.choose_product .name img{
	max-width: 80px;
	max-height: 60px;
	border: 1px solid #ccc;
	padding: 1px;
	_width: expression(this.width > 80 ? 80: true);
	_height: expression(this.height > 60 ? 60: true);
}
.choose_product_wrp {
	position: absolute;
	display: none;
	background: rgba(124, 108, 108, 0.51);
	z-index: 123;
	top: 0;
	left: 0;
	margin-left: 0;
	width: 100%;
	height: 100%;
}
.choose_product_wrp .choose_product{
	
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
.choose_product table{
	margin-bottom: 0px;
	border: 0px;
}
.choose_product .btn_control,
.choose_product .filter{
	background: #FFF;
  	text-align: center;
	padding: 5px 0px;
}
.choose_product .filter{
	font-size: 14px;
	font-weight: 800;
	text-align: left;
}
.p_bar{
  margin: 12px 0;
}
.loading{
  	height: 20px;
  	width: 20px;
  	border: 5px solid #0A0A0A;
  	border-radius: 50%;
  	border-style: dotted;
  	animation: rotate  1.5s infinite;
	animation-timing-function: cubier-bezier(.96,.61,.72,1.03) ;
	-moz-animation: rotate  1.5s infinite;	/* Firefox */
	-moz-animation-timing-function: cubier-bezier(.96,.61,.72,1.03) ;
	-webkit-animation: rotate  1.5s infinite;	/* Safari 和 Chrome */
	-webkit-animation-timing-function: cubier-bezier(.96,.61,.72,1.03) ;
	-o-animation: rotate  1.5s infinite;
	-o-animation-timing-function: cubier-bezier(.96,.61,.72,1.03) ;
	
}
@-webkit-keyframes rotate{
	0%{
		transform: rotateZ(0deg);
	}
	50%{
		transform: rotateZ(180deg);
	}
	100%{
		transform: rotateZ(360deg);
	}
}
@keyframes rotate{
	0%{
		transform: rotateZ(0deg);
	}
	50%{
		transform: rotateZ(180deg);
	}
	100%{
		transform: rotateZ(360deg);
	}
}
</style>
<script type="text/javascript">

//ajax提交后跳转

function product_save_succeed(data) {
		jslocation("<?=cpurl($module, $act, 'list', array('sid' => $sid)) ?>");
}
//提交按钮
function post_submit() {
    if($("#promote ").val()>0){
        if($("#promote_start ").val()=="" || $("#promote_end ").val()==""){
            alert("您开启了本商品的促销价， 请填写促销日期！ ");
            if($("#promote_start ").val()=="") 
           		 $("#promote_start ").focus();
            if($("#promote_end ").val()=="") 
                  $("#promote_end ").focus();
            return false;
        }
    }
    
    $("textarea").each(function(){
        if($(this).attr('usekd')=='YES') {
            eval('kd_'+$(this).attr('id')+'.sync()');
        }
    });
    ajaxPost('postform', '', 'product_save_succeed');
}

//产品类型
var p_style = function() {

    var change = function(sort) {
        $('tr[data-sort]').each(function() {
            var _this = $(this);
            if(_this.data('sort') == sort) {
                _this.show();
            } else {
                _this.hide();
            }
        });
    }

    $('[name="p_style "]').click(function(event) {
        change(this.value);
    });

    change($('[name="p_style "]:checked').val());

}
$("document ").ready(function(){
    $('[name=usd_subject_city_id]').click(function(event) {
        $('#city_id').attr('disabled',$(this).attr('checked'));
    });
    $('#product_gcategory').mselect({
        'data':_product_cate,'name':'gcatid','value':'<?=(int)$gcatid ?>',
        onchange:function(obj){
            get_product_attrs();
        }
    });
    p_style();
});</script>
<style type="text/css">.uploadimgs .upimg {
	float: left;
	text-align: center;
	width: 90px;
	height: 90px;
}
.uploadimgs .upimg img {
	display: block;
	max-width: 80px;
	max-height: 80px;
	padding: 1px;
	border: 1px solid #ccc;
	_width: expression(this.width > 80 ? 80: true);
	_height: expression(this.height > 80 ? 80: true);
}
.uploadimgs .imgthumb img {
	border: 2px solid red;
}</style>
<div id="body">
<form method="post" name="myform" id="postform" action="<?=cpurl($module, $act, 'edit_save') ?>" enctype="multipart/form-data" onsubmit="return validator(this);">
    <div class="space">
        <div class="subtitle">添加套餐</div>
        <table width="95%" cellspacing="0" cellpadding="0" class="maintable">
            <tr>
                <td class="altbg1" align="right"><span class="font_1">*</span>所属城市：</td>
                <td>
                    <?php $_G['loader'] -> helper('form'); ?>
                    <select name="city_id" id="city_id">
                        <?=form_city($detail['city_id'], true) ?>
                    </select>
                </td>
            </tr>
              <tr id="tr_subject">
                <td width="100" class="altbg1" align="right"><span class="font_1">*</span>所属主题：</td>
                <td width="*">
					<div id="subject_search">
                        <?if($subject):?>
                        <input type="hidden" name="sid" value="<?=$sid ?>">
                        <a href="<?=url("item/detail/id/$sid") ?>" terget="_blank"><?=$subject['name'] . ($subject['subname'] ? "($subject[subname])" : '') ?></a>
                        <?endif; ?>
                    </div>
                    <?if(!$subject):?>
					<script type="text/javascript">$('#subject_search').item_subject_search({
			catid: 0,
			input_class: 'txtbox2',
			btn_class: 'btn2',
			result_css: 'item_search_result',
			//location:"<?=cpurl($module, $act, 'add', array('sid' => '_SID_')) ?>",
							hide_keyword:true
						});
					</script>
                    <?endif; ?>
				</td>
            </tr>
            <tr>
                <td class="altbg1" align="right"><span class="font_1">*</span>套餐名称：</td>
                <td width="*"><input type="text" name="name" class="txtbox" class="txtbox" value="<?=$detail['name'] ?>" validator="{'empty':'N','errmsg':'请填写套餐名称。'}" /></td>
            </tr>
            
            <tr>
                <td class="altbg1" align="right"><span class="font_1">*</span>套餐价格：</td>
                <td><input type="text" name="ori_price" class="txtbox3" value="<?=$detail['ori_price'] ?>" validator="{'empty':'N','errmsg':'未填写套餐价格。'}" /> 元</td>
            </tr>
            
            <!--<tr>
                <td class="altbg1" align="right">会员价格：</td>
                <td>
                    <?foreach($usergroup as $key => $val):?>
                    <?if($val['grouptype']=="system") continue;if($op=='edit'){$arrkeys=array_keys($arruser,$key);$arrkey=$arrkeys[0];}?>
                    <label for="user_price_<?=$key?>"><?=$val['groupname']?></label> <input type="text" name="user_price[]" class="txtbox5" id="user_price_<?=$key?>" value="<?=$arrkey?$arruserprice[$arrkey]:'-1'?>" onkeyup="if(parseInt(this.value)<-1){this.value='-1';};" /> 元
                    <input type="hidden" name="usergroup[]" id="usergroup_<?=$key?>" value="<?=$val['groupid']?>" />
                    <?endforeach;?><br /><span class="font_2">会员折扣价格为-1时表示会员价格按默认价格计算，你也可以为每个会员组指定一个价格。</span>
                </td>
            </tr>-->
            
            <tr>
                <td width="100" class="altbg1" align="right">促销价：</td>
                <td width="*">
                    <input type="text" name="price" id="promote" class="txtbox4" value="<?=$detail['price'] > 0 ? $detail['price'] : '' ?>" /> 元，
                    有效期：
                    <input name="start_time" id="promote_start" type="text" class="txtbox4" readonly="true" value="<?if($detail['start_time']):?><?=date('Y-m-d', $detail['start_time']) ?><?endif; ?>" onfocus="WdatePicker({doubleCalendar:true,dateFmt:'yyyy-MM-dd'})" />
                     ~ 
                    <input name="end_time" id="promote_end" type="text" class="txtbox4" readonly="true" value="<?if($detail['end_time']):?><?=date('Y-m-d', $detail['end_time']) ?><?endif; ?>" onfocus="WdatePicker({doubleCalendar:true,dateFmt:'yyyy-MM-dd'})" />
                </td>
            </tr>
            
             <tr>
                <td align="right" class="altbg1">套餐封面：</td>
                <td class="uploadimgs">  
                    <?php 
                        if($detail['thumb']) $thumb_key = str_replace('thumb_','',pathinfo($detail['thumb'], PATHINFO_FILENAME));
                        $pictures = is_serialized($detail['pictures']) ? unserialize($detail['pictures']) : array();
                    ?>
                    <input type="hidden" name="thumb" value="<?=$thumb_key?>">
                    <div id="topic_images_foo" style="margin-bottom:5px;">
                        <button type="button" class="btn2" onclick="product_upimg('topic_content','<?=$MOD['upimages']?>');">上传图片</button>
                    </div>
                    <?php foreach($pictures as $key => $val):?>
                    <div class="upimg<?=$key==$thumb_key?' imgthumb':''?>" id="upimg_<?=$key?>">
                        <img src="<?=URLROOT?>/<?=$val?>" />
                        <input type="hidden" name="pictures[<?=$key?>]" value="<?=$val?>" />
                        <a href="javascript:void(0);" onclick="product_setthumb('<?=$key?>');return false;">设为封面</a>
                        <a href="javascript:void(0);" onclick="product_delimg('<?=$key?>');return false;">删除</a>
                    </div>
                    <?php endforeach;?>
                </td>
            </tr>
            
            <tr>
                <td class="altbg1" align="right"><span class="font_1">*</span>关联产品：</td>
                <td width="*">
                		<a href="javascript:void(0)" class="js_join_product" >关联产品</a>
                		<input type="hidden" name="pids" id="pids" value="<?= $detail['pids'] ?>" />
                		<div class="added_products">
                			<?php foreach($detail['_products'] as $vo) {?>
                				<div class='added_ele added_<?= $vo['pid'] ?>'>
                					<img src='<?= $vo['thumb'] ?>' alt='' /> 
                					<i class='js_del' data-pid="<?= $vo['pid'] ?>" >删除</i> 
                				</div>
                			<?php } ?>
                		</div>
                </td>
            </tr>	
            <tr>
                <td class="altbg1" align="right" valign="top">提成比例(0~1之间)：</td>
                <td><input name="brokerage" style="width:120px;" value="<?=$vo['brokerage'] ?>" />
                    <div class="font_2">0.1表示提成10%</div></td>
            </tr>
            <tr>
                <td class="altbg1" align="right" valign="top">简单介绍：</td>
                <td><textarea name="desc" style="width:99%;height:40px;"><?=$detail['desc'] ?></textarea></td>
            </tr>
            
            <tr >
                <td class="altbg1" align="right" valign="top">筛选属性：</td>
                <td>
                    <textarea name="tags" style="width:425px;height:50px;"><?=$detail['tags'] ?></textarea>
                    <div class="font_2">一行一条筛选属性。格式：属性名=属性值1,属性值2,属性值3&nbsp;&nbsp;例如：颜色=红色,蓝色,白色,黑色</div>
                </td>
            </tr>
            <tr>
                <td class="altbg1" align="right">上架：</td>
                <td><?=form_bool('onshelf', isset($detail['onshelf']) ? $detail['onshelf'] : 1) ?></td>
            </tr>
        </table>

        
        
        <center>
            <input type="hidden" name="id" value="<?= $detail['id'] ?>" />
            <input type="hidden" name="do" value="<?=$op ?>" />
            <input type="hidden" name="forward" value="<?=get_forward() ?>" />
            <?if(DEBUG):?>
            <button type="submit" class="btn" name="dosubmit" value="yes">DEBUG提交</button>
            <?endif; ?>
            <button type="button" class="btn" onclick="post_submit();">提交</button>
            <button type="button" class="btn" onclick="jslocation('<?=cpurl($module, $act, 'list') ?>');">返回</button>
        </center>
    </div>
</form>
</div>


<div class="choose_product_wrp" >
	
	<div class="choose_product" >
		<div class="loading"  >
						
		</div>
		<div class="filter">
			产品名称: <input type="text" name="pname" class="pname" style="width: 100px;" /> <button type="button" class="btn js_search_product">搜索</button>
		</div>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
			<thead>
		        <tr class="altbg1">
		            <td width="25">编号ID</td>
		            <td width="*">名称</td>
		            <td width="140">价格</td>
		            <td width="120">操作</td>
		        </tr>
				
			</thead>	
			
	        <tbody style="" class="js_data_products">
	        		
	        </tbody>
	    </table>
	    <div class="btn_control">
	    		<button type="button" class="btn js_added" >确定</button>
	    		<button type="button" class="btn js_cancel"  >取消</button>
	    </div>
	    <div>
	    		<?= 	($products['multipage']); ?>
	    </div>
	</div>
</div>


<script type="text/javascript">

function remove_product(pid){
	
	console.log("remove",pid);
	var pid_list = $("#pids").val();
	if(pid_list.indexOf(pid+",") > -1){
		
		pid_list = pid_list.replace(pid+",","");		
		$("#pids").val(pid_list);		
		$("#postform .added_products .added_"+pid).remove();
	}
}

function add_product(ele){
	var $cont = $("#postform .added_products");
	var p = $("<div class='added_ele added_"+ele['pid']+"'><img src='"+ ele['img'] +"' alt='' /> <i class='js_del' data-pid="+ele['pid']+">删除</i> </div>");
	$cont.append(p);
}

function bind_btn_control(){
	
	$("#postform .added_products").click(function(ev){
		console.log($(ev.target).hasClass("js_del"));
		if($(ev.target).hasClass("js_del")){			
			remove_product($(ev.target).attr("data-pid"));
		}
			
	});
	
	$(".choose_product .js_cancel").click(function(){
		$('.choose_product_wrp').hide();
	})
	$(".choose_product .js_added").click(function(){
		var added  =   new Array();
		$(".choose_product .js_data_products input[type='checkbox']:checked").each(function(index,item){
//			console.log(index,item);
			var ele = [];
			ele['pid'] = $(item).val();
			ele['img'] = $(item).parents("tr").find("td.name img").attr("src");
			ele['name'] = $(item).parents("tr").find("td.name").text();
			array_push(added,ele);
			var pids = $("#pids").val();
			
			if(pids.indexOf(ele['pid']+",") == -1){
				$("#pids").val(pids+ele['pid']+",");		
				add_product(ele);			
			}
			
			
		});
		console.log(added);	
		$(".choose_product_wrp").hide();
		
	});
	
}


function append_product(cont,product){
	var $tr = $("<tr></tr>");
	cont.append($tr);
	console.log(product);
	var td_list = "<td>{id}</td><td class='name'><img src='{imgsrc}' alt='{name}' />{name}</td><td>{price}</td><td><label><input type='checkbox' name='add_pids[]' value='{id}' />加入套餐</label></td>";
	td_list = td_list.replace(new RegExp("{id}","g"),product.pid).replace(new RegExp("{imgsrc}","g"),product.picture).replace(new RegExp("{name}","g"),product.subject).replace('{price}',product.price);
	$tr.append($(td_list));
}
function create_products(data) {
	
	var cont = $(".choose_product .js_data_products");
	cont.empty();
	if(!data.list){
		cont.text("无相应数据!");
		//无数据
		return ;
	}
	console.log("create_products",data);
	for(var i=0;i<data.list.length;i++){
		append_product(cont,data.list[i]);
	}
	var page = $(data.page);
	cont.append(page);
	page.find("a").each(function(ind,ele){
		
		$(ele).attr("data-href",$(ele).attr("href"));
		$(ele).attr("href","javascript:void(0)");
		
	});
}
function loading(show){
	if(show){
		//TODO: 显示载入中
		$(".choose_product .loading").show();
	}else{
		//TODO: 隐藏载入中
		$(".choose_product .loading").hide();
		
	}
}

$(function() {
	
	$(".choose_product .js_search_product").click(function(){
		var pname = $(".choose_product input.pname").val();
		$.ajax({
			url: "<?=cpurl($module, $act, 'list_product') ?>",
			data:{'in_ajax':1,'pname':pname},
			type:"post",
			dataType:"json",
			beforeSend:function(){
				loading(true);
				console.log("beforeSend");
			},
			success:function(data){
				if(data.status){
					create_products(data.info);
				}else{
					alert(data.info);
				}
				loading(false);
			}
		});//end of ajax
		
	})
	$(".choose_product .js_data_products").click(function(ev){
		
		if($(ev.target).hasClass("p_num") || $(ev.target).hasClass("p_redirect")){
			var pname = $(".choose_product input.pname").val();
			console.log("p_num");
			$.ajax({
				url: $(ev.target).attr("data-href"),
				data:{'in_ajax':1,'pname':pname},
				type:"post",
				dataType:"json",
				beforeSend:function(){
					loading(true);
					console.log("beforeSend");
				},
				success:function(data){
					if(data.status){
						create_products(data.info);
					}else{
						alert(data.info);
					}
					loading(false);
				}
			});//end of ajax
			ev.preventDefault();
			ev.stopPropagation();
		}
		
	});
	
	$(".js_join_product").click(function() {
		
		$(".choose_product_wrp").show();
		
		$.ajax({
			url: "<?=cpurl($module, $act, 'list_product') ?>",
			data:{'in_ajax':1},
			type:"post",
			dataType:"json",
			beforeSend:function(){
				loading(true);
				console.log("beforeSend");
			},
			success:function(data){
				if(data.status){
					create_products(data.info);
				}else{
					alert(data.info);
				}
				loading(false);
			}
		});//end of ajax
	
	})
	
//	$(".js_join_product").click();
	bind_btn_control();
})
	</script>