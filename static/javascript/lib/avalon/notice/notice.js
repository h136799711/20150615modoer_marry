define("lib/avalon/notice/notice",["../avalon.getModel"],function(a,b,c){function d(){for(var a=avalon(document).scrollTop(),b=0,c=k.length;c>b;b++){var d=k[b],e=d.style,f=avalon(d),g=d.vmodel;if(a>=l[b][2]){if("fixed"!==e.position||m&&"absolute"!==e.position){for(var h=0,i=0,j=1;b>=j;j++)h+=l[j-1][0];h=m?a+h:h,i=l[b][3],f.css({width:l[b][1]+"px",top:h+"px",left:i+"px",position:m?"absolute":"fixed","z-index":n}),g.affixPlaceholderDisplay="block"}}else f.css("position","static"),g.affixPlaceholderDisplay="none"}}function e(){for(var a,b,c=document.body.children,d=10,e=0;b=c[e++];)1===b.nodeType&&(a=~~avalon(b).css("z-index"),a&&(d=Math.max(d,a)));return d+1}function f(a){return a.indexOf("-")<0&&a.indexOf("_")<0?a:a.replace(/[-_][^-_]/g,function(a){return a.charAt(1).toUpperCase()})}function g(a){var b,c=["","-webkit-","-o-","-moz-","-ms-"],d=document.documentElement.style;for(b in c)if(camelCase=f(c[b]+a),camelCase in d)return!0;return!1}function h(a,b){function c(){return d=a?d+1:d-1,0>d?void(b.height=0):d>e?(b.height=e,void setTimeout(function(){b.$fire("elementHeightOk")},600)):(b.height=d,void setTimeout(c,0))}var d,e=b.elementHeight;g("transition")?(d=a?e:0,a?b.height=d:setTimeout(function(){b.height=d},10),d&&setTimeout(function(){b.$fire("elementHeightOk")},600)):(d=a?0:e,c())}a("../avalon.getModel");var i='<div class="oni-notice" ms-class="{{typeClass}}" ms-css-height="height" ms-visible="toggle"><div class="oni-notice-inner"><div class="oni-notice-header" ms-if="!!header" ms-html="title"></div><div class="oni-notice-content"><span class="js_notice_content" ms-html="content"></span><span class="oni-notice-close js_notice_close" ms-if="hasCloseBtn" ms-click="_close">X关闭</span></div></div></div><div ms-if="_isAffix" ms-css-width="noticeAffixWidth" ms-css-height="noticeAffixHeight" ms-css-display="affixPlaceholderDisplay"></div>',j=[],k=[],l=[],m=-1!==(window.navigator.userAgent||"").toLowerCase().indexOf("msie 6"),n=e(),o=avalon.ui.notice=function(a,b,c){function e(){x.timer&&(window.clearTimeout(x.$closeTimer),x.$closeTimer=window.setTimeout(function(){x.toggle=!1},x.timer))}function f(){if(x._isAffix){var a=avalon(r),b=a.offset(),c=r.offsetWidth,e=r.offsetHeight;x.noticeAffixWidth=c,x.noticeAffixHeight=e,r.vmodel=x,k.push(r),l.push([e,c,b.top,b.left]),d()}}function g(){setTimeout(function(){var a,b=document.createElement("div"),c=r.cloneNode(!0),d=avalon(r).innerWidth(),e=r.parentNode;if(!d)for(;e&&(1===e.nodeType&&(d=avalon(e).innerWidth()),!d);)e=e.parentNode;b.style.position="absolute",b.style.height=0,document.body.appendChild(b),b.appendChild(c),a=avalon(c),a.css({visibility:"hidden",width:d,height:"auto"}),x.elementHeight=a.height(),document.body.removeChild(b)},10)}function m(){for(var a=[],b=x.container,c=!1,d=0,e=j.length;e>d;d++){var f=j[d];if(f[2]===b){if(c=!0,b=x.isPlace?f[0]:f[1],!b){var g=document.createElement("div"),h=x.container.childNodes[0];h?x.container.insertBefore(g,h):x.container.appendChild(g),x.isPlace?(f[0]=b=g,avalon(g).addClass("oni-notice-flow")):(avalon(g).addClass("oni-notice-detach"),f[1]=b=g)}break}}if(!c){var g=document.createElement("div");if(x.isPlace){var h=b.childNodes[0];h?b.insertBefore(g,h):b.appendChild(g)}else b.appendChild(g);avalon(g).addClass(x.isPlace?"oni-notice-flow":"oni-notice-detach"),a[2]=b,x.isPlace?a[0]=b=g:a[1]=b=g,j.push(a)}return b}var o=b.noticeOptions,p=i;o.animate?(p=i.replace('ms-visible="toggle"',""),o.height=0):o.height="auto",o.template=o.getTemplate(p,o);var q=o.container;o.container=q?1===q.nodeType?q:document.getElementById(q.substr(1)):a;var r=null,s=a.innerHTML.trim(),t=o.onShow,u=null,v=o.onHide,w=null;"string"==typeof t&&(u=avalon.getModel(t,c),o.onShow=u&&u[1][u[0]]||avalon.noop),"string"==typeof v&&(w=avalon.getModel(v,c),o.onHide=w&&w[1][w[0]]||avalon.noop),a.innerHTML="","notice title"!==o.header&&"notice title"===o.title&&(o.title=o.header);var x=avalon.define(b.noticeId,function(i){avalon.mix(i,o),i.$closeTimer=0,i.$skipArray=["template","widgetElement","_isAffix","container","elementHeight"],i.elementHeight=0,i.content=i.content||s,i._isAffix=i.isPlace&&i.isAffix,i.widgetElement=a,i.typeClass=i[i.type+"Class"],i.noticeAffixWidth=0,i.noticeAffixHeight=0,i.affixPlaceholderDisplay="none",i.isPlace?i.container:i.container=document.body,i._show=function(d){e(),f(),x.animate&&h(d,x),x.onShow.call(a,b,c)},i.$watch("elementHeightOk",function(){x.height="auto"}),i._close=function(){x.toggle=!1},i._hide=function(e){var f=k.indexOf(r),g=avalon(r);if(x.animate&&(x.elementHeight=g.innerHeight(),g.css("height",x.elementHeight),h(e,x)),-1!==f){r.style.position="static",k.splice(f,1),l.splice(f,1);for(var i=0,j=k.length;j>i;i++)k[i].style.position="static";j&&d()}x.onHide.call(a,b,c)},i.setContent=function(a){x.content=a},i.$init=function(){var b=null,d=avalon.parseHTML(o.template),e=d.lastChild;if(r=d.firstChild,b=m(),b.appendChild(r),!x.isPlace){var f=avalon(o.container),h=avalon(f[0].parentNode);r.style.width=(f.width()||h.width())+"px",r.style.position="relative",r.style.left=f.offset().left+"px"}x._isAffix&&(b.appendChild(e),avalon.scan(e,[x])),avalon.scan(r,[x].concat(c)),"function"==typeof o.onInit&&o.onInit.call(a,x,o,c),x.animate&&g()},i.$remove=function(){for(var a=r.parentNode,b=0,c=j.length;c>b;b++){var d=j[b];if(d[2]===o.container)break}if(x._isAffix){var e=r.nextSibling;a.removeChild(e)}r.innerHTML=r.textContent="",a.removeChild(r),a.children.length||(a.parentNode.removeChild(a),d[0]=void 0)}});return x.$watch("toggle",function(a){a?x._show(a):x._hide(a)}),x.$watch("type",function(a){x.typeClass=x[a+"Class"]}),x.$watch("header",function(a){x.title=a}),x.$watch("successClass",function(a){x.typeClass=a}),x.$watch("errorClass",function(a){x.typeClass=a}),x.$watch("infoClass",function(a){x.typeClass=a}),x.$watch("zIndex",function(a){n=a,d()}),x.$watch("content",function(){x.animate&&g()}),x};avalon.bind(window,"scroll",function(){d()}),o.version=1,o.defaults={content:"",container:"",type:"info",header:"notice title",title:"notice title",timer:0,hasCloseBtn:!0,toggle:!1,isPlace:!0,isAffix:!1,onShow:avalon.noop,onHide:avalon.noop,successClass:"oni-notice-info",errorClass:"oni-notice-danger",infoClass:"",widgetElement:"",zIndex:"auto",animate:!0,getTemplate:function(a,b){return a}},c.exports=avalon});