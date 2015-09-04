{template 'head.php'}

        {form::header()}

			<div class="panel panel-login">
				<div class="panel-heading text-center">
					<h1 class="text-overflow">{t('$1管理',C('site.name'))}</h1>
				</div>
				<div class="panel-body">

					<div class="form-status">{t('请输入您的用户名和密码')}</div>

					<div class="form-group">
						<div class="input-group input-group-merge">
							<label for="username" class="input-group-addon"><i class="fa fa-user fa-fw"></i></label>
							{field type="text" name="username" value="$remember_username" placeholder="t('用户名')" required="required"}
							<label for="remember" class="input-group-addon" title="{t('记住用户名')}" data-placement="right">
								<input type="checkbox" class="checkbox" id="remember" name="remember" value="30" tabindex="-1" {if $remember_username}checked="checked"{/if}/>
							</label>
						</div>
					</div>

					<div class="form-group">
						<div class="input-group input-group-merge">
							<label for="password" class="input-group-addon"><i class="fa fa-lock fa-fw"></i></label>
							{field type="password" name="password" placeholder="t('密码')" required="required"}
						</div>
					</div>

					{if c('system.login_captcha')}
					<div class="form-group">
						<div class="input-group input-group-merge">
							<label for="captcha" class="input-group-addon"><i class="fa fa-key fa-fw"></i></label>
							{field type="captcha" name="captcha" placeholder="t('验证码')" required="required"}
						</div>
					</div>
					{/if}

					<div class="form-group">
						{form::field(array('type'=>'submit','value'=>t('登 录'),'data-loading-text'=>t('登录中，请稍后……'),'class'=>'btn-block'))}
					</div>

				</div>
			</div>

		{form::footer()}

	<script type="text/javascript">
		// 禁止被包含
		if(top!= self){top.location = self.location;}

		//加入收藏夹
		$(function(){
			$("a.add-favorite").on('click',function(){
				var title = window.document.title;
				var url = window.location.href;
				try{
					if ( window.sidebar && 'object' == typeof( window.sidebar ) && 'function' == typeof( window.sidebar.addPanel ) ){
						window.sidebar.addPanel(title, url , '');
					}else if ( document.all && 'object' == typeof( window.external ) ){
						window.external.addFavorite(url , title);
					}else {
						$.error('{t('您使用的浏览器不支持此功能，请按“Ctrl+D”键手工加入收藏')}');
					}
				}catch(e){
					$.error('{t('您使用的浏览器不支持此功能，请按“Ctrl+D”键手工加入收藏')}');
				}
				return false;
			});
		})

		// 登录
		$(function(){
			$('form.form').validate({
				rules: {
					username: 'required',
					password: 'required'
				},
				messages: {
					username: "{t('请输入您的用户名')}",
					password: "{t('请输入您的密码')}",
					captcha: {required:"{t('请输入验证码')}"}
				},
				showErrors:function(errorMap,errorList){
					if (errorList[0]) $('.form-status').html('<span class="text-error">'+ errorList[0].message +'</span>');
				},
				submitHandler:function(form){

					$('.form-status').html('{t('登录中, 请稍后……')}');
					$('.submit').button('loading');

					$.post($(form).attr('action'), $(form).serialize(), function(msg){

						$('.form-status').html('<span class="'+msg.state+'">'+ msg.content +'</span>');

						if( msg.url ){
							location.href = msg.url;
							return true;
						}

						$('.submit').button('reset');

						return false;
					},'json');
				}
			});
		});
	</script>

{template 'foot.php'}