<div class="side scrollable">
	<div class="side-header">
		<div class="title">{t('系统')}</div>
	</div><!-- side-header -->
	<div class="side-body">
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
</div>