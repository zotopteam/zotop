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
				<caption>{t('区域和语言')}</caption>
				<tbody>
				<tr>
					<td class="label">{form::label(t('语言'),'locale_language',false)}</td>
					<td class="input">
					<?php echo form::field(array(
					   'type'=>'language',
					   'name'=>'locale_language',
					   'value'=>c('system.locale_language'),
					))?>
					{form::tips(t('选择系统语言，自动检测将获取客户端浏览器的语言设置'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('时区'),'locale_timezone',false)}</td>
					<td class="input">
						{form::field(array('type'=>'timezone','name'=>'locale_timezone','value'=>c('system.locale_timezone')))}
						{form::tips(t('当前时间:<b>%s</b>，如果当前时间与您本地时间相差几个小时，那么您需要更改自己的时区设置',format::date(ZOTOP_TIME)))}
					</td>
				</tr>
				</tbody>
			</table>

			<table class="field">
				<caption>{t('日期和时间')}</caption>
				<tbody>
				<tr>
					<td class="label">{form::label(t('日期格式'),'locale_date',false)}</td>
					<td class="input">
					<?php echo form::field(array(
					   'type'=>'select',
					   'options'=>array(
							'Y-m-d'=>format::date(ZOTOP_TIME,'Y-m-d'),
							'y-m-d'=>format::date(ZOTOP_TIME,'y-m-d'),
							'Y年m月d日'=>format::date(ZOTOP_TIME,'Y年m月d日'),
							'Y/m/d'=>format::date(ZOTOP_TIME,'Y/m/d'),
							'd/m/Y'=>format::date(ZOTOP_TIME,'d/m/Y')
						),
					   'name'=>'locale_date',
					   'value'=>c('system.locale_date'),
					   'column'=>1
					))?>
					{form::tips(t('设置日期的显示格式'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('时间格式格式'),'locale_time',false)}</td>
					<td class="input">
					<?php echo form::field(array(
					   'type'=>'select',
					   'options'=>array(
							'H:i:s'=>format::date(ZOTOP_TIME,'H:i:s'),
							'H:i'=>format::date(ZOTOP_TIME,'H:i'),
							'a g:i'=>format::date(ZOTOP_TIME,'A g:i'),
							'g:i A'=>format::date(ZOTOP_TIME,'g:i A')
						),
					   'name'=>'locale_time',
					   'value'=>c('system.locale_time'),
					   'column'=>1
					))?>
					{form::tips(t('设置时间的显示格式'))}
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
</script>
{template 'footer.php'}