/**
 * jquery.upload.js
 *
 * Copyright 2010, zotop team
 * Released under GPL License.
 * License: http://www.zotop.com/license
 */

/**
jquery upload api

@example

	<div id="uploader">
		<p>Your browser doesn't have HTML5,Flash support</p>
	</div>

	<script>
		$('#uploader-button').upload({
			url : '../upload.php',
			multi : true,
			autostart : true
			params : {username:'admin'},
			fileext : 'jpg,gif,png',
			filedescription : 'image file',
			maxsize: '2mb',
			resize : {width : 200, height : 200, quality : 90, crop: false},
			rename: true,
			dragdrop : '.dragdrop-area',
			uploaded : function(up, file){
				// 单个文件上传完成
			},
			complete : function(up,files){
				// 全部上传完成
			}
		});
	</script>

@example
	// Retrieving a reference to plupload.Uploader object
	var uploader = $('#uploade-button').upload();

	uploader.bind('FilesAdded', function() {

		// Autostart
		setTimeout(uploader.start, 1); // "detach" from the main thread
	});

@class upload
@constructor
@param {Object} 详细设置选项说明
	@param {String} [settings.runtimes="html5,flash,silverlight,html4"] 上传运行时，依次加载
	@param {String} [settings.url] 服务器端的接收上传数据的url
	@param {String} [settings.fileext] 允许上传的文件格式，默认为图片："jpg,jpeg,gif,png" 错误：`plupload.FILE_EXTENSION_ERROR`
	@param {String} [settings.filedescription] 允许上传的文件说明，默认为："Image file"
	@param {Object} [settings.params] 文件上传时传递的参数
	@param {Number|String} [settings.maxsize=20MB] 选择文件时的文件大小限制, 默认为byte ,支持 b, kb, mb, gb, tb 等单位. 如： "10mb" 或者 "100kb"`. 错误： `plupload.FILE_SIZE_ERROR`.
	@param {Number|String} [settings.maxcount=0] 选择文件个数限制，默认为不限制
	@param {Boolean} [settings.multi=true] 是否允许一次选择多个文件
	@param {Object} [settings.resize] 客户端图片缩放. 仅支持 `image/jpeg` 和 `image/png` 的图片类型文件， 如： {width : 200, height : 200, quality : 90, crop: true}`
		@param {Number} [settings.resize.width] 文件最大宽度，超出自动缩放
		@param {Number} [settings.resize.height] 文件最大
		@param {Number} [settings.resize.quality=90] jpg图片压缩质量1-100
		@param {Boolean} [settings.resize.crop=false] 是否采用裁剪方式缩放图片，默认是缩放
	@param {Boolean|String} [settings.dragdrop=true] 支持拖动上传,默认元素为：[buttonid]_dragdrop
	@param {Boolean} [settings.autostart=true] 选择文件后自动开始上传
	@param {Number|String} [settings.chunk_size] 上传大文件时候切分文件上传可以突破上传限制
*/

(function($) {
	// 全部实例化的上传
	plupload.uploaders = {};

	// 文件数错误代码
	plupload.FILE_COUNT_ERROR = -9001;

	// 获取plupload的根路径
	plupload.basepath = function(){
		var els = document.getElementsByTagName('script'), src;
		for (var i = 0, len = els.length; i < len; i++) {
			src = els[i].src || '';
			if (/upload[\w\-\.]*\.js/.test(src)) {
				return src.substring(0, src.lastIndexOf('/') + 1);
			}
		}
		return '';
	}

	//语言翻译
	plupload.t = function(str, args) {
		str = plupload.translate(str) || str;
		str = plupload.format(str, args);
		return str;
	}

	//格式化 "{0} {1} {0}"
	plupload.format = function(str,args){
		if ( typeof args == 'object' ){
			return str.replace(/\{(\d+)\}/g, function(m,i){
				return args[i];
			});
		}
		return str;
	};

	//获取文件格式
	plupload.ext = function(o){
		return o.replace(/.+\./,"").toLowerCase();
	}

	// 数字转化
	plupload.parseInt = function(i, d){
		return isNaN(parseInt(i)) ? ( d || 0 ) : parseInt(i);
	}

	//转换resize
	plupload.resize = function(r,w,h,q){
		r = plupload.parseInt(r);
		w = plupload.parseInt(w);
		h = plupload.parseInt(h);
		q = plupload.parseInt(q, 90);

		if ( r && w && h ){
			c = ( r == 2 ) ? true : false;
			return {width:w, height:h, quality:q, crop : c}
		}

		return false;
	}

	// 处理返回数据
	plupload.parseJSON = function(data) {
		var obj;
		try {
			obj = $.parseJSON(data);
		} catch (e) {
			obj = {code:1,content:data};
		}
		return obj;
	}

    //返回uploader对象
    $.fn.upload = function(options) {
        if (options){

            $(this)._upload(options);
        }
        return $(this)._upload();
    }

	//该插件不能直接返回uploader对象
	$.fn._upload = function(options){

		if (!options) return plupload.uploaders[$(this[0]).attr('id')];

		var defaults = {
			runtimes : 'html5,flash,html4',
            params : {}, // 传递的参数
			multi : true, //允许一次选择多个文件
			duplicate : false,  //是否允许重复选择上传文件
			fileext	: '',
			filedescription	: '',
            maxsize : '20mb',
            chunk_size : '2mb',
            unique_names : true, // 唯一文件名
			dragdrop : true,
            autostart : true,
			maxcount : 100,
			error : function(error,detail){
				alert(error + detail);
			}
		}

		options = $.extend({}, defaults, options);

		// 载入flash 和 silverlight 上传文件
		options.basepath = options.basepath || plupload.basepath();
		options.flash_swf_url = options.flash_swf_url || options.basepath + 'Moxie.swf';
		options.silverlight_xap_url = options.silverlight_xap_url || options.basepath + 'Moxie.xap';

		// 自定义属性的转换
		options.multipart_params = options.params;
		options.multi_selection = options.multi;
		options.filters = {
			mime_types : [{ title : options.filedescription, extensions : options.fileext}],
			max_file_size : options.maxsize,
			prevent_duplicates : !options.duplicate
		};

		// 从params转换resize
		options.resize = options.resize || plupload.resize(options.params.image_resize, options.params.image_width, options.params.image_height, options.params.image_quality);


		// 生成实例
		return $(this).each(function(){

			var $this = $(this);
			var id = $this.attr('id');

			// 给容器赋予id
			if(!id){
				id = plupload.guid();
				$this.attr('id', id);
			}

			// 设置选择文件id为当前对象ID
			options.browse_button = id;

			// 设置默认拖拽上传区域，如果不存在，则将按钮本身设为拖拽区域
			options.drop_element = id + "-dragdrop";

			if ( options.dragdrop && $('#'+options.drop_element).length == 0 )
			{
				options.drop_element = id;
			}

			// 创建上传对象
			var uploader = new plupload.Uploader(options);
				uploader.self = $this;
				uploader.id = id;
				uploader.content = $this.html();

				//设置附加属性
				uploader.params = function(key,val){
					if( val === undefined ){
						return uploader.settings.multipart_params[key];
					}else{
						return uploader.settings.multipart_params[key] = typeof val == 'function' ? val() : val;
					}
				}

			// 存储对象
			plupload.uploaders[id] = uploader;

			// 删除实例
			function destroy() {
				delete plupload.uploaders[id];
				uploader.destroy();
				$this.html(uploader.content);
				uploader = $this = null;
			}

			//初始化
			uploader.bind('Init', function(up, res) {

				//设置提示信息
				$(up.settings.browse_button).attr('title', function(){
					 var allowedexts='';
					 $.each( up.settings.filters , function(i,m){
						if( i == 'mime_types' ){
							allowedexts = allowedexts + ', ' +  m[0].extensions;
						}
					 });
					 return '' +
						plupload.t('Max file size:') + up.settings.maxsize + '<br/>' +
						plupload.t('Allowed exts:') + allowedexts.substring(2);
				});

				//zotop.debug(up);

			});

			uploader.bind("PostInit", function(up) {

				// 拖放区域设置
				if ( up.features.dragdrop && up.settings.drop_element ){
					$(up.settings.drop_element).on('dragover',function(){
						$(this).addClass('dragover');
					}).on('drop',function(){
						$(this).addClass('drop');
						$(this).removeClass('dragover');
					}).on('dragleave',function(){
						$(this).removeClass('drop dragover');
					}).addClass('dropbox');
				}

				typeof options.init == 'function' && options.init(up);

				uploader.content = $this.html();
			});


			// 检查文件个数
			if ( uploader.settings.maxcount ) {
				uploader.settings.multiple_queues = false; // one go only

				uploader.bind('FilesAdded', function(up, selectedFiles) {
					var selectedCount = selectedFiles.length;
					var extraCount = up.files.length + selectedCount - up.settings.max_file_count;

					if (extraCount > 0) {
						selectedFiles.splice(selectedCount - extraCount, extraCount);

						up.trigger('Error', {
							code : plupload.FILE_COUNT_ERROR,
							message :plupload.t('File count error.')
						});
					}
				});
			}

			uploader.init();


			// 添加事件
			uploader.bind('FilesAdded', function(up, files) {

				typeof options.add == 'function' && options.add(up, files);

				//自动上传
				if (up.settings.autostart) {
					setTimeout(function(){uploader.start();}, 10);
				}

				up.refresh(); // Reposition Flash/Silverlight
			});

			uploader.bind("Error", function(up, err) {

				var file = err.file, message = err.message, details = "";

				switch (err.code) {
					case plupload.FILE_EXTENSION_ERROR:
						details = plupload.t("Invalid file extension: {0}", [file.name]);
						break;

					case plupload.FILE_SIZE_ERROR:
						details = plupload.t("File {0} is too large,max file size: {1}" , [file.name, up.settings.maxsize]);
						break;

					case plupload.FILE_DUPLICATE_ERROR:
						details = plupload.t("{0} already present in the queue" , [file.name]);
						break;

					case plupload.FILE_COUNT_ERROR:
						details = plupload.t("Upload element accepts only {0} file(s) at a time. Extra files were stripped", [up.settings.maxcount]);
						break;

					case plupload.IMAGE_FORMAT_ERROR :
						details = plupload.t("Image format either wrong or not supported");
						break;

					case plupload.IMAGE_MEMORY_ERROR :
						details = plupload.t("Runtime ran out of available memory");
						break;

					case plupload.HTTP_ERROR:
						details = plupload.t("Upload URL might be wrong or doesn't exist");
						break;
				}

				if (err.code === plupload.INIT_ERROR) {
					setTimeout(function(){self.destroy();}, 1);
				}else {
					options.error(message, details);
				}

			});


			//状态
			uploader.bind('StateChanged', function() {
				if ( uploader.state === plupload.STARTED ) {

					// 禁用上传按钮
					uploader.disableBrowse(true);

					// 如果找到总进度条，则显示
					$('#'+id+'-progress').show();

					typeof options.started == 'function' && options.started(uploader);
				}else{

					//启用上传按钮
					uploader.disableBrowse(false);

					typeof options.stoped == 'function' && options.stoped(uploader);
				}
			});

			//队列变化
			uploader.bind('QueueChanged', function(){

				typeof options.changed == 'function' && options.changed(up, file);
			});

			// 初始化当前文件上传
			uploader.bind("BeforeUpload", function(up, file){

				//开启unique_names时，将原文件名通过params传入
				up.settings.multipart_params['filename'] = file.name;

				typeof options.beforeupload == 'function' && options.beforeupload(up, file);
			});

			// 设置上传进度
			uploader.bind("UploadProgress", function(up, file) {

				// 自定义进度
				typeof options.progress == 'function' && options.progress(up, file);

				// 更新总进度
				$('#'+id+'-progress').find('.progress').css('width',up.total.percent + '%');
				$('#'+id+'-progress').find('.percent').html(up.total.percent + '%');				
			});

			//文件上传文成
			uploader.bind('FileUploaded', function(up, file, info){

				var data = plupload.parseJSON(info.response);

			    if (typeof console != 'undefined'){
			        console.log(data);
			    }
			    
				//每个上传成功都会调用该函数
				typeof options.uploaded == 'function' && options.uploaded(up,file,data);

			});

			// Set file specific progress
			uploader.bind("UploadComplete", function(up, files) {

				$('#'+id+'-progress').hide();

				typeof options.complete == 'function' && options.complete(up, files);
			});
		});
	}

})(jQuery);