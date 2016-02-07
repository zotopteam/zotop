{template 'header.php'}

{template '[app.id]/admin_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<form action="{u('[app.id]/admin/index')}" class="searchbar input-group" method="post" role="search">				
			<input type="text" name="keywords" value="{$keywords}" placeholder="{t('请输入关键词')}" class="form-control" x-webkit-speech/>
			<span class="input-group-btn">
				<button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
			</span>
		</form>	
		<div class="action">
			<a class="btn btn-icon-text btn-primary" href="{U('[app.id]/admin/add')}">
				<i class="fa fa-plus"></i><b>{t('添加')}</b>
			</a>
		</div>
	</div> <!-- main-header -->
	{form action="post"}
	<div class="main-body scrollable">
		{if empty($data)}
			<div class="nodata">{t('没有找到任何数据')}</div>
		{else}
		<table class="table table-nowrap table-hover table-stripped list">
			<thead>
				<tr>
					<td class="select"><input type="checkbox" class="select-all"/></td>
					<td>{t('名称')}</td>
					<td class="w120">{t('时间')}</td>
				</tr>
			</thead>
			<tbody>
				{loop $data $r}
				<tr>
					<td class="select"><input type="checkbox" class="checkbox" name="id[]" value="{$r.id}"/></td>
					<td>
						<div class="title text-overflow">{$r.title}</div>
						<div class="manage">
							<a href="{u('[app.id]/admin/edit/'.$r.id)}">{t('编辑')}</a>
							<s>|</s>
							<a href="{u('[app.id]/admin/delete/'.$r.id)}" class="js-confirm">{t('删除')}</a>
						</div>
					</td>
					<td>
						{format::date($r.createtime)}
					</td>
				</tr>
				{/loop}
			<tbody>
		</table>
		{/if}
	</div> <!-- main-body -->
	<div class="main-footer">
		<div class="footer-text">{A('[app.id].description')}</div>
	</div> <!-- main-footer -->
	{/form}
</div> <!-- main -->

{template 'footer.php'}