{template 'header.php'}

{template 'system/mine_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div><!-- main-header -->

	{form class="form-horizontal"}
	<div class="main-body scrollable">

			<div class="container-fluid">

				<div class="form-group">
					<label class="col-sm-2 control-label" for="username">{t('用户名')}</label>
					<div class="col-sm-8">
						<div class="form-control-static">{$data['username']}</div>
					</div>					
				</div>

				<div class="form-group">
					<label class="col-sm-2 control-label required" for="password">{t('原密码')}</label>
					<div class="col-sm-8">
						
					{form::field(array('type'=>'password','name'=>'password','value'=>'','minlength'=>6,'maxlength'=>20,'required'=>'required','remote'=>u('system/mine/checkpassword')))}
					{form::tips(t('请输入当前正在使用的密码'))}
					</div>
				
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label required" for="newpassword">{t('新密码')}</label>
					<div class="col-sm-8">
					
					{form::field(array('type'=>'password','name'=>'newpassword','value'=>$data['newpassword'],'minlength'=>6,'maxlength'=>20,'required'=>'required'))}
					{form::tips(t('请输入您要设置的新密码'))}
					</div>
					
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label required" for="newpassword2">{t('确认新密码')}</label>
					<div class="col-sm-8">					
					{form::field(array('type'=>'password','name'=>'newpassword2','value'=>$data['newpassword2'],'equalto'=>'#newpassword','minlength'=>6,'maxlength'=>20,'required'=>'required'))}
					{form::tips(t('为确保安全，请再次输入新密码'))}
					</div>
				</div>
				
			</div>
	</div><!-- main-body -->
	<div class="main-footer">
		{field type="submit" value="t('保存')"}
	</div><!-- main-footer -->
	{/form}
</div><!-- main -->

<script type="text/javascript">
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$(form).find('.submit').button('loading');
			$.loading();
			$.post(action, data, function(msg){
				$.msg(msg);

				if ( msg.state ){
					$(form).get(0).reset();
				}

				$(form).find('.submit').button('reset');
			},'json');
		}});
	});
</script>
{template 'footer.php'}