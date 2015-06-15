function showbox(box1) {
	
	if($(box1).show()){//显示box
		
		//显示遮罩层
	    var bh = $("body").height();
	    $(".gray_layer").css({
	        height:bh,
	        display:"block"
	    });
	}
    
    if(window.ActiveXObject){//判断浏览器是否属于IE
		 var browser=navigator.appName ;
		 var b_version=navigator.appVersion ;
		 var version=b_version.split(";"); 
		 var trim_Version=version[1].replace(/[ ]/g,""); 
		 if(browser=="Microsoft Internet Explorer" && trim_Version=="MSIE6.0") { 
			$(document).ready(function() {
				var domThis = $(box1)[0];
				var wh = $(window).height() / 2;
				$("body").css({
					"background-image": "url(about:blank)",
					"background-attachment": "fixed"
				});
				domThis.style.setExpression('top', 'eval((document.documentElement).scrollTop + ' + wh + ') + "px"');
			});	
		 }
   	}
}
//关闭灰色 jQuery 遮罩
function closeBg() {
    $(".iframe_box,.gray_layer").hide();
}

//调用Frame
function setFrame(id,path){
	var i = document.getElementById(id);
	i.src = path;
	showbox($('.box1'));
	//return true;
}

function setFrameIndex(id){
	var i = document.getElementById(id);
	showbox($('.box1'));
	return true;
}

$(function() {
	$('.selectNo .closed_layer_s').click(function(){
		$('.gray_layer').hide();
	});
});