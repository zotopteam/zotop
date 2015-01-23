<table class="field">
<tr>
	<td class="label">{form::label(t('字符长度'),'length',false)}</td>
	<td class="input">
		
		<div class="input-group">
				<span class="input-group-addon">{t('最小长度')}</span>

				{form::field(array('type'=>'number','name'=>'settings[minlength]','value'=>intval($data['settings']['minlength']),'min'=>0))}
		</div>
		 -
		<div class="input-group">
			<span class="input-group-addon">{t('最大长度')}</span>

			{form::field(array('type'=>'number','name'=>'settings[maxlength]','value'=>$data['settings']['maxlength']))}
		</div>
	</td>
</tr>
</table>

<!-- /字段类型相关参数 -->
<table class="field">
	<tr class="field-extend field-style">
		<td class="label">{form::label(t('控件样式'),'settings[style]',false)}</td>
		<td class="input">
			{form::field(array('type'=>'text','name'=>'settings[style]','value'=>$data['settings']['style']))}
			{form::tips('定义控件的[style]样式，如：width:200px;height:300px;')}
		</td>
	</tr>
	<tr class="field-extend field-search">
		<td class="label">{form::label(t('允许搜索'),'search',false)}</td>
		<td class="input">
			{form::field(array('type'=>'bool','name'=>'search','value'=>(int)$data['search']))}
		</td>
	</tr>
</table>
