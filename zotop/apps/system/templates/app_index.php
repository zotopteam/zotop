{template 'header.php'}
<div class="side">
	{template 'system/system_side.php'}
</div>
<div class="main side-main">
	<div class="main-header">
		<div class="title">{t('应用管理')}</div>
		<ul class="navbar">
			{loop $navbar $k $n}
			<li{if ZOTOP_ACTION == $k} class="current"{/if}><a href="{$n['href']}">{$n['text']}</a></li>
			{/loop}
		</ul>
	</div><!-- main-header -->
	<div class="main-body scrollable">

		{form::header()}
		<table class="table list sortable" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<td class="drag"></td>
					<td class="w50 center">{t('图标')}</td>
					<td class="w260">{t('名称')}</td>
					<td class="w60">{t('版本')}</td>
					<td>{t('说明')}</td>
				</tr>
			</thead>
			<tbody>
			{loop $apps $id $m}

			{if !in_array($id, $cores)}
			<tr{if $m['status']==0} class="disabled"{/if}>
				<td class="drag">&nbsp;<input type="hidden" name="id[]" value="{$m['id']}"></td>
				<td class="w50 vt center">
					<img src="{ZOTOP_URL_APPS}/{$m['dir']}/app.png" style="width:48px;height:48px;margin-top:4px;{if $m['status']==0}opacity: 0.2;{/if}">
				</td>
				<td class="w260 vt">
					<div class="title">{$m['name']} <span class="green f12">{$m['id']}</span></div>

					<div class="manage">

						{if file::exists(ZOTOP_PATH_APPS.DS.$m['dir'].DS.'controllers'.DS.'config.php')}
							<a href="{U($id.'/config')}">{t('设置')}</a>
							<s></s>
						{/if}

						{if $m['status']==0}
						<a class="dialog-confirm" title="{t('启用该应用')}" href="{U('system/app/status/'.$id)}">{t('启用')}</a>
						{else}
						<a class="dialog-confirm" title="{t('禁用该应用')}" href="{U('system/app/status/'.$id)}">{t('禁用')}</a>
						{/if}

						<s></s>
						<a class="dialog-open" data-width="800" data-height="400" title="{t('卸载该应用')}" href="{U('system/app/uninstall/'.$id)}">{t('卸载')}</a>

					</div>

				</td>
				<td class="w60 vt">
					v{$m['version']}
				</td>
				<td class="vt">
					<div>{$m['description']}</div>
					<div class="manage gray f12">
						{if $m['author']} {t('作者')}: {$m['author']} {/if}
						{if $m['homepage']} <s></s> <a target="_blank" href="{$m['homepage']}">{t('网站')}</a> {/if}
					</div>
				</td>
			</tr>
			{/if}
			{/loop}
			</tbody>
	</table>
	{form::footer()}
	</div><!-- main-body -->
	<div class="main-footer textflow">
		<div class="tips">
		{t('小贴士：应用是对系统现有功能的扩展。如：内容发布使用内容管理应用(content)，会员管理使用会员系统(member)等，获取最新应用请登陆<a target="_blank"href="%s">官方网站</a>','http://www.zotop.com')}
		</div>
	</div><!-- main-footer -->
</div>

<script type="text/javascript">
//sortable
$(function(){
	$("table.sortable").sortable({
		items: "tbody > tr",
		axis: "y",
		placeholder:"ui-sortable-placeholder",
		helper: function(e,tr){
			tr.children().each(function(){
				$(this).width($(this).width());
			});
			return tr;
		},
		update:function(){
			var action = $(this).parents('form').attr('action');
			var data = $(this).parents('form').serialize();
			$.post(action, data, function(msg){
				$.msg(msg);location.reload();
			},'json');
		}
	});
});
</script>
{template 'footer.php'}