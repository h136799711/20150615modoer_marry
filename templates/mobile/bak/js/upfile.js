function mUpImage (options) {
    var opts = {
    	message:null,
    	preview:null,
    	uploaded:null,
        postUrl: '',
        postName:'pictures',
        headerName:'ajaxuploadfilename',
        maxSize: 512000,
        maxNum: 10,
        fileInput: 'fileImage',
        upButton: 'fileSubmit',
        onClick: null		//点击已上传的图片产生的事件
    }
    opts = $.extend({}, opts, options);

    var message = $('#' + opts.message);
    var preview = $('#' + opts.preview);
    var uploaded = $('#' + opts.uploaded);

    var uploadedCount = 0;

    function addUploaded(file, upload_filename) {
        var ipt = $('<input type="hidden" name="'+opts.postName+'[]" value="'+upload_filename+'">');
        var html = $('<li class="upload_append_list"></li>').append('<div class="thumb">' +
                '<img src="'+urlroot+'/' + upload_filename + '" class="upload_image" /></div>'+
                '<span><a href="javascript:;" class="upload_delete" data-name="'+upload_filename+'">删除</a></span>');
        html.append(ipt);
        html.find('.upload_delete').click(function() {
            delUploadFile($(this).parent().parent());
        });
        html.find('.upload_image').click(function() {
        	if(opts.onClick) {
        		var src=$(this).attr('src');
        		opts.onClick(src, upload_filename);
        	}
        });
        uploaded.append(html);
        $('div.uploaded_box').show();
        uploadedCount++;
    }

    function delUploadFile(obj) {
        obj.fadeOut().remove();
        uploadedCount = uploaded.find('li').size();
        if(uploadedCount == 0) {
            $('div.uploaded_box').hide();
        }
    }

    function addUploadMsg(msg, auto_hide_time) {
        var a = $('<a>[关闭]</a>').attr('href','javascript:;').click(function() {
            $(this).parent().remove();
        });
        var p = $('<p>'+msg+'</p>').append(a);
        message.append(p);
        if(auto_hide_time && auto_hide_time > 0) {
            window.setTimeout(function(){
                p.fadeOut();
            }, auto_hide_time);
        }
    }

    var events = {
		filter : function(files, queueCount) {
		    var arrFiles = [];
		    if(uploadedCount + queueCount > opts.maxNum) {
				addUploadMsg('图片单次只能上传'+opts.maxNum+'张，无法继续添加图片。', 2000);
				return arrFiles;
		    }
		    var addCount = 0;
		    for (var i = 0, file; file = files[i]; i++) {
		        if (file.type.indexOf("image") == 0) {
		            if (file.size >= opts.maxSize) {
		                addUploadMsg('您这张"'+ file.name +'"图片过大，应小于'+Math.round(opts.maxSize/1024)+'k', 2000);    
		            } else {
		            	addCount++;
		            	if(uploadedCount + queueCount + i + 1 > opts.maxNum) {
		            		addUploadMsg('图片单次只能上传'+opts.maxNum+'张，无法继续添加图片'+file.name+'。', 2000);
		            		return arrFiles;
		            	} else {
		            		arrFiles.push(file);
		            	}
		            }           
		        } else {
		            addUploadMsg('文件"' + file.name + '"不是图片。', 2000);
		        }
		    }
		    return arrFiles;
		},
		onSelect : function(files) {
		    var html = '', i = 0;
		    preview.html('<div class="upload_loading">正在读取图片...</div>');
		    var funAppendImage = function() {
		        file = files[i];
		        if (file) {
		            var reader = new FileReader()
		            reader.onload = function(e) {
		                html = html + '<li id="uploadList_'+ i +'" class="upload_append_list"><div class="thumb">' +
		                    '<img id="uploadImage_' + i + '" src="' + e.target.result + '" class="upload_image" /></div>'+
		                    '<span><a href="javascript:" class="upload_delete" title="删除" data-index="'+ i +'">删除</a></span>'+
		                    '<span id="uploadProgress_' + i + '" class="upload_progress"></span>' +
		                '</li>';
		                i++;
		                funAppendImage();
		            }
		            reader.readAsDataURL(file);
		        } else {
		            preview.html(html);
		            if (html) {
		                //删除方法
		                $(".upload_delete").click(function() {
		                    ZXXFILE.funDeleteFile(files[parseInt($(this).attr("data-index"))]);
		                    return false;   
		                });
		                //提交按钮显示
		                $('#'+opts.upButton).show();    
		            } else {
		                //提交按钮隐藏
		                $('#'+opts.upButton).hide();    
		            }
		        }
		    };
		    funAppendImage();  
		},
		onDelete : function(file) {
			$("#uploadList_" + file.index).fadeOut();
		},
		onProgress : function(file, loaded, total) {
		    var eleProgress = $("#uploadProgress_" + file.index), percent = (loaded / total * 100).toFixed(2) + '%';
		    eleProgress.show().html(percent);
		},
		onSuccess : function(file, response) {
		    var box = $('#uploadList_'+file.index);
		    var pro = $("#uploadProgress_" + file.index);
		    var message = "";
		    var autohide = 0;
		    if(is_message(response)) {
		        message = myAlert(response, true);
		    } else if(is_json(response)) {
		        data = parse_json(response);
		        if(data.code=='200') {
		            addUploaded(file, data.filename);
		            autohide = 1000;
		            message = "图片上传成功！";
		        }
		    }
		    if(message=='') message = "图片上传失败！";
		    addUploadMsg(message + "("+file.name+")", autohide);
		},
		onFailure : function(file) {
		    addUploadMsg("图片" + file.name + "上传失败！");
		    $("#uploadImage_" + file.index).css("opacity", 0.2);
		},
		onComplete : function() {
		    //提交按钮隐藏
		    $("#fileSubmit").hide();
		    //file控件value置空
		    $("#fileImage").val("");
		}
    }

	var params = {
        fileInput: $('#'+opts.fileInput).get(0),
        upButton: $('#'+opts.upButton).get(0),
        url: opts.postUrl,
        events: events,
        headerName: opts.headerName
    };
    ZXXFILE = $.extend(ZXXFILE, params);
    ZXXFILE.init();
    $('#'+opts.upButton).hide();

    return {
    	getUploadCount : function(){
    		return uploadedCount;
    	},
    	getQueueCount : function() {
    		return ZXXFILE.getQueueCount();
    	}
    }
}

/*
 * zxxFile.js
 * http://www.zhangxinxu.com/wordpress/?p=1923
 * by zhangxinxu 2011-09-12
*/
var ZXXFILE = {
	fileInput: null,				//html file控件
	upButton: null,					//提交按钮
	url: "",						//ajax地址
	events: null,					//触发事件
	fileFilter: [],					//过滤后的文件数组
	filter: function(files) {		//选择文件组的过滤方法
		return files;	
	},
	/*
	onSelect: function() {},		//文件选择后
	onDelete: function() {},		//文件删除后
	onProgress: function() {},		//文件上传进度
	onSuccess: function() {},		//文件上传成功时
	onFailure: function() {},		//文件上传失败时,
	onComplete: function() {},		//文件全部上传完毕时
	*/
	
	/* 开发参数和内置方法分界线 */
	//获取选择文件
	funGetFiles: function(e) {
		var queueCount = this.fileFilter.length;
		// 获取文件列表对象
		var files = e.target.files || e.dataTransfer.files;
		//继续添加文件
		var newfiles = this.events.filter(files, queueCount);
		if(newfiles.length > 0) {
			this.fileFilter = this.fileFilter.concat(newfiles);
			this.funDealFiles();
		}
		return this;
	},
	
	//选中文件的处理与回调
	funDealFiles: function() {
		for (var i = 0, file; file = this.fileFilter[i]; i++) {
			//增加唯一索引值
			file.index = i;
		}
		//执行选择回调
		this.events.onSelect(this.fileFilter);
		return this;
	},
	
	//删除对应的文件
	funDeleteFile: function(fileDelete) {
		var arrFile = [];
		for (var i = 0, file; file = this.fileFilter[i]; i++) {
			if (file != fileDelete) {
				arrFile.push(file);
			} else {
				this.events.onDelete(fileDelete);	
			}
		}
		this.fileFilter = arrFile;
		return this;
	},
	
	//文件上传
	funUploadFile: function() {
		var self = this;	
		if (location.host.indexOf("sitepointstatic") >= 0) {
			//非站点服务器上运行
			return;	
		}
		for (var i = 0, file; file = this.fileFilter[i]; i++) {
			(function(file) {
				var xhr = new XMLHttpRequest();
				if (xhr.upload) {
					// 上传中
					xhr.upload.addEventListener("progress", function(e) {
						self.events.onProgress(file, e.loaded, e.total);
					}, false);
					// 文件上传成功或是失败
					xhr.onreadystatechange = function(e) {
						if (xhr.readyState == 4) {
							if (xhr.status == 200) {
								self.events.onSuccess(file, xhr.responseText);
								self.funDeleteFile(file);
								if (!self.fileFilter.length) {
									//全部完毕
									self.events.onComplete();	
								}
							} else {
								self.events.onFailure(file, xhr.responseText);		
							}
						}
					};
					var fd = new FormData();
					fd.append(self.headerName, file);
					// 开始上传
					xhr.open("POST", self.url, true);
					xhr.setRequestHeader(self.headerName, encodeURIComponent(file.name));
					xhr.send(fd);
				}
			})(file);
		}
	},
	
	init: function() {
		var self = this;
		//文件选择控件选择
		if (this.fileInput) {
			this.fileInput.addEventListener("change", function(e) { self.funGetFiles(e); }, false);	
		}
		//上传按钮提交
		if (this.upButton) {
			this.upButton.addEventListener("click", function(e) { self.funUploadFile(e); }, false);	
		}
	},

	//当前等待上传队列里文件数量
	getQueueCount: function() {
		return this.fileFilter.length;
	}
};