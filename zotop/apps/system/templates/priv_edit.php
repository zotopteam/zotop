{template 'dialog.header.php'}

	{form::header()}
		<div class="container-fluid">
		
			<div class="form-horizontal">
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('权限名称'),'name',true)}</div>
				<div class="col-sm-10">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'class'=>'medium','maxlength'=>32,'required'=>'required'))}
				</div>
			</div>
			{if empty($data['controller'])}
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('应用'),'app',true)}</div>
				<div class="col-sm-10">
					{form::field(array('type'=>'text','name'=>'app','value'=>$data['app'],'class'=>'medium','maxlength'=>32,'required'=>'required'))}
					{form::tips(t('权限对应的应用编号'))}
				</div>
			</div>
			{elseif empty($data['action'])}
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('应用'),'app',true)}</div>
				<div class="col-sm-10">
					{form::field(array('type'=>'text','name'=>'app','value'=>$data['app'],'class'=>'medium readonly','maxlength'=>32,'readonly'=>'readonly'))}
					{form::tips(t('权限对应的应用'))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('控制器'),'controller',true)}</div>
				<div class="col-sm-10">
					{form::field(array('type'=>'text','name'=>'controller','value'=>$data['controller'],'class'=>'medium','maxlength'=>32,'required'=>'required'))}
					{form::tips(t('权限对应的控制器'))}
				</div>
			</div>
			{else}
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('应用'),'app',true)}</div>
				<div class="col-sm-10">
					{form::field(array('type'=>'text','name'=>'app','value'=>$data['app'],'class'=>'medium readonly','maxlength'=>32,'readonly'=>'readonly'))}
					{form::tips(t('权限对应的应用编号'))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('控制器'),'controller',true)}</div>
				<div class="col-sm-10">
					{form::field(array('type'=>'text','name'=>'controller','value'=>$data['controller'],'class'=>'medium readonly','maxlength'=>32,'readonly'=>'readonly'))}
					{form::tips(t('权限对应的控制器'))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('动作'),'action',true)}</div>
				<div class="col-sm-10">
					{form::field(array('type'=>'text','name'=>'action','value'=>$data['action'],'class'=>'medium','maxlength'=>255,'required'=>'required'))}
					{form::tips(t('权限对应的动作，多个动作可以使用英文逗号隔开'))}
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