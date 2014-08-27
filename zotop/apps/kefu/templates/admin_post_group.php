{form::field(array('type'=>'hidden','name'=>'account','value'=>''))}

<table class="field">
	<tbody>
	<tr>
		<td class="label">{form::label(t('组名'),'text',true)}</td>
		<td class="input">
			{form::field(array('type'=>'title','name'=>'text','value'=>$data['text'],'style'=>$data['style'],'required'=>'required','maxlength'=>50))}
			{form::tips(t('请输入分组名称'))}
		</td>
	</tr>					
	</tbody>
</table>