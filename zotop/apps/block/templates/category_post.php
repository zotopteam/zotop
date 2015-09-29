{template 'dialog.header.php'}

	{form::header()}

			<div class="form-group">
				{form::label(t('名称'),'name',true)}				
				{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'required'=>'required'))}				
			</div>
			<div class="form-group">
				{form::label(t('说明'),'description',false)}				
				{form::field(array('type'=>'textarea','name'=>'description','value'=>$data['description']))}				
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