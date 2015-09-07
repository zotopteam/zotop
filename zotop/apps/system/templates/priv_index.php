{template 'head.php'}

<div class="side">
	{template 'system/system_side.php'}
</div>

<div class="main side-main">
	<div class="main-header">
		<div class="title">{t('权限管理')}</div>
		<div class="action">
			<a class="btn btn-primary js-open" href="{u('system/priv/add')}" data-width="600px" data-height="180px">
				<i class="fa fa-plus fa-fw"></i><b>{t('添加权限')}</b>
			</a>
		</div>
	</div><!-- main-header -->
	{form::header()}
	<div class="main-body scrollable">
		<table id="tree" class="table hidden">
		<thead>
			<tr>
			<td class="w400">{t('权限名称')}</td>
			<td>{t('权限标识')}</td>
			<td class="w300">{t('管理')}</td>
			</tr>
		</thead>
		<tbody>
		{loop $dataset $data}
			<tr data-tt-id="{$data['id']}"{if $data['parentid']} data-tt-parent-id="{$data['parentid']}"{/if}>
				<td class="name"><i class="icon {if $data['_child']}icon-folder{else}icon-item{/if}"></i>{$data['name']}</td>
				<td>{$data['app']}{if !empty($data['controller'])}/{$data['controller']}{/if}{if !empty($data['action'])}/{$data['action']}{/if}</td>
				<td>
					<div class="manage">
						<a class="js-open" href="{u('system/priv/add/'.$data['id'])}" data-width="600px" data-height="260px"><i class="fa fa-plus fa-fw"></i>{t('添加子权限')}</a>
						<s></s>
						<a class="js-open" href="{u('system/priv/edit/'.$data['id'])}" data-width="600px" data-height="260px"><i class="fa fa-edit fa-fw"></i>{t('编辑')}</a>
						<s></s>
						<a class="js-confirm" href="{u('system/priv/delete/'.$data['id'])}"><i class="fa fa-delete fa-fw"></i>{t('删除')}</a>
					</div>
				</td>
			</tr>
		{/loop}
		</tbody>
		</table>
	</div><!-- main-body -->
	<div class="main-footer">
		<div class="footer-text">{t('通过权限管理可以管理或者定义全部权限设定')}</div>
	</div><!-- main-footer -->
	{form::footer()}
</div><!-- main -->

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

	$("#tree").treetable("reveal", "{$id}");
})
</script>
{template 'foot.php'}