{template 'dialog.header.php'}

	{form::header()}

		{form::field(array('type'=>'hidden','name'=>'name','value'=>$data['name']))}
		{form::field(array('type'=>'hidden','name'=>'email','value'=>$data['email']))}

			<div class="form-group">
				{form::label(t('留言'),'content',true)}				
				{form::field(array('type'=>'textarea','name'=>'content','value'=>$data['content'],'class'=>'medium','required'=>'required','minlength'=>c('guestbook.minlength'),'maxlength'=>c('guestbook.maxlength')))}
				
			</div>
			<div class="form-group">
				{form::label(t('回复'),'reply',true)}				
				{form::field(array('type'=>'textarea','name'=>'reply','value'=>$data['reply'],'class'=>'medium','required'=>'required'))}				
			</div>
			<div class="form-group">
				{form::label(t('状态'),'status',false)}				
				{form::field(array('type'=>'radio','name'=>'status','value'=>'publish','options'=>$statuses))}				
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