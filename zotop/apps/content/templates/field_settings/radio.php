
	<div class="form-group">
		<div class="col-sm-2 control-label">{form::label(t('选项列表'),'settings[options]',true)}</div>
		<div class="col-sm-8">
			<?php
				if ( empty( $data['settings']['options'] ) )
				{
					$data['settings']['options'] = "选项名称A|选项值A
选项名称b|选项值b";
				}
			?>
			{form::field(array('type'=>'textarea','name'=>'settings[options]','value'=>$data['settings']['options'],'required'=>'required','style'=>'height:150px;line-height:25px;'))}
			{form::tips('每行一个，选项名称与选项值之间用竖线“|”隔开，如：<b>选项名称1|选项值1</b>')}
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-2 control-label">{form::label(t('数据类型'),'settings[_datatype]',true)}</div>
		<div class="col-sm-5">

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
		</div>
	</div>


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