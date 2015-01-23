
// 图集编辑器
$.fn.galleryeditor = function(name,value,params){

	return this.each(function(){
		new galleryeditor($(this), name, value, params);
	});
}

function galleryeditor(gallery, name, value, params){
	var self = this;
	var list = gallery.find('table.gallery-list tbody');
	var area = gallery.find('div.gallery-data');
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
				items: "tr",
				axis: "y",
				placeholder:"ui-sortable-placeholder",
				helper: function(e,tr){
					tr.children().each(function(){
						$(this).width($(this).width());
					});
					return tr;
				}
			});

		},
		// update selectall state
		add : function(image,description){

			var c = "<tr>";
				c += '<td class="drag"></td>';
				c += '<td class="w50"><div class="image"><img src="'+ image +'"/></div><input type="hidden" name="'+ name +'['+ n +'][image]" value="'+ image +'"></td>';
				c += '<td><textarea class="textarea" name="'+ name +'['+ n +'][description]" placeholder="图片描述……">' + (description || '') + "</textarea></td>";
				c += '<td class="w50 center">';
				c += '<a class="delete"><i class="icon icon-remove"></i></a>';
				c += "</td>";
				c += "</tr>";

			list.append(c);

			n++;
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
				area.scrollTop(area[0].scrollHeight);
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
				$.each(files,function(i,data){
					self.add(data.url, data.description);
				})
			},
			cancel:function(){}
		},true);
	});


	// 删除
	list.on('click','a.delete',function(){
		  $(this).parent().parent().remove();
	})
}

