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
	<div class="col-sm-2 control-label">{form::label(t('占位符'),'settings[placeholder]',false)}</div>
	<div class="col-sm-8">
		{field type="text" name="settings[placeholder]" value="$data.settings.placeholder" placeholder="t('例如这就是占位符')"}
		<div class="help-block">
			{t('定义控件的[placeholder]属性，提供可描述输入字段预期值的提示信息')}
		</div>		
	</div>
</div>

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

<div class="form-group">
	<div class="col-sm-2 control-label">{form::label(t('数据验证'),'pattern',false)}</div>
	<div class="col-sm-8">
		
		<div class="row">
			<div class="col-sm-4">
				{form::field(array('type'=>'text','name'=>'settings[pattern]','value'=>$data['settings']['pattern'],'placeholder'=>t('数据验证正则表达式')))}
			</div>
			<div class="col-sm-2">
			<?php echo form::field(array(
				'type'=>'select',
				'name'=>'pattern_example',
				'options'=>array(''=>t('常用正则')) + zotop::filter('member.field.regexs', array(
					'^[0-9.-]+$'		=> t('数字'),
					'^[0-9-]+$'			=> t('整数'),
					'^[a-zA-Z]+$'		=> t('字母'),
					'^[0-9a-zA-Z]+$'	=> t('字母+数字'),
					'^[\w-\s]+$'		=> t('字母+数字+下划线'),
					'^[0-9]{5,20}$'		=> t('QQ'),
					'^(1)[0-9]{10}$'	=> t('手机号码'),
					'^[0-9-]{6,13}$'	=> t('固定电话'),
					'^[0-9]{6}$'		=> t('邮编'),
					'^\d{15}$)|(^\d{17}([0-9]|X|x)$' => t('身份证号码'),
					)),
				'class'=>'short'
			))?>
			</div>				
		</div>


		{form::tips(t('正则校验表单提交的数据是否正确，如果不校验请留空'))}
		<div class="blank"></div>
		{form::field(array('type'=>'text','name'=>'settings[data-msg-pattern]','value'=>$data['settings']['data-msg-pattern']))}
		{form::tips(t('数据校验未通过的提示信息'))}
	</div>
</div>


<!-- /字段类型相关参数 -->
<div class="form-group field-extend field-unique none">
	<div class="col-sm-2 control-label">{form::label(t('值唯一'),'unique',false)}</div>
	<div class="col-sm-10">			
		{form::field(array('type'=>'bool','name'=>'unique','value'=>(int)$data['unique']))}			
	</div>
</div>
<div class="form-group field-extend field-search">
	<div class="col-sm-2 control-label">{form::label(t('允许搜索'),'search',false)}</div>
	<div class="col-sm-10">
		{form::field(array('type'=>'bool','name'=>'search','value'=>(int)$data['search']))}
	</div>
</div>

<script type="text/javascript">
	// maxlegth 切换
	$('#settings-maxlength').on('change',function(){
		$('[name=length]').val($(this).val());
	});

	// 正则切换
	$('[name=pattern_example]').on('change',function(){
		$(this).parents('.row').find('input:first').val($(this).val());
	});
</script>