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


	<div class="form-group">
		<div class="col-sm-2 control-label">{t('工具栏')}</div>
		<div class="col-sm-10">
			{form::field(array('type'=>'radio','name'=>'settings[toolbar]','value'=>$data['settings']['toolbar'],'options'=>array('basic'=>t('简洁'),'standard'=>t('标准'),'full'=>t('全能'))))}
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-2 control-label">{t('允许上传类型')}</div>
		<div class="col-sm-10">
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
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-2 control-label">{t('图片缩放')}</div>
		<div class="col-sm-10">

			<div class="row">
				<div class="col-sm-2">
					{form::field(array('type'=>'select','name'=>'settings[image_resize]','value'=>$data['settings']['image_resize'],'options'=>array(0=>t('原图'),1=>t('缩放'),2=>t('裁剪'))))}
				</div>
				<div class="col-sm-3">
					<div class="input-group imagesize">
						<span class="input-group-addon">{t('宽高')}</span>
						{form::field(array('type'=>'number','name'=>'settings[image_width]','value'=>$data['settings']['image_width'],'required'=>'required'))}
						<span class="input-group-addon">×</span>
						{form::field(array('type'=>'number','name'=>'settings[image_height]','value'=>$data['settings']['image_height'],'required'=>'required'))}
						<span class="input-group-addon">px</span>
					</div>
				</div>
			</div>

			<label for="settings-image_width" class="error">{t('必须是数字且不能为空')}</label>
			<label for="settings-image_height" class="error">{t('必须是数字且不能为空')}</label>

			<script type="text/javascript">
				var $image_resize = $('[name="settings[image_resize]"]');
				
				$image_resize.val() == 0 ? $('.imagesize').hide() : $('.imagesize').show();
				
				$image_resize.on('change',function(){
						$(this).val() == 0 ? $('.imagesize').hide() : $('.imagesize').show();
				});
			</script>
		</div>
	</div>



<!-- /字段类型相关参数 -->

	<div class="form-group field-extend field-search">
		<div class="col-sm-2 control-label">{form::label(t('允许搜索'),'search',false)}</div>
		<div class="col-sm-10">
			{form::field(array('type'=>'bool','name'=>'search','value'=>(int)$data['search']))}
		</div>
	</div>
