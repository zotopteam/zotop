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
		<td class="label">{form::label(t('编辑器设置'),'theme',false)}</td>
		<td class="input">
			<table class="controls vt">
				<tr>
					<td class="w120">{t('编辑器类型')}</td>
					<td>
						{form::field(array('type'=>'radio','name'=>'settings[theme]','value'=>$data['settings']['theme'],'options'=>array('basic'=>t('简洁'),'standard'=>t('标准'),'full'=>t('全能'))))}
					</td>
				</tr>
				<tr>
					<td class="w120">{t('允许上传类型')}</td>
					<td>
						<?php echo form::field(array(
							'type'=>'checkbox',
							'name'=>'settings[tools]',
							'options'=>array(
								'image'		=> t('图片'),
								'file'		=> t('文件'),
								'video'		=> t('视频'),
								'audio'		=> t('音频'),
							),
							'value'=>$data['settings']['tools'],
							'class'=>'short'
						))?>
					</td>
				</tr>
				<tr>
					<td class="w120">{t('图片缩放')} <i class="icon icon-help" title="{t('上传图片尺寸大于设置值时自动压缩')}"></i></td>
					<td>
						<div class="fl">{form::field(array('type'=>'hidden','name'=>'settings[image_resize]','value'=>$data['settings']['image_resize']))}</div>

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

</script>