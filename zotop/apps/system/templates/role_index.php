{template 'header.php'}

<div class="side">
	{template 'system/system_side.php'}
</div>

{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{t('角色管理')}</div>
		<div class="action">
			<a class="btn btn-highlight btn-icon-text dialog-open" href="{u('system/role/add')}" data-width="600px" data-height="220px">
				<i class="icon icon-add"></i><b>{t('添加角色')}</b>
			</a>
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
		<table class="table zebra list" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<td class="w40 center">{t('状态')}</td>
				<td class="w300">{t('名称')}</td>				
				<td>{t('说明')}</td>
				<td class="w120">{t('管理员数')}</td>
			</tr>
		</thead>
		<tbody>
		{loop $dataset $data}
			<tr>
				<td class="center">{if $data['disabled']}<i class="icon icon-false false"></i>{else}<i class="icon icon-true true"></i>{/if}</td>
				<td>
					<div class="title">
						{$data['name']}					
					</div>
					<div class="manage">
						<a class="dialog-open" href="{u('system/role/edit/'.$data['id'])}" data-width="600px" data-height="280px">{t('编辑')}</a>
						{if $data['id'] == 1}
						<s></s>
						<a class="disabled">{t('权限设置')}</a>
						<s></s>
						<a class="disabled">{t('删除')}</a>
						{else}
						<s></s>
						<a class="dialog-open" href="{u('system/role/priv/'.$data['id'])}" data-width="800px" data-height="480px">{t('权限设置')}</a>
						<s></s>
						<a class="dialog-confirm" href="{u('system/role/delete/'.$data['id'])}">{t('删除')}</a>
						{/if}
					</div>					
				</td>				
				<td>{$data['description']}</td>
				<td>{$data['count']}</td>
			</tr>
		{/loop}
		</tbody>
		</table>
	</div><!-- main-body -->
	<div class="main-footer">
		<div class="tips">{t('通过角色可以为管理员分配权限，从而限制管理员执行某些操作的能力')}</div>
	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}
<script type="text/javascript">

</script>
{template 'footer.php'}