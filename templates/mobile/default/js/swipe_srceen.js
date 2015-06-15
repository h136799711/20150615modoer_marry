//swipe_srceen = new Object;
swipe_srceen = function(body_id, images_id, first_index) {
	var body = $(body_id);

	var slider = null;
	var srceenObj = null;
	var scrollTop = 0;

	var srceen = {};
	var swipe = {};

	var i_swipe = null;

	var getImages = function() {
		var images = new Array();
        body.find(images_id).each(function() {
        	url = $(this).data('url');
        	if(url) {
        		images.push(url);
        	} else {
        		images.push($(this).attr('src'));
        	}
        });
	    return images;
	}

	swipe.init = function() {
		var images = getImages();
		if(images.length == 0) return;

		srceen.open();

		slider = $('<div></div>').addClass('swipe').attr('id', 'slider');
		var sw = $('<div></div>').addClass('swipe-wrap');
		$.each(images, function(index, val) {
	        sw.append('<figure><div class="wrap"><div class="image" style="background-size: cover">'+
	        	'<img class="wrapimg" src="'+val+'" /></div></figure>');
		});
	    slider.append(sw).click(function(){
	        srceen.close();
	    });
	    $(document.body).append(slider);
	    swipe.autoSize();

	    if(!first_index) first_index=0;
	    i_swipe = Swipe(document.getElementById('slider'), {
	    	'startSlide':first_index
	    });

	    $(window).resize(function(){
		    if(slider[0]) swipe.autoSize();
		});
	}
	swipe.autoSize = function() {
	    slider.width($(window).width());
	    var max_height = 0;
	    slider.find(".wrapimg").each(function(){
	        max_height = Math.max(max_height, $(this).height());
	        $(this).css('max-width', $(window).width() + 'px');
	    });
	    max_height = $(window).height();
	    slider.css({
	        top: 0,
	        height: max_height+'px'
	    });
	    slider.find('.image').css('height', max_height + 'px');
	}

	srceen.open = function () {
	    scrollTop = $(window).scrollTop();
	    $(window).scrollTop(0);//回到页面
	    $('#mobile_body').hide();//隐藏网页内容
	    if(srceenObj != null) {
	    	srceenObj.show();
	    }
	    $(window).scrollTop(0);
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
	    var o = 99/100;
	    srceenObj = $('<div></div>').css({
            display:"block",
            top:"0px",
            left:"0px",
            margin:"0px",
            padding:"0px",
            width:"100%",
            height:wh,
            position:"absolute",
            zIndex:1099,
            background:"#000",
            filter:"alpha(opacity=99)",
            opacity:o,
            MozOpacity:o,
            display:"none"
	    });
	    srceenObj.fadeIn("fast").click(function() {
	        this.close();
	    });
		$(document.body).append(srceenObj);
	}

	srceen.close = function () {
	    srceenObj.fadeOut("fast", function() {
	        srceenObj.remove();
	    });
	    slider.remove();
	    $('#mobile_body').show();//显示网页内容
	    $(window).scrollTop(scrollTop);//窗口回到之前显示的位置
	}

	swipe.init();
}