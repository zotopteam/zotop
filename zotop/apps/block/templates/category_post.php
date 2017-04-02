{template 'dialog.header.php'}
<div class="main scrollable">

	{form data="$data"}

		<div class="form-group">
			<label for="name" class="required">{t('名称')}</label>				
			{field type="text" name="name" required="required"}			
		</div>
		<div class="form-group">
			<label for="description" class="required">{t('说明')}</label>
			{field type="textarea" name="description" required="required"}
		</div>

	{/form}

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

				if( msg.state ){
					$dialog.close();
				}

				$.msg(msg);

			},'json');
		}});
	});
</script>
{template 'dialog.footer.php'}