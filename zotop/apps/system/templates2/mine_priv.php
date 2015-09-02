{template 'header.php'}

<div class="side">
	{template 'system/mine_side.php'}
</div>


{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
		<table id="tree" class="table list hidden">
		<thead>
			<tr>
				<td class="w400">{t('权限名称')}</td>
				<td>{t('权限标识')}</td>
				<td class="w80 center">{t('状态')}</td>
			</tr>
		</thead>
		<tbody>
		{loop $dataset $data}
			<tr data-tt-id="{$data['id']}"{if $data['parentid']} data-tt-parent-id="{$data['parentid']}"{/if}>
				<td class="name"><i class="icon {if $data['_child']}icon-folder{else}icon-item{/if}"></i>{$data['name']}</td>
				<td>{$data['app']}{if !empty($data['controller'])}/{$data['controller']}{/if}{if !empty($data['action'])}/{$data['action']}{/if}</td>
				<td class="center">{if $groupid == 1 or $data['status']}<i class="icon icon-true true"></i>{else}<i class="icon icon-false false"></i>{/if}</td>
			</tr>
		{/loop}
		</tbody>
		</table>
	</div><!-- main-body -->
	<div class="main-footer">
		<div class="tips">{t('我的角色: "$1", $2', $role['name'], $role['description'])}</div>
	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}
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
	}).removeClass('hidden');
})
</script>
{template 'footer.php'}