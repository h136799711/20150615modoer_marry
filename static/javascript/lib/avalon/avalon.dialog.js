define("dialog",["../avalon.getModel"],function(a,b,c){function d(a){var b=a;if(b?"string"===avalon.type(b)&&(b=avalon.vmodels[b]):b=avalon.define("dialogVM"+setTimeout("1"),function(a){}),!b)throw new Error("您查找的"+b+"不存在");return!avalon.isPlainObject(b)||b.$id||b.$accessors||(b=avalon.define("dialogVM"+setTimeout("1"),function(b){avalon.mix(b,a)})),[].concat(b)}function e(a,b,c,d){var e,f=null;return function(){var g=this,h=+new Date;clearTimeout(f),e||(e=h),h-e>=c?(a.apply(g,d),e=h):f=setTimeout(function(){a.apply(g,d)},b)}}function f(a,b,c){var d,e,f,g,h,i,j,k=avalon(l),m=avalon(o),n=avalon(b),q=0,s=0;if(a.toggle&&(j=document.compatMode&&"css1compat"==document.compatMode.toLowerCase()?document.documentElement:document.body,d=document.documentElement.clientWidth?document.documentElement.clientWidth:document.body.clientWidth,e=document.documentElement.clientHeight?document.documentElement.clientHeight:document.body.clientHeight,h=document.body.scrollTop+document.documentElement.scrollTop,i=j.scrollLeft,f=b.offsetWidth,g=b.offsetHeight,q=e>g?(e-g)/2:0,s=d>f?(d-f)/2+i:0,e>g&&d>f?r||(a.position="fixed"):r||(a.position="absolute"),!c||"fixed"!=a.position)){if("absolute"===a.position){if(p.length>1)for(var t=0;t<p.length-1;t++)p[t].widgetElement.style.display="none";k.css({height:e,width:d,top:h,position:"absolute"}),m.css({height:e,width:d,top:h,overflow:"auto",position:"absolute"})}else{if(p.length>1)for(var t=0;t<p.length-1;t++)p[t].widgetElement.style.display="block";k.css({height:"auto",width:"auto",top:0,position:"fixed"}),m.css({height:"auto",width:"auto",top:0,position:"static"})}n.css({left:s,top:q})}}function g(){for(var a,b,c=document.body.children,d=10,e=0;b=c[e++];)if(1===b.nodeType){if(b===l)continue;a=~~avalon(b).css("z-index"),a&&(d=Math.max(d,a))}return d+1}function h(){var a=document.createElement('<iframe src="javascript:\'\'" style="position:absolute;top:0;left:0;bottom:0;margin:0;padding:0;right:0;zoom:1;width:'+l.style.width+";height:"+l.style.height+";z-index:"+(l.style.zIndex-1)+';"></iframe>');return document.body.appendChild(a),a}var i='<div class="oni-dialog-layout"></div>MS_OPTION_WIDGET<div ms-widget="dialog,MS_OPTION_ID,MS_OPTION_OPTS" ms-css-position="position">MS_OPTION_DIALOG_CONTENT</div>MS_OPTION_INNERWRAPPER<div class="oni-dialog-inner"></div>MS_OPTION_HEADER<div class="oni-dialog-header"><div class="oni-dialog-close" ms-click="_close" ms-if="showClose"><i class="iconfont">&#xe622;</i></div><div class="oni-dialog-title" ms-html="title"></div></div>MS_OPTION_CONTENT<div class="oni-dialog-content"></div>MS_OPTION_FOOTER<div class="oni-dialog-footer oni-helper-clearfix"><div class="oni-dialog-btns"><button ms-widget="button" data-button-color="success" ms-hover="oni-state-hover" ms-click="_confirm" ms-text="confirmName"></button><button ms-widget="button" ms-if="type == confirmStr" ms-click="_cancel" ms-text="cancelName"></button></div></div>MS_OPTION_LAYOUT_SIMULATE<div></div>',j=i.split("MS_OPTION_WIDGET"),k=j[0],l=avalon.parseHTML(k).firstChild,m=!1,n=i.split("MS_OPTION_LAYOUT_SIMULATE")[1],o=avalon.parseHTML(n).firstChild,p=[],q=0,r=-1!==(window.navigator.userAgent||"").toLowerCase().indexOf("msie 6"),s=null,t=document.compatMode&&"css1compat"==document.compatMode.toLowerCase()?document.documentElement:document.body,u=avalon.ui.dialog=function(a,b,c){q++;var j=b.dialogOptions;j.type=j.type.toLowerCase(),j.template=j.getTemplate(i,j);var k=j.template.split("MS_OPTION_FOOTER"),n=k[0].split("MS_OPTION_CONTENT"),u=n[0].split("MS_OPTION_HEADER"),v=u[0].split("MS_OPTION_INNERWRAPPER"),w=n[1],x=u[1],y=k[1].split("MS_OPTION_LAYOUT_SIMULATE")[0],z=v[1],A="",B="",C=avalon(a),D=j.onConfirm,E=null,F=j.onCancel,G=null,H=j.onOpen,I=null,J=j.onClose,K=null,L=!0,M=r?"absolute":"fixed";"string"==typeof D&&(E=avalon.getModel(D,c),j.onConfirm=E&&E[1][E[0]]||avalon.noop),"string"==typeof F&&(G=avalon.getModel(F,c),j.onCancel=G&&G[1][G[0]]||avalon.noop),"string"==typeof J&&(avalon.log("ms-widget='dialog' data-dialog-on-close 此用法已经被废弃"),K=avalon.getModel(J,c),j.onClose=K&&K[1][K[0]]||avalon.noop),"string"==typeof H&&(I=avalon.getModel(H,c),j.onOpen=I&&I[1][I[0]]||avalon.noop),y=j.getFooter(y,j);var N=avalon.define(b.dialogId,function(b){avalon.mix(b,j),b.confirmStr="confirm",b.$skipArray=["widgetElement","template","container","modal","zIndexIncrementGlobal","initChange","content"],b.widgetElement=a,b.position=M,b.showClose="alert"===b.type?!1:b.showClose,b.initChange=!0,b._confirm=function(a){if("function"!=typeof N.onConfirm)throw new Error("onConfirm必须是一个回调方法");N.onConfirm.call(a.target,a,N)!==!1&&N._close(a)},b._open=function(b){var c=0,d=document.getElementsByTagName("select").length,e=N.zIndex;avalon.Array.ensure(p,N),c=p.length,c&&(avalon(l).css("display","block"),avalon(o).css("display","block")),l.style.zIndex=2*c+e-1,o.style.zIndex=2*c+e-1,a.style.zIndex=2*c+e,b||(document.documentElement.style.overflow="hidden",f(N,a),r&&d&&null===s&&N.modal?s=h():r&&d&&N.modal&&(s.style.display="block",s.style.width=l.style.width,s.style.height=l.style.height,s.style.zIndex=l.style.zIndex-1),N.onOpen.call(a,N))},b._close=function(c){avalon.Array.remove(p,b);var d=p.length,e=N.zIndex,g=d&&p[d-1];if(c&&(L=!1),N.toggle=!1,!g||!g.modal)return avalon(l).css("display","none"),avalon(o).css("display","none"),null!==s&&(s.style.display="none"),document.documentElement.style.overflow="",void N.onClose.call(a,N);avalon(l).css("display","block"),avalon(o).css("display","block"),g.widgetElement.style.display="block",f(g,g.widgetElement);var h=2*d+e-1;l.style.zIndex=h,o.style.zIndex=h,s&&(s.style.zIndex=h-1),N.onClose.call(a,N)},b._cancel=function(a){if("function"!=typeof N.onCancel)throw new Error("onCancel必须是一个回调方法");N.onCancel.call(a.target,a,N)!==!1&&N._close(a)},b.setContent=function(a,b,d){var e=d?d:[N].concat(c);return A=a,B.innerHTML=A,b||avalon.scan(B,e),N},b.setTitle=function(a){return N.title=a,N},b.setModel=function(a){return a.$content&&N.setContent(a.$content,a.noScan,[N].concat(d(a)).concat(c)),a.$title&&(N.title=a.$title),N},b._renderView=function(){var b=null;a.setAttribute("ms-css-width","width"),B=avalon.parseHTML(w).firstChild,A=a.innerHTML||N.content,a.innerHTML="",B.innerHTML=A,b=avalon.parseHTML(z).firstChild,b.innerHTML=x,b.appendChild(B),b.appendChild(avalon.parseHTML(y)),a.appendChild(b),m||(document.body.appendChild(l),document.body.appendChild(o),m=!0)},b.$init=function(d){var h=N.container,i=t.clientHeight,k=document.body,l=("object"===avalon.type(h)&&1===h.nodeType&&k.contains(h)?h:document.getElementById(h))||k,m=avalon.ui.dialog.defaults;m.zIndex||(m.zIndex=g()),avalon(k).height()<i&&avalon(k).css("min-height",i),N.zIndex=N.zIndex+N.zIndexIncrementGlobal,N.title=N.title||"&nbsp;",C.addClass("oni-dialog"),a.setAttribute("ms-visible","toggle"),a.setAttribute("ms-css-position","position"),b._renderView(),k.contains(o)&&k==l?o.appendChild(a):l.appendChild(a),a.resizeCallback=avalon(window).bind("resize",e(f,50,100,[N,a])),a.scrollCallback=avalon(window).bind("scroll",e(f,50,100,[N,a,!0])),avalon.scan(a,[N].concat(c)),d?d():(avalon.log("avalon请尽快升到1.3.7+"),avalon.scan(a,[N].concat(c)),"function"==typeof j.onInit&&j.onInit.call(a,N,j,c))},b.$remove=function(){q--,a.innerHTML="",avalon.unbind(window,"resize",a.resizeCallback),avalon.unbind(window,"scroll",a.scrollCallback),q||(l.parentNode.removeChild(l),l.parentNode.removeChild(o),m=!1)},b.$watch("toggle",function(a){a?N._open():L===!1?L=!0:N._close()}),b.$watch("zIndex",function(a){N.initChange?N.initChange=!1:N._open(!0)})});return N};u.version=1,u.defaults={width:480,title:"&nbsp;",draggable:!1,type:"confirm",content:"",onConfirm:avalon.noop,onOpen:avalon.noop,onCancel:avalon.noop,onClose:avalon.noop,setTitle:avalon.noop,setContent:avalon.noop,setModel:avalon.noop,showClose:!0,toggle:!1,widgetElement:"",container:"body",confirmName:"确定",cancelName:"取消",getTemplate:function(a,b){return a},getFooter:function(a){return a},modal:!0,zIndex:0,zIndexIncrementGlobal:0},avalon(window).bind("keydown",function(a){var b=a.which,c=p.length;27===b&&c&&(p[c-1].toggle=!1)}),c.exports=avalon});