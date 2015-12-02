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

<div class="form-group">
	<div class="col-sm-2 control-label">{form::label(t('字符长度'),'length',false)}</div>
	<div class="col-sm-5">
		<div class="input-group">
			{form::field(array('type'=>'number','name'=>'settings[minlength]','value'=>$data['settings']['minlength'],'min'=>0,'placeholder'=>t('最小长度')))}
			<span class="input-group-addon">-</span>
			{form::field(array('type'=>'number','name'=>'settings[maxlength]','value'=>$data['settings']['maxlength'],'max'=>255,'min'=>1,'required'=>'required','placeholder'=>t('最大长度')))}
		</div>
	</div>
</div>

<script type="text/javascript">
	// maxlegth 切换
	$('#settings-maxlength').on('change',function(){
		$('[name=length]').val($(this).val());
	});
</script>