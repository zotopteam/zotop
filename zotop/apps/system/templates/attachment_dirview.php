{template 'dialog.header.php'}
<link rel="stylesheet" type="text/css" href="{A('system.url')}/common/css/attachment.css" />

<div class="side">
	{template 'system/attachment_side.php'}
</div>

<div class="main side-main no-footer">
	<div class="main-header">
		<div class="position">
			{t('当前位置')} :
			{loop $position $n $p}
				{if $n}<s class="arrow">></s>{/if} <a href="{$p['url']}">{$p['text']}</a>
			{/loop}
		</div>
	</div>
	<div class="main-body">
			<div class="filelist-body filelist" id="filelist">
				{loop $folders $f}
				<a href="{$f['url']}">
				<div class="fileitem folder clearfix">
					<div class="preview">
						<div class="icon"><b class="icon icon-folder"></b></div>
					</div>
					<div class="title textflow">{$f['name']}</div>
				</div>
				</a>
				{/loop}

				{loop $files $f}
				<div class="fileitem file clearfix" id="{$f['id']}" data-name="{$f['name']}" data-url="{$f['url']}" data-size="{$f['size']}" data-ext="{$f['ext']}" title="{t('名称')} : {$f['name']}&#10;{t('大小')} : {format::size($f['size'])}{if $f['width']}&#10;{t('宽高')} : {$f['width']}px × {$f['height']}px{/if}">
					<i class="icon icon-selected"></i>
					<div class="preview">
						{if $f['type'] == 'image'}
						<div class="image"><img src="{$f['url']}"></div>
						{else}
						<div class="icon"><b class="icon-ext icon-{$f['type']} icon-{$f['ext']}"></b><b class="ext">{$f['ext']}</b></div>
						{/if}
					</div>
					<div class="title textflow">{$f['name']}</div>
				</div>
				{/loop}
			</div>
	</div><!-- main-body -->
</div><!-- main -->
<link rel="stylesheet" type="text/css" href="{A('system.url')}/common/plupload/plupload.css" />
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
				insert.push({'name':$(this).attr('data-name'), 'url':$(this).attr('data-url')})
			});

			$dialog.ok(insert);
			return true;

	};

	$dialog.title('{t('插入%s', $typename)}');


	//选择文件
	$('#filelist').on('click','.file',function(e){
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