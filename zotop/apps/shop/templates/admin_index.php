{template 'header.php'}
<div class="side">
{template 'shop/admin_side.php'}
</div>


<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a class="btn btn-icon-text btn-highlight" href="{U('shop/goods/add')}">
				<i class="icon icon-add"></i><b>{t('添加')}</b>
			</a>
		</div>
	</div>
	<div class="main-body scrollable">

	</div>
	<div class="main-footer">

	</div>
</div>
{template 'footer.php'}