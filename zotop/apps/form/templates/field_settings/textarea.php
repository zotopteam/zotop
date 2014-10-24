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
