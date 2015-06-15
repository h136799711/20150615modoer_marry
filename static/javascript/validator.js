/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
//表单提交验证
function validator(form, callback) {
    var formsubmit = true;
    for (var i=0; i<form.length; i++) {
        var t = $(form[i]);
        if(t.attr('validator_disable')=='Y') continue;
        var v = t.attr('validator');
        if(!v) continue;
        var vi = eval('('+v+')'); //JSON转换
        if(vi.callback) {
            formsubmit = vi.callback(this);
            if(!formsubmit) return false;
        } else if(vi.empty == 'N') {
            var error = false;
            switch(t.attr('tagName')) {
                case 'INPUT':
                    switch (t.attr('type')) {
                        case 'text':
                        case 'hidden':
                        case 'password':
                        case 'textarea':
                            error = t.val() == '';
                            break;
                        case 'checkbox':
                            error = !validator_checkbox(t);
                            break;
                        case 'radio':
                            error = !validator_radiobox(t);
                            break;
                    }
                    break;
                case 'SELECT':
                    error = !validator_select(t);
                    break;
                case 'TEXTAREA':
                    error = !validator_textarea(t);
                    break;
            }
            if(error) {
                var disdiv = $('#validator_'+t.attr('name'));
                if(disdiv.length>0) {
                    disdiv.html(vi.errmsg).css('color','red').show();
                } else {
                    alert(vi.errmsg);
                    return false;
                }
            } else {
                formsubmit = true;
            }
        }
    }
    if($(form).find("[name='msgOpen']")[0]) {
        msgOpen($(form).find("[name='msgOpen']").val());
        $(form).find("[name='msgOpen']").remove();
    } else if($(form).find("[name='dlgOpen']")[0]) {
        dlgOpen('提示信息',"<p>"+$(form).find("[name='dlgOpen']").val()+"</p>",300);
        $(form).find("[name='dlgOpen']").remove();
    }
    return formsubmit;
}

validator_checkbox = function(t) {
    var check = document.getElementsByTagName('input');
    var name = t.attr('name');
    for (var i=0; i<check.length; i++) {
        if (check[i].type == 'checkbox' && check[i].checked && !check[i].disabled && check[i].name == name) {
            return true;
        }
    }
    return false;
}

validator_radiobox = function(t) {
    var radio = document.getElementsByTagName('input');
    var name = t.attr('name');
    for (var i=0; i<radio.length; i++) {
        if (radio[i].type == 'radio' && radio[i].checked && radio[i].name == name) {
            return true;
        }
    }
    return false;
}

validator_select = function(t) {
    var value = t.val();
    return value != null && value != '';
}

validator_textarea = function(t) {
    var value = t.val();
    return value != null && value != '';
}
