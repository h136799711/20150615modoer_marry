define("lib/avalon/draggable/avalon.draggable",["avalon"],function(a,b,c){function d(a,b){var c="page"+b;return r?a.changedTouches[0][c]:a[c]}function e(a,b,c,e,f){var g=d(a,e);if(c.containment){var h="X"===e?c.containment[0]:c.containment[1],i="X"===e?c.containment[2]:c.containment[3],j=g-("X"===e?c.clickX:c.clickY);h>j?g+=Math.abs(h-j):j>i&&(g-=Math.abs(i-j))}c["page"+e]=g;var k=w[e],l=k.toLowerCase(),m=c["start"+k]+g-c["startPage"+e]+(f?c["end"+k]:0);c[l]=m,c["drag"+e]&&(b.style[l]=m+"px")}function f(){var a=window.event||{};a.returnValue=!1}function g(a,b){if(!a.containment){if(Array.isArray(b.containment))return;return void(b.containment=null)}var c=b.$element.width(),d=b.$element.height();if("window"===a.containment){var e=avalon(window);return void(b.containment=[e.scrollLeft(),e.scrollTop(),e.scrollLeft()+e.width()-b.marginLeft-c,e.scrollTop()+e.height()-b.marginTop-d])}if("document"===a.containment)return void(b.containment=[0,0,avalon(document).width()-b.marginLeft,avalon(document).height()-b.marginTop]);if(Array.isArray(a.containment)){var f=a.containment;return void(b.containment=[f[0],f[1],f[2]-c,f[3]-d])}if("parent"===a.containment||"#"===a.containment.charAt(0)){var g;if(g="parent"===a.containment?b.element.parentNode:document.getElementById(a.containment.slice(1))){var h=avalon(g).offset();b.containment=[h.left+b.marginLeft,h.top+b.marginTop,h.left+g.offsetWidth-b.marginLeft-c,h.top+g.offsetHeight-b.marginTop-d]}}}a("avalon");var h={ghosting:!1,delay:0,axis:"xy",started:!0,start:avalon.noop,beforeStart:avalon.noop,drag:avalon.noop,beforeStop:avalon.noop,stop:avalon.noop,scrollPlugin:!0,scrollSensitivity:20,scrollSpeed:20},i=document.getElementById("avalonStyle"),j=".ui-helper-global-drag *{ -webkit-touch-callout: none;-khtml-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;}.ui-helper-global-drag img{-webkit-user-drag:none; pointer-events:none;}";try{i.innerHTML+=j}catch(k){i.styleSheet.cssText+=j}var l,m=navigator.userAgent,n=/Android/i.test(m),o=/BlackBerry/i.test(m),p=/IEMobile/i.test(m),q=/iPhone|iPad|iPod/i.test(m),r=n||o||p||q;if(r)s="touchstart",t="touchmove",u="touchend";else var s="mousedown",t="mousemove",u="mouseup";var v=avalon.bindingHandlers.draggable=function(a,b){var c,e,f=a.value.match(avalon.rword)||[],i=f[0]||"$",j=f[1]||"draggable";if("$"==i||(c=avalon.vmodels[i])){a.element.removeAttribute("ms-draggable"),c||(c=b.length?b[0]:null);var k=c||{};c&&"object"==typeof c[j]&&(e=c[j],e.$model&&(e=e.$model),k=e);var m=a.element,n=avalon(m),o=avalon.mix({},h,e||{},a[j]||{},avalon.getWidgetData(m,"draggable"));"drag,stop,start,beforeStart,beforeStop".replace(avalon.rword,function(a){var b=o[a];"string"==typeof b&&"function"==typeof k[b]&&(o[a]=k[b])}),""===o.axis||/^(x|y|xy)$/.test(o.axis)||(o.axis="xy"),l=document.body,n.bind(s,function(a){var b=avalon.mix({},o,{element:m,$element:n,pageX:d(a,"X"),pageY:d(a,"Y"),marginLeft:parseFloat(n.css("marginLeft"))||0,marginTop:parseFloat(n.css("marginTop"))||0});if(b.startPageX=b.pageX,b.startPageY=b.pageY,o.axis.replace(/./g,function(a){b["drag"+a.toUpperCase()]=!0}),b.dragX||b.dragY||(b.started=!1),"function"==typeof o.beforeStart&&o.beforeStart.call(b.element,a,b),b.handle&&k){var c=k[b.handle];if(c="function"==typeof c?c:b.handle,"function"==typeof c){var e=c.call(m,a,b);if(!e||1!==e.nodeType)return;if(!m.contains(e))return}}A();var f=n.css("position");/^(?:r|a|f)/.test(f)||(m.style.position="relative",m.style.top="0px",m.style.left="0px"),o.delay&&isFinite(o.delay)&&(b.started=!1,setTimeout(function(){b.started=!0},o.delay));var h=n.offset();if(o.ghosting){var i=m.cloneNode(!0);avalon(i).css("opacity",.7).width(m.offsetWidth).height(m.offsetHeight),b.clone=i,"fixed"!==f&&(i.style.position="absolute",i.style.top=h.top-b.marginTop+"px",i.style.left=h.left-b.marginLeft+"px"),l.appendChild(i)}var j=avalon(b.clone||b.element);b.startLeft=parseFloat(j.css("left")),b.startTop=parseFloat(j.css("top")),b.endLeft=parseFloat(n.css("left"))-b.startLeft,b.endTop=parseFloat(n.css("top"))-b.startTop,b.clickX=b.pageX-h.left,b.clickY=b.pageY-h.top,g(o,b),v.dragData=b,"start,drag,beforeStop,stop".replace(avalon.rword,function(a){v[a]=[o[a]]}),v.plugin.call("start",a,b)})}},w={X:"Left",Y:"Top"};v.dragData={},v.start=[],v.drag=[],v.stop=[],v.beforeStop=[],v.plugin={add:function(a,b){for(var c in b){var d=b[c];"function"==typeof d&&Array.isArray(v[c])&&(d.isPlugin=!0,d.pluginName=a+"Plugin",v[c].push(d))}},call:function(a,b,c){var d=v[a];if(Array.isArray(d)&&d.forEach(function(a){("undefined"==typeof a.pluginName?!0:c[a.pluginName])&&a.call(c.element,b,c)}),"stop"===a)for(var e in v)d=v[e],Array.isArray(d)&&d.forEach(function(a){a.isPlugin||avalon.Array.remove(d,a)})}};var x=new Date-0,y=document.querySelector?12:30;avalon(document).bind(t,function(a){var b=new Date-x;if(b>y){x=b;var c=v.dragData;if(c.started===!0){a.preventDefault();var d=c.clone||c.element;e(a,d,c,"X"),e(a,d,c,"Y"),v.plugin.call("drag",a,c)}}}),avalon(document).bind(u,function(a){var b=v.dragData;if(b.started===!0){B();var c=b.element;v.plugin.call("beforeStop",a,b),b.dragX&&e(a,c,b,"X",!0),b.dragY&&e(a,c,b,"Y",!0),b.clone&&l.removeChild(b.clone),v.plugin.call("stop",a,b),v.dragData={}}});var z=document.documentElement,A=function(){avalon(z).addClass("ui-helper-global-drag")},B=function(){avalon(z).removeClass("ui-helper-global-drag")};if(window.VBArray&&!("msUserSelect"in z.style)){var C;A=function(){C=l.onselectstart,l.onselectstart=f},B=function(){l.onselectstart=C}}return avalon});