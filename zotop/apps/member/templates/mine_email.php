{template 'member/header.php'}

{form class="form-horizontal"}
	{field type="hidden" name="modelid" value="$data.modelid"}
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-title">{$title}</div>
		</div>
		<div class="panel-body">

			
				
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('当前邮箱地址'),'')}</div>
					<div class="col-sm-8">
						{field type="static" value="$data.email"}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('新邮箱地址'),'email',true)}</div>
					<div class="col-sm-8">
						
						<div class="input-group">
						
							{field type="email" name="email" required="required" remote="u('member/mine/check/email','ignore='.$data['email'])"}
						
							<div class="input-group-addon">
								<a href="">{t('发送验证码')}</a>
							</div>
						</div>

					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('邮箱验证码'),'verifycode',true)}</div>
					<div class="col-sm-4">
						{form::field(array('type'=>'verifycode','name'=>'verifycode','value'=>'','required'=>'required'))}
					</div>
				</div>			

		</div><!-- panel-body -->
		<div class="panel-footer">
			{field type="submit" value="t('提交')"}
		</div>
	</div> <!-- panel -->
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