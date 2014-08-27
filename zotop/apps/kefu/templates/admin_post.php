{template 'dialog.header.php'}

	{form::header()}
		<table class="field">
			<tbody>

			<tr>
				<td class="label">{form::label(t('类型'),'type',true)}</td>
				<td class="input">
					{form::field(array('type'=>'radio','options'=>m('kefu.kefu.types'),'name'=>'type','value'=>$data['type']))}
				</td>
			</tr>
			</tbody>
		</table>

		<div id="options"></div>

		<table class="field">
			<tbody>
			<tr>
				<td class="label">{form::label(t('禁用'),'disabled',false)}</td>
				<td class="input">
					{form::field(array('type'=>'bool','name'=>'disabled','value'=>(int)$data['disabled']))}
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

	function showoptions(type){
		var data = {json_encode($data)};
			data.type = type || data.type;

		$('#options').html('<i class="icon icon-loading" style="margin-left:120px;"></i>').load("{u('kefu/admin/options')}",data);
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