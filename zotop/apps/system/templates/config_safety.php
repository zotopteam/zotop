{template 'header.php'}

{template 'system/system_side.php'}


<div class="main side-main">
	<div class="main-header">
		<div class="title">{t('全局设置')}</div>
		<ul class="nav nav-tabs tabdropable">
			{loop $navbar $k $n}
			<li{if ZOTOP_ACTION == $k} class="active"{/if}>
				<a href="{$n.href}"><i class="{$n.icon}"></i> <span>{$n.text}</span></a>
			</li>
			{/loop}
		</ul>
	</div><!-- main-header -->

	{form::header()}
	<div class="main-body scrollable">
		<div class="container-fluid">	

			<fieldset class="form-horizontal">
				<legend>{t('系统安全')}</legend>
				
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('加密安全码'),'safekey',true)}</div>
					<div class="col-sm-5">

						<div class="input-group">
							{form::field(array('type'=>'text','name'=>'safekey','value'=>c('system.safekey'),'maxlength'=>32,'required'=>'required'))}
							<div class="input-group-btn">
								<a class="btn btn-primary" id="createsafekey"><i class="fa fa-key"></i> {t('生成')}</a>
							</div>
						</div>						

						{form::tips(t('请使用32位随机字符串，用于系统加密，重设后需要重新登录'))}
					</div>
				</div>				
			</fieldset>

			<fieldset class="form-horizontal">
				<legend>{t('系统操作日志')}</legend>
				
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('开启日志'),'log',false)}</div>
					<div class="col-sm-10">
						{form::field(array('type'=>'bool','name'=>'log','value'=>c('system.log')))}
						{form::tips(t('开启后将自动记录用户的操作'))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('保留时间'),'log_expire',true)}</div>
					<div class="col-sm-5">
						<div class="input-group">
							{form::field(array('type'=>'number','name'=>'log_expire','value'=>c('system.log_expire'),'min'=>3,'required'=>'required'))}
							<span class="input-group-addon">{t('天')}</span>
						</div>
						{form::tips(t('超出保留时间的日志将被自动删除'))}
					</div>
				</div>				
			</fieldset>

			<fieldset class="form-horizontal">
				<legend>{t('系统登录设置')}</legend>
				
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('开启验证码'),'login_captcha',false)}</div>
					<div class="col-sm-10">
						{form::field(array('type'=>'bool','name'=>'login_captcha','value'=>c('system.login_captcha')))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('最大失败次数'),'login_maxfailed',true)}</div>
					<div class="col-sm-5">
					{form::field(array('type'=>'number','name'=>'login_maxfailed','value'=>c('system.login_maxfailed'),'min'=>1,'required'=>'required'))}
					{form::tips(t('登录失败次数到达设定值时候，用户将被锁定'))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('失败锁定时间'),'login_locktime',true)}</div>
					<div class="col-sm-5">
						<div class="input-group">
							{form::field(array('type'=>'number','name'=>'login_locktime','value'=>c('system.login_locktime'),'min'=>1,'required'=>'required'))}
							<span class="input-group-addon">{t('分钟')}</span>
						</div>
						{form::tips(t('登录失败被锁定的时间'))}
					</div>
				</div>				
			</fieldset>

		</div>	
	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}
	</div><!-- main-footer -->
	{form::footer()}

</div><!-- main -->

<script type="text/javascript">
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$(form).find('.submit').button('loading');
			$.post(action, data, function(msg){
				$.msg(msg);
				$(form).find('.submit').button('reset');
			},'json');
		}});
	});

	$(function(){
		$('#createsafekey').on('click',function(){
			　　var len = 32;
			　　var chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz0123456789';
			　　var max = chars.length;
			　　var key = '';
			　　for (i = 0; i < len; i++) {
			　　　　key += chars.charAt(Math.floor(Math.random() * max));
			　　}

				$(this).parents('.form-group').find('input').val(key);
		});
	})
</script>
{template 'footer.php'}