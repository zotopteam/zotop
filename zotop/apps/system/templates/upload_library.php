{template 'dialog.header.php'}

{template 'system/upload_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="single-select" style="padding-top:8px;">
		<select id="folderid" class="form-control select" style="width:200px;">
			<option value="">{t('全部')}</option>
			{loop  m('system.attachment_folder.category') $f}
			<option value="{$f['id']}">{$f['name']}</option>
			{/loop}
		</select>
		</div>
	</div>
	<div class="main-body scrollable">

		<div class="container-fluid">
			{if $data}
			<div class="filelist" id="filelist">
				{loop $data $r}
				<div class="fileitem clearfix" {loop $r $k $v} data-{$k}="{$v}"{/loop}>
					<div class="preview">
						{if $r.type=='image'}
						<div class="image"><img src="{$r.url}"/></div>
						{else}
						<div class="icon"><b class="fa fa-{$r.type} fa-{$r.ext}" ></b><b class="ext">{$r.ext}</b></div>
						{/if}
					</div>
					<div class="title">
						<div class="name text-overflow">{$r.name}</div>
						<div class="info text-overflow">{$r.size} {if $r.width} {$r.width}px × {$r.height}px {/if}</div>
					</div>
					<div class="action"><a class="delete" title="{t('删除')}"><i class="fa fa-times"></i></a></div>
				</div>
				{/loop}
			</div>
			{else}
				<div class="nodata">{t('暂时没有文件')}</div>
			{/if}
			
			

		</div>
	</div><!-- main-body -->
	<div class="main-footer">
		{pagination::instance($total,$pagesize,$page)}
	</div>
</div><!-- main -->

<link rel="stylesheet" type="text/css" href="{A('system.url')}/assets/plupload/plupload.css"/>

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

	$(function(){

		$('#folderid').on('change',function(){

		});
	})

	//删除文件
	$(function(){
		$('#filelist').on('click','a.delete',function(e){
			var $item = $(this).parents('.fileitem');

			$.confirm("{t('您确定要删除该文件嘛?')}",function(){
				//删除操作
				$.get("{u('system/attachment/delete')}",{id : $item.attr('id')}, function(msg){
					msg.state ? $item.removeClass('selected').hide().remove() : $.error(msg.content);
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
					$.error("{t('最多允许选择 %s 个文件',$select)}");
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