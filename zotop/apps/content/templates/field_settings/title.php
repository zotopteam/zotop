<?php
	if ( empty( $data['settings']['minlength'] ) )
	{
		$data['settings']['minlength'] = 0;
	}

	if ( empty( $data['settings']['maxlength'] ) )
	{
		$data['settings']['maxlength'] = $data['length'];
	}
?>
<table class="field">
	<tr>
		<td class="label">{form::label(t('字符长度'),'length',false)}</td>
		<td class="input">
			<div class="input-group">
				<span class="input-group-addon">{t('最小长度')}</span>

				{form::field(array('type'=>'number','name'=>'settings[minlength]','value'=>$data['settings']['minlength'],'min'=>0))}

			</div>
			-
			<div class="input-group">
				<span class="input-group-addon">{t('最大长度')}</span>

				{form::field(array('type'=>'number','name'=>'settings[maxlength]','value'=>$data['settings']['maxlength'],'max'=>255,'min'=>1,'required'=>'required'))}
			</div>
		</td>
	</tr>
</table>
<script type="text/javascript">
	// maxlegth 切换
	$('#settings-maxlength').on('change',function(){
		$('[name=length]').val($(this).val());
	});
</script>