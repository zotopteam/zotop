
//编辑器函数
$.fn.editor = function(options){
	var name = $(this).attr('name');
	var width = $(this).outerWidth();
	var height = $(this).outerHeight();
	var settings = {
		UEDITOR_HOME_URL : options.root,
		serverUrl : options.server,
		initialFrameWidth : width,
		initialFrameHeight : height,
		//shortcutMenu:['paragraph',"fontfamily", "fontsize", "bold", "italic", "underline", "forecolor", "backcolor", "insertorderedlist", "insertunorderedlist"],
		initialStyle : options.css || 'html{hight:100%;}body{font-size:14px;line-height:25px;padding:5px;}p{font-size:14px;line-height:25px;margin:8px 0px;}img,embed,object{max-width:100%;}',
		wordCount : false,
		enableAutoSave: false,
		elementPathEnabled : false,
		scaleEnabled:false, //拉高
		autoHeightEnabled : false,
		autoFloatEnabled : false, // 工具条浮动
		//topOffset : 85,
		initialContent : '',
		charset:"utf-8",
		autotypeset : {
			mergeEmptyline : true,         //合并空行
			removeClass : true,           //去掉冗余的class
			removeEmptyline : true,      //去掉空行
			textAlign : "left" ,           //段落的排版方式，可以是 left,right,center,justify 去掉这个属性表示不执行排版
			imageBlockLine : 'center',      //图片的浮动方式，独占一行剧中,左右浮动，默认: center,left,right,none 去掉这个属性表示不执行排版
			pasteFilter : true,            //根据规则过滤粘贴进来的内容
			clearFontSize : true,          //去掉所有的内嵌字号，使用编辑器默认的字号
			clearFontFamily : true,        //去掉所有的内嵌字体，使用编辑器默认的字体
			removeEmptyNode : true ,       // 去掉空节点
			removeTagNames : {'object':1},
			indent : true,                 // 行首缩进
			indentValue : '2em'             //行首缩进的大小
		}
	};

	switch(options.theme){
		case 'basic':
			settings.toolbars =  [['bold', 'italic', 'underline', 'strikethrough', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist','|','justifyleft', 'justifycenter', 'justifyright','|','link', 'unlink','emotion']];
			break;
		case 'standard':
			settings.toolbars =  [['undo', 'redo', 'autotypeset','removeformat','bold', 'italic', 'underline', 'strikethrough', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'fontsize','justifyleft', 'justifycenter', 'justifyright','link', 'unlink','insertimage', 'emotion','inserttable','fullscreen', 'source']];
			break;
		default:
			settings.toolbars = [
				['undo', 'redo','paragraph', 'fontfamily', 'fontsize','insertcode','bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'blockquote', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist'],
				['pasteplain','autotypeset','removeformat', 'formatmatch','indent','lineheight', 'justifyleft', 'justifycenter', 'justifyright','link', 'unlink', 'anchor','date', 'time','insertimage', 'emotion','insertvideo','music','map','highlightcode','horizontal','spechars','insertframe','inserttable','searchreplace','preview','source','fullscreen']
			];
			break;
	}

	UE.getEditor(name, settings);
}

$(function(){
	$('.editor-insert').click(function(event){
		event.preventDefault();

		var field = $(this).attr('data-field'); // 插入到字段
		var type = $(this).attr('data-type'); // 返回数据类型
		var title = $(this).attr('title') || $(this).text() ;
		var value = '';
		var handle = $(this).attr('href');
		var editor = UE.getEditor(field);

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
					html = data;
				}else{
					for(var i=0; i<data.length; i++){
						var name = data[i].name;
						var url = data[i].url;
						var description = data[i].description;
						var ext = data[i].ext || url.replace(/.+\./,"");

						html += ' <span class="attachment '+ext+'">';
						if ( ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'gif' || ext == 'bmp'){
							html += '<img src="'+url+'" alt="'+(description||name)+'" />';
						} else if ( ext =='swf' ){
							html += '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"><param name="quality" value="high" /><param name="movie" value="'+url+'" /><embed pluginspage="http://www.macromedia.com/go/getflashplayer" quality="high" src="'+url+'" type="application/x-shockwave-flash" width="500" height="400"></embed></object>';
						}else{
							html += '<a href="'+url+'" title="'+(description||name)+'" target="_blank">'+name+'</a>';
						}
						html += '</span> ';
					}
				}

				editor.execCommand("inserthtml",html);
				return true;
			},
			cancel:function(){}
		},true);
	});
});

