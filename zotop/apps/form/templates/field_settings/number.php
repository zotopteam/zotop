<table class="field">
	<tr>
		<td class="label">{form::label(t('数值范围'),'length',false)}</td>
		<td class="input">
			<div class="input-group">
				<span class="input-group-addon">{t('最小值')}</span>
				{form::field(array('type'=>'number','name'=>'settings[min]','value'=>$data['settings']['min']))}
			</div>
			 -
			<div class="input-group">
				<span class="input-group-addon">{t('最大值')}</span>
				{form::field(array('type'=>'number','name'=>'settings[max]','value'=>$data['settings']['max']))}
			</div>			
			
		</td>
	</tr>
</table>
<script type="text/javascript">

</script>