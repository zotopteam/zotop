{template 'header.php'}

<div class="side">
	{template 'system/system_side.php'}
</div>

{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{t('全局设置')}</div>
		<ul class="navbar">
			{loop $navbar $k $n}
			<li{if ZOTOP_ACTION == $k} class="current"{/if}>
				<a href="{$n['href']}">{if $n['icon']}<i class="icon {$n['icon']}"></i>{/if} {$n['text']}</a>
			</li>
			{/loop}
		</ul>
	</div><!-- main-header -->
	<div class="main-body scrollable">
			<table class="field">
				<caption>{t('系统安全')}</caption>
				<tbody>
				<tr>
					<td class="label">{form::label(t('加密安全码'),'safekey',true)}</td>
					<td class="input">
						{form::field(array('type'=>'text','name'=>'safekey','value'=>c('system.safekey'),'maxlength'=>32,'required'=>'required'))}

						<a class="btn" id="createsafekey">{t('生成')}</a>

						{form::tips(t('请使用32位随机字符串，用于系统加密，重设后需要重新登录'))}
					</td>
				</tr>
				</tbody>
			</table>
			<table class="field">
				<caption>{t('系统操作日志')}</caption>
				<tbody>
				<tr>
					<td class="label">{form::label(t('开启日志'),'log',false)}</td>
					<td class="input">
						{form::field(array('type'=>'bool','name'=>'log','value'=>c('system.log')))}
						{form::tips(t('开启后将自动记录用户的操作'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('保留时间'),'log_expire',true)}</td>
					<td class="input">
						<div class="input-group">
							{form::field(array('type'=>'number','name'=>'log_expire','value'=>c('system.log_expire'),'min'=>3,'required'=>'required'))}
							<span class="input-group-addon">{t('天')}</span>
						</div>
						{form::tips(t('超出保留时间的日志将被自动删除'))}
					</td>
				</tr>
				</tbody>
			</table>
			<table class="field">
				<caption>{t('系统登录设置')}</caption>
				<tbody>
				<tr>
					<td class="label">{form::label(t('开启验证码'),'login_captcha',false)}</td>
					<td class="input">
						{form::field(array('type'=>'bool','name'=>'login_captcha','value'=>c('system.login_captcha')))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('最大失败次数'),'login_maxfailed',true)}</td>
					<td class="input">
					{form::field(array('type'=>'number','name'=>'login_maxfailed','value'=>c('system.login_maxfailed'),'min'=>1,'required'=>'required'))}
					{form::tips(t('登录失败次数到达设定值时候，用户将被锁定'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('失败锁定时间'),'login_locktime',true)}</td>
					<td class="input">
						<div class="input-group">
							{form::field(array('type'=>'number','name'=>'login_locktime','value'=>c('system.login_locktime'),'min'=>1,'required'=>'required'))}
							<span class="input-group-addon">{t('分钟')}</span>
						</div>
						{form::tips(t('登录失败被锁定的时间'))}
					</td>
				</tr>
				</tbody>
			</table>
	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}
	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}
<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$(form).find('.submit').disable(true);
			$.loading();
			$.post(action, data, function(msg){
				$.msg(msg);
				$(form).find('.submit').disable(false);
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

				$(this).prev('input').val(key);
		});
	})
</script>
{template 'footer.php'}