<div class="side">
	<div class="side-header">
		{A('member.name')}
	</div><!-- side-header -->
	<div class="side-body no-footer scrollable">

		<ul class="nav nav-pills nav-stacked nav-side">
			{loop member_hook::admin_sidebar() $m}
			<li{if $m.active} class="active"{/if}>
				<a href="{$m.href}">
					<i class="{$m.icon} fa-fw"></i><span>{$m.text}</span>
				</a>
			</li>
			{/loop}
		</ul>

	</div><!-- side-body -->
</div>