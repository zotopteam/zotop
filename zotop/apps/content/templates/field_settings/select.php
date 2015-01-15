<table class="field">
	<tr>
		<td class="label">{form::label(t('选项列表'),'settings[options]',true)}</td>
		<td class="input">
			<?php
				if ( empty( $data['settings']['options'] ) )
				{
					$data['settings']['options'] = "选项名称A|选项值A
选项名称b|选项值b";
				}
			?>
			{form::field(array('type'=>'textarea','name'=>'settings[options]','value'=>$data['settings']['options'],'required'=>'required','style'=>'height:150px;line-height:25px;'))}
			{form::tips('每行一个，选项名称与选项值之间用竖线“|”隔开，如：<b>选项名称1|选项值1</b>')}
		</td>
	</tr>
	<tr>
		<td class="label">{form::label(t('数据类型'),'settings[_datatype]',true)}</td>
		<td class="input">

			<?php echo form::field(array(
				'type'		=> 'select',
				'options'	=> zotop::filter('member.field.datatype', array(
					'varchar-50'	=> t('字符串 VARCHAR(50)'),
					'varchar-100'	=> t('字符串 VARCHAR(100)'),
					'varchar-255'	=> t('字符串 VARCHAR(255)'),
					'char-20'		=> t('固定长度字符串 CHAR(20)'),
					'tinyint-3'		=> t('微型整数 TINYINT(3)'),
					'smallint-5'	=> t('小型整数 SMALLINT(5)'),
					'mediumint-8'	=> t('中等整数 MEDIUMINT(8)'),
					'int-10'		=> t('整数 INT(10)'),
				)),
				'id'		=> 'settings_datatype',
				'name'		=> 'settings[_datatype]',
				'value'		=> $data['settings']['_datatype']
			))?>

			{form::tips('请根据您的选项列表设置的选项值选择合适的数据类型及长度')}
		</td>
	</tr>
</table>

<!-- /字段类型相关参数 -->
<table class="field">
	<tr class="field-extend field-style none">
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
	<tr class="field-extend field-search none">
		<td class="label">{form::label(t('允许搜索'),'search',false)}</td>
		<td class="input">
			{form::field(array('type'=>'bool','name'=>'search','value'=>(int)$data['search']))}
		</td>
	</tr>
</table>

<script type="text/javascript">
$(function(){
	$('#settings_datatype').on('change',function(){
		var val = $(this).val();
			val = val.split('-');
		if( val.length == 2 ){
			//设置字段属性
			$('[name=type]').val(val[0]);
			$('[name=length]').val(val[1]);
		}
	});
})
</script>