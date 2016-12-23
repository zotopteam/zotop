//编辑器函数
$.fn.editor = function(options){
	var settings = {};
    settings.menubar                       = false;
    //settings.elementpath                 = false;
    settings.language                      = 'zh_CN';
    settings.skin                          = 'zotop';	
    
    settings.force_br_newlines             = false;
    settings.force_p_newlines              = true;
    settings.forced_root_block             = 'p';
    settings.invalid_elements              = "script,applet";	
    // repair bug
    //settings.block_formats                 = 'Paragraph=p;Heading 1=h1;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Pre=pre;Div=div;Blockquote=blockquote;';
    
    settings.toolbar_items_size            = 'small';
    settings.imagetools_toolbar            = 'alignleft aligncenter alignright imageoptions';
    settings.powerpaste_allow_local_images = true;
    settings.paste_data_images             = true;
    
    settings.convert_urls                  = false;
    settings.image_advtab                  = true;	
    settings.images_upload_credentials     = true;

	
	// settings.file_browser_callback	= function(field_name, url, type, win) {
	// 	win.document.getElementById(field_name).value = 'my browser value'+url+'///'+type;
	// };

	options = $.extend(settings,options,{}); 

	$(this).tinymce(options);
}



$(function(){

	// $('.editor-insert').click(function(event){
	// 	event.preventDefault();

	// 	var field  = $(this).attr('data-field'); // 插入到字段
	// 	var type   = $(this).attr('data-type'); // 返回数据类型
	// 	var title  = $(this).attr('title') || $(this).text() ;
	// 	var value  = '';
	// 	var handle = $(this).attr('href');
	// 	var editor = $('[name='+field+']').tinymce();

	// 	var dialog = $.dialog({
	// 		id: 'insert-html',
	// 		url: handle,
	// 		title: $(this).attr('title') || $(this).text(),
	// 		width: $(this).attr('data-width') || 1000,
	// 		height: $(this).attr('data-height') || 460,
	// 		statusbar: '<i class="fa fa-spinner"></i>',
	// 		ok : function(data){
	// 			var html='';

	// 			if ( type == 'html' ){
	// 				editor.insertContent(data);
	// 				return true;
	// 			}

	// 			for(var i=0; i<data.length; i++){
	// 				var name        = data[i].name;
	// 				var url         = data[i].url;
	// 				var description = data[i].description;
	// 				var ext         = data[i].ext || url.replace(/.+\./,"");

	// 				html += '<p class="insert-data insert-data-'+ext+'">';
	// 				if ( ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'gif' || ext == 'bmp'){
	// 					html += '<img src="'+url+'" alt="'+(description||name)+'" />';
	// 				} else if ( ext =='swf' ){
	// 					html += '<embed quality="high" src="'+url+'" type="application/x-shockwave-flash" allowScriptAccess="always" allowFullScreen="true" mode="transparent" width="500" height="400"></embed>';
	// 				}else{
	// 					html += '<a href="'+url+'" title="'+(description||name)+'" target="_blank">'+name+'</a>';
	// 				}
	// 				html += '</p>';
	// 			}

	// 			editor.insertContent(html);
	// 			return true;
	// 		},
	// 		cancel:function(){}
	// 	},true);
	// });

	//编辑器头部fixed
    // var main = $('.main-body');
    // main.find('.field-editor').each(function(){
    //     var field         = $(this);
    //     var offset        = $(this).offset();  
    //     var fixed     = $('.global-header').outerHeight() + $('.main-header').outerHeight(); 

    //     console.log('offsettop'+offset.top);

    //     main.on('scroll',function(e){
    //         var toolbar       = field.find('.mce-toolbar-grp');
    //         var editarea      = field.find('.mce-edit-area');
    //         var offset_top    = offset.top;
    //         var offset_bottom = offset.top + field.outerHeight() - toolbar.outerHeight() - 100;
            
    //         if ( (main.scrollTop() > offset_top) && (main.scrollTop() < offset_bottom) ) {    
    //             toolbar.css({
    //                 position: 'fixed',
    //                 zindex:10000,
    //                 top: fixed+'px',
    //                 width: toolbar.width() + 'px'
    //             });
    //             editarea.css('padding-top',toolbar.outerHeight()+'px');
    //         }else{
    //             editarea.css('padding-top','');

    //             toolbar.css({
    //                 position: 'static',
    //                 top: '',
    //                 width: '',
    //             });         
    //         }

    //     });            
    // });
});