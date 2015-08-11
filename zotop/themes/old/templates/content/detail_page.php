{template 'header.php'}
<div class="position">
    <ul>
        <li><a href="{u()}">{t('首页')}</a></li>
		{content action="position" cid="$category.id"}
		<li><a href="{$r.url}">{$r.name}</a></li>
		{/content}
        <li>{$content.title}</li>
    </ul>
</div>

<div class="row">
<div class="main">
<div class="main-inner">

	<div class="content">
		<h1 class="content-title">
			{$content.title}
		</h1>
		<div class="content-body">
			{$content.content}
		</div>
	</div><!-- content -->

</div>
</div><!-- main -->

<div class="side">
	<div class="box">
	<div class="box-head">
		<div class="box-title">{$category.name}</div>
	</div>
	<div class="box-body">

		<ul class="list">
			{content cid="$category.id" size="5"}
			<li><a href="{$r.url}" {$r.style}>{$r.title}{$r.new}</a></li>
			{/content}
		</ul>

	</div><!-- box-body -->
	</div><!-- box -->
</div><!-- side -->
</div><!-- row -->

{template 'footer.php'}