{template 'dialog.header.php'}
<link rel="stylesheet" type="text/css" href="{A('system.url')}/common/css/attachment.css" />

<div class="side">
	{template 'system/attachment_side.php'}
</div>
<div class="main side-main">
	<div class="main-header">
		<div style="padding-top:6px;">
		<select id="folderid" class="select" style="width:160px;">
			<option value="">{t('全部')}</option>
			{loop $folders $f}
			<option value="{$f['id']}">{$f['name']}</option>
			{/loop}
		</select>
		</div>
	</div>
	<div class="main-body">
			<div class="filelist" id="filelist"></div>
	</div><!-- main-body -->
	<div class="main-footer">
		<div id="pagination"></div>&nbsp;
	</div>
</div><!-- main -->
<link rel="stylesheet" type="text/css" href="{A('system.url')}/common/plupload/plupload.css" />

<!-- 分页 -->
<script type="text/javascript" src="{A('system.url')}/common/js/jquery.pagination.js"></script>
<script type="text/javascript">

	//选择文件个数
	var select = {intval($select)};

	// 对话框设置
	$dialog.callbacks['ok'] = function(){

			var $selected = $('#filelist').find('.selected');

			if ( $selected.length == 0 ){
				$.error('{t('请选择要插入的文件')}');
				return false;
			}

			var insert = new Array();

			$selected.each(function(){
				insert.push($(this).data());
			});

			$dialog.ok(insert);
			return true;

	};

	$dialog.title('{t('插入%s', $typename)}');

	function getdatalist(index){
		var page = index || 0;
		var pagesize = 6;
		var folderid = $('#folderid').val();
		var loading = $.loading();

		$.ajax({
			url: "{u('system/attachment/librarydata')}",
			data:{page:page+1,pagesize:pagesize, type: '{$type}', folderid:folderid},
			dataType:'json',
			success:function(result){
				loading.close();

				var html = '';

				$.each(result.data,function(i,file){
					html += '<div class="fileitem file clearfix" id="'+file.id+'" data-name="'+file.name+'" data-url="'+file.url+'" data-size="'+file.size+'" data-ext="'+file.ext+'" title="{t('名称')} : '+file.name+'<br/>' + '{t('大小')} : '+ zotop.formatsize(file.size) +'<br/> ' + (file.width > 0 ? '{t('宽高')} : '+file.width+'px × '+file.height+'px' : '') +'">';
					html += '<i class="icon icon-selected"></i>';
					html += '<div class="preview">';
					html += ( file.type == 'image' ) ? '	<div class="image"><img src="'+file.url+'"></div>' : '<div class="icon"><b class="icon-ext icon-'+file.type+' icon-'+file.ext+'"></div></b>';
					html += '</div>';
					html += '<div class="progressbar"><span class="progress">100%</span></div>';
					html += '<div class="title"><span class="textflow">'+file.name+'</span></div>';
					html += '<div class="action"><a class="delete" title="{t('删除')}"><i class="icon icon-delete"></i></a></div>';
					html += '</div>';
				});

				$('#filelist').html(html);

				if( result.total > 0 && $('#pagination').html().length == 0 ){
					$('#pagination').pagination(result.total,{
						items_per_page:pagesize,
						num_edge_entries: 1,
						num_display_entries: 7,
						prev_text : "{t('前页')}",
						next_text : "{t('下页')}",
						load_first_page : false,
						show_if_single_page : true,
						callback:function(index,jq){
							getdatalist(index);
						}
					});
				}
			}
		});
	}

	$(function(){
		getdatalist(0);

		$('#folderid').on('change',function(){
			$('#pagination').empty();
			getdatalist(0);
		});
	})

	//删除文件
	$(function(){
		$('#filelist').on('click','a.delete',function(e){
			var $item = $(this).parents('.fileitem');

			$.confirm("{t('您确定要删除该文件嘛?')}",function(){
				//删除操作
				$.get("{u('system/attachment/delete')}",{id : $item.attr('id')}, function(msg){
					if( msg.state ){
						$item.removeClass('selected').hide().remove();
					}else{
						alert(msg.content);
					}
				},'json');
			},function(){});

			return false;
		});
	});

	//选择文件
	$('#filelist').on('click','.fileitem',function(e){
		//当点击为按钮时，禁止选择
		if( $(e.target).parent().attr('tagName') == 'A' ) return false;

		if ( $(this).hasClass('selected') ) {
			$(this).removeClass("selected");
		}else{
			if ( select == 1 ) {
				$(this).addClass('selected').siblings(".fileitem").removeClass('selected'); //单选
			} else {
				var num = $('.selected').length;
				if( select>1 && num > select ) {
					alert("{t('最多允许选择 %s 个文件',$select)}");
					return false;
				}else{
					$(this).addClass("selected");
				}
			}
		}

		return false;
	});
</script>
{template 'dialog.footer.php'}