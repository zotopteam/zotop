{template 'header.php'}
<link rel="stylesheet" type="text/css" href="{zotop::app('system.url')}/common/css/jquery.tree.css" />
<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.tree.js"></script>
<script type="text/javascript">
	$(function(){
		$('#treeview').tree();
	});
</script>

<div class="side">
	<div class="side-header">{t('单元测试')}</div>
	<div class="side-body scrollable">
		<ul class="sidenavlist">
			{loop $navlist $nav}
			<li><a href="{$nav[href]}"{if request::url(false)==$nav['href']} class="current"{/if}><b>{$nav[text]}</b></a></li>
			{/loop}
		<ul>
	</div>
</div>
<div class="main side-main no-footer">
	<div class="main-header"><div class="title">{$title}</div></div>
	<div class="main-body scrollable">
		<table id="treeview" class="treeview">
			<tbody>
			{loop $tree $node}
				<tr id="node-{$node[id]}"  class="{if $node[parentid] == 'root'}tree-root{else}parent-node-{$node[parentid]}{/if}">
					<td><i class="icon"></i>{$node['title']}</td>
					<td></td>
				</tr>
			{/loop}
			</tbody>
		</table>
	</div>
</div>
{template 'footer.php'}