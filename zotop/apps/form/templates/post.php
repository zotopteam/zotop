{template 'header.php'}


{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
		{form::field(array('type'=>'hidden','name'=>'formid','value'=>$data['formid'],'required'=>'required'))}

		<table class="field">
			<tbody>
			{loop $fields $f}
			<tr>
				<td class="label">{form::label($f['label'], $f['for'], $f['required'])}</td>
				<td class="input">
					{form::field($f['field'])}
					{form::tips($f['tips'])}
				</td>
			</tr>
			{/loop}
			</tbody>
		</table>

	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}

		{form::field(array('type'=>'button','value'=>t('取消'), 'onclick'=>'history.go(-1)'))}
	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}

<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$(form).find('.submit').disable(true);
			$.loading();
			$.post(action, data, function(msg){

				if ( !msg.state ){
					$(form).find('.submit').disable(false);
				}

				$.msg(msg);
			},'json');
		}});
	});
</script>

{template 'footer.php'}