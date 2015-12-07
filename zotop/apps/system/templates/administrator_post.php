{template 'header.php'}

{template 'system/system_side.php'}


<div class="main side-main">
	<div class="main-header">
		<div class="goback"><a href="javascript:history.go(-1);"><i class="fa fa-angle-left"></i> <span>{t('返回')}</span></a></div>	
		<div class="title">{$title}</div>
	</div><!-- main-header -->
	{form::header()}
	<div class="main-body scrollable">

		<div class="container-fluid">

			<fieldset class="form-horizontal">
				<div class="form-title">{t('账户密码')}</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('用户名'),'username',true)}</div>
					<div class="col-sm-5">
					{if $data['username']}
						<p class="form-control-static"><b>{$data['username']}</b></p>
					{else}
						{form::field(array('type'=>'text','name'=>'username','value'=>$data['username'],'minlength'=>2,'maxlength'=>32,'required'=>'required','remote'=>u('system/administrator/check/username','ignore='.$data['username'])))}
						{form::tips(t('用户名长度在2-32位之间，允许英文、数字和下划线，不能含有特殊字符'))}
					{/if}
					</div>
				</div>
				{if empty($data['password'])}
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('用户密码'),'password',true)}</div>
					<div class="col-sm-5">
						{form::field(array('type'=>'password','name'=>'password','minlength'=>6,'maxlength'=>20,'required'=>'required'))}
						{form::tips(t('请输入您要设置的新密码'))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('密码确认'),'password2',true)}</div>
					<div class="col-sm-5">
						{form::field(array('type'=>'password','name'=>'password2','equalto'=>'#password','minlength'=>6,'maxlength'=>20,'required'=>'required'))}
						{form::tips(t('为确保安全，请再次输入新密码'))}
					</div>
				</div>
				{else}
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('新密码'),'password',false)}</div>
					<div class="col-sm-5">
						{form::field(array('type'=>'password','name'=>'password','minlength'=>6,'maxlength'=>20))}
						{form::tips(t('请输入您要设置的新密码，不修改密码请留空'))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('密码确认'),'password2',false)}</div>
					<div class="col-sm-5">
						{form::field(array('type'=>'password','name'=>'password2','equalto'=>'#password','minlength'=>6,'maxlength'=>20))}
						{form::tips(t('为确保安全，请再次输入新密码，不修改密码请留空'))}
					</div>
				</div>
				{/if}

			</fieldset>
			<fieldset class="form-horizontal">
				<div class="form-title">{t('用户资料')}</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('昵称'),'nickname',true)}</div>
					<div class="col-sm-5">
						{form::field(array('type'=>'text','name'=>'nickname','value'=>$data['nickname'],'minlength'=>2,'maxlength'=>32,'required'=>'required','remote'=>u('system/administrator/check/nickname','ignore='.$data['nickname'])))}
						{form::tips(t('前台显示的昵称'))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('电子邮件'),'email',true)}</div>
					<div class="col-sm-5">
						{form::field(array('type'=>'email','name'=>'email','value'=>$data['email'],'required'=>'required','remote'=>u('system/administrator/check/email','ignore='.$data['email'])))}
					</div>
				</div>

				{if $data['id']!=1}
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('角色'),'groupid',false)}</div>
					<div class="col-sm-5">
					{form::field(array('type'=>'radio','options'=>m('system.role.getoptions'),'name'=>'groupid','value'=>$data['groupid'],'column'=>1))}
					</div>
				</div>
				{/if}

				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('真实姓名'),'realname',true)}</div>
					<div class="col-sm-5">
						{form::field(array('type'=>'text','name'=>'realname','value'=>$data['realname'],'minlength'=>2,'maxlength'=>20,'required'=>'required'))}
					</div>
				</div>
			</fieldset>

		</div>

	</div><!-- main-body -->
	<div class="main-footer">
		{field type="submit" value="t('保存')"}
		{field type="cancel" value="t('取消')"}
	</div><!-- main-footer -->
	{form::footer()}

</div><!-- main -->

<script type="text/javascript">
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();

			$(form).find('.submit').button('loading');
			$.loading();

			$.post(action, data, function(msg){

				if ( !msg.state ){
					$(form).find('.submit').button('reset');
				}

				$.msg(msg);
			},'json');
		}});
	});
</script>

{template 'footer.php'}