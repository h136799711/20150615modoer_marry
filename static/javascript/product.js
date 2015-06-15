/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/

$(function() {
    if (website.module.flag == 'product') {
        var fun = 'website.module.product.pageinit.' + website.module.page;
        eval("if(" + fun + ") " + fun + "();");        
    }
});

website.module.product = {};
website.module.product.pageinit = {};

//表单元素生成
website.module.product.form = {
    input:function(caption, name, value) {
        var lbl = $('<label for="">'+caption+'：</label><br />');
        var txt = $('<input type="text" class="t_input" style="width:95%;" />').attr('name',name).val(value);
        return $('<div></div>').append(lbl).append(txt);
    },
    select:function(caption, name, array, value) {
        var lbl = $('<label for="">'+caption+'：</label><br />');
        var select = $('<select></select>');
        if(typeof(name)=='object') {
            $.each(name, function(n, v) { select.attr('data-'+n, v); });
        } else {
            select.attr('name', name);
        }
        if(array && array.length > 0) {
            for (var i = 0; i < array.length; i++) {
                select.append($('<option></option>').val(i).text(array[i]));
            };
        }
        return $('<div></div>').append(lbl).append(select);
    },
    button:function(caption, name, value, type, click_callback) {
        if(!name) name = '';
        if(!type) type = 'button';
        var btn = $('<button type="'+type+'" class="btn"></button>').attr({'name':name}).text(caption);
        if(click_callback) btn.click(function(){click_callback()});
        return btn;
    },
    hidden:function(name, value) {
        return $('<input type="hidden" />').attr('name', name).val(value);
    }
}

// 模块公用函数
website.module.funs = {

    inputnum:function (c,b,a) {
        c.value = c.value.replace(/\D+/g,"");
        if(c.value > a) {
            c.value = a;
        }
        if(c.value < b) {
            c.value = b;
        }
    },

    increment:function (d, max) {
        if(!max) max = 999;
        if(d.size()>0){
            var a=d.val();
            var c=/^[1-9]\d{0,2}$/g;
            if(!a.match(c)) {
                d.val(1);
                a=1;
            }
            var b=parseInt(a)+1;
            if(b > max){
                b=max;
            }
            d.val(b);
        }
    },

    decrement:function (d, max) {
        if(!max) max = 999;
        if(d.size() > 0){
            var a=d.val();
            var c=/^[1-9]\d{0,2}$/g;
            if(!a.match(c)){
                d.val(1);
                a=1;
            }
            var b=parseInt(a)-1;
            if(b>max){
                b=max
            }
            if(b<=0){
                b=1;
            }
            d.val(b)
        }
    },

    //浮点数四则运算
    fixMath:function (m, n, op) {
        var a = (m+"");
        var b = (n+"");
        var x = 1;
        var y = 1;
        var c = 1;
        if(a.indexOf(".") > 0) {
            x = Math.pow(10, a.length - a.indexOf(".") - 1);
        }
        if(b.indexOf(".") > 0) {
            y = Math.pow(10, b.length - b.indexOf(".") - 1);
        }
        switch(op) {
            case '+':
            case '-':
                c = Math.max(x,y);
                m = Math.round(m*c);
                n = Math.round(n*c);
                break;
            case '*':
                c = x*y
                m = Math.round(m*x);
                n = Math.round(n*y);
                break;
            case '/':
                c = Math.max(x,y);
                m = Math.round(m*c);
                n = Math.round(n*c);
                c = 1;
                break;
        }
        return eval("("+m+op+n+")/"+c);
    },

    //快速购买
    once_order:function(pid) {

        if(!is_id(pid, '商品ID')) return;
        $.get(Url("product/cart/op/product/pid/"+pid+"/in_ajax/1"), function(data) {
            if(is_message(data)) {
                myAlert(data);
            } else if(is_json(data)) {
                var json = parse_json(data);
                if(json.code!=200) {
                    alert(json.message);
                } else {
                    show_form(json.product, json.buyattr);
                }
            }
        });

        function add_cart(pid, buyattr, quantity) {
            $.post(Url('product/cart/op/add'), {'pid':pid, buyattr:buyattr, 'num':quantity, in_ajax:1 }, 
            function(result) {
                console.debug(result);
                if(is_message(result)) {
                    myAlert(result);
                } else if(is_numeric(result.trim())) {
                    var cid = result.trim();
                    jslocation(Url("product/member/ac/order/cids/"+cid));
                } else {
                    console.debug(result);
                    alert('产品读取失败，可能网络忙碌，请稍后尝试。');
                }
            });
        }

        //显示购物选项
        function show_form (product, buyattr) {
            if(!product.stock || product.stock<=0) {
                alert('商品库存不足。');
                return false;
            } 
            var pform = website.module.product.form;
            var form = $('<form></form>');
            form.append(pform.input('购买数量','product_num', 1));
            if(buyattr) {
                $.each(buyattr, function(index, val) {
                    myname = { type:'buyattr_select', name:val.name, id:val.id };
                    form.append(pform.select(val.name, myname, val.value));
                });
            }
            var box = $('<div style="margin-top:10px;"></div>');
            box.append(pform.button('立刻购买', 'dosubmit', 'yes', 'submit'));
            box.append(pform.button('关闭', '', '', 'button', dlgClose));
            form.append(box);
            form.bind('submit',function() {
                var product_num = parseInt(form.find('input[name="product_num"]').val());
                if(!is_numeric(product_num) || product_num < 0) {
                    alert('未填写购买数量。');
                } else if(product_num>product.stock) {
                    alert('库存不足，商品库存仅剩'+product.stock+'件。');
                } else {
                    var buyattr = '';
                    form.find('[data-type="buyattr_select"]').each(function() {
                        var _select = $(this);
                        buyattr += (buyattr?';':'')+_select.data('id')+':'+_select.val();
                    });
                    add_cart(product.pid, buyattr, product_num);
                }
                return false;
            });
            dlgOpen('购买商品', form, 300);
        }
    }
}

//产品列表页js初始化
website.module.product.pageinit.list = function() {

    $('a[data-name="once_order_btn"]').click(function(event) {
        event.preventDefault();
        website.module.funs.once_order($(this).data('pid'));
    });
}

//产品内容页js初始化
website.module.product.pageinit.detail = function() {

    function get_buyattr_exp() {
        var exp = '';
        if(buyattr.length > 0) {
            for (var i = 0; i < buyattr.length; i++) {
                var attr = buyattr[i];
                var index = $('select[data-id="'+attr.id+'"]').val();
                if(!is_numeric(index)) {
                    alert('请先选择商品' + attr.name + '。');
                    return false;
                } else {
                    exp += (exp?';':'')+attr.id+':'+index;
                }
            };
        }
        return exp;
    }

    //加入到购物车中
    function add_cart(callback) {
        var buyattr_exp = get_buyattr_exp();
        if(buyattr.length> 0 && !buyattr_exp) {
            return;
        }
        if(!buyattr_exp) buyattr_exp = '';
        var quantity = parseInt($("#quantity_txt").val());
        if(quantity > params.stock) {
            alert('很抱歉，库存不足，无法下单！');
            return false;
        }
        $.post(Url('product/cart/op/add'), {'pid':params.pid, buyattr:buyattr_exp, 'num':quantity, in_ajax:1 }, 
        function(result) {
            if(is_message(result)) {
                myAlert(result);
            } else if(is_numeric(result.trim())) {
                //$('#cart_num').html(Number(cartnum) + Number(pnum));
                var cid = result.trim();
                if(callback) {
                    callback(cid);
                } else {
                    if(window.confirm('成功将该商品加入购物车！是否要进入购物车页面，进行结算？')) {
                        jslocation(Url('product/cart'));
                    }
                }
            } else {
                console.debug(result);
                alert('产品读取失败，可能网络忙碌，请稍后尝试。');
            }
        });
    }

    //获取参数
    var params = $('#params').data();
    var buyattr = new Array();

    $('#quantity_dec_btn').click(function() {
        website.module.funs.decrement($("#quantity_txt"), params.stock);
    });
    $('#quantity_add_btn').click(function() {
        website.module.funs.increment($("#quantity_txt"), params.stock);
    });
    $('#quantity_txt').bind('KeyUp', function(event) {
        website.module.funs.inputnum(this,1,params.stock);
    });

    //下单属性
    $('[name="buyattr"]').each(function() {
        buyattr.push($(this).data());
    });

    //立刻购买按钮
    $('#once_order_btn').click(function(e) {
        e.preventDefault();
        add_cart(function(cid) {
            jslocation(Url('product/member/ac/order/cids/'+cid));
        })
    });

    //加入购物车按钮
    $('#add_cart_btn').click(function(e) {
        e.preventDefault();
        add_cart();
    });

    //禁止加入购物车
    $('#add_cart_btn_disable').click(function(e) {
        e.preventDefault();
        alert('虚拟产品，不能加入购物车');
    });
}

//产品购物车页js初始化
website.module.product.pageinit.cart = function() {

    //商品数量增减
    var quantity = function(input, succeed_call_back) {

        var _t_this = this;
        var data = input.data();
        var current_num = input.val();

        //定时执行更新商品购买数量
        var time = null;

        input.keyup(function(event) {
            change();
        });

        this.add = function() {
            var num = get_num();
            if(num >= data.stock) {
                num = data.stock;
            } else {
                num++;
                num = Math.max(num, 1);
            }
            set_num(num);
        }

        this.dec = function() {
            var num = get_num();
            if(num <= 1) {
                num = 1;
            } else {
                num--;
                num = Math.min(num, data.stock);
            }
            set_num(num);
        }

        function change() {
            var num = get_num();
            if(num <= 0){
                num = 1;
            } else if(num>data.stock){
                num = data.stock;
            }
            set_num(num);
        }

        function set_num(num) {
            num = parseInt(num);
            input.val(num);
            interval();
        }

        function get_num() {
            var num = parseInt(input.val());
            if(num > 0) return num;
            return 0;
        }

        //写入数据库
        function update_num(old_num, new_num) {
            
            $.post(Url('product/cart/op/num_change'), 
            { cid:data.cid, num:num, in_ajax:1 },
            function(result) {
                time = null;
                if(is_message(result)) {
                    myAlert(result);
                } else if(result == 'OK') {
                    input.val(num);
                    current_num = num;
                    if(succeed_call_back) {
                        succeed_call_back(data, num);
                    }
                } else {
                    console.debug(result);
                    alert('信息读取失败，可能网络忙碌，请稍后尝试。');
                }
            });
        }

        //Interval
        function interval() {
            if(time) return;
            time = window.setInterval(function() {
                num = get_num();
                if(current_num != num) {
                    update_num(num);
                }
                window.clearTimeout(time);
                time = null;
            }, 1000);
        }

    }

    //结算
    function checkout(store) {
        var sid = store.data('sid');
        var cids = new Array();

        //选择购买的商品
        store.find('input:checked').each(function() {
            if(this.name == 'cids[]') cids.push(this.value);
        });

        if(cids.length==0) {
            alert('请选择需要结账的商品。');
            return false;
        }

        var action = store.parents('form').attr('action');
        var params = {op:'checkout', sid:sid , cids:cids, in_ajax:1};

        $.post(action.url(), params, function(data) {
            if(is_message(data)) {
                myAlert(data);
            } else if(is_json(data)) {
                var json = parse_json(data);
                if(json.code != 200) {
                    alert(json.message);
                } else {
                    jslocation(json.url);
                }
            } else {
                console.debug(data);
                alert('未知错误。');
            }
        });

        return false;
    }

    //购买属性判断
    function get_buyattr_exp(buyattr) {
        var attr_str = '';
        if(buyattr.length > 0) {
            for (var i = 0; i < buyattr.length; i++) {
                var attr = buyattr[i];
                if(!is_numeric(attr.index) || attr.index < 0) {
                    alert('请选择商品的' + attr.name + '。');
                    return false;
                } else {
                    attr_str += (attr_str?';':'')+attr.id+':'+attr.index;
                }
            };
        }
        return attr_str;
    }

    //购买属性选择行为
    function select_buyattr(cart_item) {
        var item = cart_item.data();
        var buyattr = new Array();
        cart_item.find('div[data-name="buyattr"]').each(function() {
            var data = $(this).find('[name="buyattr"]').data();
            data.index = $(this).find('[name="buyattr"]').val();
            buyattr.push(data);
        });
        buyattr_exp = get_buyattr_exp(buyattr);
        if(!buyattr_exp) {
            alert('您未选择商品的购买属性。');
            return;
        }
        $.post(Url("product/cart/op/buyattr"), { cid:item.cid, buyattr:buyattr_exp, in_ajax:1 }, 
        function(result) {
            if(is_message(result)) {
                myAlert(result);
            } else if(result=='OK') {
                //todo
            } else {
                console.debug(result);
                alert('产品读取失败，可能网络忙碌，请稍后尝试。');
            }
        });
    }

    //统计购买的数量
    function total_price(sid) {
        var num = 0;
        var goods_amount = 0;
        var store_box = $('div[data-sid="'+sid+'"]');
        store_box.find('[data-name="cart-item"]').each(function() {
            var _item = $(this);
            var myprice = _item.data('price');
            _item.find('[data-name="quantity_txt"]').each(function() {
                var num = parseInt($(this).val());
                var goods_myprice = website.module.funs.fixMath(myprice, num, '*');
                _item.find('[data-name="quantity_amount"]').text(goods_myprice);
                goods_amount += goods_myprice;
            });
        });
        store_box.find('[data-name="goods_amount"]').text(goods_amount);
    }

    //删除购物车内某件商品
    function delete_item(item) {
        var cid = item.cid;
        if(!is_numeric(cid)) { alert('无效的ID'); return; }
        if(!confirm('您确定要从购物车里删除这个商品吗?')) return;
        $.post(Url('product/cart/op/delete'), { cids:cid,in_ajax:1 },
        function(result) {
            if(is_message(result)) {
                myAlert(result);
            } else if (result=='OK') {
                msgOpen('已成功将该商品从购物车删除！', 200, 60);
                $('#product_'+cid).remove();
                if($('#shop_'+item.sid).find('[data-name="cart-item"]').length < 1) {
                    $('#shop_'+item.sid).remove();
                }
                if($('#shop_'+item.sid).length < 1) {
                    document_reload();
                }
            } else {
                console.debug(result);
                alert('信息读取失败，可能网络忙碌，请稍后尝试。');
            }
        });
    }

    //清空购物车
    function cart_clear() {
        if(!confirm('您确定要清空购物车吗?')) return;
        $.post(Url('product/cart/op/clear'), { in_ajax:1 },
        function(result) {
            if(is_message(result)) {
                myAlert(result);
            } else if (result=='OK') {
                msgOpen('购物车已清空！', 200, 60);
                document_reload();
            } else {
                console.debug(result);
                alert('信息读取失败，可能网络忙碌，请稍后尝试。');
            }
        });
    }

    //初始化
    $('div[data-name="store"]').each(function(index, el) {
        var _this = $(this);
        _this.find('[data-name="cart-item"]').each(function() {
            var _item = $(this);
            //下单属性
            _item.find('div[data-name="buyattr"] select').each(function() {
                var _select = $(this);
                if(_select.val()!='-1') {
                    _select.find('option').eq(0).remove();
                }
                _select.change(function() {
                    if($(this).val() != '-1') {
                        var opt = _select.find('option').eq(0);
                        if(opt.val()=='-1') opt.remove();
                    }
                    select_buyattr(_item);
                });
            });
            _item.find('[[data-name="delete_item"]').click(function(event) {
                delete_item(_item.data());
            });
            //数量增减操作
            var _quantity = new quantity(_item.find('[data-name="quantity_txt"]'), function(data, num) {
                total_price(_this.data('sid'));
            });
            _item.find('[data-name="quantity_dec"]').click(function(event) {
                _quantity.dec();
            });
            _item.find('[data-name="quantity_add"]').click(function(event) {
                _quantity.add();
            });
        });
        //结账
        _this.find('a[data-name="checkout"]').click(function(event) {
            event.preventDefault();
            checkout(_this);
        });
        //价格统计
        total_price(_this.data('sid'));
    });
    //全选
    $('input[data-name="items_select_all"]').click(function(event) {
        checkbox_checked('cids[]',this);
    });
    //删除所选
    $('a[data-name="delete_items"]').click(function(event) {
        easy_submit('myform','delete','cids[]');
    });
    //清空购物车
    $('a[data-name="cart_clear"]').click(cart_clear);

}

//填写订单页面js初始化
website.module.product.pageinit.order = function() {

    //订单总价计算
    function calc_amount() {
        var price_list = new Array();
        var ship_price = Number($("input[name='shipid']:checked").data('price'));

        if(is_numeric(ship_price)) {
            price_list.push(ship_price);
        }
        var integral_point = parseInt($('#integral').val());
        var max_point = params.max_integral;
        if(!is_numeric(integral_point)) integral_point = 0;
        integral_point = Math.min(integral_point, max_point);
        var integral_price = website.module.funs.fixMath(integral_point, params.rate, '/');
        $('#integral').val(integral_point>0?integral_point:'');

        if(is_numeric(integral_price)) {
            price_list.push(-integral_price);
        }
        //console.debug(price_list);
        var amount = 0;
        for (var i = 0; i < price_list.length; i++) {
            amount = website.module.funs.fixMath(amount,price_list[i],'+');
        };
        //console.debug(amount);
        return amount;
    }

    //留言
    if($('#remark').val().trim() == '') {
        $('#remark').val($('#remark').attr('placeholder'));
    }
    $('#remark').click(function() {
        if(!this.name) {
            this.value = '';
            this.name = 'remark';
        }
    });

    //订单提交处理
    $("#order_submit").click(function() {
        var addressval = $('input:radio[name="addressid"]:checked').val();
        var shipval = $('input:radio[name="shipid"]:checked').val();
        if(!addressval) {
            alert('未选择收货人地址，请选择。');
            return false;
        }
        if(!shipval) {
            alert('未选择配送方式，请选择。');
            return false;
        }
        var form = $(this).parents('form');
        var action = Url("product/ajax/do/order");
        $.post(action.url(), form.serializeArray(), function(data) {
            if(is_message(data)) {
                myAlert(data);
            } else if(is_json(data)) {
                var json = parse_json(data);
                if(json.code != 200) {
                    alert(json.message);
                } else {
                    alert('订单创建成功！点击“确定”，进入支付页面。');
                    //alert(json.url);return;
                    jslocation(json.url);
                }
            } else {
                console.debug(data);
                alert('未知错误。');
            }
        });
    });

    //获取参数
    var params = $('#params').data();

    $('#goods_amount').val(params.goods_amount);
    $('#cart_amount').val(website.module.funs.fixMath(params.goods_amount,calc_amount(),'+'));

    $("#integral").bind("keyup", function() {
        $('#cart_amount').val(website.module.funs.fixMath(params.goods_amount,calc_amount(),'+'));
        $('#order_amount').text($('#cart_amount').val());
    });

    $('input[name="shipid"]').bind("change", function() {
        $('#cart_amount').val(website.module.funs.fixMath(params.goods_amount,calc_amount(),'+'));
        $('#order_amount').text($('#cart_amount').val());
    });

}

//删除分类
function delete_category(id) {
    var catid = $('#'+id).val();
    if(!is_numeric(catid) || catid < 1) return false;
    if(confirm('您确定要删除本分类及下属的产品吗？')) {
        location.href = Url('product/member/ac/category/op/delete/catid/'+catid);
    } else {
        return false;
    }
}

//重命名分类
function rename_category(sel_id) {
    var catid = $('#'+sel_id).val();
    var name = $('#'+sel_id).find("option:selected").text();
    var catname = prompt('请输入您的分类名称：',name);
    if(!catname) return;
    $.post(Url('product/member/ac/category/op/rename'), {'catid':catid, 'catname':catname, 'in_ajax':1 }, 
        function(result) {
        if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result=='OK') {
            $('#'+sel_id).find("option:selected").text(catname);
            msgOpen('更新成功!');
        } else {
            alert('产品读取失败，可能网络忙碌，请稍后尝试。');
        }
    });
}

//新建分类
function create_product_category(sid) {
    if (!is_numeric(sid)) {
        alert('未选择产品主题。');
        return false;
    }
    var catname = prompt('请输入您的分类名称：','');
    if(!catname) return;
    $.post(Url('product/member/ac/category/op/create'), {'sid':sid, 'catname':catname, 'in_ajax':1 }, 
        function(result) {
        if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(is_numeric(result)) {
            $("<option value='"+result+"'"+' selected="selected"'+">"+catname+"</option>").appendTo($('#catid'));
        } else {
            alert('产品读取失败，可能网络忙碌，请稍后尝试。');
        }
    });
}

//取得分类列表
function get_product_category(sid) {
    if (!is_numeric(sid) || sid < 1) {
        $('#category').html('');
        return false;
    }
    $.post(Url('product/member/ac/category/op/list'), {'sid':sid, 'in_ajax':1 }, 
        function(result) {
        if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result == 'null') {
            $('#category').html('');
            $('<option value="" selected="selected">==选择产品分类==</option>').appendTo($('#category'));
        } else {
            $('#category').html(result);
        }
    });
}

//商品图片上传
function product_upimg(id, max_count) {
    if(!max_count||!is_numeric(max_count)) max_count = 6;
    var image_count = 0;
    $('.upimg').each(function(i) {
        image_count++;
    });
    if(image_count>=max_count) {
        alert('最多只能上传张 '+image_count+' 图片。');
        return false;
    }
    var html = '<div style="margin:5px 0;"><form id="frm_productupload" method="post" action="'+Url('modoer/upload/in_ajax/1')+'" enctype="multipart/form-data">';
    html += '<input type="file" name="picture" />&nbsp;';
    html += '<button type="button" class="button">开始上传</button>';
    html += '</form></div>';
    GLOBAL['mdlg_upimg'] = new $.mdialog({id:'mdlg_upimg', title:'上传图片', body:html, width:360});
    var frm = $('#frm_productupload');
    frm.find('button').click(function() {
        var file = frm.find('[name="picture"]').val();
        if(!file) {
            alert('未选准备上传的择图片文件.');
            return;
        }
        ajaxPost('frm_productupload', id, 'product_addimg', 1, 'mdlg_upimg', function(data){
            frm.show();
            $('#upload_message').remove();
            myAlert(data.replace('ERROR:',''));
        });
        frm.parent().append('<div id="upload_message" style="margin:10px 0; text-align:center;">正在上传...</div>');
        frm.hide();
    });
}

function product_addimg(id,data) {
    if(data.length==0) {
        alert('图片未上传成功。'); return;
    }
    var keyname = basename(data, data.substring(data.lastIndexOf(".")));
    var foo = $('#topic_images_foo');
    var imgfoo = $('<div class="upimg"></div>').attr('id','upimg_'+keyname);
    imgfoo.append($('<img></img>').attr('src', urlroot + '/' + data));
    imgfoo.append($('<a href="javascript:void(0);">设为封面</a>').click(function(){ product_setthumb(keyname) }));
    imgfoo.append(" | ");
    imgfoo.append($('<a href="javascript:void(0);">删除</a>').click(function(){ product_delimg(keyname) }));
    imgfoo.append("<input type=\"hidden\" name=\"pictures[]\" value=\""+data+"\" />");
    foo.after(imgfoo);
}

function product_delimg(keyname) {
    $('#upimg_'+keyname).remove();
    var thumb = $('[name="myform"]').find('[name="thumb"]');
    if(thumb[0]&&thumb.val()==keyname) thumb.remove();
}

function product_setthumb(keyname) {
    $('.upimg').each(function(i) {
        $(this).removeClass('imgthumb');
    });
    $('#upimg_'+keyname).addClass('imgthumb');
    var thumb = $('[name="myform"]').find('[name="thumb"]');
    if(!thumb[0]) {
        thumb = $("<input name='thumb' type='hidden' />");
        $('[name="myform"]').append(thumb);
    }
    thumb.val(keyname);
}

//取得主题产品
function get_products(sid, page) {
    if (!is_numeric(sid)) {
        alert('无效的ID'); 
        return;
    }
    if(!page) page = 1;
    $.post(Url('product/list/sid/'+sid+'/page/'+page), 
    { 'in_ajax':1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            $('#products').html(result);
        }
    });
}

//取得上级分类id
function product_gcatgory_parent_id(catid) {
    var result = 0;
    $.each(_product_cate.level, function(i,n) {
        $.each(n, function(_i, _n) {
            $.each(_n, function(__i, __n) {
                if(__n == catid) {
                    result = _i;
                }
            });
        });
    });
    return result;
}

function get_p_comment(idtype,id,page,endpage) {
    if (!is_numeric(id)) {
        alert('无效的ID'); 
        return;
    }
    endpage = !endpage ? 0 : 1;
    if(!page) page = 1;
    $.get(Url('product/ajax/do/comment'), 
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

function reset_comment_form() {
    $('#comment_form [name=title]').val('');
    $('#comment_form [name=grade]').get(4).checked = true;
    $('#comment_form [name=content]').val('');
    $('#comment_form [name=seccode]').val('');
    $('#seccode').empty().html();
}

var comment_time = 0;
function enable_comment_button() {
    comment_time = comment_time - 1;
    if(comment_time < 1) {
        $('#comment_button').text('发表评论').attr('disabled','');
        comment_time = 0;
    } else {
        $('#comment_button').text('发表评论('+comment_time+')').attr('disabled','disabled');
        window.setTimeout('enable_comment_button()', 1000);
    }
}

// function post_comment_behind(str) {
//  var param = str.split('-');
//  var idtype=param[0];
//  var id=param[1];
//  reset_comment_form();
//  comment_time = $('#comment_time').val();
//  jslocation (Url('product/member/ac/m_order'));
//  //$('#comment_button').attr('disabled','disabled');
//  //window.setTimeout('enable_comment_button()', 1000);
//  //get_p_comment(idtype, id, 1, true);
// }


function order_check(sid) {
    var box = $('#shop_'+sid);
    var action = box.parents('form').attr('action');
    var cids = new Array();
    box.find('input:checked').each(function() {
        if(this.name=='cids[]') cids.push(this.value);
    });

    $.post(action.url(), {op:'checkout',sid:sid,cids:cids,in_ajax:1}, function(data) {
        if(is_message(data)) {
            myAlert(data);
        } else if(is_json(data)) {
            var json = parse_json(data);
            if(json.code!=200) {
                alert(json.message);
            } else {
                jslocation(json.url);
            }
        } else {
            console.debug(data);
            alert('未知错误。');
        }
    });

    return false;
}

function cancel_order(orderid) {
    if(!is_numeric(orderid)) { alert('无效的ORDERID'); return; }
    $.post(Url('product/member/ac/g_order/op/cancel_order'), 
    { orderid:orderid,in_ajax:1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            dlgOpen('取消订单', result, 400);
        }
    });
}

//调整订单费用
function change_amount(orderid) {
    if(!is_numeric(orderid)) { alert('无效的ORDERID'); return; }
    $.post(Url('product/member/ac/g_order/op/change_amount'), 
    { orderid:orderid,in_ajax:1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            dlgOpen('调整费用', result, 500);
        }
    });
}

//发货
function change_ship(orderid) {
    if(!is_numeric(orderid)) { alert('无效的ORDERID'); return; }
    $.post(Url('product/member/ac/g_order/op/change_ship'), 
    { orderid:orderid,in_ajax:1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            dlgOpen('订单发货', result, 500);
        }
    });
}

//更改货单号
function edit_ship(orderid) {
    if(!is_numeric(orderid)) { alert('无效的ORDERID'); return; }
    $.post(Url('product/member/ac/g_order/op/edit_ship'), 
    { orderid:orderid,in_ajax:1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            dlgOpen('修改单号', result, 500);
        }
    });
}

//确认订单
function order_confirm(orderid) {
    if(!is_numeric(orderid)) { alert('无效的ORDERID'); return; }
    $.post(Url('product/member/ac/m_order/op/order_confirm'), 
    { orderid:orderid,in_ajax:1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            dlgOpen('确认收货', result, 400);
        }
    });
}

//线下付款
function product_offline_pay (ordersn, orderid) {
    var ok = '';
    var msg = "\r\n请在输入框内输入“OK”（大写，不包含引号）" 
    ok = prompt('您确定您已经收到了订单 '+ordersn+' 的支付款项吗（虚拟物品将自动发货）？'+msg,'');
    if(ok!='OK') return false;
    var frm = $('#dfsdf');
    if(frm[0]) frm.remove();
    frm = $("<form method=\"post\" action=\""+Url("product/member/ac/g_order/op/offline_pay/rand/"+getRandom())+"\"></form>");
    var hie1 = $("<input type=\"hidden\" name=\"ordersn\">").val(ordersn);
    frm.append(hie1);
    var hie2 = $("<input type=\"hidden\" name=\"orderid\">").val(orderid);
    frm.append(hie2);
    $(document.body).append(frm);
    frm[0].submit();
}


function setTab(m,n){
    var tli=document.getElementById("menu"+m).getElementsByTagName("li");
    var mli=document.getElementById("main"+m).getElementsByTagName("ul");
    for(i=0;i<tli.length;i++){
        tli[i].className=i==n?"on":"";
        mli[i].style.display=i==n?"block":"none";
    }
}

//滑动TAB
$(document).ready(function(){
    var intervalID;
    var curLi;
    $(".tabs li a").mouseover(function(){
        curLi=$(this);
        intervalID=setInterval(onMouseOver,200);//鼠标移入的时候有一定的延时才会切换到所在项，防止用户不经意的操作
    }); 
    function onMouseOver(){
        $(".dis").removeClass("dis");
        $(".sub-con").eq($(".tabs li a").index(curLi)).addClass("dis");
        $(".on").removeClass("on");
        curLi.addClass("on");
    }
    $(".tabs li a").mouseout(function(){
        clearInterval(intervalID);
    });

    $(".tabs li a").click(function(){//若延时设定的比较久，用鼠标点击也可以切换
        clearInterval(intervalID);
        $(".dis").removeClass("dis");
        $(".sub-con").eq($(".tabs li a").index(curLi)).addClass("dis");
        $(".on").removeClass("on");
        curLi.addClass("on");
    });
});


/*jdMarquee*/
(function($){$.fn.jdMarquee=function(option,callback){if(typeof option=="function"){callback=option;option={};};var s=$.extend({deriction:"up",speed:10,auto:false,width:null,height:null,step:1,control:false,_front:null,_back:null,_stop:null,_continue:null,wrapstyle:"",stay:5000,delay:20,dom:"div>ul>li".split(">"),mainTimer:null,subTimer:null,tag:false,convert:false,btn:null,disabled:"disabled",pos:{ojbect:null,clone:null}},option||{});var object=this.find(s.dom[1]);var subObject=this.find(s.dom[2]);var clone;if(s.deriction=="up"||s.deriction=="down"){var height=object.eq(0).outerHeight();var step=s.step*subObject.eq(0).outerHeight();object.css({width:s.width+"px",overflow:"hidden"});};if(s.deriction=="left"||s.deriction=="right"){var width=subObject.length*subObject.eq(0).outerWidth();object.css({width:width+"px",overflow:"hidden"});var step=s.step*subObject.eq(0).outerWidth();};var init=function(){var wrap="<div style='position:relative;overflow:hidden;z-index:1;width:"+s.width+"px;height:"+s.height+"px;"+s.wrapstyle+"'></div>";object.css({position:"absolute",left:0,top:0}).wrap(wrap);s.pos.object=0;clone=object.clone();object.after(clone);switch(s.deriction){default:case "up":object.css({marginLeft:0,marginTop:0});clone.css({marginLeft:0,marginTop:height+"px"});s.pos.clone=height;break;case "down":object.css({marginLeft:0,marginTop:0});clone.css({marginLeft:0,marginTop:-height+"px"});s.pos.clone=-height;break;case "left":object.css({marginTop:0,marginLeft:0});clone.css({marginTop:0,marginLeft:width+"px"});s.pos.clone=width;break;case "right":object.css({marginTop:0,marginLeft:0});clone.css({marginTop:0,marginLeft:-width+"px"});s.pos.clone=-width;break;};if(s.auto){initMainTimer();object.hover(function(){clear(s.mainTimer);},function(){initMainTimer();});clone.hover(function(){clear(s.mainTimer);},function(){initMainTimer();});};if(callback){callback();};if(s.control){initControls();}};var initMainTimer=function(delay){clear(s.mainTimer);s.stay=delay?delay:s.stay;s.mainTimer=setInterval(function(){initSubTimer()},s.stay);};var initSubTimer=function(){clear(s.subTimer);s.subTimer=setInterval(function(){roll()},s.delay);};var clear=function(timer){if(timer!=null){clearInterval(timer);}};var disControl=function(A){if(A){$(s._front).unbind("click");$(s._back).unbind("click");$(s._stop).unbind("click");$(s._continue).unbind("click");}else{initControls();}};var initControls=function(){if(s._front!=null){$(s._front).click(function(){$(s._front).addClass(s.disabled);disControl(true);clear(s.mainTimer);s.convert=true;s.btn="front";if(!s.auto){s.tag=true;};convert();});};if(s._back!=null){$(s._back).click(function(){$(s._back).addClass(s.disabled);disControl(true);clear(s.mainTimer);s.convert=true;s.btn="back";if(!s.auto){s.tag=true;};convert();});};if(s._stop!=null){$(s._stop).click(function(){clear(s.mainTimer);});};if(s._continue!=null){$(s._continue).click(function(){initMainTimer();});}};var convert=function(){if(s.tag&&s.convert){s.convert=false;if(s.btn=="front"){if(s.deriction=="down"){s.deriction="up";};if(s.deriction=="right"){s.deriction="left";}};if(s.btn=="back"){if(s.deriction=="up"){s.deriction="down";};if(s.deriction=="left"){s.deriction="right";}};if(s.auto){initMainTimer();}else{initMainTimer(4*s.delay);}}};var setPos=function(y1,y2,x){if(x){clear(s.subTimer);s.pos.object=y1;s.pos.clone=y2;s.tag=true;}else{s.tag=false;};if(s.tag){if(s.convert){convert();}else{if(!s.auto){clear(s.mainTimer);}}};if(s.deriction=="up"||s.deriction=="down"){object.css({marginTop:y1+"px"});clone.css({marginTop:y2+"px"});};if(s.deriction=="left"||s.deriction=="right"){object.css({marginLeft:y1+"px"});clone.css({marginLeft:y2+"px"});}};var roll=function(){var y_object=(s.deriction=="up"||s.deriction=="down")?parseInt(object.get(0).style.marginTop):parseInt(object.get(0).style.marginLeft);var y_clone=(s.deriction=="up"||s.deriction=="down")?parseInt(clone.get(0).style.marginTop):parseInt(clone.get(0).style.marginLeft);var y_add=Math.max(Math.abs(y_object-s.pos.object),Math.abs(y_clone-s.pos.clone));var y_ceil=Math.ceil((step-y_add)/s.speed);switch(s.deriction){case "up":if(y_add==step){setPos(y_object,y_clone,true);$(s._front).removeClass(s.disabled);disControl(false);}else{if(y_object<=-height){y_object=y_clone+height;s.pos.object=y_object;};if(y_clone<=-height){y_clone=y_object+height;s.pos.clone=y_clone;};setPos((y_object-y_ceil),(y_clone-y_ceil));};break;case "down":if(y_add==step){setPos(y_object,y_clone,true);$(s._back).removeClass(s.disabled);disControl(false);}else{if(y_object>=height){y_object=y_clone-height;s.pos.object=y_object;};if(y_clone>=height){y_clone=y_object-height;s.pos.clone=y_clone;};setPos((y_object+y_ceil),(y_clone+y_ceil));};break;case "left":if(y_add==step){setPos(y_object,y_clone,true);$(s._front).removeClass(s.disabled);disControl(false);}else{if(y_object<=-width){y_object=y_clone+width;s.pos.object=y_object;};if(y_clone<=-width){y_clone=y_object+width;s.pos.clone=y_clone;};setPos((y_object-y_ceil),(y_clone-y_ceil));};break;case "right":if(y_add==step){setPos(y_object,y_clone,true);$(s._back).removeClass(s.disabled);disControl(false);}else{if(y_object>=width){y_object=y_clone-width;s.pos.object=y_object;};if(y_clone>=width){y_clone=y_object-width;s.pos.clone=y_clone;};setPos((y_object+y_ceil),(y_clone+y_ceil));};break;}};if(s.deriction=="up"||s.deriction=="down"){if(height>=s.height&&height>=s.step){init();}};if(s.deriction=="left"||s.deriction=="right"){if(width>=s.width&&width>=s.step){init();}}}})(jQuery);
