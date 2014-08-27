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
			{form::field(array('type'=>'number','name'=>'settings[minlength]','value'=>$data['settings']['minlength'],'min'=>0,'title'=>t('最小长度')))}
			 -
			{form::field(array('type'=>'number','name'=>'settings[maxlength]','value'=>$data['settings']['maxlength'],'max'=>255,'min'=>10,'required'=>'required','title'=>t('最大长度')))}
		</td>
	</tr>
	<tr>
		<td class="label">{form::label(t('数据验证'),'pattern',false)}</td>
		<td class="input">
			{form::field(array('type'=>'text','name'=>'settings[pattern]','value'=>$data['settings']['pattern'],'placeholder'=>t('数据验证正则表达式')))}

			<?php echo form::field(array(
				'type'=>'select',
				'name'=>'pattern_example',
				'options'=>array(''=>t('常用正则')) + zotop::filter('member.field.regexs', array(
					'^[0-9.-]+$'		=> t('数字'),
					'^[0-9-]+$'		=> t('整数'),
					'^[a-zA-Z]+$'		=> t('字母'),
					'^[0-9a-zA-Z]+$'	=> t('字母+数字'),
					'^[\w-\s]+$'		=> t('字母+数字+下划线'),
					'^[0-9]{5,20}$'	=> t('QQ'),
					'^(1)[0-9]{10}$'	=> t('手机号码'),
					'^[0-9-]{6,13}$'	=> t('固定电话'),
					'^[0-9]{6}$'		=> t('邮编'),
					'^\d{15}$)|(^\d{17}([0-9]|X|x)$' => t('身份证号码'),
				)),
				'class'=>'short'
			))?>
			{form::tips('正则校验表单提交的数据是否正确，如果不校验请留空')}
			<div class="blank"></div>
			{form::field(array('type'=>'text','name'=>'settings[data-msg-pattern]','value'=>$data['settings']['data-msg-pattern']))}
			{form::tips('验证失败时的提示信息')}
		</td>
	</tr>
</table>
<script type="text/javascript">
	// maxlegth 切换
	$('#settings-maxlength').on('change',function(){
		$('[name=length]').val($(this).val());
	});

	// 正则切换
	$('[name=pattern_example]').on('change',function(){
		$(this).parents('td').find('input:first').val($(this).val());
	});
</script>