<div class="side-header">
	{t('系统')}
</div><!-- side-header -->
<div class="side-body scrollable">
	<ul class="nav nav-pills nav-stacked nav-side">
		{loop zotop::filter('system.menu',zotop::data(A('system.path').DS.'menu.php')) $m}
		<li{if $m.active} class="active"{/if}>
			<a href="{$m.href}">
				<i class="{$m.icon} fa-fw"></i><span>{$m.text}</span>
			</a>
		</li>
		{/loop}
	</ul>
</div><!-- side-body -->