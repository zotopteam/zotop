<div class="side">
	<ul class="nav nav-pills nav-stacked nav-side">
		{loop system_hook::upload_navbar($type) $n}
			<li {if $n.active}class="active"{/if}>
				<a href="{$n.href}"><i class="{$n.icon}"></i>{$n.text}</a>
			</li>
		{/loop}
	</ul>
</div>