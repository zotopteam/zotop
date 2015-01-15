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

<!-- /字段类型相关参数 -->
<table class="field">
	<tr class="field-extend field-style none">
		<td class="label">{form::label(t('控件样式'),'settings[style]',false)}</td>
		<td class="input">
			{form::field(array('type'=>'text','name'=>'settings[style]','value'=>$data['settings']['style']))}
			{form::tips('定义控件的[style]样式，如：width:200px;height:300px;')}
		</td>
	</tr>
	<tr class="field-extend field-notnull none">
		<td class="label">{form::label(t('不能为空'),'notnull',false)}</td>
		<td class="input">
			{form::field(array('type'=>'bool','name'=>'notnull','value'=>(int)$data['notnull']))}
		</td>
	</tr>
	<tr class="field-extend field-unique none">
		<td class="label">{form::label(t('值唯一'),'unique',false)}</td>
		<td class="input">			
			{form::field(array('type'=>'bool','name'=>'unique','value'=>(int)$data['unique']))}			
		</td>
	</tr>
	<tr class="field-extend field-post">
		<td class="label">{form::label(t('前台投稿'),'post',false)} <i class="icon icon-help" title="{t('当表单允许前台发布时是否显示并允许录入数据')}"></i></td>
		<td class="input">
			{form::field(array('type'=>'bool','name'=>'post','value'=>$data['post']))}
			
		</td>
	</tr>
	<tr class="field-extend field-base">
		<td class="label">{form::label(t('基本信息'),'base',false)} <i class="icon icon-help" title="{t('基本信息将显示在添加编辑页面的主要位置')}"></i></td>
		<td class="input">
			{form::field(array('type'=>'bool','name'=>'base','value'=>(int)$data['base']))}			
		</td>
	</tr>
	<tr class="field-extend field-search none">
		<td class="label">{form::label(t('允许搜索'),'search',false)}</td>
		<td class="input">
			{form::field(array('type'=>'bool','name'=>'search','value'=>(int)$data['search']))}
		</td>
	</tr>
</table>
