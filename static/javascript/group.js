
mo.group = {};
mo.group.page = {};

$(document).ready(function() {
    mo.group.page_init();
});

//页面加载后进行JS调整
mo.group.page_init = function() {
    $('.grouplist').find('div.join').each(function() {
        var join = $(this);
        var p = join.parent();
        join.hide();
        p.mouseout(function() {
            join.hide();
        }).mouseover(function() {
            join.show();
        });
    });
}

function group_join(gid) {
    if (!is_numeric(gid)) {
        alert('无效的小组ID');
        return;
    }
    $.post(Url('group/member/ac/group/op/join'), 
    { 'gid':gid, 'in_ajax':1,'empty':getRandom() },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result == 'OK') {
            document_reload();
        } else if(result == 'CHECK') {
            alert('加入申请已提交，请等待组长审核。');
        } else {
            dlgOpen('申请加入', result, 400);
        }
    });
}

function group_join_after(gid,data) {
    //
}

function group_quit(gid,forward) {
    if (!is_numeric(gid)) {
        alert('无效的小组ID'); 
        return;
    }
    if(!window.confirm('您确定要退出小组吗？')) {
        return;
    }
    $.post(Url('group/member/ac/group/op/quit'), 
    { 'gid':gid, 'in_ajax':1,'empty':getRandom() },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result == 'OK') {
            if(forward) {
                jslocation(forward);
            } else {
               document_reload(); 
            }
        } else {
            alert('操作失败。');
        }
    });
}

function group_topic_top(tpid) {
    if (!is_numeric(tpid)) {
        alert('无效的小组ID'); 
        return;
    }
    $.post(Url('group/member/ac/topic/op/top'), 
    { 'tpid':tpid, 'in_ajax':1,'empty':getRandom() },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result == 'OK') {
            document_reload();
        } else {
            alert('操作失败。');
        }
    });
}

function group_topic_digest(tpid) {
    if (!is_numeric(tpid)) {
        alert('无效的小组ID'); 
        return;
    }
    $.post(Url('group/member/ac/topic/op/digest'), 
    { 'tpid':tpid, 'in_ajax':1,'empty':getRandom() },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result == 'OK') {
            document_reload();
        } else {
            alert('操作失败。');
        }
    });
}

function group_topic_close(tpid,op) {
    if (!is_numeric(tpid)) {
        alert('无效的小组ID'); 
        return;
    }
    var operation = !op ? '关闭' : '打开';
    if(!window.confirm('确定要'+operation+'回复功能吗？')) return;
    $.post(Url('group/member/ac/topic/op/close'), 
    { 'tpid':tpid, 'in_ajax':1,'empty':getRandom() },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result == 'OK') {
            document_reload();
        } else {
            alert('操作失败。');
        }
    });
}

function discuss_topic_upimg(id,insert_id) {
    GLOBAL['tmp_insert_id'] = insert_id;
    var html = '<div style="margin:5px 0;"><form id="frm_topicupload" method="post" action="'+Url('modoer/upload/in_ajax/1')+'" enctype="multipart/form-data">';
    html += '<input type="file" name="picture" />&nbsp;';
    html += '<button type="button" class="button">开始上传</button>';
    html += '</form></div>';
    GLOBAL['mdlg_upimg'] = new $.mdialog({'id':'mdlg_upimg', 'title':'上传图片', 'body':html, 'width':360});
    var frm = $('#frm_topicupload');
    frm.find('button').click(function() {
        var file = frm.find('[name="picture"]').val();
        if(!file) {
            alert('未选准备上传的择图片文件.');
            return;
        }
        ajaxPost('frm_topicupload', id, 'discuss_topic_addimg', 1, 'mdlg_upimg', function(data){
            frm.show();
            $('#upload_message').remove();
            myAlert(data.replace('ERROR:',''));
        });
        frm.parent().append('<div id="upload_message" style="margin:10px 0; text-align:center;">正在上传...</div>');
        frm.hide();
    });
}

function discuss_topic_addimg(id,data) {
    if(data.length==0) {
        alert('图片未上传成功。'); return;
    }
    var keyname = basename(data, data.substring(data.lastIndexOf(".")));
    var isert_id = GLOBAL['tmp_insert_id'];
    if(!isert_id) isert_id='topic_images_foo';
    var foo = $('#'+isert_id);
    var imgfoo = $('<div class="upimg"></div>').attr('id','upimg_'+keyname);
    imgfoo.append($('<img></img>').attr('src', urlroot + '/' + data));
    imgfoo.append($('<a href="javascript:void(0);">插入</a>').click(function(){ discuss_topic_insertimg(id, keyname) }));
    imgfoo.append(" | ");
    imgfoo.append($('<a href="javascript:void(0);">删除</a>').click(function(){ discuss_topic_delimg(keyname) }));
    imgfoo.append("<input type=\"hidden\" name=\"pictures[]\" value=\""+data+"\" />");
    foo.append(imgfoo);
}

function discuss_topic_delimg(keyname) {
    $('#upimg_'+keyname).remove();
}

function discuss_topic_insertimg(cid,imgname) {
    var text = "[/img:" + imgname + "/]";
    $('#'+cid).insertAtCaret(text);
}

function group_topic_video(cid) {
    var html = '<div style="margin:5px 0;" id="topic_add_video">';
    html += '<input type="text" name="videurl" size="60" />&nbsp;';
    html += '<button type="button" class="button">确定</button>';
    html += '<span class="font_1" id="video_error_msg"></span>';
    html += '<p><span class="font_3">请输入视频的网站源地址，目前支持的视频网站有土豆、优酷、酷6、腾讯视频、新浪视频。</span></p>';
    html += '</div>';
    GLOBAL['mdlg_upvideo'] = new $.mdialog({'id':'mdlg_upvideo', 'title':'添加视频', 'body':html, 'width':480});
    var foo = $('#topic_add_video');
    foo.find('button').click(function() {
        var videourl = foo.find('[name="videurl"]').val();
        group_parse_videourl(cid,videourl);
    });
}

function group_parse_videourl(cid,url) {
    if(!url) alert('未填写视频播放页面地址。');
    msgOpen('正在解析，请稍后...', 180,80);
    $.post(Url('modoer/ajax/op/getcontent'), {'url':url,'in_ajax':1},
    function(result) {
        msgClose();
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result.match(/http:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&+%]*/ig)) {
            group_topic_insertvideo(cid,result);
            dlgClose('mdlg_upvideo');
        } else {
            alert('操作失败。');
        }
    });
}

function group_topic_insertvideo(cid,videourl) {
    var text = "[/video:" + videourl + "/]";
    $('#'+cid).insertAtCaret(text);
}

var discuss_dialog = null;
function discussion_topic_edit (tpid) {
    if (!is_numeric(tpid)) {
        alert('无效的tpid'); 
		return;
    }
	$.post(Url('group/member/ac/topic/op/edit/tpid/'+tpid), 
	{ 'in_ajax':1,'empty':getRandom() },
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
            dlgOpen('编辑话题', result, 700, 0);
		}
	});
}

function discussion_topic_save(a1,data) {
    if(discuss_dialog) {
        discuss_dialog.close();
    }
    if(is_json(data)) {
        var mymsg = eval('('+data+')'); //JSON转换
        if(mymsg.url) {
            if(document.location==mymsg.url) {
                document_reload();
            } else {
                jslocation(mymsg.url);
            }            
        }
    }
    document_reload();
}

function discussion_topic_delete(tpid) {
    if (!is_numeric(tpid)) {
		alert('无效的tpid'); 
		return;
    }
	if(!confirm('您确定要删除这条话题和回复吗?')) return;
	$.post(Url('group/member/ac/topic/op/delete'), 
	{ 'tpid':tpid,'in_ajax':1,'empty':getRandom() },
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else if(result=='OK') {
            msgOpen('删除完毕！', 200, 60);
		}
	});
}

function discussion_reply_edit (rpid) {
    if (!is_numeric(rpid)) {
        alert('无效的 rpid'); 
		return;
    }
	$.post(Url('group/member/ac/reply/op/edit/rpid/'+rpid), 
	{ 'in_ajax':1,'empty':getRandom() },
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
			dlgOpen('编辑回复', result, 650);
		}
	});
}

function discussion_reply_delete(rpid) {
    if (!is_numeric(rpid)) {
		alert('无效的 rpid'); 
		return;
    }
	if(!confirm('您确定要删除这条信息吗?')) return;
	$.post(Url('group/member/ac/reply/op/delete/rpid/'+rpid), 
	{ 'in_ajax':1,'empty':getRandom() },
	function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else if(result=='OK') {
			msgOpen('删除完毕！', 200, 60);
		}
	});
}

function discussion_reply_reload(rpid) {
    if (!is_numeric(rpid)) return;
	$.post(Url('group/topic/op/reload/rpid/'+rpid), 
	{ 'in_ajax':1,'empty':getRandom() },
	function(result) {
		$('#reply_'+rpid).html(result);
        group_show_video();
	});
}

function discussion_reply_at(cid,username) {
	var at = "[/@" + username + "/]";
	var str = $('#'+cid).val();
	if(str.indexOf(at) >=0) return;
	$('#'+cid).val("[/@" + username + "/]:"+$('#'+cid).val()).focus();
}

function group_topics_subject(sid) {
    if(!is_numeric(sid)) { alert('无效的主题SID'); return false; }
    $.post(Url('group/ajax/op/item_topic'), 
    { 'sid':sid, 'in_ajax':1, 'empty':getRandom() },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            $('#display').html(result);
        }
    });
    $('#subtab li').each(function(i) {
        if(this.id=='tab_group') {
            $(this).addClass('selected');
        } else {
            $(this).removeClass('selected');
        }
    });
    return false;
}

//获取分类内置标签
function group_load_cattags(catid,id) {
    if (!is_numeric(catid)) {
        alert('无效的分类ID'); 
        return;
    }
    $.post(Url('group/ajax/op/loadtags'), 
    { 'catid':catid, 'in_ajax':1,'empty':getRandom() },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result=='') {
            alert('没有可用数据。');
        } else {
            group_parse_cattags(id,result);
        }
    });
}

//解析并显示可选标签
function group_parse_cattags(id,tags) {
    if(!tags) return;
    $('#'+id).html('');
    tags = tags.split('|');
    for (var i = 0; i < tags.length; i++) {
        var a=$("<a></a>").text(tags[i]).attr('href','javascript:').click(function(){
            addtag('tags',tags[0],website.setting.tag_split);
            addtag('tags',$(this).text(),website.setting.tag_split);
        });
        $('#'+id).append(a).append('&nbsp;');
    };
}

//显示帖子内的视频
function group_show_video(width,height) {
    var classname = 'show_video';
    if(!width) width=500;
    if(!height) height=350;
    $('.'+classname).each(function(i){
        var id = $(this).attr('id');
        var json = eval('(' + $(this).attr('params') + ')');
        if(typeof(json)=='undefined') return;
        if(!json.video) return;
        if(!json.width) json.width = width;
        if(!json.height) json.height = height;
        var so = new SWFObject(json.video, id, json.width, json.height, 7, '#FFF');
        so.addParam("allowScriptAccess", "always");
        so.addParam("allowFullScreen", "true");
        so.addParam("wmode", "transparent");
        so.write(id);
        $(this).removeClass(classname).addClass('topic_video');
    });
}