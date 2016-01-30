//编辑器函数
$.fn.editor = function(options){
	var settings = {};
	settings.width         = $(this).outerWidth();
	settings.height        = $(this).outerHeight();
	settings.menubar       = false;
	//settings.elementpath = false;
	settings.language      = 'zh_CN';
	settings.skin          = 'zotop';


	settings.force_br_newlines = false;
	settings.force_p_newlines  = true;
	settings.forced_root_block = 'p';
	settings.invalid_elements  = "script,applet,iframe";

	//settings.autoresize                  = true;
	settings.toolbar_items_size            = 'small';
	settings.imagetools_toolbar            = 'alignleft aligncenter alignright editimage imageoptions';
	settings.powerpaste_allow_local_images = true;
	//settings.paste_data_images           = false;
	
	settings.convert_urls                  = false;
	settings.image_advtab                  = true;	
	settings.images_upload_credentials     = true;
	
	// settings.file_browser_callback	= function(field_name, url, type, win) {
	// 	win.document.getElementById(field_name).value = 'my browser value'+url+'///'+type;
	// };

	// switch(options.toolbar){
	// 	case 'basic':
	// 		options.plugins = ['advlist autolink lists link image media table paste textcolor colorpicker textpattern onekeyclear localautosave tabfocus'];		
	// 		options.toolbar = 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image';
	// 		break;
	// 	case 'standard':
	// 		options.plugins = ['advlist autolink lists link image charmap preview anchor searchreplace code fullscreen media table paste hr textcolor colorpicker textpattern imagetools onekeyclear localautosave wordcount tabfocus codesample powerpaste'];		
	// 		options.toolbar = 'undo redo removeformat onekeyclear | forecolor backcolor bold italic underline strikethrough formatselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist link image codesample';
	// 		break;
	// 	case 'full':
	// 		options.plugins = ['advlist autolink lists link image charmap preview anchor searchreplace code fullscreen media table paste hr textcolor colorpicker textpattern imagetools onekeyclear localautosave tabfocus codesample powerpaste zotop_pagebreak'];
	// 		options.toolbar = 'undo redo copy paste pastetext searchreplace removeformat onekeyclear | forecolor backcolor | bold italic underline strikethrough | subscript superscript | formatselect fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | link unlink | image media | insertdatetime table anchor charmap emoticons blockquote hr | visualchars nonbreaking codesample template pagebreak | localautosave preview code fullscreen';
	// 		break;
	// }

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
			statusbar: '<i class="fa fa-spinner"></i>',
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

					html += '<p class="attachment '+ext+'">';
					if ( ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'gif' || ext == 'bmp'){
						html += '<img src="'+url+'" alt="'+(description||name)+'" />';
					} else if ( ext =='swf' ){
						html += '<embed quality="high" src="'+url+'" type="application/x-shockwave-flash" allowScriptAccess="always" allowFullScreen="true" mode="transparent" width="500" height="400"></embed>';
					}else{
						html += '<a href="'+url+'" title="'+(description||name)+'" target="_blank">'+name+'</a>';
					}
					html += '</p>';
				}

				editor.insertContent(html);
				return true;
			},
			cancel:function(){}
		},true);
	});

	// //编辑器头部fixed
	// $('.main-body').on('scroll',function(e){
	// 	var toolbar  = $('.mce-toolbar-grp');
	// 	var editarea = $('.mce-edit-area');
	// 	var top      = $('.global-header').outerHeight() + $('.main-header').outerHeight();
	// 	var width    = toolbar.width();
	// 	var height   = toolbar.outerHeight();
	// 	var offset   = toolbar.offset(); 

 //        if ( this.scrollTop > (offset.top + height + 10 ) ) {	
 //        	toolbar.css({
	//             position: 'fixed',
	//             top: top+'px',
	//             width: width + 'px'
	//         });
	//         editarea.css('padding-top',(top+height)+'px');
 //        }else{
 //        	editarea.css('padding-top','');

	// 		toolbar.css({
	// 			position: 'static',
	// 			top: '',
	// 			width: '',
	// 		});			
 //        }		
	// });
});