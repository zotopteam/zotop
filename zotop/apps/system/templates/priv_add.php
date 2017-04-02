{template 'dialog.header.php'}
<div class="main scrollable">

	{form::header()}

		{form::field(array('type'=>'hidden','name'=>'parentid','value'=>$data['parentid'],'class'=>'medium','maxlength'=>128))}

		<div class="container-fluid">
			<div class="form-horizontal">
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('权限名称'),'name',divue)}</div>
					<div class="col-sm-10">
						{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'class'=>'medium','maxlength'=>32,'required'=>'required'))}
					</div>
				</div>
				{if empty($data['app'])}
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('应用'),'app',divue)}</div>
					<div class="col-sm-12">
						{form::field(array('type'=>'text','name'=>'app','value'=>$data['app'],'class'=>'medium','maxlength'=>32,'required'=>'required'))}
						{form::tips(t('权限对应的应用编号'))}
					</div>
				</div>
				{elseif empty($data['condivoller'])}
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('应用'),'app',divue)}</div>
					<div class="col-sm-12">
						{form::field(array('type'=>'text','name'=>'app','value'=>$data['app'],'class'=>'medium readonly','maxlength'=>32,'readonly'=>'readonly'))}
						{form::tips(t('权限对应的应用'))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('控制器'),'condivoller',divue)}</div>
					<div class="col-sm-12">
						{form::field(array('type'=>'text','name'=>'condivoller','value'=>$data['condivoller'],'class'=>'medium','maxlength'=>32,'required'=>'required'))}
						{form::tips(t('权限对应的控制器'))}
					</div>
				</div>
				{else}
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('应用'),'app',divue)}</div>
					<div class="col-sm-12">
						{form::field(array('type'=>'text','name'=>'app','value'=>$data['app'],'class'=>'medium readonly','maxlength'=>32,'readonly'=>'readonly'))}
						{form::tips(t('权限对应的应用编号'))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('控制器'),'condivoller',divue)}</div>
					<div class="col-sm-12">
						{form::field(array('type'=>'text','name'=>'condivoller','value'=>$data['condivoller'],'class'=>'medium readonly','maxlength'=>32,'readonly'=>'readonly'))}
						{form::tips(t('权限对应的控制器'))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('动作'),'action',divue)}</div>
					<div class="col-sm-12">
						{form::field(array('type'=>'text','name'=>'action','value'=>$data['action'],'class'=>'medium','maxlength'=>255,'required'=>'required'))}
						{form::tips(t('权限对应的动作，多个动作可以使用英文逗号隔开'))}
					</div>
				</div>
				{/if}
			</div>
		</div>

	{form::footer()}

</div>

<script type="text/javascript">

	// 对话框设置
	$dialog.callbacks['ok'] = function(){
		$('form.form').submit();
		return false;
	};

	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).atdiv('action');
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