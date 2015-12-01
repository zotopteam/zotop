{template 'dialog.header.php'}

{form}

	<div class="form-group">
		{form::label(t('模型名称'),'name',true)}
		
		{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'required'=>'required'))}
		
		{form::tips(t('可读名称，如： 页面、文章、产品、下载'))}				
	</div>
	<div class="form-group">
		{form::label(t('模型标识'),'id',true)}
		
		{if $data.id}

			{field type="text" name="id" value="$data.id" disabled="disabled"}
			
		{else}
			
			{field type="text" name="id" value="$data.id" maxlength="32" identifier="true" required="required"}

		{/if}
		
		{form::tips(t('模型唯一标识，如： page、article，只允许英文字符和数字，最大长度32位'))}
	</div>
	<div class="form-group">
		{form::label(t('模型描述'),'description',true)}
		
		{form::field(array('type'=>'textarea','name'=>'description','value'=>$data['description'],'required'=>'required'))}
		
		{form::tips(t('模型说明，最大255个字符'))}
		
	</div>
	<div class="form-group">
		{form::label(t('内容页模板'),'template',false)}
		
		{form::field(array('type'=>'template','name'=>'template','value'=>$data['template']))}

		{form::tips(t('内容的详细页面模板，如果没有内容页（如“链接”模型）则无需填写此项'))}
		
	</div>

{/form}

<script type="text/javascript">

	// 对话框设置
	$dialog.callbacks['ok'] = function(){
		$('form.form').submit();
		return false;
	};

	$(function(){
		
		$.validator.addMethod("identifier", function(value, element) {
			return this.optional(element) || /^[a-z]$1[a-z0-9]{0,31}$/.test(value);
		}, "{t('0-32位长度的英文字符和数字，且不能以数字开头')}");

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