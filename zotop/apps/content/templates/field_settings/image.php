<?php
	if ( !isset( $data['settings']['image_resize'] ) )
	{
		$data['settings']['image_resize'] = c('system.image_resize');
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
		<td class="label">{form::label(t('图片水印'),'settings',false)}</td>
		<td class="input">
			{form::field(array('type'=>'bool','name'=>'settings[watermark]','value'=>$data['settings']['watermark']))}
		</td>
	</tr>
	<tr>
		<td class="label">{t('图片缩放')}</td>
		<td class="input">
			<div class="fl">
				{form::field(array('type'=>'radio','name'=>'settings[image_resize]','value'=>$data['settings']['image_resize'],'options'=>array(0=>t('原图'),1=>t('缩放'),2=>t('裁剪'))))}
			</div>

			&nbsp;
			<div class="input-group imagesize">
				<span class="input-group-addon">{t('宽高')}</span>
				{form::field(array('type'=>'number','name'=>'settings[image_width]','value'=>$data['settings']['image_width'],'required'=>'required'))}
				<span class="input-group-addon">×</span>
				{form::field(array('type'=>'number','name'=>'settings[image_height]','value'=>$data['settings']['image_height'],'required'=>'required'))}
				<span class="input-group-addon">px</span>
			</div>

			<label for="settings-image_width" class="error">{t('必须是数字且不能为空')}</label>
			<label for="settings-image_height" class="error">{t('必须是数字且不能为空')}</label>

			<script type="text/javascript">
				var $image_resize = $('[name="settings[image_resize]"]');
				
				$image_resize.filter(':checked').val() == 0 ? $('.imagesize').hide() : $('.imagesize').show();
				
				$image_resize.on('click',function(){
						$(this).filter(':checked').val() == 0 ? $('.imagesize').hide() : $('.imagesize').show();
				});
			</script>
		</td>
	</tr>	
</table>
