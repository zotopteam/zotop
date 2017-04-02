{template 'dialog.header.php'}
<div class="main scrollable">

	{form::header()}


		<div class="form-group">
			{form::label(t('类型'),'type',true)}				
			{form::field(array('type'=>'radio','options'=>m('kefu.kefu.types'),'name'=>'type','value'=>$data['type']))}				
		</div>

		<div id="form-options">
			
		</div>

		<div class="form-group">
			{form::label(t('禁用'),'disabled',false)}
			
				{form::field(array('type'=>'bool','name'=>'disabled','value'=>(int)$data['disabled']))}
			
		</div>

	{form::footer()}

</div>
<script type="text/javascript">

	// 对话框设置
	$dialog.callbacks['ok'] = function(){
		$('form.form').submit();
		return false;
	};

	function showoptions(type){
		var data = {json_encode($data)};
			data.type = type || data.type;

		$('#form-options').html('<i class="fa fa-spinner fa-spin"></i>').load("{u('kefu/admin/options')}",data);
	}

	$(function(){
		showoptions();

		$('[name=type]').on('click',function(){
			showoptions($(this).val());
		});
	});

	$(function(){
		$('form.form').validate({submitHandler:function(form){
			$.loading();
			$.post($(form).attr('action'), $(form).serialize(), function(msg){
				if( msg.state ){
					$dialog.close();
				}
				$.msg(msg);
			},'json');
		}});
	});
</script>
{template 'dialog.footer.php'}