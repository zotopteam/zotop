<div class="side-header">
	{A('content.name')}
</div><!-- side-header -->
<div class="side-body scrollable">
	<ul class="sidenavlist">
		<li class="title">
			<a href="{u('content/content/index')}" {if ZOTOP_CONTROLLER == 'content' and !$categoryid}class="current"{/if}><i class="icon icon-admin"></i>{t('内容管理')}</a>
		</li>
		<li>
		<table id="tree" class="table list navtree none" cellspacing="0" cellpadding="0">
			<tbody>
				{loop m('content.category.active') $c}
				<tr data-tt-id="a{$c['id']}" {if $c['parentid'] !=0 }data-tt-parent-id="a{$c['parentid']}"{/if} {if $categoryid==$c['id']}class="current"{/if}>
					<td class="name">
						<a data-href="{u('content/content/index/'.$c['id'])}"><i class="icon {if $c['childid']}icon-folder{else}icon-item{/if}"></i>{$c['name']}</a>
					</td>
				</tr>
				{/loop}
			</tbody>
		</table>
		</li>
		<li><a href="{u('content/config')}" {if substr_count(ZOTOP_URI,'content/config')}class="current"{/if}><i class="icon icon-config"></i>{t('内容设置')}</a></li>
		<li><a href="{u('content/category')}" {if substr_count(ZOTOP_URI,'content/category')}class="current"{/if}><i class="icon icon-category"></i>{t('栏目管理')}</a></li>
		<li><a href="{u('content/model')}" {if substr_count(ZOTOP_URI,'content/model')}class="current"{/if}><i class="icon icon-model"></i>{t('模型管理')}</a></li>
		<li class="none"><a href="{u('content/admintags')}" {if substr_count(ZOTOP_URI,'content/admintags')}class="current"{/if}><i class="icon icon-tag"></i>{t('Tag管理')}</a></li>
	</ul>


</div><!-- side-body -->


<link rel="stylesheet" type="text/css" href="{A('system.url')}/common/css/jquery.treetable.css"/>
<script type="text/javascript" src="{A('system.url')}/common/js/jquery.treetable.js"></script>
<script type="text/javascript">
$(function(){
	$("#tree").treetable({
		column : 0,
		indent : 18,
		expandable : true,
		persist: true,
		initialState : 'collapsed', //"expanded" or "collapsed".
		clickableNodeNames : true,
		stringExpand: "{t('展开')}",
		stringCollapse: "{t('关闭')}"
	}).removeClass('none');

	{if $categoryid}
		$("#tree").treetable("reveal", "a{$categoryid}");
	{/if}


	$("#tree").find('a[data-href]').click(function(){
		location.href = $(this).attr('data-href');
	});
})
</script>