{template 'dialog.header.php'}

	{form::header()}
		<table class="field">
			<tbody>
			<tr>
				<td class="label">{form::label(t('权限名称'),'name',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'class'=>'medium','maxlength'=>32,'required'=>'required'))}
				</td>
			</tr>
			{if empty($data['controller'])}
			<tr>
				<td class="label">{form::label(t('应用'),'app',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'app','value'=>$data['app'],'class'=>'medium','maxlength'=>32,'required'=>'required'))}
					{form::tips(t('权限对应的应用编号'))}
				</td>
			</tr>
			{elseif empty($data['action'])}
			<tr>
				<td class="label">{form::label(t('应用'),'app',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'app','value'=>$data['app'],'class'=>'medium readonly','maxlength'=>32,'readonly'=>'readonly'))}
					{form::tips(t('权限对应的应用'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('控制器'),'controller',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'controller','value'=>$data['controller'],'class'=>'medium','maxlength'=>32,'required'=>'required'))}
					{form::tips(t('权限对应的控制器'))}
				</td>
			</tr>
			{else}
			<tr>
				<td class="label">{form::label(t('应用'),'app',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'app','value'=>$data['app'],'class'=>'medium readonly','maxlength'=>32,'readonly'=>'readonly'))}
					{form::tips(t('权限对应的应用编号'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('控制器'),'controller',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'controller','value'=>$data['controller'],'class'=>'medium readonly','maxlength'=>32,'readonly'=>'readonly'))}
					{form::tips(t('权限对应的控制器'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('动作'),'action',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'action','value'=>$data['action'],'class'=>'medium','maxlength'=>255,'required'=>'required'))}
					{form::tips(t('权限对应的动作，多个动作可以使用英文逗号隔开'))}
				</td>
			</tr>
			{/if}
			</tbody>
		</table>
	{form::footer()}

<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
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