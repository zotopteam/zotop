{template 'header.php'}
{template 'form/admin_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="goback"><a href="javascript:history.go(-1);"><i class="fa fa-angle-left"></i><span>{t('返回')}</span></a></div>
		<div class="title">{$title}</div>
	</div><!-- main-header -->

	{form::header()}
	<div class="main-body scrollable">
		{form::field(array('type'=>'hidden','name'=>'formid','value'=>$data['formid'],'required'=>'required'))}
		{form::field(array('type'=>'hidden','name'=>'type','value'=>$data['type'],'required'=>'required'))}
		{form::field(array('type'=>'hidden','name'=>'length','value'=>$data['length'],'required'=>'required'))}

		<div class="container-fluid">
			<div class="form-horizontal">

			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('控件类型'),'control',true)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'select','name'=>'control','value'=>$data['control'],'options'=>m('form.field.control_options'),'class'=>'short'))}
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
				<div class="col-sm-2 control-label">{form::label(t('字段名'),'name',true)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'fieldname'=>'true','required'=>'required'))}
					{form::field(array('type'=>'hidden','name'=>'_name','value'=>$data['name']))}
					{form::tips('由小写英文字母、数字和下划线组成，并且仅能字母开头，不以下划线结尾')}
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

			<!-- 字段类型相关参数，从设定的模板加载HTML -->
			<div id="settings">	</div>


			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('不能为空'),'notnull',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'bool','name'=>'notnull','value'=>(int)$data['notnull']))}

					{form::tips('当录入数据时该字段是否不能为空')}
				</div>
			</div>


			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('发布页显示'),'base',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'bool','name'=>'post','value'=>$data['post']))}
					{form::tips('当表单允许发布时是否显示该字段并允许录入数据')}
				</div>
			</div>

			<div class="form-group form-extend list">
				<div class="col-sm-2 control-label">{form::label(t('列表页显示'),'list',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'bool','name'=>'list','value'=>(int)$data['list']))}

					{form::tips('字段是否在列表页面显示')}
				</div>
			</div>

			<div class="form-group form-extend show">
				<div class="col-sm-2 control-label">{form::label(t('详细页显示'),'show',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'bool','name'=>'show','value'=>$data['show']))}

					{form::tips('字段是否在详细页面显示')}
				</div>
			</div>


			<div class="form-group form-extend order">
				<div class="col-sm-2 control-label">{form::label(t('排序'),'search',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'radio','name'=>'order','options'=>array(''=>t('否'),'ASC'=>t('升序'),'DESC'=>t('降序')),'value'=>$data['order']))}
					{form::tips('列表数据是否根据该字段进行排序')}
				</div>
			</div>
		</div>
		</div>
	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}
		{form::field(array('type'=>'button','value'=>t('取消'), 'onclick'=>'history.go(-1)'))}
	</div><!-- main-footer -->
	{form::footer()}

</div><!-- main -->

<script type="text/javascript">
	var controls = {json_encode($controls)};

	// 显示相关选项
	function show_settings(control){

		var data = {json_encode($data)};
			data.control = control;

		$('.form-extend').show();
		$('#settings').load("{U('form/field/settings')}", data, function(){
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

		$.validator.addMethod("fieldname", function(value, element) {
			return this.optional(element) || /^[a-z][a-z0-9_]{0,18}[a-z0-9]$/.test(value);
		}, "{t('长度2-20，允许小写英文字母、数字和下划线，并且仅能字母开头，不以下划线结尾')}");

		
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