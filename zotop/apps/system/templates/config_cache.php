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
	
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('缓存方式'),'cache_driver',true)}</div>
					<div class="col-sm-10">
					<?php echo form::field(array(
					   'type'		=> 'radio',
					   'options'	=> zotop::filter('cache_driver', array(
							'file'			=> t('文件'),
							'memcache'		=> t('Memcache'),
							'eaccelerator'	=> t('Eaccelerator'),
							'apc'			=> t('Apc'),
						)),
					   'name'		=> 'cache_driver',
					   'value'		=> c('system.cache_driver'),
					   'remote'		=> u('system/config/checkcachedriver'),
					))?>

					{form::tips(t('需要服务器支持'))}
					</div>
				</div>
				<div class="form-group memcache_servers {if c('system.cache_driver') != 'memcache'}hidden{/if}">
					<div class="col-sm-2 control-label">{form::label(t('缓存服务器'),'cache_memcache',true)}</div>
					<div class="col-sm-10">
						{form::field(array('type'=>'textarea','name'=>'cache_memcache','value'=>c('system.cache_memcache'),'required'=>'required'))}
						{form::tips(t('每行一个服务器，格式 服务器地址:端口:持久(127.0.0.1:11211:1)'))}
					</div>
				</div>				
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('缓存时间'),'cache_expire',true)}</div>
					<div class="col-sm-4">
						<div class="input-group">
							{form::field(array('type'=>'number','name'=>'cache_expire','value'=>c('system.cache_expire'),'min'=>0))}
							<span class="input-group-addon">{t('秒')}</span>
						</div>
						{form::tips(t('默认缓存有效时间'))}
					</div>
				</div>

			</fieldset>
		</div>
	</div><!-- main-body -->
	<div class="main-footer">
		{field type="submit" value="t('保存')" data-loading-text="t('保存中……')"}
	</div><!-- main-footer -->
	{form::footer()}

</div><!-- main -->

<script type="text/javascript">
	$(function(){
		$('[name=cache_driver]').on('click',function(){
			if( $(this).val() == 'memcache' ){
				$('.memcache_servers').removeClass('hidden');
			}else{
				$('.memcache_servers').addClass('hidden');
			}
		});
	});

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