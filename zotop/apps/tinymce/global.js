//编辑器函数
$.fn.editor = function(options){
	var settings = {};
	settings.width         = $(this).outerWidth();
	settings.height        = $(this).outerHeight();
	settings.plugins       = ['advlist autolink lists link image charmap preview anchor searchreplace code fullscreen media table paste hr textcolor colorpicker textpattern imagetools'];
	settings.menubar       = false;
	//settings.elementpath = false;
	settings.language      = 'zh_CN';
	settings.skin          = 'zotop';
	settings.toolbar_items_size = 'small';

	switch(options.toolbar){
		case 'basic':
			options.toolbar =  'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image';
			break;
		case 'standard':
			options.toolbar =  'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image';
			break;
		case 'full':
			options.toolbar =  "undo redo copy paste searchreplace removeformat | forecolor backcolor bold italic underline strikethrough subscript superscript formatselect fontselect fontsizeselectcut | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | blockquote link unlink anchor image media  insertdatetime table charmap emoticons hr | visualchars visualblocks nonbreaking template pagebreak restoredraft preview code fullscreen";
			break;
	}

	options = $.extend(settings,options,{});

	$(this).tinymce(options);
}

// $(function(){
// 	$('.editor-insert').click(function(event){
// 		event.preventDefault();

// 		var field  = $(this).attr('data-field'); // 插入到字段
// 		var type   = $(this).attr('data-type'); // 返回数据类型
// 		var title  = $(this).attr('title') || $(this).text() ;
// 		var value  = '';
// 		var handle = $(this).attr('href');
// 		var editor = UE.getEditor(field);

// 		var dialog = $.dialog({
// 			id: 'insert-html',
// 			url: handle,
// 			title: $(this).attr('title') || $(this).text(),
// 			width: $(this).attr('data-width') || 1000,
// 			height: $(this).attr('data-height') || 460,
// 			statusbar: '<i class="icon icon-loading"></i>',
// 			ok : function(data){
// 				var html='';

// 				if ( type == 'html' ){
// 					editor.execCommand("inserthtml",data);
// 					return true;
// 				}

// 				for(var i=0; i<data.length; i++){
// 					var name        = data[i].name;
// 					var url         = data[i].url;
// 					var description = data[i].description;
// 					var ext         = data[i].ext || url.replace(/.+\./,"");

// 					html += '<span class="attachment '+ext+'">';
// 					if ( ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'gif' || ext == 'bmp'){
// 						html += '<img src="'+url+'" alt="'+(description||name)+'" />';
// 					} else if ( ext =='swf' ){
// 						html += '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"><param name="quality" value="high" /><param name="movie" value="'+url+'" /><embed pluginspage="http://www.macromedia.com/go/getflashplayer" quality="high" src="'+url+'" type="application/x-shockwave-flash" width="500" height="400"></embed></object>';
// 					}else{
// 						html += '<a href="'+url+'" title="'+(description||name)+'" target="_blank">'+name+'</a>';
// 					}
// 					html += '</span>';
// 				}
				
// 				editor.execCommand("inserthtml",html);
// 				return true;
// 			},
// 			cancel:function(){}
// 		},true);
// 	});
// });