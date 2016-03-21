{template 'header.php'}
<div class="side">
	{template 'member/admin_side.php'}
</div>


<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div><!-- main-header -->
	{form class="form-horizontal"}
	<div class="main-body scrollable">
		{form::field(array('type'=>'hidden','name'=>'type','value'=>$data['type'],'required'=>'required'))}
		{form::field(array('type'=>'hidden','name'=>'length','value'=>$data['length'],'required'=>'required'))}
		{form::field(array('type'=>'hidden','name'=>'modelid','value'=>$data['modelid'],'required'=>'required'))}

		<div class="container-fluid">

			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('控件'),'control',true)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'select','name'=>'control','value'=>$data['control'],'options'=>$control_options,'class'=>'short'))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('字段名'),'name',true)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'pattern'=>'^[a-z]$1[a-z0-9_]{0,18}[a-z0-9]$1$','required'=>'required'))}
					{form::field(array('type'=>'hidden','name'=>'_name','value'=>$data['name']))}
					{form::tips('由小写英文字母、数字和下划线组成，并且仅能字母开头，不以下划线结尾')}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('标签名'),'label',true)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'text','name'=>'label','value'=>$data['label'],'required'=>'required'))}
					{form::tips('可读名称，如“名称”，“地址”')}
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('输入提示'),'tips',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'text','name'=>'tips','value'=>$data['tips']))}
					{form::tips('显示在字段别名下方作为表单输入提示')}
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('默认值'),'default',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'text','name'=>'default','value'=>$data['default']))}
					{form::tips('多个默认值用英文逗号“,”隔开')}
				</div>
			</div>

			<!-- 字段类型相关参数，从空间设定的模板加载HTML -->
			<div id="settings">
			</div>
			<!-- /字段类型相关参数 -->

			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('注册时显示'),'base',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'bool','name'=>'base','value'=>$data['base']))}
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('禁用'),'disabled',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'bool','name'=>'disabled','value'=>$data['disabled']))}
				</div>
			</div>
		</div>
	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}

		{form::field(array('type'=>'button','value'=>t('取消'), 'onclick'=>'history.go(-1)'))}
	</div><!-- main-footer -->
	{/form}

</div><!-- main -->


	<script type="text/javascript">
		var controls = {json_encode($controls)};

		// 显示相关选项
		function show_settings(control){

			var data = {json_encode($data)};
			data.control = control;

			$('#settings').load("{U('member/field/settings')}", data, function(){

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
				$(form).find('.submit').button('loading');
				$.loading();
				$.post(action,data,function(msg){
					if( !msg.state ){
						$(form).find('.submit').button('reset');
					}
					$.msg(msg);
				},'json');
			}});
		});
	</script>

{template 'footer.php'}