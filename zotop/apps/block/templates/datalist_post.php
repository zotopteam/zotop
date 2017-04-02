{template 'dialog.header.php'}

<div class="main scrollable">
{form data="$data"}

	{loop $block.fields $k $f}

		{if $f.show and $f.name='data['.$f.name.']'}
		<div class="form-group">
		
			{form::label($f.label, $f.name, $f.required=='required')}
			
			{if $f.type == 'title'}
				{form::field(array_merge($f,array('stylefield'=>'data[style]')))}
			{else}
				{form::field($f)}
			{/if}
			
		</div>
		{/if}

	{/loop}

	{field type="hidden" name="blockid" required="required"}
	{field type="hidden" name="dataid" required="required"}

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
					parent.location.reload();
					$dialog.close();
				}

				$.msg(msg);

			},'json');
		}});
	});

</script>
{template 'dialog.footer.php'}