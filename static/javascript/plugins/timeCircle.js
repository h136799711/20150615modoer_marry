define("plugins/timeCircle",function(b,a,c){(function(h){var o=window;if(!Object.keys){Object.keys=(function(){var z=Object.prototype.hasOwnProperty,A=!({toString:null}).propertyIsEnumerable("toString"),y=["toString","toLocaleString","valueOf","hasOwnProperty","isPrototypeOf","propertyIsEnumerable","constructor"],x=y.length;return function(D){if(typeof D!=="object"&&(typeof D!=="function"||D===null)){throw new TypeError("Object.keys called on non-object")}var B=[],E,C;for(E in D){if(z.call(D,E)){B.push(E)}}if(A){for(C=0;C<x;C++){if(z.call(D,y[C])){B.push(y[C])}}}return B}}())}var f=false;var i=200;var p=(location.hash==="#debug");function g(x){if(p){console.log(x)}}var r=["Days","Hours","Minutes","Seconds"];var t={Seconds:"Minutes",Minutes:"Hours",Hours:"Days",Days:"Years"};var u={Seconds:1,Minutes:60,Hours:3600,Days:86400,Months:2678400,Years:31536000};function v(z){var y=/^#?([a-f\d])([a-f\d])([a-f\d])$/i;z=z.replace(y,function(B,D,C,A){return D+D+C+C+A+A});var x=/^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(z);return x?{r:parseInt(x[1],16),g:parseInt(x[2],16),b:parseInt(x[3],16)}:null}function j(){var x=document.createElement("canvas");return !!(x.getContext&&x.getContext("2d"))}function e(){return Math.floor((1+Math.random())*65536).toString(16).substring(1)}function q(){return e()+e()+"-"+e()+"-"+e()+"-"+e()+"-"+e()+e()+e()}if(!Array.prototype.indexOf){Array.prototype.indexOf=function(y){var x=this.length>>>0;var z=Number(arguments[1])||0;z=(z<0)?Math.ceil(z):Math.floor(z);if(z<0){z+=x}for(;z<x;z++){if(z in this&&this[z]===y){return z}}return -1}}function k(C){var y=C.match(/^[0-9]{4}-[0-9]{2}-[0-9]{2}\s[0-9]{1,2}:[0-9]{2}:[0-9]{2}$/);if(y!==null&&y.length>0){var A=C.split(" ");var x=A[0].split("-");var z=A[1].split(":");return new Date(x[0],x[1]-1,x[2],z[0],z[1],z[2])}var B=Date.parse(C);if(!isNaN(B)){return B}B=Date.parse(C.replace(/-/g,"/").replace("T"," "));if(!isNaN(B)){return B}return new Date()}function m(L,N,I,E,D){var G={};var F={};var y={};var M={};var H={};var C={};var A=null;for(var B=0;B<E.length;B++){var K=E[B];var z;if(A===null){z=I/u[K]}else{z=u[A]/u[K]}var x=(L/u[K]);var J=(N/u[K]);if(D){if(x>0){x=Math.floor(x)}else{x=Math.ceil(x)}if(J>0){J=Math.floor(J)}else{J=Math.ceil(J)}}if(K!=="Days"){x=x%z;J=J%z}G[K]=x;y[K]=Math.abs(x);F[K]=J;C[K]=Math.abs(J);M[K]=Math.abs(x)/z;H[K]=Math.abs(J)/z;A=K}return{raw_time:G,raw_old_time:F,time:y,old_time:C,pct:M,old_pct:H}}var s={};function n(){if(typeof o.TC_Instance_List!=="undefined"){s=o.TC_Instance_List}else{o.TC_Instance_List=s}d(o)}function d(z){var A=["webkit","moz"];for(var y=0;y<A.length&&!z.requestAnimationFrame;++y){z.requestAnimationFrame=z[A[y]+"RequestAnimationFrame"];z.cancelAnimationFrame=z[A[y]+"CancelAnimationFrame"]}if(!z.requestAnimationFrame||!z.cancelAnimationFrame){z.requestAnimationFrame=function(F,C,x){if(typeof x==="undefined"){x={data:{last_frame:0}}}var B=new Date().getTime();var D=Math.max(0,16-(B-x.data.last_frame));var E=z.setTimeout(function(){F(B+D)},D);x.data.last_frame=B+D;return E};z.cancelAnimationFrame=function(x){clearTimeout(x)}}}var l=function(y,x){this.element=y;this.container;this.listeners=null;this.data={paused:false,last_frame:0,animation_frame:null,interval_fallback:null,timer:false,total_duration:null,prev_time:null,drawn_units:[],text_elements:{Days:null,Hours:null,Minutes:null,Seconds:null},attributes:{canvas:null,context:null,item_size:null,line_width:null,radius:null,outer_radius:null},state:{fading:{Days:false,Hours:false,Minutes:false,Seconds:false}}};this.config=null;this.setOptions(x);this.initialize()};l.prototype.clearListeners=function(){this.listeners={all:[],visible:[]}};l.prototype.addTime=function(x){if(this.data.attributes.ref_date instanceof Date){var y=this.data.attributes.ref_date;y.setSeconds(y.getSeconds()+x)}else{if(!isNaN(this.data.attributes.ref_date)){this.data.attributes.ref_date+=(x*1000)}}};l.prototype.initialize=function(E){this.data.drawn_units=[];for(var A=0;A<Object.keys(this.config.time).length;A++){var G=Object.keys(this.config.time)[A];if(this.config.time[G].show){this.data.drawn_units.push(G)}}h(this.element).children("div.time_circles").remove();if(typeof E==="undefined"){E=true}if(E||this.listeners===null){this.clearListeners()}this.container=h("<div>");this.container.addClass("time_circles");this.container.appendTo(this.element);var I=this.element.offsetHeight;var y=this.element.offsetWidth;if(I===0){I=h(this.element).height()}if(y===0){y=h(this.element).width()}if(I===0&&y>0){I=y/this.data.drawn_units.length}else{if(y===0&&I>0){y=I*this.data.drawn_units.length}}var F=document.createElement("canvas");F.width=y;F.height=I;this.data.attributes.canvas=h(F);this.data.attributes.canvas.appendTo(this.container);var D=j();if(!D&&typeof G_vmlCanvasManager!=="undefined"){G_vmlCanvasManager.initElement(F);f=true;D=true}if(D){this.data.attributes.context=F.getContext("2d")}this.data.attributes.item_size=Math.min(y/this.data.drawn_units.length,I);this.data.attributes.line_width=this.data.attributes.item_size*this.config.fg_width;this.data.attributes.radius=((this.data.attributes.item_size*0.8)-this.data.attributes.line_width)/2;this.data.attributes.outer_radius=this.data.attributes.radius+0.5*Math.max(this.data.attributes.line_width,this.data.attributes.line_width*this.config.bg_width);var A=0;for(var H in this.data.text_elements){if(!this.config.time[H].show){continue}var x=h("<div>");x.addClass("textDiv_"+H);x.css("top",Math.round(0.35*this.data.attributes.item_size));x.css("left",Math.round(A++*this.data.attributes.item_size));x.css("width",this.data.attributes.item_size);var z=h("<span>");z.appendTo(x);x.appendTo(this.container);var B=h("<h4>");B.text(this.config.time[H].text);B.appendTo(x);this.data.text_elements[H]=z}this.start();if(!this.config.start){this.data.paused=true}var C=this;this.data.interval_fallback=o.setInterval(function(){C.update.call(C,true)},100)};l.prototype.update=function(C){if(typeof C==="undefined"){C=false}else{if(C&&this.data.paused){return}}if(f){this.data.attributes.context.clearRect(0,0,this.data.attributes.canvas[0].width,this.data.attributes.canvas[0].hright)}var G,N;var O=this.data.prev_time;var Q=new Date();this.data.prev_time=Q;if(O===null){O=Q}if(!this.config.count_past_zero){if(Q>this.data.attributes.ref_date){for(var L=0;L<this.data.drawn_units.length;L++){var R=this.data.drawn_units[L];this.data.text_elements[R].text("0");var F=(L*this.data.attributes.item_size)+(this.data.attributes.item_size/2);var E=this.data.attributes.item_size/2;var I=this.config.time[R].color;this.drawArc(F,E,I,0)}this.stop();return}}else{if(Q>this.data.attributes.ref_date){this.config.endTime&&this.config.endTime()}}G=(this.data.attributes.ref_date-Q)/1000;N=(this.data.attributes.ref_date-O)/1000;var M=this.config.animation!=="smooth";var z=m(G,N,this.data.total_duration,this.data.drawn_units,M);var A=m(G,N,u["Years"],r,M);var L=0;var J=0;var D=null;var K=this.data.drawn_units.slice();for(var L in r){var R=r[L];if(Math.floor(A.raw_time[R])!==Math.floor(A.raw_old_time[R])){this.notifyListeners(R,Math.floor(A.time[R]),Math.floor(G),"all")}if(K.indexOf(R)<0){continue}if(Math.floor(z.raw_time[R])!==Math.floor(z.raw_old_time[R])){this.notifyListeners(R,Math.floor(z.time[R]),Math.floor(G),"visible")}if(!C){this.data.text_elements[R].text(Math.floor(Math.abs(z.time[R])));var F=(J*this.data.attributes.item_size)+(this.data.attributes.item_size/2);var E=this.data.attributes.item_size/2;var I=this.config.time[R].color;if(this.config.animation==="smooth"){if(D!==null&&!f){if(Math.floor(z.time[D])>Math.floor(z.old_time[D])){this.radialFade(F,E,I,1,R);this.data.state.fading[R]=true}else{if(Math.floor(z.time[D])<Math.floor(z.old_time[D])){this.radialFade(F,E,I,0,R);this.data.state.fading[R]=true}}}if(!this.data.state.fading[R]){this.drawArc(F,E,I,z.pct[R])}}else{this.animateArc(F,E,I,z.pct[R],z.old_pct[R],(new Date()).getTime()+i)}}D=R;J++}if(this.data.paused||C){return}var H=this;var B=function(){H.update.call(H)};if(this.config.animation==="smooth"){this.data.animation_frame=o.requestAnimationFrame(B,H.element,H)}else{var P=(G%1)*1000;if(P<0){P=1000+P}P+=50;H.data.animation_frame=o.setTimeout(function(){H.data.animation_frame=o.requestAnimationFrame(B,H.element,H)},P)}};l.prototype.animateArc=function(G,E,B,A,F,D){if(this.data.attributes.context===null){return}var H=F-A;if(Math.abs(H)>0.5){if(A===0){this.radialFade(G,E,B,1)}else{this.radialFade(G,E,B,0)}}else{var z=(i-(D-(new Date()).getTime()))/i;if(z>1){z=1}var I=(F*(1-z))+(A*z);this.drawArc(G,E,B,I);if(z>=1){return}var C=this;o.requestAnimationFrame(function(){C.animateArc(G,E,B,A,F,D)},this.element)}};l.prototype.drawArc=function(G,F,A,H){if(this.data.attributes.context===null){return}var D=Math.max(this.data.attributes.outer_radius,this.data.attributes.item_size/2);if(!f){this.data.attributes.context.clearRect(G-D,F-D,D*2,D*2)}if(this.config.use_background){this.data.attributes.context.beginPath();this.data.attributes.context.arc(G,F,this.data.attributes.radius,0,2*Math.PI,false);this.data.attributes.context.lineWidth=this.data.attributes.line_width*this.config.bg_width;this.data.attributes.context.strokeStyle=this.config.circle_bg_color;this.data.attributes.context.stroke()}var E,z,I;var C=(-0.5*Math.PI);var J=2*Math.PI;E=C+(this.config.start_angle/360*J);var B=(2*H*Math.PI);if(this.config.direction==="Both"){I=false;E-=(B/2);z=E+B}else{if(this.config.direction==="Clockwise"){I=false;z=E+B}else{I=true;z=E-B}}this.data.attributes.context.beginPath();this.data.attributes.context.arc(G,F,this.data.attributes.radius,E,z,I);this.data.attributes.context.lineWidth=this.data.attributes.line_width;this.data.attributes.context.strokeStyle=A;this.data.attributes.context.stroke()};l.prototype.radialFade=function(F,E,A,G,H){var D=v(A);var C=this;var z=0.2*((G===1)?-1:1);var B;for(B=0;G<=1&&G>=0;B++){(function(){var x=50*B;var y="rgba("+D.r+", "+D.g+", "+D.b+", "+(Math.round(G*10)/10)+")";o.setTimeout(function(){C.drawArc(F,E,y,1)},x)}());G+=z}if(typeof H!==undefined){o.setTimeout(function(){C.data.state.fading[H]=false},50*B)}};l.prototype.timeLeft=function(){if(this.data.paused&&typeof this.data.timer==="number"){return this.data.timer}var x=new Date();return((this.data.attributes.ref_date-x)/1000)};l.prototype.start=function(){o.cancelAnimationFrame(this.data.animation_frame);o.clearTimeout(this.data.animation_frame);var y=h(this.element).data("date");if(typeof y==="undefined"){y=h(this.element).attr("data-date")}if(typeof y==="string"){this.data.attributes.ref_date=k(y)}else{if(typeof this.data.timer==="number"){if(this.data.paused){this.data.attributes.ref_date=(new Date()).getTime()+(this.data.timer*1000)}}else{var x=h(this.element).data("timer");if(typeof x==="undefined"){x=h(this.element).attr("data-timer")}if(typeof x==="string"){x=parseFloat(x)}if(typeof x==="number"){this.data.timer=x;this.data.attributes.ref_date=(new Date()).getTime()+(x*1000)}else{this.data.attributes.ref_date=this.config.ref_date}}}this.data.paused=false;this.update.call(this)};l.prototype.restart=function(){this.data.timer=false;this.start()};l.prototype.stop=function(){if(typeof this.data.timer==="number"){this.data.timer=this.timeLeft(this)}this.data.paused=true;o.cancelAnimationFrame(this.data.animation_frame)};l.prototype.destroy=function(){this.clearListeners();this.stop();o.clearInterval(this.data.interval_fallback);this.data.interval_fallback=null;this.container.remove();h(this.element).removeAttr("data-tc-id");h(this.element).removeData("tc-id")};l.prototype.setOptions=function(x){if(this.config===null){this.default_options.ref_date=new Date();this.config=h.extend(true,{},this.default_options)}h.extend(true,this.config,x);if(this.config.use_top_frame){o=window.top}else{o=window}n();this.data.total_duration=this.config.total_duration;if(typeof this.data.total_duration==="string"){if(typeof u[this.data.total_duration]!=="undefined"){this.data.total_duration=u[this.data.total_duration]}else{if(this.data.total_duration==="Auto"){for(var y=0;y<Object.keys(this.config.time).length;y++){var z=Object.keys(this.config.time)[y];if(this.config.time[z].show){this.data.total_duration=u[t[z]];break}}}else{this.data.total_duration=u["Years"];console.error("Valid values for TimeCircles config.total_duration are either numeric, or (string) Years, Months, Days, Hours, Minutes, Auto")}}}};l.prototype.addListener=function(z,x,y){if(typeof z!=="function"){return}if(typeof y==="undefined"){y="visible"}this.listeners[y].push({func:z,scope:x})};l.prototype.notifyListeners=function(A,C,z,y){for(var x=0;x<this.listeners[y].length;x++){var B=this.listeners[y][x];B.func.apply(B.scope,[A,C,z])}};l.prototype.default_options={ref_date:new Date(),start:true,animation:"smooth",count_past_zero:true,endTime:null,circle_bg_color:"#60686F",use_background:true,fg_width:0.1,bg_width:1.2,text_size:0.07,total_duration:"Auto",direction:"Clockwise",use_top_frame:false,start_angle:0,time:{Days:{show:true,text:"Days",color:"#FC6"},Hours:{show:true,text:"Hours",color:"#9CF"},Minutes:{show:true,text:"Minutes",color:"#BFB"},Seconds:{show:true,text:"Seconds",color:"#F99"}}};var w=function(y,x){this.elements=y;this.options=x;this.foreach()};w.prototype.getInstance=function(A){var x;var z=h(A).data("tc-id");if(typeof z==="undefined"){z=q();h(A).attr("data-tc-id",z)}if(typeof s[z]==="undefined"){var y=this.options;var B=h(A).data("options");if(typeof B==="string"){B=JSON.parse(B)}if(typeof B==="object"){y=h.extend(true,{},this.options,B)}x=new l(A,y);s[z]=x}else{x=s[z];if(typeof this.options!=="undefined"){x.setOptions(this.options)}}return x};w.prototype.addTime=function(x){this.foreach(function(y){y.addTime(x)})};w.prototype.foreach=function(y){var x=this;this.elements.each(function(){var z=x.getInstance(this);if(typeof y==="function"){y(z)}});return this};w.prototype.start=function(){this.foreach(function(x){x.start()});return this};w.prototype.stop=function(){this.foreach(function(x){x.stop()});return this};w.prototype.restart=function(){this.foreach(function(x){x.restart()});return this};w.prototype.rebuild=function(){this.foreach(function(x){x.initialize(false)});return this};w.prototype.getTime=function(){return this.getInstance(this.elements[0]).timeLeft()};w.prototype.addListener=function(y,x){if(typeof x==="undefined"){x="visible"}var z=this;this.foreach(function(A){A.addListener(y,z.elements,x)});return this};w.prototype.destroy=function(){this.foreach(function(x){x.destroy()});return this};w.prototype.end=function(){return this.elements};h.fn.TimeCircles=function(x){return new w(this,x)}}($))});