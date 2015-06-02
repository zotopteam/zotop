{template 'header.php'}

{form::header()}

<div class="main">	
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">

			<table class="field">
				<caption>{t('账号信息')}</caption>
				<tbody>
				<tr>
					<td class="label">{form::label(t('公众号名称'),'name',true)}</td>
					<td class="input">
						{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'required'=>'required'))}
						{form::tips(t('公众号的帐号名称'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('微信号'),'account',true)}</td>
					<td class="input">
						{form::field(array('type'=>'text','name'=>'account','value'=>$data['account'],'maxlength'=>32,'pattern'=>'^[a-z]{1}[a-z0-9]{0,31}$','required'=>'required'))}
						{form::tips(t('公众号的微信号，一般为英文'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('原始ID'),'original',true)}</td>
					<td class="input">
						{form::field(array('type'=>'text','name'=>'original','value'=>$data['original'],'required'=>'required'))}
						{form::tips(t('建议填写，否则无法给粉丝发送客服消息'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('账号类型'),'type',true)}</td>
					<td class="input">
						{form::field(array('type'=>'radio','options'=>m('wechat.account.type'),'name'=>'type','value'=>$data['type']))}
					</td>
				</tr>								
				</tbody>
			</table>

			<table class="field">
				<caption>{t('接口信息')}</caption>
				<tbody>
				<tr>
					<td class="label">{form::label(t('AppID'),'appid',true)}</td>
					<td class="input">
						{form::field(array('type'=>'text','name'=>'appid','value'=>$data['appid'],'required'=>'required'))}
						{form::tips(t('微信公众平台开发者中心的AppID'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('AppSecret'),'appsecret',true)}</td>
					<td class="input">
						{form::field(array('type'=>'text','name'=>'appsecret','value'=>$data['appsecret'],'maxlength'=>32,'required'=>'required'))}
						{form::tips(t('微信公众平台开发者中心的AppSecret'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('Token'),'token',true)}</td>
					<td class="input">
						{form::field(array('type'=>'text','name'=>'token','value'=>$data['token'],'minlength'=>3,'maxlength'=>32,'required'=>'required'))}

						<a href="javascript:;" class="btn btn-token">{t('随机生成')}</a>

						{form::tips(t('与公众平台接入设置值一致，必须为英文或者数字，长度为3到32个字符. 请妥善保管, 泄露将可能被窃取或篡改平台的操作数据'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('EncodingAESKey'),'encodingaeskey',true)}</td>
					<td class="input">

						{form::field(array('type'=>'text','name'=>'encodingaeskey','value'=>$data['token'],'pattern'=>'^[A-Za-z0-9]{43}$','required'=>'required'))}
						
						<a href="javascript:;" class="btn btn-encodingaeskey">{t('随机生成')}</a>

						{form::tips(t('与公众平台接入设置值一致，必须为英文或者数字，长度为43个字符. 请妥善保管, 泄露将可能被窃取或篡改平台的操作数据'))}
					</td>
				</tr>								
				</tbody>
			</table>


	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}

		{form::field(array('type'=>'button','value'=>t('取消'), 'onclick'=>'history.go(-1)'))}
	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}


<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">

	$(function(){
		$('form.form').validate({submitHandler:function(form){
			$.loading();
			$(form).find('.submit').disable(true);
			$.post($(form).attr('action'), $(form).serialize(), function(msg){
				if( !msg.state ){
					$(form).find('.submit').disable(false);
				}				
				$.msg(msg);
			},'json');
		}});
	});

	$(function(){

		// 随机生成 encodingaeskey
		$('a.btn-encodingaeskey').on('click',function(){
			var letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
			var token = '';
			for(var i = 0; i < 43; i++) {
				var j = parseInt(Math.random() * 61 + 1);
				token += letters[j];
			}

			$(':text[name="encodingaeskey"]').val(token);			
		});

		// 随机生成 encodingaeskey
		$('a.btn-token').on('click',function(){
			var letters = 'abcdefghijklmnopqrstuvwxyz0123456789';
			var token = '';
			for(var i = 0; i < 32; i++) {
				var j = parseInt(Math.random() * (31 + 1));
				token += letters[j];
			}
			$(':text[name="token"]').val(token);			
		});		

	});

</script>

{template 'footer.php'}