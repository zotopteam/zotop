{template 'dialog.header.php'}

{form::header()}
	{form::field(array('type'=>'template_editor','name'=>'content','value'=>$content))}
{form::footer()}
<style>
.template-editor{width:100%;border:0px none;}
.template-editor-head{height:0;border-bottom:solid 0px #d0d0d0;display:block;}
.template-editor-body{height:500px;overflow:auto;}
.template-editor-body textarea{height:100%;}
</style>


<script type="text/javascript" src="{A('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">

	// 对话框设置
	$dialog.callbacks['ok'] = function(){
		$('form.form').submit();
		return false;
	};

	$dialog.title('{t('编辑：%s',$file)}');


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