<table class="field">
	<tr>
		<td class="label">{form::label(t('数值范围'),'length',false)}</td>
		<td class="input">
			{form::field(array('type'=>'number','name'=>'settings[min]','value'=>$data['settings']['min'],'title'=>t('最小值')))}
			 -
			{form::field(array('type'=>'number','name'=>'settings[max]','value'=>$data['settings']['max'],'title'=>t('最大值')))}
		</td>
	</tr>
</table>
<script type="text/javascript">

</script>