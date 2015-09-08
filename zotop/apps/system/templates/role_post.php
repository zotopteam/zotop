{template 'dialog.header.php'}		
	
	{form::header()}
		<div class="container-fluid">
			<div class="form-horizontal">
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('角色名称'),'name',true)}</div>
					<div class="col-sm-12">
						{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'maxlength'=>32,'required'=>'required'))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('角色说明'),'description',true)}</div>
					<div class="col-sm-12">
						{form::field(array('type'=>'textarea','name'=>'description','value'=>$data['description'],'maxlength'=>250,'required'=>'required'))}
					</div>
				</div>

				{if $data['listorder']}
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('排序'),'listorder',true)}</div>
					<div class="col-sm-12">
						{form::field(array('type'=>'number','name'=>'listorder','value'=>$data['listorder'],'required'=>'required'))}
						{form::tips(t('排序按照设定值顺序排列，越大越靠后'))}
					</div>
				</div>
				{/if}

				{if $data['id']!=1}
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('禁用'),'disabled',true)}</div>
					<div class="col-sm-12">
						{form::field(array('type'=>'bool','name'=>'disabled','value'=>$data['disabled']))}
					</div>
				</div>
				{/if}
			</div>
		</div>
	{form::footer()}

<script type="text/javascript">

	// 对话框设置
	$dialog.callbacks['ok'] = function(){
		$('form.form').submit();
		return false;
	};

	// 表单提交
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$.loading();
			$.post(action, data, function(msg){
				if( msg.state ){
					$dialog.close();
				}
				$.msg(msg);
			},'json');
		}});
	});
</script>

{template 'dialog.footer.php'}