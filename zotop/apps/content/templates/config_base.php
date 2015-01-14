{template 'header.php'}
{form::header(u('content/config/save'))}
<div class="side">
{template 'content/admin_side.php'}
</div>
<div class="main side-main">
	<div class="main-header">
		<div class="title">{t('内容设置')}</div>
		<div class="action">

		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
			<table class="field">
				<caption>{t('最新标识')}</caption>
				<tbody>
				<tr>
					<td class="label">{form::label(t('最新标识'),'newflag',false)}</td>
					<td class="input">
						{form::field(array('type'=>'radio','options'=>array(''=>t('禁用'),'createtime'=>t('以发布时间判定'),'updatetime'=>t('以更新时间判定')),'name'=>'newflag','value'=>c('content.newflag')))}
						{form::tips(t('是否在内容标题后的显示最新标识及判定依据，如: <span class="red">新</span>'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('标识期限'),'newflag_expires',false)}</td>
					<td class="input">
						<div class="input-group">
							{form::field(array('type'=>'number','name'=>'newflag_expires','value'=>c('content.newflag_expires')))}
							<span class="input-group-addon">{t('小时')}</span>
						</div>
						{form::tips(t('超出标识期限则不显示'))}
					</td>
				</tr>
				</tbody>
			</table>

			<table class="field">
				<caption>{t('摘要/缩略图/关键词')}</caption>
				<tbody>
				<tr>
					<td class="label">{form::label(t('提取摘要'),'autosummary',false)}</td>
					<td class="input">
						<div class="input-group">
							{form::field(array('type'=>'number','name'=>'autosummary','value'=>c('content.autosummary')))}
							<span class="input-group-addon">{t('字符')}</span>
						</div>
						{form::tips(t('当摘要为空时从内容提取指定长度文本作为摘要，0：关闭提取'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('提取缩略图'),'autothumb',false)}</td>
					<td class="input">
						<div class="input-group">
							<span class="input-group-addon">{t('第')}</span>
							{form::field(array('type'=>'number','name'=>'autothumb','value'=>c('content.autothumb')))}
							<span class="input-group-addon">{t('张')}</span>
						</div>
						{form::tips(t('当缩略图为空时从内容提取指定图片文本作为缩略图，0：关闭提取'))}
					</td>
				</tr>
				<tr class="none">
					<td class="label">{form::label(t('提取关键词'),'autokeywords',false)}</td>
					<td class="input">
						{form::field(array('type'=>'bool','name'=>'autokeywords','value'=>c('content.autokeywords')))}
						{form::tips(t('当关键词为空时从内容提取关键词'))}
					</td>
				</tr>
				</tbody>
			</table>

			<table class="field">
				<caption>{t('缩略图')}</caption>
				<tbody>
				<tr>
					<td class="label">{form::label(t('缩放'),'thumb_resize',false)}</td>
					<td class="input">
						{form::field(array('type'=>'bool','name'=>'thumb_resize','value'=>c('content.thumb_resize')))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('宽高'),'thumb_width',true)}</td>
					<td class="input">
						<div class="input-group">
							<span class="input-group-addon">{t('宽')}</span>
							{form::field(array('type'=>'number','name'=>'thumb_width','value'=>c('content.thumb_width'),'required'=>'required'))}
							<span class="input-group-addon">px</span>
						</div>
						<b class="center middle">×</b>
						<div class="input-group">
							<span class="input-group-addon">{t('高')}</span>
							{form::field(array('type'=>'number','name'=>'thumb_height','value'=>c('content.thumb_height'),'required'=>'required'))}
							<span class="input-group-addon">px</span>
						</div>

						<label for="thumb_width" class="error">{t('必须是数字且不能为空')}</label>
						<label for="thumb_height" class="error">{t('必须是数字且不能为空')}</label>

						{form::tips(t('图片尺寸大于设置值时自动压缩'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('品质'),'thumb_quality',true)}</td>
					<td class="input">
						{form::field(array('type'=>'number','name'=>'thumb_quality','value'=>c('content.thumb_quality'),'max'=>100,'min'=>0,'required'=>'required'))}
						{form::tips(t('请设置为0-100之间的数字，数字越大图片越清晰'))}
					</td>
				</tr>
				</tbody>
			</table>

			<table class="field">
				<caption>{t('内容图片')}</caption>
				<tbody>
				<tr>
					<td class="label">{form::label(t('缩放'),'image_resize',false)}</td>
					<td class="input">
						{form::field(array('type'=>'bool','name'=>'image_resize','value'=>c('content.image_resize')))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('宽高'),'image_width',true)}</td>
					<td class="input">
						<div class="input-group">
							<span class="input-group-addon">{t('宽')}</span>
							{form::field(array('type'=>'number','name'=>'image_width','value'=>c('content.image_width'),'required'=>'required'))}
							<span class="input-group-addon">px</span>
						</div>
						<b class="center middle">×</b>
						<div class="input-group">
							<span class="input-group-addon">{t('高')}</span>
							{form::field(array('type'=>'number','name'=>'image_height','value'=>c('content.image_height'),'required'=>'required'))}
							<span class="input-group-addon">px</span>
						</div>

						<label for="image_width" class="error">{t('必须是数字且不能为空')}</label>
						<label for="image_height" class="error">{t('必须是数字且不能为空')}</label>

						{form::tips(t('图片尺寸大于设置值时自动压缩'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('品质'),'image_quality',true)}</td>
					<td class="input">
						{form::field(array('type'=>'number','name'=>'image_quality','value'=>c('content.image_quality'),'max'=>100,'min'=>0,'required'=>'required'))}
						{form::tips(t('请设置为0-100之间的数字，数字越大图片越清晰'))}
					</td>
				</tr>
				</tbody>
			</table>
			<table class="field">
				<caption>{t('栏目图片')}</caption>
				<tbody>
				<tr>
					<td class="label">{form::label(t('缩放'),'category_image_resize',false)}</td>
					<td class="input">
						{form::field(array('type'=>'radio',options=>array(0=>t('关闭'),1=>t('缩放'),2=>t('裁剪')),'name'=>'category_image_resize','value'=>c('content.category_image_resize')))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('宽高'),'category_image_width',true)}</td>
					<td class="input">
						<div class="input-group">
							<span class="input-group-addon">{t('宽')}</span>
							{form::field(array('type'=>'number','name'=>'category_image_width','value'=>c('content.category_image_width'),'required'=>'required'))}
							<span class="input-group-addon">px</span>
						</div>
						<b class="center middle">×</b>
						<div class="input-group">
							<span class="input-group-addon">{t('高')}</span>
							{form::field(array('type'=>'number','name'=>'category_image_height','value'=>c('content.category_image_height'),'required'=>'required'))}
							<span class="input-group-addon">px</span>
						</div>

						<label for="category_image_width" class="error">{t('必须是数字且不能为空')}</label>
						<label for="category_image_height" class="error">{t('必须是数字且不能为空')}</label>

						{form::tips(t('图片尺寸大于设置值时自动压缩'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('品质'),'category_image_quality',true)}</td>
					<td class="input">
						{form::field(array('type'=>'number','name'=>'category_image_quality','value'=>c('content.category_image_quality'),'max'=>100,'min'=>0,'required'=>'required'))}
						{form::tips(t('请设置为0-100之间的数字，数字越大图片越清晰'))}
					</td>
				</tr>
				</tbody>
			</table>
	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}
	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}
<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$(form).find('.submit').disable(true);
			$.loading();
			$.post(action, data, function(msg){
				$.msg(msg);
				$(form).find('.submit').disable(false);
			},'json');
		}});
	});
</script>
{template 'footer.php'}