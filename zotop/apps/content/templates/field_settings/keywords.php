
<table class="field">
	<tr class="field-extend field-source">
		<td class="label">{form::label(t('关键词提取'),'settings[data-source]',true)}</td>
		<td class="input">
			{form::field(array('type'=>'text','name'=>'settings[data-source]','value'=>($data['settings']['data-source'] ? $data['settings']['data-source'] : 'title,content'),'required'=>'required'))}
			{form::tips('关键词提取来源字段名，一般从标题和内容(title,content)进行提取')}
		</td>
	</tr>
</table>