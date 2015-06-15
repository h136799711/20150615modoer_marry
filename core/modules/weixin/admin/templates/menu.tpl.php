<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<style type="text/css">
.form-item{margin-bottom:10px;}
.form-item label{display: block; margin-bottom:5px;}
.form-item input{margin:3px 0; display: block;}
.form-item input.inline{display:inline-block;}
.form-item span.helper{color: #808080;}

.weixin-menu-root {  margin-top:10px; position: relative; }
.weixin-menu-parent { margin-left:20px; position: relative; }

.weixin-menu { border:1px solid #ddd;background:#FFF;position: relative; z-index:10;}
.weixin-menu:hover { background:#F4f4f4; }
.weixin-menu:last-child {border-bottom:1px solid #ddd;}
.weixin-menu:after { content:" "; display:block; height:0; clear:both; visibility:hidden; }
.weixin-menu.th { background:#EEE;}
.weixin-menu.sub { margin-left:20px; margin-top:-1px; z-index:11; }
.weixin-menu > li { float:left; padding:8px 10px 6px; line-height:16px; 
    white-space:nowrap;text-overflow:ellipsis; overflow-x:hidden;}
.weixin-menu > li:last-child { border-left:0; };
.weixin-menu > .listorder { width:80px; }
.weixin-menu > .name { width:180px; }
.weixin-menu > .type { width:80px; }
.weixin-menu > .value { width:300px; }
.weixin-menu > .op { width:230px; }
.weixin-menu > .op > a { margin-right:5px; }

.weixin-menu-parent > .weixin-menu { border-top:0; }
.weixin-menu-parent > .weixin-menu > .name { width:160px; }

#menu_box { margin-bottom:10px; }
#menu_box > .message {
    border:1px solid #DDD;
    padding:30px 10px;
    line-height:18px;
    font-size:16px;
    text-align: center;
    background: #FFF;
    border-top: 0;
}
</style>
<div id="body">
    <div class="space">
        <div class="subtitle">操作提示</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr><td>使用自定义菜单功能，您的微信公众平台号必须通过认证才能使用。</td></tr>
            <tr><td>创建自定义菜单后，由于微信客户端缓存，需要24小时微信客户端才会展现出来。建议测试时可以尝试取消关注公众账号后再次关注，则可以看到创建后的效果。</td></tr>
        </table>
    </div>
    <form method="post" action="<?=cpurl($module,$act)?>&">
        <div class="space">
            <div class="subtitle">自定义菜单管理</div>
            <ul class="weixin-menu th">
                <li class="name">菜单名称</li>
                <li class="type">菜单类型</li>
                <li class="value">类型值</li>
                <li class="op">操作</li>
            </ul>
            <div id="menu_box"></div>
            <center id="operation_button">
                <button type="button" class="btn" id="submit_btn">提交编辑</button>
                <button type="button" class="btn secondary" id="add_root_menu_btn">添加父菜单</button>
            </center>
        </div>
    </form>
</div>
<script type="text/javascript">

var _menu_num_limit = {
    root:3,
    sub:5
}

var _lang = {
    form_dialog_title:"菜单添加/修改",
    form_root_menu_limit: '一级菜单只能添加[num]个。',
    form_sub_menu_limit: '二级级菜单只能添加[num]个。',
    form_name_empty:'请填写菜单名称。',
    form_type_click_key_empty:'请输入指令标识。',
    form_type_view_url_empty:'请输入手机web页面url。',
    form_type_cannot_choose_root:'二级菜单类型不能选择为父菜单类型。'
};

// 菜单编辑对话框
function menu_dialog(options) {

    this.values = null;
    this.is_root  = true;
    this.form_src   = "<?=cpurl($module,$act,'edit')?>";
    this.fn_result  = null;

    var _dlg        = null;
    var _form       = null;
    var _this       = this;

    var _open = function() {
        //获取菜单编辑表
        $.post(_this.form_src.url(), {in_ajax:1}, function(data) {

            _dlg    = dlgOpen(_lang.form_dialog_title, data, 450);
            _form   = _dlg.area_obj().find('form');

            _form.find('select[name="type"]').change(function() {
                var type = $(this).val();
                $('div.[data-type="option"]').hide();
                $('div.[data-name="'+type+'"]').show();
            });

            if(!_this.is_root) {
                _form.find('[name="type"] > option:first').remove();
                _form.find('[name="type"]').change();
            }

            _form.find('[data-type="submit"]').click(function() {
                _submit();
            });

            //赋值
            if(_this.values) {
                _set_values();
            }

        });
    }

    //表单元素赋值
    var _set_values = function() {
        _form.find('[name="name"]').val(_this.values.name);
        _form.find('[name="url"]').val(_this.values.url);
        _form.find('[name="key"]').val(_this.values.key);
        _form.find('[name="type"]').val(_this.values.type).change();
        _form.find('[data-type="submit"]').text('提交修改');
    }

    var _submit = function() {
        var params = {};
        var form = _dlg.area_obj().find('form');
        $.each(form.serializeArray(), function(index, val) {
            var code = "params."+val.name+"="+(val.value?("'"+val.value.trim()+"'"):"''")+";";
            eval(code);
        });
        if(params.name=='') {
            alert(_lang.form_name_empty);
            return;
        }
        if(params.type=='click' && params.key=='') {
            alert(_lang.form_type_click_key_empty);
            return;
        } else if(params.type=='view' && params.url=='') {
            alert(_lang.form_type_view_url_empty);
            return;
        }
        if(!_this.is_root && params.type=='root') {
            alert(_lang.form_type_cannot_choose_root);
            return;
        }
        _dlg.close();
        _this.values = params;
        if(_this.fn_result) {
            _this.fn_result(_this.values);
        }
    }

    this.open = function() {
        _open();
    }
}

// 菜单列表控件
function menu_list(container) {
    var _this = this;
    var _container = $(container);

    _item = function() {
        var _mythis = this;
        var item = $('<ul>').addClass('weixin-menu').data('this',this);
        this.addend = function(value, classname) {
            var length = item.find('> li').length;
            var element = $('<li>').html(value).attr('data-index', length);
            if(classname) element.addClass(classname);
            item.append(element);
            return element;
        };
        this.element = function(index) {
            return item.find('> li[data-index="'+index+'"]');
        };
        this.item = function() {
            return item;
        };
        this.remove = function() {
            item.remove();
        };
        this.clear = function() {
            item.empty();
        };
        this.value = function(index, value) {
            _mythis.element(index).html(value);
        }
    }

    _form = function() {
        var form = $('<form>');
        this.add_hidden = function(name,value) {
            form.append($('<input>').attr({type:'hidden',name:name,value:value}));
        }
        this.serialize = function() {
            return form.serialize();
        }
        this.form  = function() {
            return form;
        }
    }

    var _create = {
        add_link:function(title, click, dataName) {
            var a = $('<a>').attr('href','javascript:;').html(title).click(click);
            if(dataName) a.attr('data-name', dataName);
            return a;
        },
        add_item:function(item_cls, values) {
            item_cls.addend(values.name, 'name');
            item_cls.addend(values.type, 'type');

            var value = '-';
            if(values.type == 'view') {
                value = values.url;
            } else if(values.type == 'click') {
                value = values.key;
            }
            item_cls.addend(value, 'value');
            var operation = item_cls.addend('', 'op');

            item_cls.item().data(values);
            _create.op_bind(item_cls,operation,values);
        },
        op_bind: function(item_cls, operation, values) {
            operation.append(_create.add_link('上移', function() {
                _moveup(item_cls.item());
            }, 'moveup'));

            operation.append(_create.add_link('下移', function() {
                _movedown(item_cls.item());
            }, 'movedown'));

            operation.append(_create.add_link('编辑', function() {
                _edit(item_cls.item());
            }, 'edit'));

            operation.append(_create.add_link('删除', function() {
                if(item_cls.item().data('root')) {
                    item_cls.item().parent().remove()
                } else {
                    //var parent = _parent(item_cls.item());
                    item_cls.remove();
                    //_reset_index(parent);
                }
            }, 'delete'));

            if(values.type == 'root') {
                operation.append(_create.add_link('增加子菜单', function() {
                    _add(item_cls.item());
                }, 'new'));
            }
        }
    }

    /*
    //索引INDEX重建
    var _reset_index = function(root) {
    }

    //某个菜单项的上级菜单项
    var _parent = function(item) {
    }
    */
    //某个菜单项的下级菜单
    var _length = function(root_item) {
        if(!root_item) {
            return _container.find('div.weixin-menu-root').length;
        } else {
            return root_item.next().find('> .weixin-menu').length;
        }
    }

    var _moveup = function(item) {
        var box = item.data('root')?item.parent():item;
        var prevBox = box.prev();
        if(!prevBox[0]) return;
        box.slideUp('fast');
        prevBox.before(box);
        box.slideDown('fast');
    }

    var _movedown = function(item) {
        var box = item.data('root')?item.parent():item;
        var prevBox = box.next();
        if(!prevBox[0]) return;
        box.slideUp('fast');
        prevBox.after(box);
        box.slideDown('fast');
    }

    //编辑数据
    var _edit = function(item) {
        var values = item.data();
        var dialog = new menu_dialog();
        dialog.is_root = item.data('root');
        dialog.values = values;
        //添加表单确认时的回调
        dialog.fn_result = function(values) {
            _this.edit(values, item);
        }
        dialog.open();
    }

    //增加数据
    var _add = function(parent) {
        if(!_create_check(false, parent)) return;
        var dialog = new menu_dialog();
        dialog.is_root = false;
        //添加表单确认时的回调
        dialog.fn_result = function(values) {
            _this.add(values, parent);
        }
        dialog.open();
    }

    //清空指定菜单旗下菜单
    var _clear = function(parent) {
        parent.next().remove();
    }

    var _export = function(root_item) {
        var data = new Array();
        if(!root_item) {
            _container.find('div.weixin-menu-root > .weixin-menu').each(function(index, el) {
                data[index] = $(this).data();
            });
        } else {
            root_item.next().find('> .weixin-menu').each(function(index, el) {
                data[index] = $(this).data();
            });
        }
        return data;
    }

    this.add = function(values, parent) {
        var item_cls = new _item();
        _create.add_item(item_cls, values);
        var item = item_cls.item().data('root', !parent);
        if(parent) {
            var parent_container = parent.next();
            if(!parent_container[0]) {
                parent_container = $('<div>').addClass('weixin-menu-parent');
                parent.after(parent_container);
            }
            parent_container.append(item);
        } else {
            _container.find('div.message').remove();
            $('<div>').addClass('weixin-menu-root').append(item).appendTo(_container);
        }
        return item;
    }

    this.edit = function(values, item) {
        var item_cls = item.data('this');
        item_cls.clear();
        _create.add_item(item_cls, values);
        //清空子菜单
        if(item.data('root') && values.type!='root') {
            _clear(item);
        }
    }

    //导出成post数据
    this.export = function() {
        var form_obj = new _form();
        _container.find('div.weixin-menu-root').each(function(index, el) {
            var data = $(this).find('> .weixin-menu').data();
            for(var k in data) {
                if(k=='this') continue;
                eval("var v=data."+k+";");
                form_obj.add_hidden('button['+index+']['+k+']', v);
            }
            if(data.type=='root') {
                $(this).find('>.weixin-menu-parent>.weixin-menu').each(function(index2, el2) {
                    var data2 = $(this).data();
                    for(var k2 in data2) {
                        if(k2=='this') continue;
                        eval("var v2=data2."+k2+";");
                        form_obj.add_hidden('button['+index+'][sub_button]['+index2+']['+k2+']', v2);
                    }
                });
            }
        });
        return form_obj.serialize();
    }

    //导入菜单数据
    this.import = function(buttons) {
        if(!buttons) return;
        $.each(buttons, function(index, val) {
            if(!val.type) val.type = 'root';
            var item = _this.add(val);
            if(val.type && val.sub_button.length > 0) {
                $.each(val.sub_button, function(index2, val2) {
                    _this.add(val2, item);
                });
            }
        });
    }
}

var _create_check = function(root, parent) {
    //菜单添加数量限制
    if(root && $('div.weixin-menu-root').length >= _menu_num_limit.root) {
        alert(_lang.form_root_menu_limit.replace('[num]', _menu_num_limit.root));
        return false;
    } else if(!root && parent.next().find('> .weixin-menu').length >= _menu_num_limit.sub) {
        alert(_lang.form_sub_menu_limit.replace('[num]', _menu_num_limit.sub));
        return false;
    }
    return true;
}

// 从微信服务器拉去自定义菜单信息
function get_menus(menus) {
    var url="<?=cpurl($module,$act,'get_menus')?>";
    $('#menu_box').html('<div class="message">正在加载自定义菜单数据，请稍后...</div>');
     $('#operation_button').hide();
    $.post(url.url(), {in_ajax:1}, function(data, textStatus, xhr) {
        if(data.is_message()) {
            myAlert(data);
        } else {
            if(!data.is_json()) {
                $('#menu_box').html('<div class="message">'+data+'</div>');
                return;
            }
            var json = parse_json(data);
            if(json.code > 200) {
                $('#menu_box').html(json.message);
                $('#operation_button').hide();
            } else {
                $('#operation_button').show();
                if(json.errcode||json.menu.button.length==0) {
                    $('#menu_box').html('<div class="message">暂无数据</div>');
                } else {
                    menus.import(json.menu.button);
                }
            }
        }
    });
}

$(document).ready(function() {

    var menus = new menu_list('#menu_box');

    get_menus(menus);

    $('#add_root_menu_btn').click(function() {
        if(!_create_check(true)) return;
        var dialog = new menu_dialog();
        dialog.is_root = true; //添加一级菜单
        //添加表单确认时的回调
        dialog.fn_result = function(values) {
            menus.add(values, 0);
        }
        dialog.open();
    });

    $('#submit_btn').click(function(event) {
        var params = menus.export();
        var post_url = "<?=cpurl($module,$act,'post')?>";
        msgOpen('正在提交数据，请稍候...');
        $.post(post_url.url(), params, function(data, textStatus, xhr) {
            msgClose();
            if(data.is_message()) {
                myAlert(data);
            } else {
                if(!data.is_json()) {
                    alert('未知错误！');
                    return;
                }
                var json = parse_json(data);
                if(json.code!=200) {
                    alert(json.message);
                    return;
                }
                alert('自定义菜单提交成功！');
            }
        });
    });

});
</script>