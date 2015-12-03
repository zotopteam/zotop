{template 'header.php'}

{template 'content/admin_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="goback"><a class="goback" href="{u('content/field/index/'.$data.modelid)}"><i class="fa fa-angle-left"></i><span>{t('返回')}</span></a></div>
		<div class="title">{$title} {if $data.label}: {$data.label}{/if}</div>
		<div class="breadcrumb hidden">
			<li><a href="{u('content/model')}">{t('模型管理')}</a></li>
			<li><a href="{u('content/field/index/'.$data.modelid)}">{t('字段管理')} : {m('content.model.get',$data.modelid,'name')}</a></li>
			<li>{$title} {if $data.label}: {$data.label}{/if}</li>
		</div>
	</div><!-- main-header -->

	{form::header()}
	<div class="main-body scrollable">
		<div class="container-fluid">

		{form::field(array('type'=>'hidden','name'=>'modelid','value'=>$data['modelid'],'required'=>'required'))}
		{form::field(array('type'=>'hidden','name'=>'system','value'=>$data['system'],'required'=>'required'))}

		{form::field(array('type'=>'hidden','name'=>'type','value'=>$data['type'],'required'=>'required'))}
		{form::field(array('type'=>'hidden','name'=>'length','value'=>$data['length'],'required'=>'required'))}

			<div class="form-horizontal">

				{if !$data.system}
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('控件类型'),'control',true)}</div>
					<div class="col-sm-5">
						{form::field(array('type'=>'select','name'=>'control','value'=>$data['control'],'options'=>m('content.field.control_options'),'class'=>'short'))}
					</div>
				</div>
				{/if}
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('标签名'),'label',true)}</div>
					<div class="col-sm-5">
						{form::field(array('type'=>'text','name'=>'label','value'=>$data['label'],'required'=>'required'))}
						{form::tips('可读名称，如“名称”，“地址”')}
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('字段名'),'name',true)}</div>
					<div class="col-sm-5">
						{if $data.system}
							{field type="text" name="name" value="$data.name" required="required" disabled="disabled" fieldname="true"}
						{else}
							{field type="text" name="name" value="$data.name" required="required" fieldname="true"}
						{/if}

						{field type="hidden" name="_name" value="$data.name"}

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
				<div id="settings">

				</div>

				<div class="field field-extend {if $data.name=='title'}none{/if}">

					<div class="form-group field-notnull">
						<div class="col-sm-2 control-label">{form::label(t('不能为空'),'notnull',false)}</div>
						<div class="col-sm-10">
							{form::field(array('type'=>'bool','name'=>'notnull','value'=>(int)$data['notnull']))}
						</div>
					</div>

					<div class="form-group field-post">
						<div class="col-sm-2 control-label">
							{form::label(t('前台投稿'),'post',false)}
							<i class="fa fa-help" title="{t('当表单允许前台发布时是否显示并允许录入数据')}"></i>
						</div>
						<div class="col-sm-10">
							{form::field(array('type'=>'bool','name'=>'post','value'=>$data['post']))}

						</div>
					</div>

					<div class="form-group field-base">
						<div class="col-sm-2 control-label">
							{form::label(t('基本信息'),'base',false)}
							<i class="fa fa-help" title="{t('基本信息将显示在添加编辑页面的主要位置')}"></i>
						</div>
						<div class="col-sm-10">
							{form::field(array('type'=>'bool','name'=>'base','value'=>$data['base']))}
						</div>
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

		$('.field-extend').show();
		$('#settings').load("{U('content/field/settings')}", data, function(){

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
			$(form).find('.submit').prop('disabled',true);
			$.loading();
			$.post(action,data,function(msg){
				if( !msg.state ){
					$(form).find('.submit').prop('disabled',false);
				}
				$.msg(msg);
			},'json');
		}});
	});
</script>

{template 'footer.php'}