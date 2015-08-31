{template 'dialog.header.php'}

	{form::header()}
		<table class="field">
			<tbody>

			<tr>
				<td class="label">{form::label(t('角色名称'),'name',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'maxlength'=>32,'required'=>'required'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('角色说明'),'description',true)}</td>
				<td class="input">
					{form::field(array('type'=>'textarea','name'=>'description','value'=>$data['description'],'maxlength'=>250,'required'=>'required'))}
				</td>
			</tr>
			{if $data['listorder']}
			<tr>
				<td class="label">{form::label(t('排序'),'listorder',true)}</td>
				<td class="input">
					{form::field(array('type'=>'number','name'=>'listorder','value'=>$data['listorder'],'required'=>'required'))}
					{form::tips(t('排序按照设定值顺序排列，越大越靠后'))}
				</td>
			</tr>
			{/if}
			{if $data['id']!=1}
			<tr>
				<td class="label">{form::label(t('禁用'),'disabled',true)}</td>
				<td class="input">
					{form::field(array('type'=>'bool','name'=>'disabled','value'=>$data['disabled']))}
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