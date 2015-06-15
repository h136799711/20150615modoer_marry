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
!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):"object"==typeof exports?module.exports=a:a(jQuery)}(function(a){function b(b){var g=b||window.event,h=i.call(arguments,1),j=0,l=0,m=0,n=0,o=0,p=0;if(b=a.event.fix(g),b.type="mousewheel","detail"in g&&(m=-1*g.detail),"wheelDelta"in g&&(m=g.wheelDelta),"wheelDeltaY"in g&&(m=g.wheelDeltaY),"wheelDeltaX"in g&&(l=-1*g.wheelDeltaX),"axis"in g&&g.axis===g.HORIZONTAL_AXIS&&(l=-1*m,m=0),j=0===m?l:m,"deltaY"in g&&(m=-1*g.deltaY,j=m),"deltaX"in g&&(l=g.deltaX,0===m&&(j=-1*l)),0!==m||0!==l){if(1===g.deltaMode){var q=a.data(this,"mousewheel-line-height");j*=q,m*=q,l*=q}else if(2===g.deltaMode){var r=a.data(this,"mousewheel-page-height");j*=r,m*=r,l*=r}if(n=Math.max(Math.abs(m),Math.abs(l)),(!f||f>n)&&(f=n,d(g,n)&&(f/=40)),d(g,n)&&(j/=40,l/=40,m/=40),j=Math[j>=1?"floor":"ceil"](j/f),l=Math[l>=1?"floor":"ceil"](l/f),m=Math[m>=1?"floor":"ceil"](m/f),k.settings.normalizeOffset&&this.getBoundingClientRect){var s=this.getBoundingClientRect();o=b.clientX-s.left,p=b.clientY-s.top}return b.deltaX=l,b.deltaY=m,b.deltaFactor=f,b.offsetX=o,b.offsetY=p,b.deltaMode=0,h.unshift(b,j,l,m),e&&clearTimeout(e),e=setTimeout(c,200),(a.event.dispatch||a.event.handle).apply(this,h)}}function c(){f=null}function d(a,b){return k.settings.adjustOldDeltas&&"mousewheel"===a.type&&b%120===0}var e,f,g=["wheel","mousewheel","DOMMouseScroll","MozMousePixelScroll"],h="onwheel"in document||document.documentMode>=9?["wheel"]:["mousewheel","DomMouseScroll","MozMousePixelScroll"],i=Array.prototype.slice;if(a.event.fixHooks)for(var j=g.length;j;)a.event.fixHooks[g[--j]]=a.event.mouseHooks;var k=a.event.special.mousewheel={version:"3.1.12",setup:function(){if(this.addEventListener)for(var c=h.length;c;)this.addEventListener(h[--c],b,!1);else this.onmousewheel=b;a.data(this,"mousewheel-line-height",k.getLineHeight(this)),a.data(this,"mousewheel-page-height",k.getPageHeight(this))},teardown:function(){if(this.removeEventListener)for(var c=h.length;c;)this.removeEventListener(h[--c],b,!1);else this.onmousewheel=null;a.removeData(this,"mousewheel-line-height"),a.removeData(this,"mousewheel-page-height")},getLineHeight:function(b){var c=a(b),d=c["offsetParent"in a.fn?"offsetParent":"parent"]();return d.length||(d=a("body")),parseInt(d.css("fontSize"),10)||parseInt(c.css("fontSize"),10)||16},getPageHeight:function(b){return a(b).height()},settings:{adjustOldDeltas:!0,normalizeOffset:!0}};a.fn.extend({mousewheel:function(a){return a?this.bind("mousewheel",a):this.trigger("mousewheel")},unmousewheel:function(a){return this.unbind("mousewheel",a)}})});

/**
 * 表单增加隐藏字段
 * $('#form').form_hidden(json);
 */
(function($) {
	$.fn.form_hidden = function(json_data) {
		var $this = this;
		if(!json_data) return;
		$.each(json_data, function(key, val) {
			 $this.append('<input type="hidden" name="'+key+'" value="'+val+'">');
		});
	}
})(jQuery);

/**
 * 复选列表控件
 * http://www.modoer.com
 * by mouferstudio
 */
(function($) {
	$.fn.mchecklist = function(options) {
		$.fn.mchecklist.defaults = {
			css_class:'mchecklist',      //修饰的css样式
			line_num:3,                  //每一样显示复选框数量
			height:75,                   //控件总高度
			width:500,                   //控件总宽度
			perch:null,
			linkage:null
		}
		var opts = $.extend({}, $.fn.mchecklist.defaults, options);
		var my = $(this);
		var myid = $(this).attr('id');
		var mcl = $('#'+myid + '_mcl');
		var nums = 0;
		if(mcl[0]) mcl.remove();
		mcl = $('<div></div>').attr('id', myid + '_mcl').addClass(opts.css_class);
		var info = $('<div class="mchecklist-info"></div>');
		var checks = $('<ul class="mchecklist-box"></ul>').css({"max-height":opts.height+50,"width":opts.width});
		my.hide();
		if(opts.linkage) {
			$('#'+opts.linkage.id).bind(opts.linkage.bind, function() {
				init_box();
			});
		}

		init_box();

		function init_box() {
			checks.empty();
			my.find('option').each(function (i) {
				nums++;
				var opt = $(this);
				var id = myid + '_check_' + i;
				var checked = $(this).attr('selected') ? "checked=\"checked\"" : '';
				var check = $("<input type=\"checkbox\" "+checked+">").attr({
						'id':id,
						'value':opt.val(),
						'checked':$(this).attr('selected'),
						'text':$(this).text()
					}).click(function () {
						opt.attr("selected",$(this).attr('checked'));
						set_info();
					});
				var lbl = $("<label></label>").text($(this).text()).attr('for',id);
				var num = round(100 / opts.line_num) - 3;
				var foo = $("<li></li>").css("float","left").css("width",num+"%").css("height","20px");
				checks.append(foo.append(check).append(lbl));
			});
			my.parent().append(mcl.append(checks).append('<div style="clear:both"></div>').append(info));
			if($.browser.msie) checks.css({"height":opts.height});
			set_info();
			if(!nums) mcl.remove();
		}

		function set_info () {
			var txt=split='';
			checks.find('input').each(function (i) {
				if($(this).attr('checked')) {
					txt += split+ $(this).attr('text');
					split='<span class="mchecklist-split">,</span>';
				}
			});
			if(txt=='') {
				txt = '没有选择任何信息.';
			} else {
				txt = '已选择<span class="mchecklist-split">:</span>' + txt;
			}
			info.html(txt);
		}
	}
})(jQuery);

/**
 * select无限级联动
 * http://www.modoer.com
 * by mouferstudio
 */
(function($) {
	$.fn.mselect = function(options) {
		$.fn.mselect.defaults = {
			data:null,              //固定格式包含层级数据的数组
			value:'',               //被选择的值
			name:'catid',           //无限联动空间名
			level:null,             //最多联动层级，默认不限制
			onchange:null           //定义选择下拉框后产生的事件
		}
		var opts = $.extend({}, $.fn.mselect.defaults, options);
		var my = $(this);
		var frm_val = $('<input type="hidden" />').attr('name',opts.name).attr('value',opts.value);
		my.append(frm_val);
		
		if(is_numeric(opts.value) && (opts.value>0||opts.value<0)) {
			init_hav_default(opts.value);
		} else {
			create_select();
		}

		//设置初始值后进行自动联动
		function init_hav_default(catid) {
			var lpath = new Array();
			lpath.push(catid);
			var lcatid = catid;
			var res = new Array();
			do {
				res = get_parent_id(lcatid);
				lcatid = res[1];
				if(lcatid>0)lpath.unshift(lcatid);
			} while(res[0]>1);
			for (var i=0; i<lpath.length; i++) {
				var pid=i>0?lpath[i-1]:0;
				remove_select(i);
				create_select(i,pid,lpath[i]);
			}
		}
		//生成一个新一级的下拉框
		function create_select (level,pid,select_id) {
			if(opts.level>0 && opts.level<=level) {
				return;
			}
			if(!level) level=0;
			if(!pid) pid=0;
			var select = $('<select></select>').attr('level',level+1);
			var arr = opts.data.level[level];
			if(!arr) return;
			var selected=0;
			$.each(arr, function(i) {
				if(!pid||i==pid) {
					$.each(arr[i],function(j) {
						var option=$('<option value='+arr[i][j]+'>'+opts.data.data[arr[i][j]]+'</option>');
						select.append(option);
						if(select_id==arr[i][j]) {
							selected=select_id;
						}
					});
				}
			});
			if(select.text().length>0) {
				if(level==0 && opts.extra_data) {
					$.each(opts.extra_data,function(j) {
						select.prepend($('<option value='+j+'>'+opts.extra_data[j]+'</option>'));
					});
				}
				select.prepend('<option value="" selected="selected">请选择...</option>');
				my.append(select);
				select.change(function(){onchange($(this))});
				if(selected) {
					select.val(selected);
				}
			}
			if(select.val()) {
				onchange(select);
			}
		}
		//下拉框值更改后相应事件
		function onchange (obj) {
			var catid=obj.val();
			var level=parseInt(obj.attr('level'));
			remove_select(level);
			if(catid!='') {
				create_select(level,catid);
			}else{
				get_curent_value();
			}
			get_curent_value();
			if(opts.onchange) {
				opts.onchange(obj);
			}
		}
		//删除一个层级的下拉框
		function remove_select (level) {
			my.find('select').each(function(){
				if(parseInt($(this).attr('level'))>level) $(this).remove();
			});
		}
		//当前选择的值
		function get_curent_value () {
			var is_null=true;
			my.find('select').each(function(){
				if($(this).val()) {
					is_null = false;
					frm_val.val($(this).val()).attr('level',$(this).attr('level'));
				}
			});
			if(is_null) {
				frm_val.val('',0);
			}
		}
		//获取上一级ID
		function get_parent_id (catid) {
			var result = [0,0];
			if(catid>0)for (var i=0; i<opts.data.level.length; i++) {
				var arr=opts.data.level[i];
				$.each(arr, function(j) {
					$.each(arr[j], function(k) {
						if(arr[j][k]==catid) {
							result[0]=i;
							result[1]=j;
							return false;
						}
					});
				});
				if(result[1]>0) break;
			}
			return result;
		}

	}
})(jQuery);

/**
 * 框架形式无刷新提交表单
 * new $.mframe_form('表单ID',{选项},{回掉函数})
 * http://www.modoer.com
 * by mouferstudio
 */
(function($) {
	$.mframe_form = function(selector, options, callbacks) {
		//默认配置参数
		var _d_options = {
			set_domain:false,
			remove_posted:true
		}
		//默认回调函数列表
		var _d_callbacks = {
			onSucceed:null,
			onError:null
		}

		//当前类
		var _self = this;
		//工作区内容
		var _form = $(selector);
		//插件配置参数
		var _opts = $.extend({}, _d_options, options);
		//回调函数
		var _callbacks = $.extend({}, _d_callbacks, callbacks);

		var iframe = null;
		var iframeid = 'iframe_'+getRandom();

		var init = function() {

			_form.attr("target", iframeid);
			_form.append('<input type="hidden" name="in_ajax" value="1">');
			_form.append('<input type="hidden" name="in_iframe" value="1">');

			iframe = $('<iframe name="'+iframeid+'" id="'+iframeid+'"></iframe>').css("display","none");
			$(document.body).append(iframe);
			iframe.load(function() {
				load();
			});
		}

		var load = function() {
			if(_opts.set_domain && is_domain(sitedomain)) {
				document.domain = sitedomain;
			}
			//console.debug(iframe.html());
			var data = $(document.getElementById(iframeid).contentWindow.document.body).html();
			if(data.indexOf('ERROR:') > 1) {
				if(_callbacks.onError) {
					_callbacks.onError(data);
				} else {
					myAlert(data.replace('ERROR:',''));
				}
				return;
			} else {
				if(_callbacks.onSucceed) {
					_callbacks.onSucceed(data);
				} else {
					if(is_message(data)) {
						myAlert(data);
					}
				}
				if(_opts.remove_posted) iframe.remove();
			}
		}

		init();

		this.submit = function() {
			_form[0].submit();
		}

		this.remove = function() {
			iframe.remove();
		}

	}
})(jQuery);

/**
 * 内容显示滚动条
 * http://www.modoer.com
 * by mouferstudio
 */
 (function($) {
	$.fn.mscorllbar = function(options) {
		var defaults = {
			showbox:'J_article',
			setp:10,
			height:60,
			auto_init:true
		};
		var _this = this;
		var _opts = $.extend({}, defaults, options);
		var _enable = false;
		var _my = $(this);
		var _showbox = _my.find(_opts.showbox);
		var _scrollbox = null;
		var _srcollbar = null;
		var _height = 0;
		var _b_height = 0; //显示区步长比率
		var _bar_height = 0; //滚动条高度
		var _mouse = {};

		if(_opts.auto_init) init();

		function init() {

			 if(_scrollbox) {
				_scrollbox.remove();
				_showbox.css({position:'static'});
			}

			//高度计算
			_height = _showbox.height();
			_b_height = _height / _opts.height;
			_bar_height = _opts.height * (_opts.height / _height);

			_mouse.start = false;
			_mouse.begin_y = 0;

			//显示内容高于显示区
			_enable = _height > _opts.height;
			if(!_enable) return;

			_my.height(_opts.height + 1).addClass('scroll-div').css({overflow:'hidden'});
			_showbox.css({position:'relative'});
			_scrollbox = $('<div></div>').addClass('scroll-bar-box').height(_opts.height);
			_srcollbar = $('<div></div>').addClass('scroll-bar').height(_bar_height);
			_scrollbox.append(_srcollbar);
			_my.append(_scrollbox);

			_srcollbar.mousedown(function(e) {
				$(this).addClass('mousedown');
				_mouse.start = true;
				_mouse.begin_y = e.pageY;
				//鼠标不可选文字
				$(document).bind('selectstart', function() {
					return false;
				});
			});
			//鼠标拖动
			$(document).mousemove(function(e) {
				if(!_mouse.start) return;
				var y = e.pageY - _mouse.begin_y;
				if(y > 0) scroll_down(y);
				if(y < 0) scroll_up(Math.abs(y));
				_mouse.begin_y = e.pageY;
			});
			$(document).mouseup(function(e) {
				_srcollbar.removeClass('mousedown');
				_mouse.start = false;
				_mouse.begin_y = 0;
				$(document).unbind('selectstart');
			});
			//鼠标滚轮
			_my.bind('mousewheel', function(e, delta) {
				var px = Math.abs(delta) * _opts.setp;
				if(delta > 0) {
					scroll_up(px);
				} else {
					scroll_down(px);
				}
				return false;
			});
		}
		function scroll_down(setp) {
			if(!_enable) return;
			if(!setp) setp = _opts.setp;

			var top = get_top(_srcollbar);
			if (top + _srcollbar.height() >= _opts.height) {
				return;
			}
			bar_top = top + setp; //滚动条高度
			bar_top = Math.min(_opts.height -_bar_height, top + setp);
			dis_top = bar_top * _b_height; //显示内容高度

			_showbox.css({'top' : -dis_top + 'px'});
			_srcollbar.css({'top' : bar_top + 'px'});
		}
		function scroll_up(setp) {
			if(!_enable) return;
			if(!setp) setp = _opts.setp;

			var top = get_top(_srcollbar);
			if(top <= 0) return;

			bar_top = Math.max(0, top - setp);
			dis_top = bar_top * _b_height;

			_srcollbar.css({'top' : bar_top + 'px'});
			_showbox.css({'top' : -dis_top + 'px'});
		}
		function get_top(obj) {
			var top = obj.css('top');
			top = top.replace('px','');
			return parseInt(top);
		}

		return {
			init:function() {
				init();
			}
		};
	};
})(jQuery);

/*!
 * powerFloat.js by zhangxinxu(.com)
 * http://www.zhangxinxu.com/wordpress/?p=1328
 */
(function(a){a.fn.powerFloat=function(d){return a(this).each(function(){var g=a(this).attr("params");if(g){g=a.parseJSON(g)}var f=a.extend({},b,d||{},g||{});var h=function(j,i){if(c.target&&c.target.css("display")!=="none"){c.targetHide()}c.s=j;c.trigger=i},e;switch(f.eventType){case"hover":a(this).hover(function(){if(c.timerHold){c.flagDisplay=true}var i=parseInt(f.showDelay,10);h(f,a(this));if(i){if(e){clearTimeout(e)}e=setTimeout(function(){c.targetGet.call(c)},i)}else{c.targetGet()}},function(){if(e){clearTimeout(e)}if(c.timerHold){clearTimeout(c.timerHold)}c.flagDisplay=false;c.targetHold()});if(f.hoverFollow){a(this).mousemove(function(i){c.cacheData.left=i.pageX;c.cacheData.top=i.pageY;c.targetGet.call(c);return false})}break;case"click":a(this).click(function(i){if(c.display&&c.trigger&&i.target===c.trigger.get(0)){c.flagDisplay=false;c.displayDetect()}else{h(f,a(this));c.targetGet();if(!a(document).data("mouseupBind")){a(document).bind("mouseup",function(l){var j=false;if(c.trigger){var k=c.target.attr("id");if(!k){k="R_"+Math.random();c.target.attr("id",k)}a(l.target).parents().each(function(){if(a(this).attr("id")===k){j=true}});if(f.eventType==="click"&&c.display&&l.target!=c.trigger.get(0)&&!j){c.flagDisplay=false;c.displayDetect()}}return false}).data("mouseupBind",true)}}});break;case"focus":a(this).focus(function(){var i=a(this);setTimeout(function(){h(f,i);c.targetGet()},200)}).blur(function(){c.flagDisplay=false;setTimeout(function(){c.displayDetect()},190)});break;default:h(f,a(this));c.targetGet();a(document).unbind("mouseup").data("mouseupBind",false)}})};var c={targetGet:function(){if(!this.trigger){return this}var h=this.trigger.attr(this.s.targetAttr),g=typeof this.s.target=="function"?this.s.target.call(this.trigger):this.s.target;switch(this.s.targetMode){case"common":if(g){var i=typeof(g);if(i==="object"){if(g.size()){c.target=g.eq(0)}}else{if(i==="string"){if(a(g).size()){c.target=a(g).eq(0)}}}}else{if(h&&a("#"+h).size()){c.target=a("#"+h)}}if(c.target){c.targetShow()}else{return this}break;case"ajax":var d=g||h;this.targetProtect=false;if(!d){return}if(!c.cacheData[d]){c.loading()}var f=new Image();f.onload=function(){var m=f.width,q=f.height;var p=a(window).width(),s=a(window).height();var r=m/q,o=p/s;if(r>o){if(m>p/2){m=p/2;q=m/r}}else{if(q>s/2){q=s/2;m=q*r}}var n='<img class="float_ajax_image" src="'+d+'" width="'+m+'" height = "'+q+'" />';c.cacheData[d]=true;c.target=a(n);c.targetShow()};f.onerror=function(){if(/(\.jpg|\.png|\.gif|\.bmp|\.jpeg)$/i.test(d)){c.target=a('<div class="float_ajax_error">图片加载失败。</div>');c.targetShow()}else{a.ajax({url:d,success:function(m){if(typeof(m)==="string"){c.cacheData[d]=true;c.target=a('<div class="float_ajax_data">'+m+"</div>");c.targetShow()}},error:function(){c.target=a('<div class="float_ajax_error">数据没有加载成功。</div>');c.targetShow()}})}};f.src=d;break;case"list":var k='<ul class="float_list_ul">',j;if(a.isArray(g)&&(j=g.length)){a.each(g,function(n,p){var o="",r="",q,m;if(n===0){r=' class="float_list_li_first"'}if(n===j-1){r=' class="float_list_li_last"'}if(typeof(p)==="object"&&(q=p.text.toString())){if(m=(p.href||"javascript:")){o='<a href="'+m+'" class="float_list_a">'+q+"</a>"}else{o=q}}else{if(typeof(p)==="string"&&p){o=p}}if(o){k+="<li"+r+">"+o+"</li>"}})}else{k+='<li class="float_list_null">列表无数据。</li>'}k+="</ul>";c.target=a(k);this.targetProtect=false;c.targetShow();break;case"remind":var l=g||h;this.targetProtect=false;if(typeof(l)==="string"){c.target=a("<span>"+l+"</span>");c.targetShow()}break;default:var e=g||h,i=typeof(e);if(e){if(i==="string"){if(/^.[^:#\[\.,]*$/.test(e)){if(a(e).size()){c.target=a(e).eq(0);this.targetProtect=true}else{if(a("#"+e).size()){c.target=a("#"+e).eq(0);this.targetProtect=true}else{c.target=a("<div>"+e+"</div>");this.targetProtect=false}}}else{c.target=a("<div>"+e+"</div>");this.targetProtect=false}c.targetShow()}else{if(i==="object"){if(!a.isArray(e)&&e.size()){c.target=e.eq(0);this.targetProtect=true;c.targetShow()}}}}}return this},container:function(){var d=this.s.container,e=this.s.targetMode||"mode";if(e==="ajax"||e==="remind"){this.s.sharpAngle=true}else{this.s.sharpAngle=false}if(this.s.reverseSharp){this.s.sharpAngle=!this.s.sharpAngle}if(e!=="common"){if(d===null){d="plugin"}if(d==="plugin"){if(!a("#floatBox_"+e).size()){a('<div id="floatBox_'+e+'" class="float_'+e+'_box"></div>').appendTo(a("body")).hide()}d=a("#floatBox_"+e)}if(d&&typeof(d)!=="string"&&d.size()){if(this.targetProtect){c.target.show().css("position","static")}c.target=d.empty().append(c.target)}}return this},setWidth:function(){var d=this.s.width;if(d==="auto"){if(this.target.get(0).style.width){this.target.css("width","auto")}}else{if(d==="inherit"){this.target.width(this.trigger.width())}else{this.target.css("width",d)}}return this},position:function(){if(!this.trigger||!this.target){return this}var h,x=0,k=0,m=0,y=0,s,o,e,E,u,q,f=this.target.data("height"),C=this.target.data("width"),r=a(window).scrollTop(),B=parseInt(this.s.offsets.x,10)||0,A=parseInt(this.s.offsets.y,10)||0,w=this.cacheData;
if(!f){f=this.target.outerHeight();if(this.s.hoverFollow){this.target.data("height",f)}}if(!C){C=this.target.outerWidth();if(this.s.hoverFollow){this.target.data("width",C)}}h=this.trigger.offset();x=this.trigger.outerHeight();k=this.trigger.outerWidth();s=h.left;o=h.top;var l=function(){if(s<0){s=0}else{if(s+x>a(window).width()){s=a(window).width()-k}}},i=function(){if(o<0){o=0}else{if(o+x>a(document).height()){o=a(document).height()-x}}};if(this.s.hoverFollow&&w.left&&w.top){if(this.s.hoverFollow==="x"){s=w.left;l()}else{if(this.s.hoverFollow==="y"){o=w.top;i()}else{s=w.left;o=w.top;l();i()}}}var g=["4-1","1-4","5-7","2-3","2-1","6-8","3-4","4-3","8-6","1-2","7-5","3-2"],v=this.s.position,d=false,j;a.each(g,function(F,G){if(G===v){d=true;return}});if(!d){v="4-1"}var D=function(F){var G="bottom";switch(F){case"1-4":case"5-7":case"2-3":G="top";break;case"2-1":case"6-8":case"3-4":G="right";break;case"1-2":case"8-6":case"4-3":G="left";break;case"4-1":case"7-5":case"3-2":G="bottom";break}return G};var n=function(F){if(F==="5-7"||F==="6-8"||F==="8-6"||F==="7-5"){return true}return false};var t=function(H){var I=0,F=0,G=(c.s.sharpAngle&&c.corner)?true:false;if(H==="right"){F=s+k+C+B;if(G){F+=c.corner.width()}if(F>a(window).width()){return false}}else{if(H==="bottom"){I=o+x+f+A;if(G){I+=c.corner.height()}if(I>r+a(window).height()){return false}}else{if(H==="top"){I=f+A;if(G){I+=c.corner.height()}if(I>o-r){return false}}else{if(H==="left"){F=C+B;if(G){F+=c.corner.width()}if(F>s){return false}}}}}return true};j=D(v);if(this.s.sharpAngle){this.createSharp(j)}if(this.s.edgeAdjust){if(t(j)){(function(){if(n(v)){return}var G={top:{right:"2-3",left:"1-4"},right:{top:"2-1",bottom:"3-4"},bottom:{right:"3-2",left:"4-1"},left:{top:"1-2",bottom:"4-3"}};var H=G[j],F;if(H){for(F in H){if(!t(F)){v=H[F]}}}})()}else{(function(){if(n(v)){var G={"5-7":"7-5","7-5":"5-7","6-8":"8-6","8-6":"6-8"};v=G[v]}else{var H={top:{left:"3-2",right:"4-1"},right:{bottom:"1-2",top:"4-3"},bottom:{left:"2-3",right:"1-4"},left:{bottom:"2-1",top:"3-4"}};var I=H[j],F=[];for(name in I){F.push(name)}if(t(F[0])||!t(F[1])){v=I[F[0]]}else{v=I[F[1]]}}})()}}var z=D(v),p=v.split("-")[0];if(this.s.sharpAngle){this.createSharp(z);m=this.corner.width(),y=this.corner.height()}if(this.s.hoverFollow){if(this.s.hoverFollow==="x"){e=s+B;if(p==="1"||p==="8"||p==="4"){e=s-(C-k)/2+B}else{e=s-(C-k)+B}if(p==="1"||p==="5"||p==="2"){E=o-A-f-y;q=o-y-A-1}else{E=o+x+A+y;q=o+x+A+1}u=h.left-(m-k)/2}else{if(this.s.hoverFollow==="y"){if(p==="1"||p==="5"||p==="2"){E=o-(f-x)/2+A}else{E=o-(f-x)+A}if(p==="1"||p==="8"||p==="4"){e=s-C-B-m;u=s-m-B-1}else{e=s+k-B+m;u=s+k+B+1}q=h.top-(y-x)/2}else{e=s+B;E=o+A}}}else{switch(z){case"top":E=o-A-f-y;if(p=="1"){e=s-B}else{if(p==="5"){e=s-(C-k)/2-B}else{e=s-(C-k)-B}}q=o-y-A-1;u=s-(m-k)/2;break;case"right":e=s+k+B+m;if(p=="2"){E=o+A}else{if(p==="6"){E=o-(f-x)/2+A}else{E=o-(f-x)+A}}u=s+k+B+1;q=o-(y-x)/2;break;case"bottom":E=o+x+A+y;if(p=="4"){e=s+B}else{if(p==="7"){e=s-(C-k)/2+B}else{e=s-(C-k)+B}}q=o+x+A+1;u=s-(m-k)/2;break;case"left":e=s-C-B-m;if(p=="1"){E=o-A}else{if(p=="8"){E=o-(f-x)/2-A}else{E=o-(f-x)-A}}u=e+C-1;q=o+(x-y)/2;break}}if(y&&m&&this.corner){this.corner.css({left:u,top:q,zIndex:this.s.zIndex+1})}this.target.css({position:"absolute",left:e,top:E,zIndex:this.s.zIndex});return this},createSharp:function(g){var j,k,f="",d="";var i={left:"right",right:"left",bottom:"top",top:"bottom"},e=i[g]||"top";if(this.target){j=this.target.css("background-color");if(parseInt(this.target.css("border-"+e+"-width"))>0){k=this.target.css("border-"+e+"-color")}if(k&&k!=="transparent"){f='style="color:'+k+';"'}else{f='style="display:none;"'}if(j&&j!=="transparent"){d='style="color:'+j+';"'}else{d='style="display:none;"'}}var h='<div id="floatCorner_'+g+'" class="float_corner float_corner_'+g+'">'+'<span class="corner corner_1" '+f+">◆</span>"+'<span class="corner corner_2" '+d+">◆</span>"+"</div>";if(!a("#floatCorner_"+g).size()){a("body").append(a(h))}this.corner=a("#floatCorner_"+g);return this},targetHold:function(){if(this.s.hoverHold){var d=parseInt(this.s.hideDelay,10)||200;if(this.target){this.target.hover(function(){c.flagDisplay=true},function(){if(c.timerHold){clearTimeout(c.timerHold)}c.flagDisplay=false;c.targetHold()})}c.timerHold=setTimeout(function(){c.displayDetect.call(c)},d)}else{this.displayDetect()}return this},loading:function(){this.target=a('<div class="float_loading"></div>');this.targetShow();this.target.removeData("width").removeData("height");return this},displayDetect:function(){if(!this.flagDisplay&&this.display){this.targetHide();this.timerHold=null}return this},targetShow:function(){c.cornerClear();this.display=true;this.container().setWidth().position();this.target.show();if(a.isFunction(this.s.showCall)){this.s.showCall.call(this.trigger,this.target)}return this},targetHide:function(){this.display=false;this.targetClear();this.cornerClear();if(a.isFunction(this.s.hideCall)){this.s.hideCall.call(this.trigger)}this.target=null;this.trigger=null;
this.s={};this.targetProtect=false;return this},targetClear:function(){if(this.target){if(this.target.data("width")){this.target.removeData("width").removeData("height")}if(this.targetProtect){this.target.children().hide().appendTo(a("body"))}this.target.unbind().hide()}},cornerClear:function(){if(this.corner){this.corner.remove()}},target:null,trigger:null,s:{},cacheData:{},targetProtect:false};a.powerFloat={};a.powerFloat.hide=function(){c.targetHide()};var b={width:"auto",offsets:{x:0,y:0},zIndex:999,eventType:"hover",showDelay:0,hideDelay:0,hoverHold:true,hoverFollow:false,targetMode:"common",target:null,targetAttr:"rel",container:null,reverseSharp:false,position:"4-1",edgeAdjust:true,showCall:a.noop,hideCall:a.noop}})(jQuery);

/*!
 * powerSwitch.js by zhangxinxu(.com)
 * under MIT License
 * you can use powerSwitch to switch anything
*/
(function(b,c,d){var a=typeof history.pushState=="function";c.powerSwitch=function(f,e){c(f).powerSwitch(e)};c.extend(c.powerSwitch,{getRelative:function(f,h){f=c(f);if(f.length==0){return c()}var e=[],g=false;f.each(function(j,k){var i=c(this).attr(h.attribute)||(c(this).attr("href")||"").split("#")[1];if(i&&e[i]!=true){var l=c();if(/^\w+$/.test(i)){l=c("#"+i);if(l.length===0){l=c("."+i)}if(l.length===0){l=c(i)}}else{l=c(i)}l.each(function(m,n){e.push(n)});e[i]=true}else{if(e[i]==true){g=true}}});f.data("isMoreToOne",g);return c(e)},transition:function(h,g,f){var e="transform "+g+"ms linear";if(a==false){return}if(f==true){h.css("webkitTransition","none").css("transition","none").data("hasTransition",false)}else{if(!h.data("hasTransition")){h.css({webkitTransition:"-webkit-"+e,webkitBackfaceVisibility:"hidden",transition:e,BackfaceVisibility:"hidden"}).data("hasTransition",true)}}},translate:function(g,e,f){var h="translate"+e+"("+f+")";a?g.css("webkitTransform",h).css("transform",h):g.css(e=="X"?{left:f}:{top:f})},animation:function(p,h,k){var f=null,n=this,l=k.animation=="none";var i=function(t,r,s){if(parseInt(s)===s){s+="px"}if(a){n.transition(t,k.duration,l);n.translate(t,r,s)}else{t[l?"css":"animate"](r=="X"?{left:s}:{top:s},k.duration)}};if((h&&h.length)||(p&&p.length)){if(k.toggle==true&&k.animation=="translate"){k.animation="none"}switch(k.animation){case"translate":var j=p.data("index"),o=h.data("index");var g={vertical:"Y",horizontal:"X"};if(j!=d&&o!=d){var q=100,e=true;if(k.prevOrNext){switch(k.prevOrNext.attr("data-type")){case"prev":e=false;break;case"next":e=true;break;default:e=j<o}}q=(e*2-1)*100;n.transition(h.show(),k.duration,true);n.translate(h,g[k.direction],q+"%");setTimeout(function(){i(p,g[k.direction],-1*q+"%");i(h,g[k.direction],"0%")},17);k.prevOrNext=null}else{p.hide();h.show()}break;case"slide":if(k.duration!="sync"){if(p){p.slideUp(k.duration)}if(h){h.slideDown(k.duration)}}else{if(p){p.slideUp("normal",function(){if(h){h.slideDown()}})}else{if(h){h.slideDown()}}}break;case"fade":if(k.duration!="sync"){if(p){p.fadeOut(k.duration)}if(h){h.fadeIn(k.duration)}}else{if(p){p.fadeOut("normal",function(){if(h){h.fadeIn()}})}else{if(h){h.fadeIn()}}}break;case"visibility":if(p){p.css("visibility","hidden")}if(h){h.css("visibility","visible")}break;default:if(p){p.hide()}if(h){h.show()}}}else{if(k.container&&k.container.length){var m=k.container.data("position");f=k.container.get(0);if(k.direction=="vertical"){if(f.scrollHeight-f.clientHeight>=Math.max(m.top,1)){k.animation=="auto"?k.container.animate({scrollTop:m.top}):k.container.scrollTop(m.top)}else{i(k.container,"Y",-1*m.top)}}else{if(f.scrollWidth-f.clientWidth>=Math.max(m.left,1)){k.animation=="auto"?k.container.animate({scrollLeft:m.left}):k.container.scrollLeft(m.left)}else{i(k.container,"X",-1*m.left)}}}}}});c.fn.powerSwitch=function(k){var s={direction:"horizontal",eventType:"click",classAdd:"",classRemove:"",classPrefix:"",attribute:"data-rel",animation:"auto",duration:250,container:null,autoTime:0,number:"auto",hoverDelay:200,toggle:false,onSwitch:c.noop};var z=c.extend({},s,k||{});c.each(["disabled","prev","play","pause","next"],function(C,F){F=c.trim(F);var G=F.slice(0,1).toUpperCase()+F.slice(1),E="class"+G,D=z.classPrefix.slice(-1);if(z[E]===d){if(z.classPrefix){if(/\-/g.test(z.classPrefix)){z[E]=D=="-"?(z.classPrefix+F):[z.classPrefix,F].join("-")}else{if(/_/g.test(z.classPrefix)){z[E]=D=="_"?(z.classPrefix+F):[z.classPrefix,F].join("_")}else{z[E]=z.classPrefix+G}}}else{z[E]=F}}});var B=z.indexSelected||-1,h=parseInt(z.number)||1,f=null,w=null,v=c(),l=0;var u=c(this);if(u.length==0){if(z.container==null||z.autoTime==0){return u}}v=c.powerSwitch.getRelative(u,z);if((l=v.length)==0){return u}if(B==-1&&z.toggle==false){if(z.classAdd){u.each(function(C,D){if(B!=-1){return}if(c(D).hasClass(z.classAdd)){B=C}})}else{v.each(function(C,D){if(B!=-1){return}if(z.animation=="visibility"){if(c(D).css("visibility")!="hidden"){B=C}}else{if(c(D).css("display")!="none"){B=C}}})}}var t=false,y=c(),A=c(),g=c();var m=function(C){if(C<=0){y.addClass(z.classDisabled).removeAttr("title").attr("disabled","disabled")}else{y.removeClass(z.classDisabled).attr("title",y.data("title")).removeAttr("disabled")}if((l-C)/h>1){A.removeClass(z.classDisabled).attr("title",A.data("title")).removeAttr("disabled")}else{A.addClass(z.classDisabled).removeAttr("title").attr("disabled","disabled")}};if(u.eq(0).data("isMoreToOne")==true){t=true;if(z.classDisabled){y=u.eq(0),A=u.eq(1);y.data("title",y.attr("title"));A.data("title",A.attr("title"));m(B);if(B<=0&&z.container){c(z.container).scrollLeft(0).scrollTop(0)}}else{if(z.container){v.clone().insertAfter(v.eq(l-1));v=c.powerSwitch.getRelative(u,z);g=u.eq(1)}else{y=u.eq(0),A=u.eq(1);g=A}}}var x=false;if(u.length==1&&l>1){x=true}var r=function(G){var F=v.slice(G,G+h);var H=null,E=null,D=null;if(z.toggle==false){if(t==true){if(z.container){var C=F.position();z.container=c(z.container);z.container.data("position",C);c.powerSwitch.animation(null,null,z);z.classDisabled&&m(G)}else{c.powerSwitch.animation(v.eq(B,B+h),F,z)}z.onSwitch.call(this,F)}else{if(x==true){c.powerSwitch.animation(null,F,z);z.onSwitch.call(this,F)}else{if(B!==G){E=u.eq(G);if(B>=0){H=u.eq(B);D=v.eq(B,B+h)}else{H=c();D=c()}E.addClass(z.classAdd).removeClass(z.classRemove);H.addClass(z.classRemove).removeClass(z.classAdd);c.powerSwitch.animation(D,F,z);z.onSwitch.call(this,F,H,D)}}}B=G}else{if((z.animation=="visibility"&&F.css("visibility")=="hidden")||(z.animation!="visibility"&&F.css("display")=="none")){c.powerSwitch.animation(null,F,z);display=true}else{c.powerSwitch.animation(F,null,z);display=false}z.onSwitch.call(this,F,display)}};var e=location.href.split("#")[1];u.each(function(C,D){c(D).data("index",C);if(t==true){c(D).bind("click",function(){var F,E;if(z.classDisabled){if(c(this).attr("disabled")){return false}if(C==0){F=B-h;F=Math.max(0,F)}else{if(C==1){F=B+h;F=Math.min(F,l-1)}}r.call(this,F)}else{if(z.container&&l>h){if(C==0){F=B-h;if(F<0){E=v.eq(B+l);c(z.container).data("position",E.position());c.powerSwitch.animation(null,null,c.extend({},z,{animation:"none"}));F=B+l-h}}else{if(C==1){F=B+h;if(F>l*2-h){E=v.eq(B-l);c(z.container).data("position",E.position());c.powerSwitch.animation(null,null,c.extend({},z,{animation:"none"}));F=B-l+h}}}r.call(this,F);g=c(this)}else{C?o(this):n(this);g=c(this)}}return false})}else{if(x==true){c(D).bind("click",function(){var E;if(z.number=="auto"){h=l}if(!c(this).attr("disabled")){if(B==-1){E=0}else{E=B+h}r.call(this,E);if(E>=l-1){c(this).addClass(z.classDisabled).attr("disabled","disabled").removeAttr("title")}}return false})}else{if(z.eventType=="click"){c(D).bind("click",function(){z.prevOrNext=c(this);r.call(this,C);if(this.id!==c(this).attr(z.attribute)){return false}});if(e&&D.href&&e==D.href.split("#")[1]){c(D).trigger("click")}}else{if(/^hover|mouseover$/.test(z.eventType)){c(D).hover(function(){z.prevOrNext=c(this);clearTimeout(f);f=setTimeout(function(){r.call(D,C)},parseInt(z.hoverDelay)||0)},function(){clearTimeout(f)})}}}}});v.each(function(C,D){c(D).data("index",C)});var o=function(C){var D=B+1;if(D>=l){D=0}r.call(C||u.get(D),D)},n=function(C){var D=B-1;if(D<0){D=l-1}r.call(C||u.get(D),D)},p=function(){g.trigger("click")},j=function(){clearTimeout(w);if(j.flagAutoPlay==true){w=setTimeout(function(){t==false?o():p();j()},z.autoTime)}};if((x==false&&z.toggle==false&&t==false)||(t==true&&!z.classDisabled)){if(z.container&&t==false){var i="";u.length&&c.each(["Prev","Pause","Next"],function(C,D){if(z.autoTime==0&&D=="Pause"){return}i=i+'<a href="javascript:" class="'+z["class"+D]+'" data-type="'+D.toLowerCase()+'"></a>'});z.container.append(i).delegate("a","click",function(){var C=c(this).attr("data-type"),E=z["class"+C.slice(0,1).toUpperCase()+C.slice(1)],D=B;switch(C){case"prev":z.prevOrNext=c(this);n();break;case"play":j.flagAutoPlay=true;c(this).attr("data-type","pause").removeClass(E).addClass(z.classPause);j();break;case"pause":j.flagAutoPlay=false;c(this).attr("data-type","play").removeClass(E).addClass(z.classPlay);j();break;case"next":z.prevOrNext=c(this);o();break}return false})}if(z.autoTime){var q=[u,v,z.container];if(t==true||(document.body.contains&&z.container&&z.container.get(0).contains(v.get(0)))){q=[u,z.container]}c.each(q,function(C,D){if(D){D.hover(function(E){if(E.pageX!==d||z.eventType=="click"){clearTimeout(w)}},function(E){if(E.pageX!==d||z.eventType=="click"){j()}})}});j.flagAutoPlay=true;j()}}return u}})(window,jQuery);

