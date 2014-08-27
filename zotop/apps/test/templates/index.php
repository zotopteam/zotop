{template 'header.php'}
<div class="main">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div>
	<div class="main-body scrollable">
		<ul class="navlist">
			{loop $navlist $nav}
			<li>
				<a href="{$nav['href']}" class="nav clearfix">
					<img src="{$nav['icon']}">
					<h2>{$nav['text']}</h2>
					<p>{$nav['description']}</p>
				</a>
			</li>
			{/loop}
		<ul>
	</div>
	<div class="main-footer">请选择一个测试单元进行测试</div>
</div>
{template 'footer.php'}