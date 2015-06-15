/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
mo.comment = {};
mo.comment.page = {};
mo.comment.reply = {};

$(document).ready(function() {
    mo.comment.page_init();
});

//页面加载后进行JS调整
mo.comment.page_init = function() {
    if(mo.comment.page.name == 'load_comment') {
        mo.comment.page.load_comment();
    }
}

mo.comment.page.load_comment = function() {
	var v = mo.comment.page;
	mo.comment.get_list(v.idtype, v.id, 1);
}

//AJAX获取评论数据
mo.comment.get_list = function(idtype,id,page,endpage) {
    if(!is_id(id,'评论对象ID')) return;
	endpage = !endpage ? 0 : 1;
	if(!page) page = 1;
	$.get(Url('comment/list'), 
		{'idtype':idtype,'id':id,'page':page,'endpage':endpage,'in_ajax':1,'rand':getRandom()},
		function(result) {
			if(result == null) {
				alert('信息读取失败，可能网络忙碌，请稍后尝试。');
			} else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
				myAlert(result);
			} else {
				$('#commentlist').html(result);
				if(endpage) {
					window.location.hash="commentend";
				}
			}
		}
	);
	$('#comment_button').disabled = true;
	return false;
}

//删除评论
mo.comment.del = function(cid) {
	if(!is_id(cid,'评论ID')) return;
	$.post(Url('comment/member/ac/m_comment/op/delete'), { "cid":cid, "in_ajax":1 }, function(result) {
		if(is_message(result)) {
			myAlert(result);
		} else if(result) {
			json = parse_json(result);
			if(typeof(json) != 'object') {
				alert('操作请求返回错误。');
			}
			if(json.code=='200') {
				var info = json.info;
				if(info.del_num > 0) {
					//alert('#comment_' + info.del_cid);
					$('#comment_' + info.del_cid).remove();
				} else {
					alert('没有数据被删除。');
				}
			} else {
				alert(json.msg);
			}
		}
	});
}

//重置评论框表单控件
mo.comment.reset_form = function() {
	if($('#frm_comment [name=grade]')[0]) {
		$('#frm_comment [name=grade]').val(4);
		//$('#frm_comment [name=grade]').get(4).checked = true;
	}
	$('#frm_comment [name="content"]').val('');
	$('#frm_comment [name="seccode"]').val('');
	$('#seccode').empty().html();
}

//提交评论后处理事件
mo.comment.post_after = function(param_str) {
	var param = param_str.split('-');
	var idtype = param[0];
	var id = param[1];
	var v = mo.comment.page;
	mo.comment.get_list(v.idtype, v.id, 1, true);
	//提交按钮定时禁用
	var comment_time = mo.comment.page.comment_interval;
	var time = window.setInterval(function(){
		comment_time = comment_time - 1;
		if(comment_time < 1) {
			$('#comment_button').text('发表评论').attr('disabled','');
			comment_time = 0;
			window.clearInterval(time);
		} else {
			$('#comment_button').text('发表评论('+comment_time+')').attr('disabled','disabled');
		}
	}, 1000);
	//c重置发布表单
	mo.comment.reset_form();
}

//回应评论框
mo.comment.reply.show = function(cid) {
	if(!is_id(cid,'评论ID')) return;
	var form_id = 'reply_form_' + cid;
	var form_box = $('#comment_form').find('.J_reply_comment_form').clone();
	$('#reply_comment_form_'+cid).empty().append(form_box);
	var form = form_box.find('form');
	form.attr('id', form_id);
	form.find('[name=reply_submit_btn]').click(function() {
		var v = mo.comment.page;
		ajaxPost(form_id, cid, 'mo.comment.reply.post_after');
	});
	form.find('[name=reply_cancel_btn]').click(function() {
		$('#reply_comment_form_'+cid).empty();
	});
	if(form.find('[name=seccode]')[0]) {
		form.find('.J_seccode_d').attr('id','reply_seccode_'+cid)
		form.find('[name=seccode]').focus(function() {
			show_seccode('reply_seccode_'+cid);
		});
		//show_seccode('reply_seccode_'+cid);
	}
	form.find('.J_charlen').attr('id','reply_comment_content_'+cid);
	form.find('[name=content]').keyup(function() {
		record_charlen(this, mo.comment.page.content_max,'reply_comment_content_'+cid);
	});
	form.find('[name=reply_cid]').val(cid);
	form_box.show();
}

//回应之后
mo.comment.reply.post_after = function(cid) {
	var v = mo.comment.page;
	$('#reply_comment_form_'+cid).empty();
	mo.comment.get_list(v.idtype, v.id, 1, true);
}

//获取未显示的回应内容
mo.comment.reply.get = function(root_cid) {
	if(!is_id(root_cid,'评论ID')) return;
	var num = $('#reply_root_'+root_cid).find('li').length - 1;
	var data = $('#reply_root_'+root_cid).attr('data');
	data = jQuery.parseJSON(data);
	if(data.total > num) {
		$.post(Url('comment/list/op/reply_get'), 
			{ 'root_cid':root_cid, 'start':num, 'in_ajax':1 }, 
			function(result) {
			if(is_message(result)) {
				myAlert(result);
			} else if(result) {
				if(result.indexOf("|\t|\t|\t|\t|")) {
					ss = result.split("|\t|\t|\t|\t|");
				}
				if(ss) {
					json = parse_json(ss[0]);
					if(typeof(json) == 'object') {
						if(json.code == '200') {
							mo.comment.reply.insert(root_cid, json.info, ss[1]);
							return;
						}
					} else {
						alert(json.code);
						data = '';
					}
				}
			}
			if(!data) alert('获取数据失败！');
		});
	}
}

//加入新加载的回应内容
mo.comment.reply.insert = function(cid, info, html) {
	var hideli = $('#reply_root_'+cid).find('li.reply_data_hide');
	if(info.num > 0) {
		if(info.order=='asc') {
			hideli.after(html);
		} else {
			hideli.before(html);
		}
	}
	if(info.r_num == 0) {
		hideli.remove();
	} else {
		$('#reply_hide_'+cid).text(info.r_num);
	}
}