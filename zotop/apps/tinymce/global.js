//编辑器函数
$.fn.editor = function(options){
	var settings = {};
	settings.width         = $(this).outerWidth();
	settings.height        = $(this).outerHeight();
	settings.plugins       = ['advlist autolink lists link image charmap preview anchor searchreplace code fullscreen media table paste hr textcolor colorpicker textpattern imagetools onekeyclear autosave localautosave wordcount tabfocus powerpaste'];
	settings.menubar       = false;
	//settings.elementpath = false;
	settings.language      = 'zh_CN';
	settings.skin          = 'zotop';
	//settings.autoresize    = true;

	settings.toolbar_items_size            = 'small';
	
	settings.powerpaste_allow_local_images = true;

	// settings.paste_data_images             = true;
	// settings.automatic_uploads             = true;	
	// settings.images_upload_url             = 'postAcceptor1.php';
	// settings.images_upload_base_path       = '/some/basepath';
	// settings.images_upload_credentials     =  true;


	switch(options.toolbar){
		case 'basic':
			options.toolbar =  'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image';
			break;
		case 'standard':
			options.toolbar =  'undo redo removeformat onekeyclear | forecolor backcolor bold italic underline strikethrough formatselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist link image';
			break;
		case 'full':
			options.toolbar =  "undo redo copy paste pastetext searchreplace removeformat onekeyclear | forecolor backcolor | bold italic underline strikethrough | subscript superscript | formatselect fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | link unlink | image media | insertdatetime table anchor charmap emoticons blockquote hr | visualchars nonbreaking template pagebreak | localautosave preview code fullscreen";
			break;
	}

	options = $.extend(settings,options,{});

	$(this).tinymce(options);
}

$(function(){
	$('.editor-insert').click(function(event){
		event.preventDefault();

		var field  = $(this).attr('data-field'); // 插入到字段
		var type   = $(this).attr('data-type'); // 返回数据类型
		var title  = $(this).attr('title') || $(this).text() ;
		var value  = '';
		var handle = $(this).attr('href');
		var editor = $('[name='+field+']').tinymce();

		var dialog = $.dialog({
			id: 'insert-html',
			url: handle,
			title: $(this).attr('title') || $(this).text(),
			width: $(this).attr('data-width') || 1000,
			height: $(this).attr('data-height') || 460,
			statusbar: '<i class="icon icon-loading"></i>',
			ok : function(data){
				var html='';

				if ( type == 'html' ){
					editor.insertContent(data);
					return true;
				}

				for(var i=0; i<data.length; i++){
					var name        = data[i].name;
					var url         = data[i].url;
					var description = data[i].description;
					var ext         = data[i].ext || url.replace(/.+\./,"");

					if ( ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'gif' || ext == 'bmp'){
						html += '<img src="'+url+'" alt="'+(description||name)+'" class="attachment '+ext+'"/>';
					} else if ( ext =='swf' ){
						html += '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"><param name="quality" value="high" /><param name="movie" value="'+url+'" /><embed pluginspage="http://www.macromedia.com/go/getflashplayer" quality="high" src="'+url+'" type="application/x-shockwave-flash" width="500" height="400"></embed></object>';
					}else{
						html += '<a href="'+url+'" title="'+(description||name)+'" target="_blank" class="attachment '+ext+'">'+name+'</a>';
					}
				}
				
				editor.insertContent(html);
				return true;
			},
			cancel:function(){}
		},true);
	});
});