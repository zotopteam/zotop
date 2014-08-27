{template 'dialog.header.php'}

	{form::header()}
		<input type="hidden" name="parentid" value="{$data['parentid']}"/>
		<input type="hidden" name="level" value="{$data['level']}"/>
		<table class="field">
			<tbody>
			<tr>
				<td class="label">{form::label(t('名称'),'name',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'required'=>'required'))}
					{form::tips('省、市、县或者区域名称')}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('编码'),'id',true)}</td>
				<td class="input">
					{form::field(array('type'=>'number','name'=>'id','value'=>$data['id'],'required'=>'required'))}
					{form::tips('推荐使用行政区划代码，如果没有则使用上级编码+2位自定义编码')}
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
					$dialog.close();
				}
				$.msg(msg);
			},'json');
		}});
	});
</script>
{template 'dialog.footer.php'}