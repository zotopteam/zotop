{template 'dialog.header.php'}


{form::header()}

		<table class="field">
			<tbody>
			<tr>
				<td class="label">{form::label(t('模型名称'),'name',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'required'=>'required'))}
					{form::tips(t('可读名称，如： 页面、文章、产品、下载'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('模型标识'),'id',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'id','value'=>$data['id'],'maxlength'=>32,'pattern'=>'^[a-z]{1}[a-z0-9]{0,31}$','required'=>'required','readonly'=>$data['id']))}
					{form::tips(t('模型唯一标识，如： page、article，只允许因为字符和数字，最大长度32位'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('内容页模板'),'template',false)}</td>
				<td class="input">				
					{form::field(array('type'=>'template','name'=>'template','value'=>$data['template']))}
					{form::tips(t('内容的详细页面模板，如果没有内容页（如“链接”模型）则无需填写此项'))}
				</td>
			</tr>

			<tr>
				<td class="label">{form::label(t('模型描述'),'description',true)}</td>
				<td class="input">
					{form::field(array('type'=>'textarea','name'=>'description','value'=>$data['description'],'required'=>'required'))}
				</td>
			</tr>			
			</tbody>
		</table>


{form::footer()}


<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			$.loading();
			$(form).find('.submit').disable(true);
			$.post($(form).attr('action'), $(form).serialize(), function(msg){
				if( !msg.state ){
					$(form).find('.submit').disable(false);
				}				
				$.msg(msg);
			},'json');
		}});
	});
</script>

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