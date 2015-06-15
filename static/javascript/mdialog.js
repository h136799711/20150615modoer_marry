/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/

(function($) {
    $.fn.mydrag = function (options) {
        $.fn.mydrag.defaults = {
            obj: null,
            move_callback: null,
            end_callback: null
        }
        var opts = $.extend({}, $.fn.mydrag.defaults, options);
        var myp = $(this);

        myp.find(opts.obj).each(function() {

            this.posRange = {
                minX:0, minY:0,
                maxX:(document.compatMode == "CSS1Compat" ? document.documentElement.clientWidth : document.body.clientWidth),
                maxY:(document.compatMode == "CSS1Compat" ? document.documentElement.clientHeight : document.body.clientHeight)
            };
            this.onmousedown = function(e) {
                //this.style.zIndex = $.zIndex++;
                //this.style.position = "absolute";
               this.drag(e);
            }
            this.drag = function(e) {
                var element = this,ev = e || window.event;
                ev.rScreenX = ev.screenX;
                ev.rScreenY = ev.screenY;
                var pos = $(this).offset();
                element.dragConfig = {
                    defaultX : parseInt(pos.left,10),
                    defaultY : parseInt(pos.top,10),
                    defaultW : parseInt($(this).width(),10),
                    defaultH : parseInt($(this).height(),10)
                };
                document.onmouseup = function() {
                    element.drop();
                    opts.end_callback && opts.end_callback();
                };
                document.onmousemove = function(e) {
                    var ev2 = e || window.event;
                    if($.browser.msie && ev2.button != 1) {
                        return (element.drop(), callback && callback());
                    }
                    var mx = element.dragConfig.defaultX + (ev2.screenX - ev.rScreenX),
                    my = element.dragConfig.defaultY + (ev2.screenY - ev.rScreenY),
                    pr = element.posRange,
                    mw = element.dragConfig.defaultW, 
                    mh = element.dragConfig.defaultH;

                    var x_left = (mx<pr.minX?pr.minX:mx) + "px";
                    var x_top = (my<pr.minY?pr.minY:my) + "px";
                    //left = (mx<pr.minX?pr.minX:((mx+mw)>pr.maxX?(pr.maxX-mw):mx)) + "px";
                    //top = (my<pr.minY?pr.minY:((my+mh)>pr.maxY?(pr.maxY-mh):my)) + "px";
                    myp.css({'left':x_left, 'top':x_top});
                    
                    opts.move_callback && opts.move_callback();
                    return false;
                };
                document.onselectstart = function() { return false; }
            }
            this.drop = function() {
                document.onmousemove = document.onselectstart = document.onmouseup = null;
            }
        });
    }
})(jQuery);

(function($) {
    $.mmsg = function(options) {

        var defaults = {
            message: '',
            width:0,
            height:0,
            time:3000
        }

        var MSGID = "MODOER_MESSAGE";
        var MSGTIME = null;
        var opts = $.extend(defaults, options);
        var _msg = null;

        function _init() {
            if($("#"+MSGID)[0] != null) {
                return;
            }

            _msg = $("<div></div>").attr("id",MSGID);
            _msg.addClass("mmessage");
            _msg.append($("<div></div>").addClass("mbody").append(opts.message)).click(function(){
                _close();
            });
            $(document.body).append(_msg);

            _msg.css({
                display: "block", 
                margin: "0px", 
                padding: "0px", 
                position: "absolute", 
                zIndex: "2105"
            });
            if(opts.width > 0) _msg.css('width', opts.width);
            if(opts.height > 0) _msg.css('height', opts.height);

            var p = _axis(opts.width, opts.height);
            _msg.css({ top:p.y,  left:p.x });

            if(opts.time > 0) {
                MSGTIME = window.setTimeout(function() {
                    _close();
                }, opts.time);
            }
        }

        function _close() {
            if($("#"+MSGID)[0]!=null) {
                $("#"+MSGID).remove();
            }
            if(MSGTIME) window.clearTimeout(MSGTIME);
        }

        function _axis() {
            width = _msg.width();
            height = _msg.height();

            var dde = document.documentElement;
            if (window.innerWidth) {
                var ww = window.innerWidth;
                var wh = window.innerHeight;
                var bgX = window.pageXOffset;
                var bgY = window.pageYOffset;
            } else {
                var ww = dde.offsetWidth;
                var wh = dde.offsetHeight;
                var bgX = dde.scrollLeft;
                var bgY = dde.scrollTop;
            }
            x = bgX + ((ww - width) / 2);
            y = bgY + ((wh - height) / 2);
            return {x:x, y:y};
        }

        _init();
    }
})(jQuery);

(function($) {
    $.mdialog = function(options) {

        var defaults = {
            id: '',
            lock: false,
            title: '对话框',
            body: '',
            width: 0,
            height: 0
        }
        var opts = $.extend({}, defaults, options);

        var SRCEENID = 'MODOER_SCREENOVER';
        var MOVEX = 0;
        var MOVEY = 0;
        var MSGTIME = 0;

        var _dlg = null;

        function init() {
            if(opts.id != '' && $("#"+opts.id)[0] != null) {
                _dlg = $("#"+opts.id).hide();
                return;
            }

            _dlg = $("<div></div>");
            if(opts.id != '') {
                _dlg.attr("id", opts.id);
            }

            if(opts.lock) _srceenOpen();

            _dlg.addClass("mdialog");

            //标题
            _dlg.append($("<div></div>").html("<em></em><span></span>"));
            var dragfoo = _dlg.find("div");
            dragfoo.addClass("mheader");
            _dlg.find("div > span").text(opts.title);
            _dlg.find("div > em").attr('data-type', 'close');

            //内容区
            _dlg.append($("<div></div>").addClass("mbody").append(opts.body));
            if($.browser.msie && $.browser.version.substr(0,1)=='6' ) {
                $("select").each(function(i) { 
                    this.style.visibility="hidden";
                });
            }

            $(document.body).append(_dlg);

            _dlg.css({
                display:"block", 
                margin:"0px", 
                padding:"0px", 
                position:"absolute", 
                zIndex:"2100"
            });
            if(opts.width > 0) _dlg.css('width', opts.width);
            if(opts.height > 0) _dlg.css('height', opts.height);

            var p = _axis(opts.width, opts.height);
            if(p.y<0) p.y=0;
            if(p.x<0) p.x=0;
            _dlg.css({ top:p.y,  left:p.x });
            _dlg.mydrag({'obj':'.mheader'});

            //关闭按钮
            _dlg.find('[data-type="close"]').click(function() {
                _close();
                return false;
            });

            _dlg.fadeIn('fast');
        }

        function _close () {
            _dlg.fadeOut('fast', function() {
                _dlg.remove();
            });

            if(opts.lock) _srceenClose();
            //监测是否有其他对话框
            if(!$('.mdialog')[0]) {
                if($.browser.msie && $.browser.version.substr(0,1)=='6' ) {
                    $("select").each(function(i) {
                        this.style.visibility = "";
                    });
                }
            }
        }

        function _axis() {
            width = _dlg.width();
            height = _dlg.height();

            var dde = document.documentElement;
            if (window.innerWidth) {
                var ww = window.innerWidth;
                var wh = window.innerHeight;
                var bgX = window.pageXOffset;
                var bgY = window.pageYOffset;
            } else {
                var ww = dde.offsetWidth;
                var wh = dde.offsetHeight;
                var bgX = dde.scrollLeft;
                var bgY = dde.scrollTop;
            }
            x = bgX + ((ww - width) / 2);
            y = bgY + ((wh - height) / 2);
            return {x:x, y:y};
        }

        function _srceenOpen() {
            if($('#'+SRCEENID)[0]!=null) return;
            var wh = "100%";
            if (document.documentElement.clientHeight && document.body.clientHeight) {
                if (document.documentElement.clientHeight > document.body.clientHeight) {
                    wh = document.documentElement.clientHeight + "px";
                } else {
                    wh = document.body.clientHeight + "px";
                }
            } else if (document.body.clientHeight) {
                wh = document.body.clientHeight + "px";
            } else if (window.innerHeight) {
                wh = window.innerHeight + "px";
            }
            var o = 40/100;
            var scr = $('<div></div>').attr("id", SRCEENID).css({
                    display:"block", top:"0px", left:"0px", margin:"0px", padding:"0px",
                    width:"100%", height:wh, position:"absolute", zIndex:1099, background:"#8A8A8A",
                    filter:"alpha(opacity=25)", opacity:o, MozOpacity:o, display:"none"
            });
            $(document.body).append(scr);
            $('#'+SRCEENID).fadeIn("fast");
        }

        function _srceenClose() {
            $('#'+SRCEENID).fadeOut("fast", function() {
                $('#'+SRCEENID).remove();
            });
        }

        this.area_obj = function() {
            return _dlg;
        }

        this.close = function() {
            _close();
        }

        this.container = function() {
            return _dlg;
        }

        init();

    };
})(jQuery);

var mdialog_global = 'MODOER_MDIALOG';
dlgOpen = function(title, body, width, height, dlgid) {
    if(!width) width = 0;
    if(!height) height = 0;
    if(!dlgid) dlgid = mdialog_global;
    GLOBAL[dlgid] = new $.mdialog({
        lock: true, title:title, body:body, width:width, height:height, dlgid:dlgid
    });
    return GLOBAL[dlgid];
}

dlgClose = function(dlgid) {
	if(!dlgid) dlgid = mdialog_global;
    if(dlgid && typeof(GLOBAL[dlgid]) == 'object') {
        var dialog = GLOBAL[dlgid];
        dialog.close();
        delete window.GLOBAL[dlgid];
    }
}

msgOpen = function() {
    if(arguments.length == 0) return;
    var options = {};
    options.message = arguments[0];
    if(typeof(arguments[1])!='number') {
        options.time = arguments[1];
    }
    $.mmsg(options);
}

msgClose = function() {
    $('#MODOER_MESSAGE').remove();
}

jQuery.mytip = {
	show:function() {
		var ld = $('#ajax_loading');
		if(!ld[0]) {
			ld = $('<div>loading...</div>')
				.attr('id','ajax_loading');
		}
		ld.css({"display":"none","background":"red","color":"#FFF","padding":"2px 5px","z-index":"1000"});
		$(document.body).append(ld);
		if(typeof(arguments[0]) == 'string') ld.html(arguments[0]);
		ld.css("position", "absolute")
			.css("top", document.documentElement.scrollTop + 5)
			.css("left", window.document.documentElement.clientWidth - ld.width()-15)
			.show();  
	},
	close:function() {
		var ld = $('#ajax_loading');
		if(typeof(arguments[0]) == 'string') {
			ld.html(arguments[0])
				.css("top", document.documentElement.scrollTop + 5)
				.css("left", window.document.documentElement.clientWidth - ld.width()-15)
			window.setTimeout(function() {
				ld.fadeOut('normal',function(){ld.remove()});
			},800);
		} else {
			ld.fadeOut('normal',function(){ld.remove()});
		}
		  
	}
};

