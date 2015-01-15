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
			{form::tips(t('正则校验表单提交的数据是否正确，如果不校验请留空'))}
			<div class="blank"></div>
			{form::field(array('type'=>'text','name'=>'settings[data-msg-pattern]','value'=>$data['settings']['data-msg-pattern']))}
			{form::tips(t('数据校验未通过的提示信息'))}
		</td>
	</tr>
</table>

<!-- /字段类型相关参数 -->
<table class="field">
	<tr class="field-extend field-style">
		<td class="label">{form::label(t('控件样式'),'settings[style]',false)}</td>
		<td class="input">
			{form::field(array('type'=>'text','name'=>'settings[style]','value'=>$data['settings']['style']))}
			{form::tips('定义控件的[style]样式，如：width:200px;height:300px;')}
		</td>
	</tr>
	<tr class="field-extend field-notnull">
		<td class="label">{form::label(t('不能为空'),'notnull',false)}</td>
		<td class="input">
			{form::field(array('type'=>'bool','name'=>'notnull','value'=>(int)$data['notnull']))}
		</td>
	</tr>
	<tr class="field-extend field-unique none">
		<td class="label">{form::label(t('值唯一'),'unique',false)}</td>
		<td class="input">			
			{form::field(array('type'=>'bool','name'=>'unique','value'=>(int)$data['unique']))}			
		</td>
	</tr>
	<tr class="field-extend field-post">
		<td class="label">{form::label(t('前台投稿'),'post',false)} <i class="icon icon-help" title="{t('当表单允许前台发布时是否显示并允许录入数据')}"></i></td>
		<td class="input">
			{form::field(array('type'=>'bool','name'=>'post','value'=>$data['post']))}
			
		</td>
	</tr>
	<tr class="field-extend field-base">
		<td class="label">{form::label(t('基本信息'),'base',false)} <i class="icon icon-help" title="{t('基本信息将显示在添加编辑页面的主要位置')}"></i></td>
		<td class="input">
			{form::field(array('type'=>'bool','name'=>'base','value'=>(int)$data['base']))}			
		</td>
	</tr>
	<tr class="field-extend field-search">
		<td class="label">{form::label(t('允许搜索'),'search',false)}</td>
		<td class="input">
			{form::field(array('type'=>'bool','name'=>'search','value'=>(int)$data['search']))}
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