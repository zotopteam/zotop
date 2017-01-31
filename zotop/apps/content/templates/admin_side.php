<div class="side">
	<div class="side-header">
		{A('content.name')}
	</div><!-- side-header -->
	<div class="side-body scrollable">
		<ul class="nav nav-pills nav-stacked nav-side nav-side-tree hidden">

			{loop m('content.content.status') $s $t}
			<li {if request::is('content/content/index') and !$categoryid and $status==$s}class="active"{/if}>
				<a href="{u('content/content/index?status='.$s)}">
					<i class="fa fa-{$s}"></i> <span>{$t} </span>
					{if $statuscount=m('content.content.statuscount', $s)}
					<span class="badge badge-xs badge-danger pull-right">{$statuscount number="pretty"}</span>
					{/if}
				</a>
				
				{if $s=='publish'}
					<table id="tree" class="table nav-treetable" cellspacing="0" cellpadding="0">
						<tbody>
							{loop m('content.category.active') $c}
							<tr data-tt-id="a{$c.id}" {if $c.parentid !=0 }data-tt-parent-id="a{$c.parentid}"{/if} {if $categoryid==$c.id}class="active"{/if}>
								<td class="name">
									<a data-href="{u('content/content/index?categoryid='.$c.id.'&status='.$s)}">
										<i class="fa {if $c.childid}fa-folder{else}fa-folder{/if}"></i>{$c.name}
									</a>
								</td>
							</tr>
							{/loop}
						</tbody>
					</table>				
				{/if}

			</li>
			{/loop}		


			<li {if request::is('content/category')}class="active"{/if}>
				<a href="{u('content/category')}"><i class="fa fa-sitemap"></i> <span>{t('栏目')}</span></a>
				</li>
			<li {if request::is('content/model','content/field')}class="active"{/if}>
				<a href="{u('content/model')}"><i class="fa fa-cubes"></i> <span>{t('模型')}</span></a>
			</li>
			<li {if request::is('content/admintags')}class="active"{/if}>
				<a href="{u('content/admintags')}"><i class="fa fa-tag"></i> <span>{t('标签')}</span></a>
			</li>
			<li {if request::is('content/config')}class="active"{/if}>
				<a href="{u('content/config')}"><i class="fa fa-cog"></i> <span>{t('设置')}</span></a>
			</li>			
		</ul>


	</div><!-- side-body -->
</div>

<link rel="stylesheet" type="text/css" href="{A('system.url')}/assets/css/jquery.treetable.css"/>
<script type="text/javascript" src="{A('system.url')}/assets/js/jquery.treetable.js"></script>
<script type="text/javascript">
$(function(){
	$("#tree").treetable({
		column : 0,
		indent : 22,
		expandable : true,
		persist: true,
		initialState : 'collapsed', //"expanded" or "collapsed".
		clickableNodeNames : true,
		stringExpand: null,
		stringCollapse: null,
		onInitialized:function(){
			$('.nav-side-tree').removeClass('hidden');
		}
	});

	{if $categoryid}
		$("#tree").treetable("reveal", "a{$categoryid}");
	{/if}


	$("#tree").find('a[data-href]').click(function(){
		location.href = $(this).attr('data-href');
	});
})
</script>