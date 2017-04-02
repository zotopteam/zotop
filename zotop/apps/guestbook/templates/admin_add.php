{template 'dialog.header.php'}
<div class="main scrollable">

	{form::header()}

		<div class="form-group">
			{form::label(t('名字'),'name',true)}
			{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'required'=>'required'))}				
		</div>
		<div class="form-group">
			{form::label(t('邮箱'),'email')}				
			{form::field(array('type'=>'email','name'=>'email','value'=>$data['email']))}				
		</div>
		<div class="form-group">
			{form::label(t('内容'),'content',true)}				
			{form::field(array('type'=>'textarea','name'=>'content','value'=>$data['content'],'required'=>'required','minlength'=>c('guestbook.minlength'),'maxlength'=>c('guestbook.maxlength')))}
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