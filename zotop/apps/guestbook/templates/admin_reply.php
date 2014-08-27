{template 'dialog.header.php'}

	{form::header()}
		{form::field(array('type'=>'hidden','name'=>'name','value'=>$data['name']))}
		{form::field(array('type'=>'hidden','name'=>'email','value'=>$data['email']))}
		<table class="field">
			<tbody>
			<tr>
				<td class="label">{form::label(t('留言'),'content',true)}</td>
				<td class="input">
					{form::field(array('type'=>'textarea','name'=>'content','value'=>$data['content'],'class'=>'medium','required'=>'required','minlength'=>c('guestbook.minlength'),'maxlength'=>c('guestbook.maxlength')))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('回复'),'reply',true)}</td>
				<td class="input">
					{form::field(array('type'=>'textarea','name'=>'reply','value'=>$data['reply'],'class'=>'medium','required'=>'required'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('状态'),'status',false)}</td>
				<td class="input">
					{form::field(array('type'=>'radio','name'=>'status','value'=>'publish','options'=>$statuses))}
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

				if( msg.state ){
					$dialog.close();

					msg.onclose = function(){
						parent.location.reload();
					}
				}

				$.msg(msg);
			},'json');
		}});
	});
</script>
{template 'dialog.footer.php'}