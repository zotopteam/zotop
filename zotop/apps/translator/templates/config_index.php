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
					{form::label(t('API Key'),'baidu_clientid',true)}

					{form::field(array('type'=>'text','name'=>'baidu_clientid','value'=>c('translator.baidu_clientid'),'required'=>'required'))}

					<div class="help-block">{t('在百度开发者中心注册得到的授权API key')}</div>

					<div class="alert alert-warning" role="alert">
						<p>{t('百度翻译API是百度面向开发者推出的免费翻译服务开放接口 ,默认API KEY使用限制为1000次/小时限制，请申请一个自己的API KEY来使用')}</p>
						<p>{t('申请地址')} : <a target="_blank" href="http://developer.baidu.com/console#app/project">http://developer.baidu.com/console#app/project</a></p>
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
		$('[name=api]').on('change',function(){
			$('.api-option').addClass('hidden');
			$('.api-option-'+$(this).val()).removeClass('hidden');
		})
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