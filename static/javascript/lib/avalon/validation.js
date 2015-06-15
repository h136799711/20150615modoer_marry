define("lib/avalon/validation",["lib/avalon/promise"],function(d,f,b){d("lib/avalon/promise");function a(t){if((/^\d{15}$/).test(t)){return true}else{if((/^\d{17}[0-9xX]$/).test(t)){var u="1,0,x,9,8,7,6,5,4,3,2".split(","),s="7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2".split(","),p=t.toLowerCase().split(""),q=0;for(var o=0;o<17;o++){q+=s[o]*p[o]}return(u[q%11]==p[17])}}}function n(r){if(e.test(r)){var p=parseInt(RegExp.$1,10);var s=parseInt(RegExp.$2,10);var q=parseInt(RegExp.$3,10);var o=new Date(q,s-1,p,12,0,0,0);if((o.getUTCFullYear()===q)&&(o.getUTCMonth()===s-1)&&(o.getUTCDate()===p)){return true}}return false}var e=/^\d{4}\-\d{1,2}\-\d{1,2}$/;var h=/^([A-Z0-9]+[_|\_|\.]?)*[A-Z0-9]+@([A-Z0-9]+[_|\_|\.]?)*[A-Z0-9]+\.[A-Z]{2,3}$/i;var l=/^(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)$/i;var j=/^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(([0-9A-Fa-f]{1,4}:){0,5}:((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(::([0-9A-Fa-f]{1,4}:){0,5}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$/i;var m={cm:/^(?:0?1)((?:3[56789]|5[0124789]|8[278])\d|34[0-8]|47\d)\d{7}$/,cu:/^(?:0?1)(?:3[012]|4[5]|5[356]|8[356]\d|349)\d{7}$/,ce:/^(?:0?1)(?:33|53|8[079])\d{8}$/,cn:/^(?:0?1)[3458]\d{9}$/};avalon.mix(avalon.duplexHooks,{user:{message:"请输入你的姓名，7个中文以内",get:function(q,p,o){o(/^[\u0391-\uFFE5]+$/.test(q));return q}},trim:{get:function(p,o){if(o.element.type!=="password"){p=String(p||"").trim()}return p}},gender:{message:"选择你的角色",get:function(q,p,o){o(q!=="");return q}},role:{message:"选择你的角色",get:function(q,p,o){o(q!=="0");return q}},required:{message:"内容不能为空",get:function(q,p,o){o(q!=="");return q}},password:{message:"请输入6位以上字符",get:function(q,p,o){o(/^[^\s]{6,}$/.test(q));return q}},phone:{message:"手机号码不符合规范",get:function(s,r,q){var p=false;for(var o in m){if(m[o].test(s)){p=true;break}}q(p);return s}},alpha:{message:"必须是字母",get:function(q,p,o){o(/^[a-z]+$/i.test(q));return q}},alpha_numeric:{message:"必须为字母或数字",get:function(q,p,o){o(/^[a-z0-9]+$/i.test(q));return q}},numeric:{message:"该项必须为数字",get:function(q,p,o){o(!isNaN(q)&&q!=="");return q}},alpha_dash:{message:"必须为字母或数字及下划线等特殊字符",validate:function(q,p,o){o(/^[a-z0-9_\-]+$/i.test(q));return q}},chs:{message:"必须是中文字符",get:function(q,p,o){o(/^[\u4e00-\u9fa5]+$/.test(q));return q}},chs_numeric:{message:"必须是中文字符或数字及下划线等特殊字符",get:function(q,p,o){o(/^[\\u4E00-\\u9FFF0-9_\-]+$/i.test(q));return q}},qq:{message:"腾讯QQ号从10000开始",get:function(q,p,o){o(/^[1-9]\d{4,10}$/.test(q));return q}},id:{message:"身份证格式错误",get:function(q,p,o){o(a(q));return q}},email:{message:"邮件地址错误",get:function(r,q,p){var o=this;if(!h.test(r)){o.message="邮件地址错误";p(h.test(r));return r}else{$.getJSON("/u/ajax/checkuser/",{email:r},function(s){if(s.error){o.message="该邮箱帐号已存在";p(false)}else{p(true)}});return r}}},domain:{message:"域名格式错误",get:function(r,q,p){var o=this;if(!/^[a-z0-9\-]+$/i.test(r)){o.message="域名格式错误,必须为字母或数字等特殊字符";p(h.test(r));return r}else{$.getJSON("/u/ajax/check_wedding_domain",{domain:r},function(s){if(s.error){o.message="此域名已被注册了哦~";p(false)}else{p(true)}});return r}}},time:{message:"婚期不能为空",get:function(q,p,o){var r=q.replace(/(^\s*)|(\s*$)/g,"");o(r!=="");return q}},fantasy:{message:"昵称不能为空",get:function(r,q,p){var o=this;if(r.replace(/(^\s*)|(\s*$)/g,"")==""){o.message="昵称不能为空";p(false);return r}else{$.getJSON("/u/ajax/checkuser/?username="+r,function(s){if(s.error){o.message="该昵称已存在";p(false)}else{p(true)}});return r}}},emailtext:{message:"邮件格式错误",get:function(q,p,o){o(h.test(q));return q}},url:{message:"URL格式错误",get:function(q,p,o){o(/^(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?$/.test(q));return q}},repeat:{message:"两次密码输入不一致",get:function(r,q,p){var s=q.element.getAttribute("data-duplex-repeat")||"";var o=avalon(document.getElementById(s)).val()||"";p(r===o);return r}},minlength:{message:"最少输入{{min}}个字",get:function(t,s,q){var r=s.element;var o=parseInt(r.getAttribute("minlength"),10);if(!isFinite(o)){o=parseInt(r.getAttribute("data-duplex-minlength"),10)}var p=s.data.min=o;q(t.length>=p);return t}},maxlength:{message:"最多输入{{max}}个字",get:function(t,s,q){var r=s.element;var o=parseInt(r.getAttribute("maxlength"),10);if(!isFinite(o)){o=parseInt(r.getAttribute("data-duplex-maxlength"),10)}var p=s.data.max=o;q(t.length<=p);return t}},pattern:{message:"必须匹配/{{pattern}}/这样的格式",get:function(v,u,r){var s=u.element;var q=s.getAttribute("pattern");var o=s.getAttribute("data-duplex-pattern");var t=u.data.pattern=q||o;var p=new RegExp("^(?:"+t+")$");r(p.test(v));return v}}});function c(r){if(r.target){return r}var o={};for(var p in r){o[p]=r[p]}var t=o.target=r.srcElement;if(r.type.indexOf("key")===0){o.which=r.charCode!=null?r.charCode:r.keyCode}else{if(/mouse|click/.test(r.type)){var s=t.ownerDocument||document;var q=s.compatMode==="BackCompat"?s.body:s.documentElement;o.pageX=r.clientX+(q.scrollLeft>>0)-(q.clientLeft>>0);o.pageY=r.clientY+(q.scrollTop>>0)-(q.clientTop>>0);o.wheelDeltaY=o.wheelDelta;o.wheelDeltaX=0}}o.timeStamp=new Date-0;o.originalEvent=r;o.preventDefault=function(){r.returnValue=false};o.stopPropagation=function(){r.cancelBubble=true};return o}var g=avalon.ui.validation=function(q,s,p){var o=s.validationOptions;var r;var t=avalon.define(s.validationId,function(u){avalon.mix(u,o);u.$skipArray=["widgetElement","data","validationHooks","validateInKeyup","validateAllInSubmit","resetInBlur"];u.widgetElement=q;u.data=[];u.$init=function(){q.setAttribute("novalidate","novalidate");avalon.scan(q,[t].concat(p));if(u.validateAllInSubmit){r=avalon.bind(q,"submit",function(v){v.preventDefault();u.validateAll(u.onValidateAll)})}if(typeof o.onInit==="function"){o.onInit.call(q,t,o,p)}};u.$destory=function(){u.data=[];r&&avalon.unbind(q,"submit",r);q.textContent=q.innerHTML=""};u.validateAll=function(x){var v=typeof x==="function"?x:u.onValidateAll;var w=u.data.filter(function(y){return y.element&&!y.element.disabled&&t.widgetElement.contains(y.element)}).map(function(y){return u.validate(y,true)});Promise.all(w).then(function(B){var A=[];for(var y=0,z;z=B[y++];){A=A.concat(z)}v.call(u.widgetElement,A)})};u.resetAll=function(w){u.data.filter(function(x){return x.element}).forEach(function(x){try{u.onReset.call(x.element,{type:"reset"},x)}catch(y){}});var v=typeof w=="function"?w:u.onResetAll;v.call(u.widgetElement)};u.validate=function(A,D,v){var C=A.valueAccessor();var w=t.validationHooks;var y=avalon.duplexHooks;var B=[];var z=A.element;A.validateParam.replace(/\w+/g,function(E){var I=w[E]||y[E];if(!z.disabled){var H,G;B.push(new Promise(function(K,J){H=K;G=J}));var F=function(J){if(A.norequired){J=true}delete A.norequired;if(J){H(true)}else{var K={element:z,data:A.data,message:z.getAttribute("data-duplex-message")||I.message,validateRule:E,getMessage:k};H(K)}};A.data={};I.get(C,A,F)}});var x=Promise.all(B).then(function(H){var G=[];for(var E=0,F;F=H[E++];){if(typeof F==="object"){G.push(F)}}if(!D){if(G.length){u.onError.call(z,G,v)}else{u.onSuccess.call(z,G,v)}u.onComplete.call(z,G,v)}return G});return x};u.$watch("avalon-ms-duplex-init",function(x){var v=t.validationHooks;x.valueAccessor=x.evaluator.apply(null,x.args);switch(avalon.type(x.valueAccessor())){case"array":x.valueResetor=function(){this.valueAccessor([])};break;case"boolean":x.valueResetor=function(){this.valueAccessor(false)};break;case"number":x.valueResetor=function(){this.valueAccessor(0)};break;default:x.valueResetor=function(){this.valueAccessor("")};break}var y=avalon.duplexHooks;if(typeof x.pipe!=="function"&&avalon.contains(q,x.element)){var z=[];var w=[];x.param.replace(/\w+/g,function(B){var C=v[B]||y[B];if(C&&typeof C.get==="function"&&C.message){w.push(B)}else{z.push(B)}});x.validate=u.validate;x.param=z.join("-");x.validateParam=w.join("-");if(w.length){if(u.validateInBlur){x.bound("blur",function(B){u.validate(x,0,c(B))})}if(u.resetInFocus){x.bound("focus",function(B){u.onReset.call(x.element,c(B),x)})}var A=u.data.filter(function(B){return B.element});avalon.Array.ensure(A,x);u.data=A}return false}})});return t};var i=/\\?{{([^{}]+)\}}/gm;function k(){var o=this.data||{};return this.message.replace(i,function(q,p){return o[p]==null?"":o[p]})}g.defaults={validationHooks:{},onSuccess:avalon.noop,onError:avalon.noop,onComplete:avalon.noop,onValidateAll:avalon.noop,onReset:avalon.noop,onResetAll:avalon.noop,validateInBlur:true,validateInKeyup:true,validateAllInSubmit:true,resetInFocus:true}});