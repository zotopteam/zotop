{template 'header.php'}
<div class="side">
	{template 'member/admin_side.php'}
</div>


<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div><!-- main-header -->
	{form class="form-horizontal"}
	<div class="main-body scrollable">
		{form::field(array('type'=>'hidden','name'=>'modelid','value'=>$data['modelid'],'required'=>'required'))}
		<div class="container-fluid">
			<div class="form-title">{t('基本信息')}</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('用户名'),'username',true)}</div>
				<div class="col-sm-8">
				{if $data['username']}
					<div class="field-text"><b>{$data['username']}</b></div>
				{else}
					{form::field(array('type'=>'text','name'=>'username','value'=>$data['username'],'minlength'=>2,'maxlength'=>32,'required'=>'required','remote'=>u('member/admin/check/username','ignore='.$data['username'])))}
					{form::tips(t('用户名长度在2-32位之间，允许英文、数字和下划线，不能含有特殊字符'))}
				{/if}
				</div>
			</div>
			{if empty($data['password'])}
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('用户密码'),'password',true)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'password','name'=>'password','minlength'=>6,'maxlength'=>20,'required'=>'required'))}
					{form::tips(t('请输入您要设置的新密码'))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('密码确认'),'password2',true)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'password','name'=>'password2','equalto'=>'#password','minlength'=>6,'maxlength'=>20,'required'=>'required'))}
					{form::tips(t('为确保安全，请再次输入新密码'))}
				</div>
			</div>
			{else}
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('新密码'),'password',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'password','name'=>'password','minlength'=>6,'maxlength'=>20))}
					{form::tips(t('请输入您要设置的新密码，不修改密码请留空'))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('密码确认'),'password2',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'password','name'=>'password2','equalto'=>'#password','minlength'=>6,'maxlength'=>20))}
					{form::tips(t('为确保安全，请再次输入新密码，不修改密码请留空'))}
				</div>
			</div>
			{/if}
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('昵称'),'nickname',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'hidden','name'=>'_nickname','value'=>$data['nickname']))}
					{form::field(array('type'=>'text','name'=>'nickname','value'=>$data['nickname'],'minlength'=>2,'maxlength'=>32,'remote'=>u('member/admin/check/nickname','ignore='.$data['nickname'])))}
					{form::tips(t('前台显示的昵称'))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('电子邮件'),'email',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'hidden','name'=>'_email','value'=>$data['email']))}
					{form::field(array('type'=>'email','name'=>'email','value'=>$data['email'],'remote'=>u('member/admin/check/email','ignore='.$data['email'])))}
					{if $data['id']} {form::tips(t('邮箱修改后需要重启激活'))} {/if}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('手机号码'),'mobile',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'hidden','name'=>'_mobile','value'=>$data['mobile']))}
					{form::field(array('type'=>'mobile','name'=>'mobile','value'=>$data['mobile'],'remote'=>u('member/admin/check/mobile','ignore='.$data['mobile'])))}
				</div>
			</div>			
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('会员组'),'groupid',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'select','options'=>$groups,'name'=>'groupid','value'=>$data['groupid']))}
				</div>
			</div>

			<div class="form-title">{t('高级信息')}</div>
			{loop $fields $f}
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label($f['label'], $f['for'], $f['required'])}</div>
				<div class="col-sm-8">
					{form::field($f['field'])}
					{form::tips($f['tips'])}
				</div>
			</div>
			{/loop}

		</div>

	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}
		{form::field(array('type'=>'button','value'=>t('取消'), 'onclick'=>'history.go(-1)'))}
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