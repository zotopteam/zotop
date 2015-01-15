{template 'header.php'}
<div class="side">
	{template 'content/admin_side.php'}
</div>

{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">

		{form::field(array('type'=>'hidden','name'=>'modelid','value'=>$data['modelid'],'required'=>'required'))}
		{form::field(array('type'=>'hidden','name'=>'type','value'=>$data['type'],'required'=>'required'))}
		{form::field(array('type'=>'hidden','name'=>'length','value'=>$data['length'],'required'=>'required'))}

		<table class="field">
			{if !$data.system}
			<tr>
				<td class="label">{form::label(t('控件类型'),'control',true)}</td>
				<td class="input">
					{form::field(array('type'=>'select','name'=>'control','value'=>$data['control'],'options'=>m('content.field.control_options'),'class'=>'short'))}
				</td>
			</tr>
			{/if}
			<tr>
				<td class="label">{form::label(t('标签名'),'label',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'label','value'=>$data['label'],'required'=>'required'))}
					{form::tips('可读名称，如“名称”，“地址”')}
				</td>
			</tr>

			<tr>
				<td class="label">{form::label(t('字段名'),'name',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'pattern'=>'^[a-z]{1}[a-z0-9_]{0,18}[a-z0-9]{1}$','required'=>'required','readonly'=>$data['system']))}
					{form::field(array('type'=>'hidden','name'=>'_name','value'=>$data['name']))}
					{form::tips('由小写英文字母、数字和下划线组成，并且仅能字母开头，不以下划线结尾')}
				</td>
			</tr>


			<tr>
				<td class="label">{form::label(t('输入提示'),'tips',false)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'tips','value'=>$data['tips']))}
					{form::tips('显示在字段别名下方作为表单输入提示')}
				</td>
			</tr>

			<tr>
				<td class="label">{form::label(t('默认值'),'default',false)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'default','value'=>$data['default']))}
					{form::tips('多个默认值用英文逗号“,”隔开')}
				</td>
			</tr>
			</table>

			<!-- 字段类型相关参数，从设定的模板加载HTML -->
			<div id="settings">
			
			</div>

	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}

		{form::field(array('type'=>'button','value'=>t('取消'), 'onclick'=>'history.go(-1)'))}
	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}

	<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
	<script type="text/javascript">
		var controls = {json_encode($controls)};

		// 显示相关选项
		function show_settings(control){

			var data = {json_encode($data)};
				data.control = control;

			$('tr.field-extend').show();
			$('#settings').load("{U('content/field/settings')}", data, function(){
				$(this).find(".checkboxes").checkboxes();
				$(this).find(".radios").radios();
				$(this).find(".single-select").singleselect();		
			});
		}

		// 默认显示text相关选项
		show_settings('{$data['control']}');

		// 选择字段类型并显示相关选项
		$('[name=control]').on('change', function(){

			var control = $(this).val();

			//设置字段属性
			$('[name=type]').val(controls[control]['type']);
			$('[name=length]').val(controls[control]['length']);

			show_settings(control);
		});

		$(function(){
			$('form.form').validate({submitHandler:function(form){
				var action = $(form).attr('action');
				var data = $(form).serialize();
				$(form).find('.submit').disable(true);
				$.loading();
				$.post(action,data,function(msg){
					if( !msg.state ){
						$(form).find('.submit').disable(false);
					}
					$.msg(msg);
				},'json');
			}});
		});
	</script>

{template 'footer.php'}