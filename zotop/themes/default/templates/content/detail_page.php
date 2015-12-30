{template 'header.php'}

<div class="container">

	<ul class="breadcrumb">
	    <li><i class="fa fa-home"></i> <a href="{u()}">{t('首页')}</a></li>
		{content action="position" cid="$category.id"}
		<li><a href="{$r.url}">{$r.name}</a></li>
		{/content}
	    <li class="active">正文</li>
	</ul>

	<div class="row">
		<div class="col-sm-10 col-main">

		<div class="content content-box content-page">
			<h1 class="content-title">
				{$content.title}
			</h1>
			{if $content.pagecount}

			{else}
			<div class="content-body text-justify">
				{$content.content}
			</div>
			{/if}
		</div><!-- content -->

		</div><!-- main -->

		<div class="col-sm-2 col-side">
			<div class="blank hidden-md hidden-lg"></div>
			<dl class="list-group">
				<dt class="list-group-item">{$category.name}</dt>
				{content cid="$category.id"}
				<dd class="list-group-item"><a href="{$r.url}" {$r.style}>{$r.title}</a> {$r.new}</dd>
				{/content}			
			</dl>
		</div><!-- side -->

	</div><!-- row -->

</div> 

{template 'footer.php'}