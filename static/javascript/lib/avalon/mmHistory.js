define("mmHistory",["avalon"],function(a,b,c){function d(a){return!a||a===window.name||"_self"===a||"top"===a&&window==window.top?!0:!1}function e(a){for(var b,c=0;b=a[c++];)if("A"===b.nodeName)return b}function f(a,b){(b=document.getElementById(a))?b.scrollIntoView():(b=e(document.getElementsByName(a)))?b.scrollIntoView():window.scrollTo(0,0)}a("avalon");var g=document.createElement("a"),h=avalon.History=function(){this.location=location};h.started=!1,h.IEVersion=function(){var a=document.documentMode;return a?a:window.XMLHttpRequest?7:6}(),h.defaults={basepath:"/",html5Mode:!1,hashPrefix:"!",interval:50,fireAnchor:!0};var i=window.VBArray&&h.IEVersion<=7,j=!!window.history.pushState,k=!(!("onhashchange"in window)||window.VBArray&&i);h.prototype={constructor:h,getFragment:function(a){return null==a&&(a="popstate"===this.monitorMode?this.getPath():this.getHash()),a.replace(/^[#\/]|\s+$/g,"")},getHash:function(a){var b=(a||this).location.href;return this._getHash(b.slice(b.indexOf("#")))},_getHash:function(a){return 0===a.indexOf("#/")?decodeURIComponent(a.slice(2)):0===a.indexOf("#!/")?decodeURIComponent(a.slice(3)):""},getPath:function(){var a=decodeURIComponent(this.location.pathname+this.location.search),b=this.basepath.slice(0,-1);return a.indexOf(b)||(a=a.slice(b.length)),a.slice(1)},_getAbsolutePath:function(a){return a.hasAttribute?a.href:a.getAttribute("href",4)},start:function(a){function b(){var a=c.iframe;if("iframepoll"===c.monitorMode&&!a)return!1;var b,d=c.getFragment();if(a){var e=c.getHash(a);if(d!==c.fragment){var f=a.document;f.open(),f.write(c.iframeHTML),f.close(),a.location.hash=c.prefix+d,b=d}else e!==c.fragment&&(c.location.hash=c.prefix+e,b=e)}else d!==c.fragment&&(b=d);void 0!==b&&(c.fragment=b,c.fireRouteChange(b))}if(h.started)throw new Error("avalon.history has already been started");h.started=!0,this.options=avalon.mix({},h.defaults,a),this.html5Mode=!!this.options.html5Mode,this.monitorMode=this.html5Mode?"popstate":"hashchange",j||(this.html5Mode&&(avalon.log("如果浏览器不支持HTML5 pushState，强制使用hash hack!"),this.html5Mode=!1),this.monitorMode="hashchange"),k||(this.monitorMode="iframepoll"),this.prefix="#"+this.options.hashPrefix+"/",this.basepath=("/"+this.options.basepath+"/").replace(/^\/+|\/+$/g,"/"),this.fragment=this.getFragment(),g.href=this.basepath,this.rootpath=this._getAbsolutePath(g);var c=this,d="<!doctype html><html><body>@</body></html>";switch(this.options.domain&&(d=d.replace("<body>","<script>document.domain ="+this.options.domain+"</script><body>")),this.iframeHTML=d,"iframepoll"===this.monitorMode&&avalon.ready(function(){var a=document.createElement("iframe");a.src="javascript:0",a.style.display="none",a.tabIndex=-1,document.body.appendChild(a),c.iframe=a.contentWindow;var b=c.iframe.document;b.open(),b.write(c.iframeHTML),b.close()}),this.monitorMode){case"popstate":this.checkUrl=avalon.bind(window,"popstate",b),this._fireLocationChange=b;break;case"hashchange":this.checkUrl=avalon.bind(window,"hashchange",b);break;case"iframepoll":this.checkUrl=setInterval(b,this.options.interval)}this.fireRouteChange(this.fragment||"/")},fireRouteChange:function(a){var b=avalon.router;b&&b.navigate&&(b.setLastPath(a),b.navigate("/"===a?a:"/"+a)),this.options.fireAnchor&&f(a.replace(/\?.*/g,""))},stop:function(){avalon.unbind(window,"popstate",this.checkUrl),avalon.unbind(window,"hashchange",this.checkUrl),clearInterval(this.checkUrl),h.started=!1},updateLocation:function(a){if("popstate"===this.monitorMode){var b=this.rootpath+a;history.pushState({path:b},document.title,b),this._fireLocationChange()}else this.location.hash=this.prefix+a}},avalon.history=new h,avalon.bind(document,"click",function(a){var b="defaultPrevented"in a?a.defaultPrevented:a.returnValue===!1;if(!(b||a.ctrlKey||a.metaKey||2===a.which)){for(var c=a.target;"A"!==c.nodeName;)if(c=c.parentNode,!c||"BODY"===c.tagName)return;if(d(c.target)){var e=i?c.getAttribute("href",2):c.getAttribute("href")||c.getAttribute("xlink:href"),f=avalon.history.prefix;if(null===e)return;var g=e.replace(f,"").trim();0===e.indexOf(f)&&""!==g&&(a.preventDefault(),avalon.history.updateLocation(g))}}}),c.exports=avalon});