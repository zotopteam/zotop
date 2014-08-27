<table class="field">
	<tbody>
	<tr>
		<td class="label">{form::label(t('QQ号码'),'account',true)}</td>
		<td class="input">
			{form::field(array('type'=>'text','name'=>'account','value'=>$data['account'],'pattern'=>'^[0-9]{5,15}$','required'=>'required'))}
			{form::tips(t('请输入qq号码，并开启该qq的“在线状态”'))}
		</td>
	</tr>
	<tr>
		<td class="label">{form::label(t('说明'),'text',true)}</td>
		<td class="input">
			{form::field(array('type'=>'title','name'=>'text','value'=>$data['text'],'style'=>$data['style'],'required'=>'required','maxlength'=>50))}
			{form::tips(t('显示的标题，如 技术支持 客服一 等'))}
		</td>
	</tr>					
	</tbody>
</table>