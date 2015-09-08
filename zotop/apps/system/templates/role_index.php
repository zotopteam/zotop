{template 'header.php'}

<div class="side">
	{template 'system/system_side.php'}
</div>

<div class="main side-main">
	<div class="main-header">
		<div class="title">{t('角色管理')}</div>
		<div class="action">
			<a class="btn btn-highlight btn-icon-text js-open" href="{u('system/role/add')}" data-width="600px" data-height="220px">
				<i class="fa fa-add"></i><b>{t('添加角色')}</b>
			</a>
		</div>
	</div><!-- main-header -->
	
	<div class="main-body scrollable">

		{form::header()}
		<div class="table-responsive">
			<table class="table table-hover" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<th class="text-center">{t('状态')}</th>
						<th class="w300">{t('名称')}</th>				
						<th>{t('说明')}</td>
						<th class="w120">{t('管理员数')}</th>
					</tr>
				</thead>
				<tbody>
				{loop $dataset $data}
					<tr>
						<td class="text-center">{if $data['disabled']}<i class="fa fa-time-circle false"></i>{else}<i class="fa fa-check-circle true"></i>{/if}</td>
						<td>
							<div class="title">
								{$data['name']}					
							</div>
							<div class="manage">
								<a class="js-open" href="{u('system/role/edit/'.$data['id'])}" data-width="600px" data-height="180px">{t('编辑')}</a>
								{if $data['id'] == 1}
								<s>|</s>
								<a class="disabled">{t('权限设置')}</a>
								<s>|</s>
								<a class="disabled">{t('删除')}</a>
								{else}
								<s>|</s>
								<a class="js-open" href="{u('system/role/priv/'.$data['id'])}" data-width="800px" data-height="480px">{t('权限设置')}</a>
								<s>|</s>
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
		</div>
		{form::footer()}
	</div><!-- main-body -->
	<div class="main-footer">
		<div class="footer-text">{t('通过角色可以为管理员分配权限，从而限制管理员执行某些操作的能力')}</div>
	</div><!-- main-footer -->
</div><!-- main -->

{template 'footer.php'}