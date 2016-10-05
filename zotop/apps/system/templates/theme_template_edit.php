{template 'dialog.header.php'}

{form}
	{field type="template_editor" name="content" value="$content"}
{/form}

<style>
.body-dialog .form{padding:0;margin:0;height:100%;position:relative;}

.template-editor{width:100%;height:100%;border:0px none;}
.template-editor-head{height:0;border-bottom:solid 0px #d0d0d0;display:block;}
.template-editor-body{position:absolute;top:0;right:0;left:0;bottom:0;overflow:auto;}
.template-editor-body textarea{height:100%;}
</style>

<script type="text/javascript">

	// 对话框设置
	$dialog.callbacks['ok'] = function(){
		$('form.form').submit();
		return false;
	};

	$dialog.statusbar('{$file}');
	
	// 提交
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$.loading();
			$.post(action, data, function(msg){
				$.msg(msg);
			},'json');
		}});
	});
</script>


{template 'dialog.footer.php'}