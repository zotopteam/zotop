<?php
	if ( empty( $data['settings']['theme'] ) )
	{
		$data['settings']['theme'] = 'standard';
	}

	if ( empty( $data['settings']['tools'] ) )
	{
		$data['settings']['tools'] = array('image');
	}

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
		<td class="label">{t('编辑器设置')}</td>
		<td class="input">
			{form::field(array('type'=>'radio','name'=>'settings[theme]','value'=>$data['settings']['theme'],'options'=>array('basic'=>t('简洁'),'standard'=>t('标准'),'full'=>t('全能'))))}
		</td>
	</tr>
	<tr>
		<td class="label">{t('允许上传类型')}</td>
		<td class="input">
			<?php echo form::field(array(
				'type'		=>'checkbox',
				'name'		=>'settings[tools]',
				'options'	=>array(
					'image'		=> t('图片'),
					'file'		=> t('文件'),
					'video'		=> t('视频'),
					'audio'		=> t('音频'),
				),
				'value'		=>$data['settings']['tools'],
				'class'		=>'short'
			))?>
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


<!-- /字段类型相关参数 -->
<table class="field">
	<tr class="field-extend field-search">
		<td class="label">{form::label(t('允许搜索'),'search',false)}</td>
		<td class="input">
			{form::field(array('type'=>'bool','name'=>'search','value'=>(int)$data['search']))}
		</td>
	</tr>
</table>