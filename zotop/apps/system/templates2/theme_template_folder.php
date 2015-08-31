{template 'dialog.header.php'}

	{form::header()}
		<table class="field">
			<tbody>
			<tr>
				<td class="label">{form::label(t('名称'),'name',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'required'=>'required'))}
					{form::tips(t('只能输入由数字、26个英文字母或者下划线组成的字符串'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('注释'),'note',true)}</td>
				<td class="input">
					{form::field(array('type'=>'textarea','name'=>'note','value'=>$data['note'],'required'=>'required'))}
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
					$dialog.opener.location.reload();
					$dialog.close();
				}

				$.msg(msg);
			},'json');
		}});
	});
</script>

{template 'dialog.footer.php'}