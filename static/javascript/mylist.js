
mo.mylist = {};
mo.mylist.page = {};
mo.mylist.item = {};
mo.mylist.favorite = {};
mo.mylist.flower = {};
mo.mylist.respond = {};

$(document).ready(function() {
    mo.mylist.page_init();
});

//页面加载后进行JS调整
mo.mylist.page_init = function() {
    if(mo.mylist.page.name == 'index') {
        mo.mylist.page.index();
    } else if(mo.mylist.page.name == 'detail') {
        mo.mylist.page.detail();
    }
}

//榜单首页初始化
mo.mylist.page.index = function() {
    //赠送鲜花
    $('.J_flowers').attr('href','javascript:').click(function() {
        var mylist_id = $(this).attr('mylist_id');
        $(this).find('span').attr('id','flowers_'+mylist_id);
        mo.mylist.flower.add(mylist_id);
    });
    //加入收藏
    $('.J_favorites').attr('href','javascript:').click(function() {
        var mylist_id = $(this).attr('mylist_id');
        $(this).find('span').attr('id','favorites_'+mylist_id);
        mo.mylist.favorite.add(mylist_id);
    });
    //搜索框
    var dx = '输入榜单标题';
    $('[name="k"]').val('输入榜单标题').css({'color':'#808080'}).focus(function() {
        if($(this).val().trim() == dx) {
            $(this).val('').css({'color':'#000'});
        }
        return true;
    });
}

//榜单内容页初始化
mo.mylist.page.detail = function () {
    var page = {};
    //跳转到指定编辑框
    page.jump_post_excuse = function(id) {
        $('html,body').animate({scrollTop: $("a#item_edit_"+id).offset().top - 30}, 500);
    }
    //设置主题图片的“设为榜单封面”的按钮
    page.set_thumb = function(obj, item_id) {
        var host = obj.find('.J_item_thumb').css({'position':'relative'});
        var use = obj.find('.J_item_thumb_use');
        if(use[0]) {
            var op = $('<div class="J_set_thumb_a"></div>').addClass('set_thumb_a_transparent')
                .css({'position':'absolute','right':0,'bottom':0}).hide();
            var a = $('<a href="javascript:">设为封面</a>').css({'margin':'0 5px'}).click(function() {
                mo.mylist.item.thumb(item_id);
            });
            host.append(op.append(a))
            .mouseover(function() {
                $(this).find('.J_set_thumb_a').show();
            }).mouseout(function() {
                $(this).find('.J_set_thumb_a').hide();
            });
        }
    }
    //初始化榜单主题编辑
    page.item_edit = function(obj, item_id) {
        //编辑
        obj.find('.J_item_edt').each(function(i) {
            $(this).click(function() {
                mo.mylist.item.edit(item_id,'open');
            });
        });
        //删除
        obj.find('.J_item_del').each(function(i) {
            $(this).click(function() {
                //alert(mo.mylist.page.edit_id);
                //alert(mo.mylist.page.detail_url);
                mo.mylist.item.del(item_id,function() {
                    if(mo.mylist.page.detail_url) {
                        jslocation(mo.mylist.page.detail_url);
                    } else {
                        document_reload();
                    }
                });
            });
        });
        //提交
        obj.find('.J_btn_submit').click(function() {
            mo.mylist.item.post_excuse(item_id);
        });
        //取消
        obj.find('.J_btn_cancel').click(function() {
            mo.mylist.item.edit(item_id,'close');
        });
    }
    //查看更多内容（推荐理由显示不下）
    page.item_excuse_more = function(obj, item_id) {
        var p = $('#excuse_'+item_id).find('p');
        var src_h = $('#excuse_'+item_id).height();
        if($('#excuse_'+item_id).height() >= p.height()) return;
        var a = $('<a href="javascript:;">展开内容</a>').addClass('item-excuse-more-a J_excuse_more').click(function() {
            if($(this).text()=='收起') {
                $(this).text('展开内容');
                obj.find('.J_item_thumb').slideDown('fast');
                obj.find('.J_item_opt').show();
                var max_height = obj.height()-obj.find('.J_item_data').height() - 21;
                $('#excuse_'+item_id).height(src_h).css({'max-height':src_h+'px'});
            } else {
                $(this).text('收起');
                obj.find('.J_item_opt').hide();
                obj.find('.J_item_thumb').slideUp('fast');
                var max_height = obj.height()-obj.find('.J_item_data').height() - 21;
                $('#excuse_'+item_id).height(max_height).css({'max-height':max_height+'px'});
            }
        });
        obj.append(a);
    }

    //初始化设置为榜单封面的连接
    var currentcfg = {};
    $('.J_item').each(function(i) {
        var host = $(this);
        var item_id = host.attr('id').replace('item_','');
        //当时榜主访问访问时
        if(mo.mylist.page.myself) {
            page.set_thumb(host, item_id);
            page.item_edit(host, item_id);
        }
        page.item_excuse_more(host, item_id);
        //设置同行2条主题高度相同。避免不同高度引起的Float属性造成的页面错乱
        var height = host.find('.J_item_main').height();
        if(i % 2 == 0) {
            currentcfg.current_height = height;
            currentcfg.obj = host;
        } else {
            var v = currentcfg.current_height - height;
            var newh = 0;
            newh = v > 0 ? currentcfg.obj.height() : host.height();
            currentcfg.obj.height(newh);
            host.height(newh);
        }
    });
    //初始化关注(收藏主题）链接
    $('.J_item_fav').each(function(i) {
        var subject_id = $(this).attr('id').replace('subject_', '');
        $(this).click(function() {
            add_favorite(subject_id);
        });
    });

    //页面加载结束后执行
    window.onload = function() {
        //主题图片高度居中
        $('.J_item_thumb').each(function() {
            var height = $(this).height();
            var img = $(this).find('img');
            var top = Math.round((height - img.height()) /2);
            img.css({"margin-top":top+'px'});
        });
        if(mo.mylist.page.myself) {
            //跳转到连接指定的编辑主题
            if(mo.mylist.page.edit_id) {
                page.jump_post_excuse(mo.mylist.page.edit_id);
                //打开推荐理由编辑框
                mo.mylist.item.edit(mo.mylist.page.edit_id,'open');
            }            
        }

    }
}

//删除榜单
mo.mylist.del = function(mylist_id, callback) {
    if(!is_id(mylist_id,'榜单ID')) return;
    if(!confirm('确定要删除本榜单吗？删除后将所有与本榜单相关的内容将无法恢复。')) return;
    $.post(Url("mylist/member/ac/manage/op/delete"), { 'id':mylist_id, 'in_ajax':1 }, function (data) {
        if(data=='ok') {
        	if(callback) {
        		callback(mylist_id);
        	} else {
	            jslocation(mo.mylist.page.index_url)
        	}
        }else if(is_message(data)) {
            myAlert(data);
        } else {
            alert('操作失败，发生了错误。');
        }
    });
}

//编辑推荐理由
mo.mylist.item.edit = function(item_id,type) {
    var host = $('#item_'+item_id);
    host.find('.J_excuse_more').click();
    if(type=='open') {
        host.find('.J_hide').slideUp('fast');
        host.find('.J_edit').slideDown('fast');
        host.find('.J_excuse_more').hide();
        $('#ta_excuse_'+item_id).focus();
    } else {
        host.find('.J_edit').hide();
        host.find('.J_hide').slideDown('fast');
        host.find('.J_excuse_more').show();
    }
}

//设置榜单封面
mo.mylist.item.thumb = function(item_id) {
    if(!is_id(item_id,'榜单内容ID')) return;
    $.post(Url("mylist/member/ac/item/op/set_thumb"), { 'id':item_id, 'in_ajax':1 }, function (data) {
        if(data=='ok') {
            msgOpen('设置成功!');
        }else if(is_message(data)) {
            myAlert(data);
        } else {
            alert('添加失败，发生了错误。');
        }
    });
}

//加载榜单添加主题的UI
mo.mylist.item.add_ui = function() {
    var html = '<div id="add_mylist_subject_dlg"><div id="subject_search"></div></div>';
    dlgOpen('添加主题', html, 500);
    $('#subject_search').item_subject_search({
        sid_name:'sid',
        input_class:'t_input',
        btn_class:'btn2',
        result_css:'item_search_result',
        myreviewed:true,
        myfavorite:true,
        result_click:mo.mylist.item.add
    });
}

//榜单内添加主题操作
mo.mylist.item.add = function(obj) {
    var id = mo.mylist.page.mylist_id;
    if(!is_id(id,'榜单ID')) return;
    var sid = obj.attr('sid');
    if(!is_numeric(sid)) {
        alert('无效的主题！');
        return;
    }
    $.post(Url("mylist/member/ac/item/op/add"), { 'id':id, 'sid':sid, 'in_ajax':1 }, function (data) {
        if(is_numeric(data)) {
            jslocation(mo.mylist.page.edit_url.replace('_EDITID_', data));
        }else if(is_message(data)) {
            myAlert(data);
        } else {
            alert('主题添加失败，发生了错误。');
        }
    });
}

//榜单内删除主题
mo.mylist.item.del = function(id, callback) {
    if(!confirm('确定要删除？删除后将无法恢复。')) return;
    $.post(Url("mylist/member/ac/item/op/delete"), { 'id':id, 'in_ajax':1 }, function (data) {
        if(data=='OK') {
            msgOpen('删除成功!');
            if(callback) callback(id);
        } else if(is_message(data)) {
            myAlert(data);
        } else {
            alert('主题删除失败，发生了错误。');
        }
    });
}

//提交榜单内主题的推荐理由
mo.mylist.item.post_excuse = function(id) {
    if(!is_id(id,'榜单内容ID')) return;
    var content = $('#ta_excuse_' + id).val();
    $.post(Url("mylist/member/ac/item/op/excuse"), 
        { 'id':id, 'excuse':encodeURIComponent(content), 'in_ajax':1 }, 
        function (data) {
            data = trim(data, String.fromCharCode(0));
            if(is_message(data)) {
                myAlert(data);
            } else if(data) {
                data = jQuery.parseJSON(data); 
                if(data.code=='ok') {
                    $('#ta_excuse_'+id).val(data.message);
                    $('#excuse_'+id).html('<p>推荐理由：'+nl2br(data.message)+'</p>');
                    msgOpen('提交成功！');
                    mo.mylist.item.edit(id,'close');
                } else {
                    alert('添加失败，发生了错误。');
                }
            }
        }
    );
}

//收藏榜单
mo.mylist.favorite.add = function(mylist_id) {
    var fav = this;
    if(!is_id(mylist_id, '榜单ID')) return;
    $.post(Url("mylist/member/ac/manage/op/favorite/do/add"), { 'id':mylist_id, 'in_ajax':1 }, function (data) {
        if(is_numeric(data)) {
            msgOpen('收藏成功!');
            $('#favorites_'+mylist_id).text(data);
        } else if(data == "yet") {
            fav.del(mylist_id);
        } else if(is_message(data)) {
            myAlert(data);
        } else {
            alert('对不起，系统错误。');
        }
    });
}

//删除榜单
mo.mylist.favorite.del = function(mylist_id) {
    if(!confirm('榜单已经收藏，是否要取消收藏？')) return;
    $.post(Url("mylist/member/ac/manage/op/favorite/do/del"), { 'id':mylist_id, 'in_ajax':1 }, function (data) {
        if(is_numeric(data)) {
            msgOpen('已取消榜单收藏!');
            $('#favorites_'+mylist_id).text(data);
        } else if(is_message(data)) {
            myAlert(data);
        } else {
            alert('对不起，系统错误。');
        }
    });
}

//赠送鲜花
mo.mylist.flower.add = function(mylist_id) {
    var flower = this;
    if(!is_id(mylist_id, '榜单ID')) return;
    $.post(Url("mylist/member/ac/manage/op/flower/do/add"), { 'id':mylist_id, 'in_ajax':1 }, function (data) {
        if(is_numeric(data)) {
            msgOpen('赠送成功!');
            $('#flowers_'+mylist_id).text(data);
        } else if(data == "yet") {
            flower.del(mylist_id);
        } else if(is_message(data)) {
            myAlert(data);
        } else {
            alert('对不起，系统错误。');
        }
    });
}

//删除鲜花
mo.mylist.flower.del = function(mylist_id, show_id) {
    if(!confirm('已经赠送过鲜花，是否要取消赠送？')) return;
    $.post(Url("mylist/member/ac/manage/op/flower/do/del"), { 'id':mylist_id, 'in_ajax':1 }, function (data) {
        if(is_numeric(data)) {
            msgOpen('已取消赠送鲜花!');
            $('#flowers_'+mylist_id).text(data);
        } else if(is_message(data)) {
            myAlert(data);
        } else {
            alert('对不起，系统错误。');
        }
    });
}