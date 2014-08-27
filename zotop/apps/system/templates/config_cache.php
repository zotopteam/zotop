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
				<caption>{t('基本设置')}</caption>
				<tbody>
				<tr>
					<td class="label">{form::label(t('缓存方式'),'cache_driver',true)}</td>
					<td class="input">
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
					</td>
				</tr>
				<tr class="memcache_servers {if c('system.cache_driver') != 'memcache'}none{/if}">
					<td class="label">{form::label(t('缓存服务器'),'cache_memcache',true)}</td>
					<td class="input">
						{form::field(array('type'=>'textarea','name'=>'cache_memcache','value'=>c('system.cache_memcache'),'required'=>'required'))}
						{form::tips(t('每行一个服务器，格式 服务器地址:端口:持久(127.0.0.1:11211:1)'))}
					</td>
				</tr>				
				<tr>
					<td class="label">{form::label(t('缓存时间'),'cache_expire',true)}</td>
					<td class="input">
						<div class="input-group">
							{form::field(array('type'=>'number','name'=>'cache_expire','value'=>c('system.cache_expire'),'min'=>0))}
							<span class="input-group-addon">{t('秒')}</span>
						</div>
						{form::tips(t('默认缓存有效时间'))}
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
		$('[name=cache_driver]').on('click',function(){
			if( $(this).val() == 'memcache' ){
				$('.memcache_servers').show();
			}else{
				$('.memcache_servers').hide();
			}
		});
	});

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
</script>
{template 'footer.php'}