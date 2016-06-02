{template 'member/header.php'}


{form class="form-horizontal"}

	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-title">{$title}</div>
		</div>
		<div class="panel-body">			
				
				{if $data.password}
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('原密码'),'password',true)}</div>
					<div class="col-sm-8">
						{form::field(array('type'=>'password','name'=>'password','value'=>'','minlength'=>6,'maxlength'=>20,'required'=>'required','remote'=>u('member/mine/check_password')))}
						{form::tips(t('请输入当前正在使用的密码'))}
					</div>
				</div>
				{/if}

				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('新密码'),'newpassword',true)}</div>
					<div class="col-sm-8">
						{form::field(array('type'=>'password','name'=>'newpassword','value'=>$data['newpassword'],'minlength'=>6,'maxlength'=>20,'required'=>'required'))}
						{form::tips(t('请输入您要设置的新密码'))}
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('确认新密码'),'newpassword2',true)}</div>
					<div class="col-sm-8">
						{form::field(array('type'=>'password','name'=>'newpassword2','value'=>$data['newpassword2'],'equalto'=>'#newpassword','minlength'=>6,'maxlength'=>20,'required'=>'required'))}
						{form::tips(t('为确保安全，请再次输入新密码'))}
					</div>
				</div>			
			
		</div><!-- panel-body -->
		<div class="panel-footer">
			{field type="submit" value="t('提交')"}
		</div>
	</div>

{/form}	

<script type="text/javascript">
	// 登录
	$(function(){
		$('form.form').validate({
			submitHandler:function(form){
				var action = $(form).attr('action');
				var data = $(form).serialize();
				$(form).find('.submit').button('loading');
				$.post(action, data, function(msg){
					$(form).find('.submit').button('reset');
					$.msg(msg);
				},'json');
			}
		});
	});
</script>
{template 'member/footer.php'}