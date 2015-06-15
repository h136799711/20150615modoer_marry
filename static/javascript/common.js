// 本js在jquery后加载
var charset = document.charset;
var userAgent = navigator.userAgent.toLowerCase();
var is_opera = userAgent.indexOf('opera') != -1 && opera.version();
var is_moz = (navigator.product == 'Gecko') && userAgent.substr(userAgent.indexOf('firefox') + 8, 3);
var is_ie = (userAgent.indexOf('msie') != -1 && !is_opera) && userAgent.substr(userAgent.indexOf('msie') + 5, 3);
var is_chrome = userAgent.indexOf('chrome') != -1 && userAgent.substr(userAgent.indexOf('chrome') + 7, 3);
var is_safari = userAgent.indexOf('safari') != -1 && userAgent.substr(userAgent.indexOf('safari') + 7, 3);
var ajaxPostGlobal = 0;
var post_max_size = 20840;
//处理斜杠
if(siteurl.substr(siteurl.length -1 , 1) != '/') siteurl += '/';
//全局变量
var GLOBAL = new Array();
GLOBAL['js'] = new Array();
GLOBAL['css'] = new Array();
GLOBAL['js_root'] = urlroot + '/static/javascript/';
GLOBAL['css_root'] = urlroot + '/static/images/';
GLOBAL['url'] = siteurl;
GLOBAL['mod'] = typeof(mod) == 'undefined' ? null : mod;
GLOBAL['domain'] = document.domain;

mo = new Object;

// 去空格
String.prototype.trim = function() {
    return this.replace(/(^\s*)|(\s*$)/g, "");
}
// 数组搜索
Array.prototype.indexof = function(value) {
    for (var i in this) {
        if(i==value) return this[i];
    }
    return -1;
}
// 判断字符串是否为数字
String.prototype.is_numeric = function() {
    var patn = /^[0-9]+$/;
    return patn.test(this);
}
// 转JS可用URL
String.prototype.url = function() {
    return this.replace('&amp;','&');
}
//是否modoer的ajax返回信息
String.prototype.is_message = function() {
    return is_message(this);
}
String.prototype.is_json = function() {
    return is_json(this);
};

//动态载入JS文件
function loadscript(filename) {
    if(filename == '') return;
    if(filename.indexOf(',') > 0) {
        var files = filename.split(',');
        if(typeof(files) != null && files.length > 0) {
            for (var i=0; i<files.length; i++) {
                loadscript(files[i]);
            }
        }
    } else {
        var file = filename + '.js';
        if(typeof(arguments[1]) == 'string' && arguments[1] != '') {
            var src = arguments[1] + file;
        } else {
            var src = GLOBAL['js_root'] + file;
        }
        if (GLOBAL['js'].indexof(file)>-1) return ;
        var j = document.createElement("script");
        j.setAttribute("type", "text/javascript");
        j.setAttribute("src", src);
        document.getElementsByTagName("head")[0].appendChild(j);
        GLOBAL['js'][file] = 1;
    }
}
//动态载入CSS文件
function loadcss(filename) {
    if(filename=='') return;
    if(filename.indexOf(',') > 0) {
        var files = filename.split(',');
        if(typeof(files) != null && files.length > 0) {
            for (var i=0; i<files.length; i++) {
                loadcss(files[i]);
            }
        }
    } else {
        var file = filename + '.css';
        if(typeof(arguments[1]) == 'string' && arguments[1] != '') {
            var src = arguments[1] + file;
        } else {
            var src = GLOBAL['css_root'] + file;
        }
        if (GLOBAL['css'].indexof(file)>-1) return;
        var c = document.createElement("link");
        c.setAttribute("rel", "stylesheet");
        c.setAttribute("type", "text/css");
        c.setAttribute("href", src);
        document.getElementsByTagName("head")[0].appendChild(c);
        GLOBAL['css'][file] = 1;
    }
}
// url标签格式转换成链接
function Url(url_format, anchor, full_url) {
    if(url_format == '') return getUrl('');
    var urlarr = url_format.split('/');
    var flag = urlarr[0];
    if(!urlarr[1]) return getUrl(flag);
    var act = urlarr[1];
    if(urlarr.length <= 2) return getUrl(flag, act);
    var param = split = '';
    for (var i=2; i<urlarr.length; i++) {
        param += split + urlarr[i] + '=' + urlarr[++i];
        split = '&';
    }
    return getUrl(flag, act, param, anchor, full_url);
}
//获取相对路径
function getUrl() {
    if(arguments.length == 0) {
        return typeof(mod)=='undefined' ? '' : '';
    }

    var flag = arguments[0];
    var fullurl = arguments[4];
    var crmod = modules[flag];
    var url = !fullurl ? (urlroot+'/') : siteurl;

    //if(fullalways) fullurl = true;

    if(flag == 'modoer') {
        url += 'index.php?m=index';
    } else if(typeof(crmod)=='undefined') {
        url += '';
    } else {
        url += 'index.php?m='+flag;
    }
    /*
    if(typeof(mod)=='undefined' && typeof(crmod)=='undefined') {
        url = !fullurl ? '' : siteurl;
    } else if(typeof(mod)=='undefined' && crmod) {
        url = (!fullurl ? '' : siteurl) + crmod['directory'] + '/';
    } else if(mod && typeof(crmod)=='undefined') {
        url = !fullurl ? '../' : siteurl;
    } else if(mod['flag'] != crmod['flag']) {
        url = (!fullurl ? '../' : siteurl) + crmod['directory'] + '/';
    } else if(mod['flag'] == crmod['flag']) {
        url = !fullurl ? '' : siteurl;
    }
    */

    if(typeof(arguments[1])=='string') {
        url += '&act='+arguments[1];
    } else {
        return url;
    }

    var param = arguments[2];
    var type = typeof(param);
    if(type=='object') {
        var split = '?';
        for (var i=0; i<param.length; i++) {
            url += split + param[i];
            split = '&';
        }
    } else if(type=='string') {
        url += '&' + param;
    }

    if(typeof(arguments[3])=='string') {
        url += '#' + arguments[3];
    }
    if(fullurl) url = siteurl + url;
    /*
    if(rewrite_mod == 'pathnfo') {
        url = url.replace('.php?act=','-');
        url = url.replace('=','-');
        url = url.replace('&amp;','-');
        url = url.replace('&','-');
        url = url.replace('.php','-');
        url += '.html';
    } else {
        url = url.replace('.php?act=','/');
        url = url.replace('=','/');
        url = url.replace('&amp;','/');
        url = url.replace('&','/');
        url = url.replace('.php','/');
    }*/
    return url;
}
//获取图片的URL
function getImageUrl(path, full_url) {
    return urlroot + '/' + path;
}
//判断一个ID是否是有效的数字型ID
function is_id(id,name) {
    if(!is_numeric(id)||id < 1) {
        alert('无效的' + name + '！');
        return false;
    }
    return true;
}
//解析Json
function parse_json(str) {
    data = trim(trim(str, String.fromCharCode(0)));
    var json = null;
    try {
        json = jQuery.parseJSON(data);
    } catch(exception) {
    } finally {
        return json;
    }
}
// 判断是否为数字
function is_numeric(mixed_var) {
    return (typeof mixed_var === 'number' || typeof mixed_var === 'string') && mixed_var !== '' && !isNaN(mixed_var);
}
//判断E-mail格式是否正确
function is_email(str) {
    var patn = /^[_a-zA-Z0-9\-]+(\.[_a-zA-Z0-9\-]*)*@[a-zA-Z0-9\-]+([\.][a-zA-Z0-9\-]+)+$/;
    if(!patn.test(str)) return false;
    return true;
}
//判断是否返回信息
function is_message(str) {
    return str.match(/\{\s+caption:".*",message:".*".*\s*\}/);
}
//判断是否为json
function is_json(str) {
    return str.match(/^\{.*:.*\}$/);
}
//判断是否为有效域名
function is_domain(domain) {
    domain = domain.trim();
    if(domain=='') return false;
    if(domain.indexOf('.')<=0) return false;
    if(domain.match(/^[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+$/)) return false;
    return domain.match(/^[a-z0-9][a-z0-9\-]+[a-z0-9](\.[a-z]{2,4})+$/i) != null;
}
//对等
function isEqual(objid1, objid2) {
    return objid1.value == objid1.value;
}
//取随机值
function getRandom() {
    return Math.floor(Math.random()*1000+1);
}
//字符串长度
function mb_strlen(str) {
    var len = 0;
    for(var i = 0; i < str.length; i++) {
        len += str.charCodeAt(i) < 0 || str.charCodeAt(i) > 255 ? (document.charset == 'utf-8' ? 3 : 2) : 1;
    }
    return len;
}
//提示对话框
function myAlert(data) {
    if(is_safari || is_moz) {
        //修正safari安装迅雷扩展后的问题
        var i = data.indexOf('<embed');
        if(i>=0) data = data.substring(0,i);
        i = data.indexOf('<div id="xunlei_');
        if(i>=0) data = data.substring(0,i);
    }
    var mymsg = eval('('+data+')'); //JSON转换
    mymsg.message = mymsg.message.replace('ERROR:','');
    if(mymsg.extra == 'login') {
        /*
        if(window.confirm(mymsg.caption + '，需要“登录”后才能继续。点击“确定”，登录网站。')) {
            window.location.href = mymsg.url;
        }
        */
        dialog_login();
    } else if(mymsg.extra == 'dlg') {
        dlgOpen(mymsg.caption, mymsg.message.replace("{LF}","\r\n"), 400, 300, true);
    } else if(mymsg.extra == 'msg') {
        msgOpen(mymsg.caption, mymsg.message.replace("{LF}","\r\n"));
    } else if (mymsg.extra == 'location') {
        document.location = mymsg.message;
    } else {
        if(mymsg.message) {
            alert(mymsg.message);
        }
        if(mymsg.url=='stop') {
            return;
        } else if(mymsg.url=='reload') {
            document_reload();
        } else if(mymsg.url) {
            jslocation(mymsg.url);
        }
    }
}
//向目标加入TAG（感谢eonbell测试）
function addtag(Id, tag, split) {
    if(!split) split=',';
    var str = $("#"+Id).val();
    if ((split+str+split).indexOf(split+tag+split) == -1) {
        str += str ? split + tag : tag;
        $("#"+Id).val(str);
    }
}
//增加行数(textarea)
function addrows(obj, num) {
    obj.rows += num;
}
//减少行数(textarea)
function decrows(obj, num) {
    if (obj.rows>num) {
        obj.rows -= num;
    }
}
//所有checkbox反选
function allchecked() { 
    var check = document.getElementsByTagName('input');
    for (var i=0; i<check.length; i++) {
        if (check[i].type == 'checkbox' && !check[i].disabled) {
            check[i].checked = !check[i].checked;
        }
    }
}
//name相同的checkbox反选
function checkbox_checked(name,obj) { 
    var check = document.getElementsByTagName('input');
    for (var i=0; i<check.length; i++) {
        if (check[i].type == 'checkbox' && check[i].name == name && !check[i].disabled) {
            if(obj) {
                check[i].checked = obj.checked;
            } else {
                check[i].checked = !check[i].checked;
            }
        }
    }
}
//检测checkbox是否有选中
function checkbox_check() {
    var check = document.getElementsByTagName('input');
    if(typeof(arguments[0]) == 'string') {
        var checkname = arguments[0];
    } else {
        var checkname = null;
    }
    var ischecked = false;
    for (var i=0; i<check.length; i++) {
        if (check[i].type == 'checkbox' && check[i].checked && !check[i].disabled) {
            ischecked = checkname == null || check[i].name == checkname;
            if(ischecked) break;
        }
    }
    if (!ischecked)
        alert('请至少选择一项！');
    return ischecked;
}
//检测radio是否有选择
function checkradio(obj) {
    if(obj) {
        var check=obj.getElementsByTagName('input');
    } else {
        var check=document.getElementsByTagName('input');
    }
    var ischecked = false;
    for (var i=0; i<check.length; i++) {
        if (check[i].type == 'radio' && check[i].checked) {
            ischecked=true;
        }
    }
    return ischecked;
}
//取单选框radio的值
function getRadio(from,name) {
    if(from) {
        var radios = from.getElementsByTagName('input');
    } else {
        var radios = document.getElementsByTagName('input');
    }
    if(!radios) return;
    var value='';
    for (var i=0; i<radios.length; i++) {
        if (radios[i].type == 'radio' && radios[i].name == name && radios[i].checked) {
            value=radios[i].value;
            break;
        }
    }
    return value;
}
//显示验证码
function show_seccode() {
    var id= (arguments[0]) ? arguments[0] : 'seccode';
    if($('#'+id).html()!='') return;
    var sec = $('#'+id).empty();
    var img = $('<img />')
            .css({weight:"80px", height:"25px", cursor:"pointer"})
            .attr("title",'点击更新验证码')
            .click(function() {
                this.src= Url('modoer/seccode/x/'+getRandom());
                $('#'+id).show();
            });
    sec.append(img);
    img.click();
}
//转换全角数字
function tot(mobnumber) {
    while(mobnumber.indexOf("０")!=-1){
        mobnumber = mobnumber.replace("０","0");
    }
    while(mobnumber.indexOf("１")!=-1){
        mobnumber = mobnumber.replace("１","1");}
    while(mobnumber.indexOf("２")!=-1){
        mobnumber = mobnumber.replace("２","2");}
    while(mobnumber.indexOf("３")!=-1){
        mobnumber = mobnumber.replace("３","3");}
    while(mobnumber.indexOf("４")!=-1){
        mobnumber = mobnumber.replace("４","4");}
    while(mobnumber.indexOf("５")!=-1){
        mobnumber = mobnumber.replace("５","5");}
    while(mobnumber.indexOf("６")!=-1){
        mobnumber = mobnumber.replace("６","6");}
    while(mobnumber.indexOf("７")!=-1){
        mobnumber = mobnumber.replace("７","7");}
    while(mobnumber.indexOf("８")!=-1){
        mobnumber = mobnumber.replace("８","8");}
    while(mobnumber.indexOf("９")!=-1){
        mobnumber = mobnumber.replace("９","9");}
    return mobnumber;
}
//长度判断
function checkByteLength(str,minlen,maxlen) {
    if (str == null) return false;
    var l = str.length;
    var blen = 0;
    for(i=0; i<l; i++) {
        if ((str.charCodeAt(i) & 0xff00) != 0) {
            blen ++;
        }
        blen ++;
    }
    if (blen > maxlen || blen < minlen) {
        return false;
    }
    return true;
}
//tab层切换
function tabSelect(showId,idpre) {
    for(i=1; i<=20; i++) {
        var tab = $("#" + idpre + i);
        if(!tab[0]) break;
        if (i == showId) { 
            $("#btn_"+idpre+i).attr("className","selected");
            $("#"+idpre+i).toggleClass("none");
            var isload = $("#"+idpre+i).attr('isload');
            var dn = $("#"+idpre+i).attr('datacallname');
            var pm = $("#"+idpre+i).attr('params');
            if(!isload && dn) {
                ajax_datacall(idpre+i, dn, pm);
            }
            $("#"+idpre+i).show()
         } else {
            $("#btn_"+idpre+i).attr("className","unselected");
            $("#"+idpre+i).hide();
        }
    }
}
//ajax方式获取调用数据
function ajax_datacall(id, name, params) {
    $("#"+id).html('<div style="padding:5px;">loading...</div>');
    if(params) {
        var json = eval('(' + params + ')');
        json.datacallname = decodeURIComponent(name);
        if(typeof(postLevel)!='undefined') json.postLevel = postLevel;
    } else {
        var json = {'datacallname':'name','postLevel':postLevel}
    }
    $.post(Url('modoer/ajax/op/get_datacall/in_ajax/1/do/datacall'), json,
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            $("#"+id).html(result);
            $("#"+id).attr('isload', '1');
        }
    });
}
//光标内插入字符
function insertText(obj,str) {
    if (document.selection) {
        var sel = document.selection.createRange();
        sel.text = str;
    } else if (typeof obj.selectionStart === 'number' && typeof obj.selectionEnd === 'number') {
        var startPos = obj.selectionStart,
            endPos = obj.selectionEnd,
            cursorPos = startPos,
            tmpStr = obj.value;
        obj.value = tmpStr.substring(0, startPos) + str + tmpStr.substring(endPos, tmpStr.length);
        cursorPos += str.length;
        obj.selectionStart = obj.selectionEnd = cursorPos;
    } else {
        obj.value += str;
    }
}
//操作选择
function selectOperation(select) {
    var url = select.options[select.selectedIndex].value;
    if(url) {
        var cfm = select.options[select.selectedIndex].getAttribute("cfm");
        select.selectedIndex = 0;
        if(cfm && confirm(cfm) || !cfm) {
            window.location = url;
        }
    }
    select.selectedIndex = 0;
}
//提交操作
function easy_submit(form_name,act_value,check_name) {
    submit_form(form_name,'op',act_value,null,null,check_name);
}
//按钮式多类型操作
function submit_form(form_name, act_name, act_value, param_name, param_value, check_name) {
    var form = $("[name="+form_name+"]");
    if(check_name != null) {
        if(!checkbox_check(check_name)) return;
    }

    if(act_value == 'delete') {
        if(!confirm('确定要进行删除操作吗？')) return;
    }

    if(form.find("[name="+act_name+"]")[0] == null) {
        form.append("<input type=\"hidden\" name=\""+act_name+"\" />");
    }
    form.find("[name="+act_name+"]").val(act_value);

    if(param_name != null || param_name != '') {
        form.find("[name="+param_name+"]").val(param_value);
    }

    form.submit();
}
//刷新当前页面
function document_reload() {
     document.location.reload();
}
//Ajax提交
function ajaxPost(formid,myid,func,use_data,dlgid,err_callback,set_domain) {
    if(typeof(set_domain)=='undefined'||set_domain==true) set_domain = true;
    //if(is_domain(sitedomain)) document.domain = sitedomain;
    if(ajaxPostGlobal != 0) {
        return false;
    }
    var iframeid = 'ajaxiframe';
    if($('#'+iframeid)[0] == null) {
        $(document.body).append('<iframe name="'+iframeid+'" id="'+iframeid+'"></iframe>');
    }
    var iframe = $('#'+iframeid);
    iframe.css("display","none");

    use_data = !use_data ? 0 : 1;
    ajaxPostGlobal = [formid, iframeid, myid, func, use_data];

    var form = $("#" + formid);
    form.attr("target", iframeid);
    form.append('<input type="hidden" name="in_ajax" value="1">');
    form.append('<input type="hidden" name="in_iframe" value="1">');
    iframe.unbind();
    iframe.load(function() {
        ajaxPostLoad(dlgid,err_callback,set_domain);
    });
    form[0].submit();
}
//ajax提交后返回
function ajaxPostLoad(dlgid, err_callback, set_domain) {
    if(set_domain && is_domain(sitedomain)) document.domain = sitedomain;
    if(ajaxPostGlobal==0) return;
    var ajaxparam = ajaxPostGlobal;
    ajaxPostGlobal = 0;
    var iframe = document.getElementById(ajaxparam[1]);
    var data = $(iframe.contentWindow.document.body).html();
    if(is_safari || is_moz) {
        //修正safari安装迅雷扩展后的问题
        var i = data.indexOf('<embed');
        if(i>=0) data = data.substring(0,i);
        i = data.indexOf('<div id="xunlei_');
        if(i>=0) data = data.substring(0,i);
    }
    //alert(data);
    if(data.indexOf('ERROR:') > 1) {
        if(err_callback) {
            err_callback(data);
        } else {
            myAlert(data.replace('ERROR:',''));
        }
        return;
    } else {
        dlgClose(dlgid);
        if(data.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(data);
        }
    }
    //alert(strip_tags(data));
    if(ajaxparam[3]) {
        if(ajaxparam[4])
            setTimeout(ajaxparam[3]+'(\'' + ajaxparam[2] + '\',\'' + data.replace("'","\\'") + '\');', 10);
        else
            setTimeout(ajaxparam[3]+'(\'' + ajaxparam[2] + '\');', 10);
    }
}
//设置cookie
function set_cookie(name, value, expireDays) {
    name = cookiepre + name;
    var datetime = 0;
    var expires = new Date();
    if(typeof(expireDays)!='number') expireDays = 1;
    //if(!expireDays) expireDays = 1;
    if(expireDays != 0) {
        expires.setTime(expires.getTime() + expireDays * 24 * 3600 * 1000);
        datetime = expires.toGMTString();
    }
    var cookiestr = '';
    cookiestr = name + '=' + escape(value) + '; path=' + cookiepath;
    if(cookiedomain != '') {
        cookiestr += '; domain=' + cookiedomain;
    }
    if(datetime) cookiestr += '; expires=' + datetime;
    document.cookie = cookiestr;
}
//读取cookie字串
function get_cookieval(start) {
    var end = document.cookie.indexOf(";", start);
    if(end == -1) {
        end = document.cookie.length;
    }
    return unescape(document.cookie.substring(start, end));
}
//读取cookie
function get_cookie(name) {
    name = cookiepre + name;
    var arg = name + "=";
    var alen = arg.length;
    var clen = document.cookie.length;
    var i = 0;
    while(i < clen) {
        var j = i + alen;
        if(document.cookie.substring(i, j) == arg) return get_cookieval(j);
        i = document.cookie.indexOf(" ", i) + 1;
        if(i == 0) break;
    }
    return null;
}
//删除cookie
function del_cookie(name) {
    var expires = new Date();
    expires.setTime (expires.getTime()-1);
    var cval = '';//get_cookie(name);
    name = cookiepre + name;
    var cookiestr = '';
    cookiestr = name + '=' + cval + '; path=' + cookiepath;
    if(cookiedomain != '') {
        cookiestr += '; domain=' + cookiedomain;
    }
    cookiestr += '; expires=' + expires.toGMTString();
    //alert(cookiestr);
    document.cookie = cookiestr;
}
//获取鼠标位置
function get_mousepos(e) {
    var x, y;
    var e = e||window.event;
    return {x:e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft, y:e.clientY + document.body.scrollTop + document.documentElement.scrollTop};
}
//移动层
function tip_start(obj, not_move) {
    var s = $(obj);
    if($("#tipdiv")[0] == null) {
        $(document.body).append("<div id=\"tipdiv\" style=\"position:absolute;left:0;top:0;display:none;\"></div>");
    }
    var t = $("#tipdiv");
    var one = false;
    s.mousemove(function(e) {
        if(not_move==1 && one) return;
        var mouse = get_mousepos(e);
        t.css("left", mouse.x + 10 + 'px');
        t.css("top", mouse.y + 10 + 'px');
        t.html("<img src='" + s.attr("src")+"' />");
        t.css("display", '');
        one = true;
    });
    s.mouseout(function() {
        t.css("display", 'none');
    });
}
//表单Ajax验证码
function check_seccode(value) {
    if(!value) {
        $('#msg_seccode').html('<font color="red">请输入验证码.</font>').show();
        return false;
    }
    $.post(Url('modoer/ajax/op/check_seccode'), {'seccode':value,'in_ajax':1}, function(data) {
        // if(is_message(data)) {
        //     var mymsg = eval('('+data+')'); //JSON转换
        //     data = mymsg.message;
            
        // }
        $('#msg_seccode').html(data).show();
    });
    return true;
}
//统计和检测文字输入
function record_charlen(obj,max,d_id) {
    var con = $(obj);
    var len = con.val().length;
    if(d_id) {
        $('#'+d_id).text(len);
    }
}
//加入表情code
function insert_smilies(cid,smid) {
    var text = "[/"+smid+"/]";
    $('#'+cid).insertAtCaret(text);
}
//地区选择
function area_select(level,id,value,select) {
    if(!$('#' + id)[0]) return;
    var sel = $('#' + id).empty();
    var selected = '';
    if(level>=2) sel.append('<option value='+value+'>=不限=</option>');
    $.each(_area.level[level], function(i, n) {
        $.each(_area.level[level][i],function(j, m) {
            if(!value || (value && value==i)) {
                selected = select == _area.level[level][i][j] ? ' selected="selected"' : '';
                sel.append($('<option value='+_area.level[level][i][j]+selected+'>'+_area.data[_area.level[level][i][j]]+'</option>'));
            }
        });
    });
    sel.css('width','auto');
}
//地区联动
function area_select_link(level) {
    var sel = $('#area_' + level);
    if(sel.val()=='') return;
    var next = level + 1;
    area_select(level,'area_' + next, sel.val());
    if(next <= 2) area_select_link(next);
}
//取得上级地区id
function area_parent_id(aid) {
    var result = 0;
    $.each(_area.level, function(i,n) {
        $.each(n, function(_i, _n) {
            $.each(_n, function(__i, __n) {
                if(__n == aid) {
                    result = _i;
                }
            });
        });
    });
    return result;
}
//根据指定的aid，自定设置3级联动地区
function area_auto_select(aid) {
    var link = new Array();
    link.push(aid);
    var pid = area_parent_id(aid);
    i=0;
    while (pid>0) {
        i++;
        link.push(pid);
        pid=area_parent_id(pid);
    }
    link = link.reverse();
    var value = 0;
    for (var i=0; i<3; i++) {
        area_select(i,'area_'+(i+1),value,link[i]);
        value = link[i];
        if(i>=link.length) {
            area_select_link(i);
        }
    }
}

//DIV内容替换,用于最后加载广告等行为
function replace_content(adlist) {
    if(!adlist) return;
    adlist = adlist.replace(' ','');
    adlist = adlist.split(',');
    for(i=0; i<adlist.length; i++) {
        if(!adlist[i]) continue;
        var adv = adlist[i];
        var adv = adv.split('=');
        $('#'+adv[0]).append($('#'+adv[1]).show());
    }
}
//更新列表显示方式和顺序
function list_display(keyname, value, url) {
    set_cookie('list_display_'+keyname, value, 0);
    if(!url) {
        document.location.reload();
    } else {
        document.location = url;
    }
}
//串联表单数据
function create_form_urlparam(formname) {
    var body = split = '';
    var form=document.forms[formname];
    for(var i=0;i<form.length;i++){
        //如果是单选按钮、复选框、单选下拉框
        if (form.elements[i].type == "radio" || form.elements[i].type == "checkbox" || form.elements[i].type == "select" ) {
            if(form.elements[i].checked && form.elements[i].name != ""){
                body += encodeURI(form.elements[i].name) + '=' + encodeURI(form.elements[i].value) + split;
            }
        }
        //如果是多选下拉框
        else if (form.elements[i].type == "select-multiple" && form.elements[i].name != "") {
            for(var sm=0;sm<form.elements[i].length;sm++){
                if(form.elements[i][sm].selected) {
                    body += encodeURI(form.elements[i].name) + '=' + encodeURI(form.elements[i][sm].value) + split;
                }
            }
        }
        //Button、Hidden、Password、Submit、Text、Textarea等文本类型
        else {
            if (form.elements[i].name != "") {
                body += encodeURI(form.elements[i].name) + '=' + encodeURI(form.elements[i].value) + split;
            }
        }
        split = '&';
    }
}

/* IE6 fix png */
function fixPNG(myImage) {
    var arVersion = navigator.appVersion.split("MSIE");
    var version = parseFloat(arVersion[1]);
    if ((version >= 5.5) && (version < 7) && (document.body.filters)) {
         var imgID = (myImage.id) ? "id='" + myImage.id + "' " : "";
         var imgClass = (myImage.className) ? "class='" + myImage.className + "' " : "";
         var imgTitle = (myImage.title) ? "title='" + myImage.title   + "' " : "title='" + myImage.alt + "' ";
         var imgStyle = "display:inline-block;" + myImage.style.cssText;
         var strNewHTML = "<span " + imgID + imgClass + imgTitle + " style=\"" + "width:" + myImage.width
            + "px; height:" + myImage.height + "px;" + imgStyle + ";"
            + "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader"
            + "(src=\'" + myImage.src + "\', sizingMethod='scale');\"></span>";
        myImage.outerHTML = strNewHTML;
    }
}

function find_pos(obj) {
    var x = y = 0;
    var obj1=obj2=obj;
    if(obj1.offsetParent) {
        while (obj1.offsetParent) {
            x += obj1.offsetLeft;
            obj1 = obj1.offsetParent;
        }
    } else if (obj1.x) {
        x += obj1.x;
    }
    if(obj2.offsetParent) {
        while (obj2.offsetParent) {
            y += obj2.offsetTop;
            obj2 = obj2.offsetParent;
        }
    } else if (obj2.y) {
        y += obj2.y;
    }
    return {x:x,y:y};
}

//js跳转，同时解决ie丢失referfer
function jslocation (url) {
    url = url.replace(/&amp;/ig, "&");
    if($.browser.msie && $.browser.version == '6.0' ) {
        document.write("<a id='jslocation' href='"+url+"' style='display:none;'>a</a>");
        document.getElementById("jslocation").click();
    } else {
        document.location = url;
    }
}

function round (value, precision, mode) {
    var m, f, isHalf, sgn; // helper variables
    precision |= 0; // making sure precision is integer
    m = Math.pow(10, precision);
    value *= m;
    sgn = (value > 0) | -(value < 0); // sign of the number
    isHalf = value % 1 === 0.5 * sgn;
    f = Math.floor(value);
    if (isHalf) {
        switch (mode) {
        case 'PHP_ROUND_HALF_DOWN':
            value = f + (sgn < 0); // rounds .5 toward zero
            break;
        case 'PHP_ROUND_HALF_EVEN':
            value = f + (f % 2 * sgn); // rouds .5 towards the next even integer
            break;
        case 'PHP_ROUND_HALF_ODD':
            value = f + !(f % 2); // rounds .5 towards the next odd integer
            break;
        default:
            value = f + (sgn > 0); // rounds .5 away from zero
        }
    }
    return (isHalf ? value : Math.round(value)) / m;
}

function strip_tags (input, allowed) {
    allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join(''); 
    var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
        commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
    return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
        return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
    });
}

function basename (path, suffix) {
    var b = path.replace(/^.*[\/\\]/g, '');
    if (typeof(suffix) == 'string' && b.substr(b.length - suffix.length) == suffix) {
        b = b.substr(0, b.length - suffix.length);
    }
    return b;
}

function array_push (inputArr) {
  var i = 0,
    pr = '',
    argv = arguments,
    argc = argv.length,
    allDigits = /^\d$/,
    size = 0,
    highestIdx = 0,
    len = 0;
  if (inputArr.hasOwnProperty('length')) {
    for (i = 1; i < argc; i++) {
      inputArr[inputArr.length] = argv[i];
    }
    return inputArr.length;
  }

  // Associative (object)
  for (pr in inputArr) {
    if (inputArr.hasOwnProperty(pr)) {
      ++len;
      if (pr.search(allDigits) !== -1) {
        size = parseInt(pr, 10);
        highestIdx = size > highestIdx ? size : highestIdx;
      }
    }
  }
  for (i = 1; i < argc; i++) {
    inputArr[++highestIdx] = argv[i];
  }
  return len + i - 1;
}

function nl2br (str, is_xhtml) {
  var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br ' + '/>' : '<br>';
  return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

function trim (str, charlist) {
  var whitespace, l = 0,
    i = 0;
  str += '';

  if (!charlist) {
    // default list
    whitespace = " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
  } else {
    // preg_quote custom list
    charlist += '';
    whitespace = charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '$1');
  }

  l = str.length;
  for (i = 0; i < l; i++) {
    if (whitespace.indexOf(str.charAt(i)) === -1) {
      str = str.substring(i);
      break;
    }
  }

  l = str.length;
  for (i = l - 1; i >= 0; i--) {
    if (whitespace.indexOf(str.charAt(i)) === -1) {
      str = str.substring(0, i + 1);
      break;
    }
  }

  return whitespace.indexOf(str.charAt(0)) === -1 ? str : '';
}

/* jquery文字插入文本光标处
 * $("#ID").insertAtCaret("text");
 */
(function($){$.fn.extend({insertAtCaret:function(myValue){var $t=$(this)[0];if(document.selection){this.focus();sel=document.selection.createRange();sel.text=myValue;this.focus();}else
if($t.selectionStart||$t.selectionStart=='0'){var startPos=$t.selectionStart;var endPos=$t.selectionEnd;var scrollTop=$t.scrollTop;$t.value=$t.value.substring(0,startPos)+myValue+$t.value.substring(endPos,$t.value.length);this.focus();$t.selectionStart=startPos+myValue.length;$t.selectionEnd=startPos+myValue.length;$t.scrollTop=scrollTop;}else{this.value+=myValue;this.focus();}}})})(jQuery);

/*! Copyright (c) 2013 Brandon Aaron (http://brandon.aaron.sh)
 * Licensed under the MIT License (LICENSE.txt).
 * Version: 3.1.9
 * jquery鼠标滚轮事件插件
 */
(function(e){typeof define=="function"&&define.amd?define(["jquery"],e):typeof exports=="object"?module.exports=e:e(jQuery)})(function(e){function a(t){var n=t||window.event,o=r.call(arguments,1),u=0,a=0,c=0,h=0;t=e.event.fix(n),t.type="mousewheel","detail"in n&&(c=n.detail*-1),"wheelDelta"in n&&(c=n.wheelDelta),"wheelDeltaY"in n&&(c=n.wheelDeltaY),"wheelDeltaX"in n&&(a=n.wheelDeltaX*-1),"axis"in n&&n.axis===n.HORIZONTAL_AXIS&&(a=c*-1,c=0),u=c===0?a:c,"deltaY"in n&&(c=n.deltaY*-1,u=c),"deltaX"in n&&(a=n.deltaX,c===0&&(u=a*-1));if(c===0&&a===0)return;if(n.deltaMode===1){var p=e.data(this,"mousewheel-line-height");u*=p,c*=p,a*=p}else if(n.deltaMode===2){var d=e.data(this,"mousewheel-page-height");u*=d,c*=d,a*=d}h=Math.max(Math.abs(c),Math.abs(a));if(!s||h<s)s=h,l(n,h)&&(s/=40);return l(n,h)&&(u/=40,a/=40,c/=40),u=Math[u>=1?"floor":"ceil"](u/s),a=Math[a>=1?"floor":"ceil"](a/s),c=Math[c>=1?"floor":"ceil"](c/s),t.deltaX=a,t.deltaY=c,t.deltaFactor=s,t.deltaMode=0,o.unshift(t,u,a,c),i&&clearTimeout(i),i=setTimeout(f,200),(e.event.dispatch||e.event.handle).apply(this,o)}function f(){s=null}function l(e,t){return u.settings.adjustOldDeltas&&e.type==="mousewheel"&&t%120===0}var t=["wheel","mousewheel","DOMMouseScroll","MozMousePixelScroll"],n="onwheel"in document||document.documentMode>=9?["wheel"]:["mousewheel","DomMouseScroll","MozMousePixelScroll"],r=Array.prototype.slice,i,s;if(e.event.fixHooks)for(var o=t.length;o;)e.event.fixHooks[t[--o]]=e.event.mouseHooks;var u=e.event.special.mousewheel={version:"3.1.9",setup:function(){if(this.addEventListener)for(var t=n.length;t;)this.addEventListener(n[--t],a,!1);else this.onmousewheel=a;e.data(this,"mousewheel-line-height",u.getLineHeight(this)),e.data(this,"mousewheel-page-height",u.getPageHeight(this))},teardown:function(){if(this.removeEventListener)for(var e=n.length;e;)this.removeEventListener(n[--e],a,!1);else this.onmousewheel=null},getLineHeight:function(t){return parseInt(e(t)["offsetParent"in e.fn?"offsetParent":"parent"]().css("fontSize"),10)},getPageHeight:function(t){return e(t).height()},settings:{adjustOldDeltas:!0}};e.fn.extend({mousewheel:function(e){return e?this.bind("mousewheel",e):this.trigger("mousewheel")},unmousewheel:function(e){return this.unbind("mousewheel",e)}})})
