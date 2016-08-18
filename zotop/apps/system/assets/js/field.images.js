
// 图集编辑器
$.fn.imagesfield = function(name,value,params){
	return this.each(function(){
		new imagesfield($(this), name, value, params);
	});
}

function imagesfield($this, name, value, params){
	var self        = this;
	var list        = $this.find('.field-images-list');
	var select      = $this.find('a.select');
	var upload      = $this.find('a.upload');
	var description = $this.find('a.description');
	var progressbar = $this.find('div.progressbar');
	var n = 0;

	// API methods
	$.extend(self, {
		init : function(){
			value = value || [];

			// 初始化数据
			$.each(value, function(i,data){
				self.add(data.image, data.description);
			});

			// 拖动
			list.sortable({
				items: ".field-images-list-item",
				placeholder:"field-images-list-item sortable-placeholder",
				update:function(){
					self.updatenumber();
				}				
			});

		},
		// update selectall state
		add : function(image, description){

			var c = '<div class="field-images-list-item">';
				c += '	<div class="thumbnail">';
				c += '		<div class="image-preview"><img src="'+ image +'"/><input type="hidden" name="'+ name +'['+ n +'][image]" value="'+ image +'"></div>';
				c += '		<div class="image-description"><textarea rows="2" class="form-control" name="'+ name +'['+ n +'][description]" placeholder="图片描述……">' + (description || '') + "</textarea></div>";
				c += '		<div class="image-manage clearfix">';
				c += '			<b class="number">#'+ (n+1) +'</b>';
				c += '			<a class="upload pull-right" href="javascript:;" title="重新上传"><i class="fa fa-upload"></i></a>';
				c += '			<a class="delete pull-right" href="javascript:;" title="删除"><i class="fa fa-trash"></i></a>';
				c += '		</div>';
				c += '	</div>';
				c += '</div>';

			var item = $(c).appendTo(list);

				item.find('a.upload').upload({
						url: upload.attr('href'),
						params: params,
						multiple: false,
						extensions: 'jpg,jpeg,png,gif,bmp',
						title: '选择图片',
						uploaded: function(up,file,msg){
							if ( msg.state ){						
								item.find('.image-preview').find('img').attr('src',msg.file.url);
								item.find('.image-preview').find('input').val(msg.file.url);
								return true;
							}
							$.msg(msg);
						}
				});

			list.find('.field-images-list-empty').hide();

			n++;
		},

		// 重排行号
		updatenumber: function(){
		    
		    list.find(".field-images-list-item").each(function(d, a) {
		        $(a).find("b.number").text('#' + (d + 1));
		    });

		    if ( list.find('.field-images-list-item').length > 0 ){
		    	list.find('.field-images-list-empty').hide();
		    }else{
		    	list.find('.field-images-list-empty').show();
		    	n = 0;
		    }	
		}		
	});

	// 初始化
	self.init();

	//上传
	upload.upload({
		url: upload.attr('href'),
		params: params,
		extensions:'jpg,jpeg,png,gif,bmp',
		title: '选择图片',
		uploaded: function(up,file,msg){
			if ( msg.state ){
				self.add(msg.file.url, msg.file.description);
				return true;
			}			
			$.msg(msg);
		}
	});

	// 图库
	select.on('click',function(e){
		e.preventDefault();		
		var dialog = $.dialog({
			url: $(this).attr('href'),
			title: $(this).attr('title') || $(this).text(),
			width: $(this).attr('data-width') || 1000,
			height: $(this).attr('data-height') || 460,
			ok: function(files){
				$.each(files,function(i,data){
					self.add(data.url, data.description);
				})
			},
			cancel:function(){}
		},true);
	});

	description.on('click',function(e){
		e.preventDefault();

		var prompt = '设置全部图片的描述信息';
		var title  = $(this).text();
		var value  = description.data('defaultvalue');

		$.prompt(prompt,function(val){

			description.data('defaultvalue',val);

			list.find('.image-description').find('textarea').val(val);

		},value,'textarea').title(title);
	})

	// 删除
	list.on('click','a.delete',function(){
		  $(this).tooltip('destroy');
		  $(this).parent().parent().parent().remove();
		  self.updatenumber();	  
	})


}

