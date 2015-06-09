
// 图集编辑器
$.fn.imagesfield = function(name,value,params){
	return this.each(function(){
		new imagesfield($(this), name, value, params);
	});
}

function imagesfield(gallery, name, value, params){
	var self = this;
	var list = gallery.find('ul.images-list');
	var select = gallery.find('a.select');
	var upload = gallery.find('a.upload');
	var progressbar = gallery.find('div.progressbar');
	var n = 0;

	// API methods
	$.extend(self, {
		init : function(){
			// 初始化数据
			$.each(value,function(i,data){
				self.add(data.image, data.description);
			});

			// 拖动
			list.sortable({
				items: ".images-list-item",
				placeholder:"ui-sortable-placeholder",
				update:function(){
					self.updatenumber();
				}				
			});

		},
		// update selectall state
		add : function(image,description){

			var c = '<li class="images-list-item">';
				c += '<div class="image-preview"><img src="'+ image +'"/><input type="hidden" name="'+ name +'['+ n +'][image]" value="'+ image +'"></div>';
				c += '<div class="image-description"><textarea class="textarea" name="'+ name +'['+ n +'][description]" placeholder="图片描述……">' + (description || '') + "</textarea></div>";
				c += '<div class="image-manage clearfix">';
				c += '	<b class="number">#'+ (n+1) +'</b>';
				c += '	<a class="upload" href="javascript:;" title="重新上传"><i class="icon icon-upload"></i></a>';
				c += '	<a class="delete" href="javascript:;" title="删除"><i class="icon icon-remove"></i></a>';
				c += '</div>';
				c += '</li>';

			var item = $(c).appendTo(list);

				item.find('a.upload').upload({
						url		: upload.attr('href'),
						params	: params,
						multi	: false,
						fileext	:'jpg,jpeg,png,gif,bmp',
						filedescription: '选择图片',
						uploaded : function(up,file,msg){
							if ( msg.state ){						
								item.find('.image-preview').find('img').attr('src',msg.file.url);
								item.find('.image-preview').find('input').val(msg.file.url);
								return true;
							}
							$.msg(msg);
						}
				});

			list.find('.image-list-empty').hide();

			n++;
		},

		// 重排行号
		updatenumber: function(){
		    
		    list.find(".images-list-item").each(function(d, a) {
		        $(a).find("b.number").text('#' + (d + 1));
		    });

		    if ( list.find('.images-list-item').length > 0 )
		    {
		    	list.find('.image-list-empty').hide();
		    }else{
		    	list.find('.image-list-empty').show();
		    }	
		}		
	});

	// 初始化
	self.init();

	//上传
	upload.upload({
		url		: upload.attr('href'),
		params	: params,
		fileext	:'jpg,jpeg,png,gif,bmp',
		filedescription: '选择图片',
		uploaded : function(up,file,msg){
			if ( msg.state ){
				self.add(msg.file.url, msg.file.description);
				return true;
			}			
			$.msg(msg);
		}
	});

	// 图库
	select.on('click',function(e){e.preventDefault();
		var dialog = $.dialog({
			url: $(this).attr('href'),
			title: $(this).attr('title'),
			width: $(this).attr('data-width') || 1000,
			height: $(this).attr('data-height') || 460,
			ok: function(files){
				zotop.debug(files);
				$.each(files,function(i,data){
					self.add(data.url, data.description);
				})
			},
			cancel:function(){}
		},true);
	});

	// 删除
	list.on('click','a.delete',function(){
		  $(this).tooltip('destroy');
		  $(this).parent().parent().remove();
		  self.updatenumber();		  
	})


}

