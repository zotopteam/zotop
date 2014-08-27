<table class="field">
	<caption>{t('图片上传')}</caption>
	<tbody>
	<tr>
		<td class="label">{form::label(t('缩放'),'settings[image_resize]',false)}</td>
		<td class="input">
			{form::field(array('type'=>'radio',options=>array(0=>t('关闭'),1=>t('缩放'),2=>t('裁剪')),'name'=>'settings[image_resize]','value'=>$data['settings']['image_resize']))}
		</td>
	</tr>
	<tr>
		<td class="label">{form::label(t('宽高'),'settings[image_width]',true)}</td>
		<td class="input">
			<div class="input-group">
				<span class="input-group-addon">{t('宽')}</span>
				{form::field(array('type'=>'number','name'=>'settings[image_width]','value'=>$data['settings']['image_width']))}
				<span class="input-group-addon">px</span>
			</div>
			<b class="center middle">×</b>
			<div class="input-group">
				<span class="input-group-addon">{t('高')}</span>
				{form::field(array('type'=>'number','name'=>'settings[image_height]','value'=>$data['settings']['image_height']))}
				<span class="input-group-addon">px</span>
			</div>

			<label for="category_image_width" class="error">{t('必须是数字且不能为空')}</label>
			<label for="category_image_height" class="error">{t('必须是数字且不能为空')}</label>

			{form::tips(t('图片尺寸大于设置值时自动压缩'))}
		</td>
	</tr>
	<tr>
		<td class="label">{form::label(t('品质'),'settings[image_quality]',true)}</td>
		<td class="input">
			{form::field(array('type'=>'number','name'=>'settings[image_quality]','value'=>$data['settings']['image_quality'],'max'=>100,'min'=>0))}
			{form::tips(t('请设置为0-100之间的数字，数字越大图片越清晰'))}
		</td>
	</tr>
	<tr>
		<td class="label">{form::label(t('水印'),'watermark',false)}</td>
		<td class="input">
			{form::field(array('type'=>'bool','name'=>'settings[watermark]','value'=>$data['settings']['watermark']))}
		</td>
	</tr>
	</tbody>
</table>