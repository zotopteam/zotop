<table class="field">
<tr>
	<td class="label">{form::label(t('长度范围'),'length',false)}</td>
	<td class="input">
		{form::field(array('type'=>'number','name'=>'settings[minlength]','value'=>intval($data['settings']['minlength']),'min'=>0,'title'=>t('最小长度')))}
		 -
		{form::field(array('type'=>'number','name'=>'settings[maxlength]','value'=>$data['settings']['maxlength'],'title'=>t('最大长度')))}
	</td>
</tr>
</table>
