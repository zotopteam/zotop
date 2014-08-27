{template 'dialog.header.php'}

	{form::header()}
		<div class="m20 ml0 mr0">
		<table class="field">
			<tbody>

			<tr>
				<td class="label">{form::label(t('IP 地址'),'ip',true)}</td>
				<td class="input">
					{form::field(array('type'=>'ip','name'=>'ip','value'=>$data['ip'],'maxlength'=>15,'required'=>'required','remote'=>u('system/ipbanned/check/ip','ignore='.$data['ip'])))}
					{form::tips(t('IP地址格式如：202.102.224.68'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('解封时间'),'description',true)}</td>
				<td class="input">
					{form::field(array('type'=>'datetime','name'=>'expires','value'=>$data['expires'],'min'=>ZOTOP_TIME,'required'=>'required'))}
				</td>
			</tr>
			</tbody>
		</table>
		</div>
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