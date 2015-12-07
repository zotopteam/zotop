{template 'dialog.header.php'}

{template 'system/upload_side.php'}

<div class="main side-main no-footer">
	<div class="main-header">
		{if count($position)>1}
		<div class="goback"><a href="javascript:history.go(-1);"><i class="fa fa-angle-left"></i> <span>{t('上级')}</span></a></div>
		{else}
		<div class="goback"><a class="disabled" href="javascript:;"><i class="fa fa-angle-left"></i> <span>{t('上级')}</span></a></div>
		{/if}
		<ul class="breadcrumb">
			{loop $position $n $p}
				<li><a href="{$p.url}"><i class="{$p.icon}"></i> {$p.text}</a></li>
			{/loop}
		</ul>
		<div class="action">
			<a href="javascript:location.reload();" class="btn btn-default btn-icon" title="{t('刷新')}"><span class="fa fa-refresh"></span></a>
		</div>
	</div>
	<div class="main-body scrollable">
		<div class="container-fluid">
			
			<div class="filelist">				
				{loop $folders $f}				
				<div class="fileitem folder clearfix">
					<a href="{$f['url']}">
					<div class="preview">
						<div class="icon"><b class="fa fa-folder"></b></div>
					</div>
					<div class="title">
						<div class="name text-overflow"><b>{$f['name']}</b></div>
						<div class="info text-overflow">{t('文件夹')}</div>
					</div>
					</a>
				</div>				
				{/loop}

				{loop $files $f}
				<div class="fileitem file clearfix" data-name="{$f['name']}" data-url="{$f['url']}" data-size="{$f['size']}" data-ext="{$f['ext']}">
					<div class="preview">
						{if $f['type'] == 'image'}
						<div class="image"><img src="{$f['url']}"></div>
						{else}
						<div class="icon"><b class="fa fa-{$f['type']} fa-{$f['ext']}"></b></div>
						{/if}
					</div>
					<div class="title">
						<div class="name text-overflow">{$f['name']}</div>
						<div class="info text-overflow">{strtoupper($f['ext'])} {format::size($f['size'])}</div>
					</div>
				</div>
				{/loop}
			</div>

		</div>
	</div><!-- main-body -->
</div><!-- main -->
<link rel="stylesheet" type="text/css" href="{A('system.url')}/assets/plupload/plupload.css" />
<script type="text/javascript">

	//选择文件个数
	var select = {intval($select)};

	// 对话框设置
	$dialog.callbacks['ok'] = function(){

			var $selected = $('.filelist').find('.selected');

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
	$('.filelist').on('click','.file',function(e){
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