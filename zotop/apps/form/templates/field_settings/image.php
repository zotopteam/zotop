<?php
	if ( empty( $data['settings']['image_resize'] ) )
	{
		$data['settings']['image_resize'] = 1;
	}

	if ( empty( $data['settings']['image_width'] ) )
	{
		$data['settings']['image_width'] = c('system.image_width');
	}

	if ( empty( $data['settings']['image_height'] ) )
	{
		$data['settings']['image_height'] = c('system.image_height');
	}
?>
<table class="field">
	<tr>
		<td class="label">{form::label(t('上传设置'),'settings',false)}</td>
		<td class="input">
			<table class="controls vt">
				<tr>
					<td class="w100">{t('图片水印')}</td>
					<td>
						{form::field(array('type'=>'bool','name'=>'settings[watermark]','value'=>$data['settings']['watermark']))}
					</td>
				</tr>

				<tr>
					<td class="w100">{t('图片尺寸')}</td>
					<td>
						<div class="fl">
							{form::field(array('type'=>'radio','name'=>'settings[image_resize]','value'=>$data['settings']['image_resize'],'options'=>array(0=>t('原图'),1=>t('缩放'),2=>t('裁剪'))))}
						</div>
						&nbsp;
						<div class="input-group">
							<span class="input-group-addon">{t('宽')}</span>
							{form::field(array('type'=>'number','name'=>'settings[image_width]','value'=>$data['settings']['image_width'],'required'=>'required'))}
							<span class="input-group-addon">px</span>
						</div>

						<b class="center middle">×</b>

						<div class="input-group">
							<span class="input-group-addon">{t('高')}</span>
							{form::field(array('type'=>'number','name'=>'settings[image_height]','value'=>$data['settings']['image_height'],'required'=>'required'))}
							<span class="input-group-addon">px</span>
						</div>
						<label for="settings-image_width" class="error">{t('必须是数字且不能为空')}</label>
						<label for="settings-image_height" class="error">{t('必须是数字且不能为空')}</label>
					</td>
				</tr>
			</table>

		</td>
	</tr>
</table>
<script type="text/javascript">
$(function(){
	//$('tr.search').hide();
	//$('tr.unique').hide();
	//$('tr.order').hide();
});
</script>