{template 'dialog.header.php'}

	{form::header()}
		<table class="field">
			<tbody>

			<tr>
				<td class="label">{form::label(t('名字'),'name',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'required'=>'required'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('邮箱'),'email')}</td>
				<td class="input">
					{form::field(array('type'=>'email','name'=>'email','value'=>$data['email']))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('内容'),'content',true)}</td>
				<td class="input">
					{form::field(array('type'=>'textarea','name'=>'content','value'=>$data['content'],'required'=>'required','minlength'=>c('guestbook.minlength'),'maxlength'=>c('guestbook.maxlength')))}
				</td>
			</tr>
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
				$.msg(msg);
				if( msg.state ){
					$dialog.close();
				}
			},'json');
		}});
	});
</script>
{template 'dialog.footer.php'}