<div class="side">
	<div class="side-header">
		{A('[app.id].name')}
	</div><!-- side-header -->
	<div class="side-body no-footer scrollable">

		<ul class="nav nav-pills nav-stacked nav-side">
			<li {if request::is('[app.id]/admin')}class="active"{/if}>
				<a href="{u('[app.id]/admin')}">
					<i class="fa fa-dashboard"></i> <span>{t('[app.name]管理')}</span>
				</a>
			</li>
			{if file::exists(A('[app.id].path').DS.'controllers'.DS.'config.php')}
			<li {if request::is('[app.id]/config')}class="active"{/if}>
				<a href="{u('[app.id]/config')}">
					<i class="fa fa-cog"></i> <span>{t('[app.name]设置')}</span>
				</a>
			</li>
			{/if}
		</ul>

	</div><!-- side-body -->
</div> <!-- side -->