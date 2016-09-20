{template 'header.php'}

<div class="container-fluid container-primary">
	<div class="jumbotron text-center">
		<h1>{A('translator.name')}</h1>
		<p>{A('translator.description')}</p>
	</div>
</div>

<div class="container-fluid container-default">

	<div class="panel">
		<div class="panel-heading">
			<h2>{t('参数设置')}</h2>
		</div>			
		<div class="panel-body">
			
			{form action="u('translator/config/save')"}
				
				<div class="form-group">
					<label for="api">{t('接口选择')}</label>

					{field type="radio" options="array('youdao'=>t('有道翻译'),'baidu'=>t('百度翻译'))" name="api" value="c('translator.api')"}

					<div class="help-block">{t('选择并使用相关接口翻译别名')}</div>
				</div>


				<div class="form-group api-option api-option-youdao">
					{form::label(t('API Key'),'youdao_key',true)}

					{form::field(array('type'=>'text','name'=>'youdao_key','value'=>c('translator.youdao_key'),'required'=>'required'))}

					<div class="blank"></div>

					{form::label(t('keyfrom'),'youdao_keyfrom',true)}

					{form::field(array('type'=>'text','name'=>'youdao_keyfrom','value'=>c('translator.youdao_keyfrom'),'required'=>'required'))}					

					<div class="help-block">{t('在有道翻译申请到的API key和keyfrom')}</div>

					<div class="blank"></div>

					<div class="alert alert-warning" role="alert">					
						<p>{t('使用API key 时，默认请求频率限制为每小时1000次，超过限制会被封禁，请申请一个自己的API KEY来使用')}</p>
						<p>{t('申请地址')} : <a target="_blank" href="http://fanyi.youdao.com/openapi?path=data-mode">http://fanyi.youdao.com/openapi?path=data-mode</a></p>
					</div>
				</div>				

				<div class="form-group api-option api-option-baidu hidden">
					{form::label(t('API ID'),'baidu_appid',true)}

					{form::field(array('type'=>'text','name'=>'baidu_appid','value'=>c('translator.baidu_appid'),'required'=>'required'))}
					
					<div class="blank"></div>

					{form::label(t('密钥'),'baidu_seckey',true)}

					{form::field(array('type'=>'text','name'=>'baidu_seckey','value'=>c('translator.baidu_seckey'),'required'=>'required'))}					

					<div class="help-block">{t('在百度翻译开放平台申请的APP ID和密钥')}</div>

					<div class="alert alert-warning" role="alert">
						<p>{t('百度翻译API是百度面向开发者推出的免费翻译服务开放接口 ,每月翻译字符数低于200万，享免费服务，请申请一个自己的APP ID和密钥来使用')}</p>
						<p>{t('申请地址')} : <a target="_blank" href="http://api.fanyi.baidu.com/">http://api.fanyi.baidu.com/</a></p>
					</div>
				</div>

				<div class="form-group">
					{form::label(t('别名长度'),'alias_length',true)}
					{form::field(array('type'=>'num','name'=>'alias_length','value'=>c('translator.alias_length'),'required'=>'required','max'=>128))}
					{form::tips(t('翻译后提取的别名长度，最长为128个字符'))}						
				</div>

				<div class="form-group">
					{form::field(array('type'=>'submit','value'=>t('保存')))}
				</div>

			{/form}

		</div>
	</div>

</div>



<script type="text/javascript">
	
	// api选项切换
	$(function(){
		show_option();

		$('[name=api]').on('change',function(){
			show_option();
		})

		function show_option() {
			var api = $('[name=api]:checked').val();

			$('.api-option').addClass('hidden');
			$('.api-option-'+api).removeClass('hidden');		
		}
	});

	// 表单提交
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
</script>

{template 'footer.php'}