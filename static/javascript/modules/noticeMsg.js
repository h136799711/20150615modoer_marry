define("modules/noticeMsg",["avalon"],function(e,f,c){var g=$.now(),a='<div class="notice_msg" ms-controller="notice'+g+'" id="noticeMsg'+g+'" ms-css-left="left" ms-css-top="top" ms-class="success:success" ms-class-1="failed:failed">                    <i class="iconfont mr10" ms-html="icon"></i><span ms-text="title"></span><span ms-text="content"></span>                  </div>';$("body").append(a);var h=$("#noticeMsg"+g),b;var k=avalon.define({$id:"notice"+g,icon:"&#xe656;",title:"",content:"",success:true,failed:false,$target:0,top:0,left:"50%",action:"",$status:false,$show:function(){if(k.$status){return}k.$status=true;var m=j("transition"),l;if(k.$target){l=k.$target}else{l=80}if(m){h.css({top:l,opacity:1})}else{h.animate({"top":l,"opacity":1},100)}b=setTimeout(function(){if(m){h.css({top:0,opacity:0});i()}else{h.animate({"top":0,"opacity":0},100,function(){i()})}},1500)}});k.$watch("action",function(m){var l=m.split("|");if(parseInt(l[0],10)){k.success=true;k.failed=false;k.icon="&#xe656;"}else{k.success=false;k.failed=true;k.icon="&#xe657;"}k.title=l[1];k.content=l[2];k.$show()});avalon.scan(h.get(0),k);function d(l){if(l.indexOf("-")<0&&l.indexOf("_")<0){return l}return l.replace(/[-_][^-_]/g,function(m){return m.charAt(1).toUpperCase()})}function j(m){var o=["","-webkit-","-o-","-moz-","-ms-"],n,l=document.documentElement.style;for(n in o){camelCase=d(o[n]+m);if(camelCase in l){return true}}return false}function i(){clearTimeout(b);b=null;k.$status=false}c.exports=k});