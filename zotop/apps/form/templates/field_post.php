{template 'header.php'}
<div class="side">
	{template 'form/admin_side.php'}
</div>

{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
		{form::field(array('type'=>'hidden','name'=>'formid','value'=>$data['formid'],'required'=>'required'))}
		{form::field(array('type'=>'hidden','name'=>'type','value'=>$data['type'],'required'=>'required'))}
		{form::field(array('type'=>'hidden','name'=>'length','value'=>$data['length'],'required'=>'required'))}

		<table class="field">
			<tr>
				<td class="label">{form::label(t('控件类型'),'control',true)}</td>
				<td class="input">
					{form::field(array('type'=>'select','name'=>'control','value'=>$data['control'],'options'=>m('form.field.control_options'),'class'=>'short'))}
				</td>
			</tr>
			
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
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'pattern'=>'^[a-z]{1}[a-z0-9_]{0,18}[a-z0-9]{1}$','required'=>'required'))}
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
			<div id="settings">	</div>

			<!-- /字段类型相关参数 -->
			<table class="field">
			<tr>
				<td class="label">{form::label(t('控件样式'),'settings[style]',false)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'settings[style]','value'=>$data['settings']['style']))}
					{form::tips('定义控件的[style]样式，如：width:200px;height:300px;')}
				</td>
			</tr>

			<tr>
				<td class="label">{form::label(t('不能为空'),'notnull',false)}</td>
				<td class="input">
					{form::field(array('type'=>'bool','name'=>'notnull','value'=>(int)$data['notnull']))}

					{form::tips('当录入数据时该字段是否不能为空')}
				</td>
			</tr>
			<tr class="extend unique">
				<td class="label">{form::label(t('值唯一'),'unique',false)}</td>
				<td class="input">
					
					{form::field(array('type'=>'bool','name'=>'unique','value'=>(int)$data['unique']))}
					
					{form::tips('录入的数据需要全局唯一值')}

				</td>
			</tr>

			<tr>
				<td class="label">{form::label(t('前台发布'),'base',false)}</td>
				<td class="input">
					{form::field(array('type'=>'bool','name'=>'post','value'=>$data['post']))}
					{form::tips('当表单允许前台发布时是否显示该字段并允许录入数据')}
				</td>
			</tr>

			<tr class="extend list">
				<td class="label">{form::label(t('列表显示'),'list',false)}</td>
				<td class="input">
					{form::field(array('type'=>'bool','name'=>'list','value'=>(int)$data['list']))}

					{form::tips('字段是否在列表页面显示')}
				</td>
			</tr>			

			<tr class="extend show">
				<td class="label">{form::label(t('前台显示'),'show',false)}</td>
				<td class="input">
					{form::field(array('type'=>'bool','name'=>'show','value'=>$data['show']))}

					{form::tips('字段是否在详细页面显示')}
				</td>
			</tr>

			<tr class="extend search">
				<td class="label">{form::label(t('允许搜索'),'search',false)}</td>
				<td class="input">
					{form::field(array('type'=>'bool','name'=>'search','value'=>(int)$data['search']))}

					{form::tips('字段是否搜索')}
				</td>
			</tr>

			<tr class="extend order">
				<td class="label">{form::label(t('排序'),'search',false)}</td>
				<td class="input">
					{form::field(array('type'=>'radio','name'=>'order','options'=>array(''=>t('否'),'ASC'=>t('升序'),'DESC'=>t('降序')),'value'=>$data['order']))}
					{form::tips('列表数据是否根据该字段进行排序')}
				</td>
			</tr>										

		</table>
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

			$('tr.extend').show();
			$('#settings').load("{U('form/field/settings')}", data, function(){
				$(this).find(".inline-checkboxes").checkboxes();
				$(this).find(".inline-radios").radios();
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