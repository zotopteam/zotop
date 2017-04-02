{template 'dialog.header.php'}
<div class="main scrollable">

	{form::header()}
		<div class="container-fluid">
			<div class="form-group">
				{form::label(t('数据表名称'),'name',true)}
				
				{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'required'=>'required','remote'=>u('developer/project/checktable/'.$data['name'])))}
				
				{form::tips(t('不含前缀的数据表名称，只允许英文数字和下划线'))}				
			</div>
			<div class="form-group">
				{form::label(t('数据表注释'),'comment',true)}
				
				{form::field(array('type'=>'textarea','name'=>'comment','value'=>$data['comment'],'required'=>'required'))}
				
				{form::tips(t('数据表注释信息'))}				
			</div>
			<div class="form-group">
				{form::label(t('数据表类型'),'engine',true)}
				
				{form::field(array('type'=>'radio','options'=>array('MyISAM'=>t('MyISAM'),'InnoDB'=>t('InnoDB')),'name'=>'engine','value'=>$data['engine']))}
				
				{form::tips(t('数据表类型，合理选择有助于提升效率'))}				
			</div>
		</div>
	{form::footer()}
	
</div>

<script type="text/javascript">

	// 对话框设置
	$dialog.callbacks['ok'] = function(){
		$('form.form').submit();
		return false;
	};

	// 表单验证
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$.loading();
			$.post(action,data,function(msg){
				msg.state && $dialog.close();
				$.msg(msg);
			},'json');
		}});
	});
</script>

{template 'dialog.footer.php'}