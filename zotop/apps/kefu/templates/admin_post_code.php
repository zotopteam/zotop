{form::field(array('type'=>'hidden','name'=>'account','value'=>''))}

<table class="field">
	<tbody>
	<tr>
		<td class="label">{form::label(t('代码'),'text',true)}</td>
		<td class="input">
			{form::field(array('type'=>'textarea','name'=>'text','value'=>$data['text'],'required'=>'required'))}
			{form::tips(t('请输入代码'))}
		</td>
	</tr>					
	</tbody>
</table>
<style type="text/css">
	textarea.textarea{width:90%;height:160px;}
</style>