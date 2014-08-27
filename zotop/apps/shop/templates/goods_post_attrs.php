<table class="field">
	<caption>{t('扩展属性')}</caption>
	<tbody>
	<tr>
	{loop $fields $f}
	
		<td class="label">{form::label($f.label, $f.field.name, false)}</td>
		<td class="input">
			{form::field($f.field)}

			{if $f.filed.type=='text' and $f.filed.options}
				{form::field(array('type'=>'select','name'=>'attr_option','options'=>array(''=>t('可选值')) + $f.filed.options))}
			{/if}					
		</td>

		{if $n%2==0}
		</tr>
		<tr>
		{/if}
	
	{/loop}
	</tr>		
	</tbody>
</table>

<script>
	$(function(){
		$('[name=attr_option]').change(function(){

			$(this).parents('td').find('input').val($(this).val());
		});
	});
</script>
