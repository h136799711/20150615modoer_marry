/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
$(document).ready(function(){
    $("table[trmouse=Y] tr").each(function(i) {
        if(!this.className && $(this).attr('mousemove')!='N') {
            $(this).mouseover( function() { 
                $(this).attr('nextbg', $(this).css('background-color'));
                $(this).css('background-color','#ffffc6');
            } );
            $(this).mouseout( function() {
                var nextbg = $(this).attr('nextbg');
                if(!nextbg) nextbg = '#FFF';
                $(this).css('background-color', nextbg); 
        } );
        }
    });
    $("table[trcheckbox=Y]").click(function(e) {
        var checked = $(e.target);
        var box = checked.parent().find('td > input:checkbox');
        if(!box[0]) return;
        box.attr('checked', !box.attr('checked'))
    });

    //tab切换
    $('div.sub-menu[data-container]').m_menu_tab();
});

//菜单 tab 切换
(function($){

    $.fn.m_menu_tab = function() {

        return this.each(function() {

            var $this = $(this);
            var container = $(this).data('container');
            var _init = function() {
                $this.find('> .sub-menu-item').each(function(index) {
                    $(this).focus(function() {
                        this.blur();
                    });
                    $(this).click(function(e) {
                        if($(this).data('type')=='url') return true;
                        e.preventDefault();
                        _click($(this), index)
                    });
                });
            }
            var _click = function(my, show_index) {
                var name = my.data('name');
                $(container).hide();
                $this.find('> .sub-menu-item').removeClass('selected');
                $this.find('> .sub-menu-item').eq(show_index).addClass('selected');
                if(name)
                    if(name=='*')
                        $(container+'[data-name]').show();
                    else
                        $(container+'[data-name="'+name+'"]').show();
                else
                    $(container).eq(show_index).show();
            }
            if(container) {
                _init();
            }
        });
    }

})(jQuery);

//提交多行为表单
function submit_op(formname, act, checkname) {
    submitform(formname, 'op', act, checkname);
}

function submitform(formname, actname, act, checkname) {
    var form = $('[name='+formname+']');
    if(checkname != null) {
        if(!checkbox_check(checkname)) return;
    }
    if(act == 'delete') {
        if(!confirm('确定要进行删除操作吗？')) return;
    }
    form.find('[name='+actname+']').val(act);
    form.submit();
}

//替换框架F5刷新
function resetEscAndF5(e) {
    e = e ? e : window.event;
    actualCode = e.keyCode ? e.keyCode : e.charCode;
    if(actualCode == 116 && parent.cpiframe) {
        parent.cpiframe.location.reload();
        if(document.all) {
            e.keyCode = 0;
            e.returnValue = false;
        } else {
            e.cancelBubble = true;
            e.preventDefault();
        }
    }
}

//选择城市所在地区
function select_city(obj,dstid) {
    var v = $(obj).val();
    if(!v) {
        $('#'+dstid).html('<option value="">全部</option>');
        return;
    }
    $.post("?module=modoer&act=area", {op:"select",city_id:v, in_ajax:1}, function(result) {
        if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            result = '<option value="">全部</option>' + result;
            $('#'+dstid).html(result);
        }
    });
}