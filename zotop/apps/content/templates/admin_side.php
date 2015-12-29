<div class="side">
	<div class="side-header">
		{A('content.name')}
	</div><!-- side-header -->
	<div class="side-body scrollable">
		<ul class="nav nav-pills nav-stacked nav-side">
			<li {if ZOTOP_CONTROLLER == 'content' and !$categoryid}class="active"{/if}>
				<a href="{u('content/content/index')}" ><i class="fa fa-list"></i> <span>{t('内容管理')}</span></a>

				<table id="tree" class="table nav-treetable hidden hidden-xs" cellspacing="0" cellpadding="0">
					<tbody>
						{loop m('content.category.active') $c}
						<tr data-tt-id="a{$c['id']}" {if $c['parentid'] !=0 }data-tt-parent-id="a{$c['parentid']}"{/if} {if $categoryid==$c['id']}class="active"{/if}>
							<td class="name">
								<a data-href="{u('content/content/index/'.$c['id'].'/publish')}">
									<i class="fa {if $c['childid']}fa-folder{else}fa-file{/if}"></i>{$c['name']}
								</a>
							</td>
						</tr>
						{/loop}
					</tbody>
				</table>
			</li>
			<li {if substr_count(ZOTOP_URI,'content/config')}class="active"{/if}>
				<a href="{u('content/config')}"><i class="fa fa-cog"></i> <span>{t('内容设置')}</span></a>
			</li>
			<li {if substr_count(ZOTOP_URI,'content/category')}class="active"{/if}>
				<a href="{u('content/category')}"><i class="fa fa-sitemap"></i> <span>{t('栏目管理')}</span></a>
				</li>
			<li {if substr_count(ZOTOP_URI,'content/model') or substr_count(ZOTOP_URI,'content/field')}class="active"{/if}>
				<a href="{u('content/model')}"><i class="fa fa-cubes"></i> <span>{t('模型管理')}</span></a>
			</li>
			<li {if substr_count(ZOTOP_URI,'content/admintags')}class="active"{/if}>
				<a href="{u('content/admintags')}"><i class="fa fa-tag"></i> <span>{t('Tag管理')}</span></a>
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
		stringCollapse: null
	}).removeClass('hidden');

	{if $categoryid}
		$("#tree").treetable("reveal", "a{$categoryid}");
	{/if}


	$("#tree").find('a[data-href]').click(function(){
		location.href = $(this).attr('data-href');
	});
})
</script>