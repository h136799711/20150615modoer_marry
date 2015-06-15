define("mmRouter",["mmHistory"],function(a,b,c){function d(){var a={};"get,post,delete,put".replace(avalon.rword,function(b){a[b]=[]}),this.routingTable=a}function e(a){var b=a.split("?"),c={},d=b[0],e=b[1];if(e)for(var f,g=e.split("&"),h=g.length,i=0;h>i;i++)g[i]&&(f=g[i].split("="),c[decodeURIComponent(f[0])]=decodeURIComponent(f[1]));return{path:d,query:c}}function f(a,b,c){var d=a.replace(/[\\\[\]\^$*+?.()|{}]/g,"\\$&");if(!b)return d;var e=c?"?":"";return d+e+"("+b+")"+e}function g(){try{return localStorage.setItem("avalon",1),localStorage.removeItem("avalon"),!0}catch(a){return!1}}function h(a){return String(a).replace(/[,;"\\=\s%]/g,function(a){return encodeURIComponent(a)})}function i(a,b){var c=new Date;c.setTime(c.getTime()+86400),document.cookie=h(a)+"="+h(b)+";expires="+c.toGMTString()}function j(a){var b=String(document.cookie).match(new RegExp("(?:^| )"+a+"(?:(?:=([^;]*))|;|$)"))||["",""];return decodeURIComponent(b[1])}a("lib/avalon/mmHistory");var k=/([:*])(\w+)|\{(\w+)(?:\:((?:[^{}\\]+|\\.|\{(?:[^{}\\]+|\\.)*\})+))?\}/g;d.prototype={error:function(a){this.errorback=a},_pathToRegExp:function(a,b){for(var c,d,e,g,h=b.keys=[],i="^",j=0;c=k.exec(a);){d=c[2]||c[3],e=c[4]||("*"==c[1]?".*":"string"),g=a.substring(j,c.index);var l=this.$types[e],m={name:d};l&&(e=l.pattern,m.decode=l.decode),h.push(m),i+=f(g,e,!1),j=k.lastIndex}g=a.substring(j),i+=f(g)+(b.strict?b.last:"/?")+"$";var n="boolean"==typeof b.caseInsensitive?b.caseInsensitive:!0;return b.regexp=new RegExp(i,n?"i":void 0),b},add:function(a,b,c,d){var e=this.routingTable[a.toLowerCase()];if("/"!==b.charAt(0))throw"path必须以/开头";d=d||{},d.callback=c,b.length>2&&"/"===b.charAt(b.length-1)&&(b=b.slice(0,-1),d.last="/"),avalon.Array.ensure(e,this._pathToRegExp(b,d))},route:function(a,b,c){b=b.trim();for(var d,e=this.routingTable[a],f=0;d=e[f++];){var g=b.match(d.regexp);if(g){d.query=c||{},d.path=b,d.params={};var h=d.keys;return g.shift(),h.length&&this._parseArgs(g,d),d.callback.apply(d,g)}}this.errorback&&this.errorback()},_parseArgs:function(a,b){for(var c=b.keys,d=0,e=c.length;e>d;d++){var f=c[d],g=a[d]||"";if("function"==typeof f.decode)var h=f.decode(g);else try{h=JSON.parse(g)}catch(i){h=g}a[d]=b.params[f.name]=h}},getLastPath:function(){return j("msLastPath")},setLastPath:function(a){i("msLastPath",a)},navigate:function(a){var b=e(a);"/"===a.charAt(0)&&(a=a.slice(1)),avalon.history.updateLocation(a),this.route("get",b.path,b.query)},$types:{date:{pattern:"[0-9]{4}-(?:0[1-9]|1[0-2])-(?:0[1-9]|[1-2][0-9]|3[0-1])",decode:function(a){return new Date(a.replace(/\-/g,"/"))}},string:{pattern:"[^\\/]*"},bool:{decode:function(a){return 0===parseInt(a,10)?!1:!0},pattern:"0|1"},"int":{decode:function(a){return parseInt(a,10)},pattern:"\\d+"}}},"get,put,delete,post".replace(avalon.rword,function(a){return d.prototype[a]=function(b,c,d){this.add(a,b,c,d)}}),g()&&(d.prototype.getLastPath=function(){return localStorage.getItem("msLastPath")},d.prototype.setLastPath=function(a){localStorage.setItem("msLastPath",a)}),avalon.router=new d});