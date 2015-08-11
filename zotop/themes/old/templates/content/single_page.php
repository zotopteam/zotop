{template 'header.php'}

<div class="channel clearfix">
	<h1>{m('content.category.get',$category.rootid, 'name')}</h1>
	<ul>
		{content cid="$category.id" size="5"}
		<li class="item-{$n} {if $content.id==$r.id}current{/if}"><a href="{$r.url}" {$r.style}><s></s>{$r.title}{$r.new}</a></li>
		{/content}
	</ul>
</div>

<div class="position none">
    <ul>
        <li><a href="{u()}">{t('首页')}</a></li>
		{content action="position" cid="$category.id"}
		<li><a href="{$r.url}">{$r.name}</a></li>
		{/content}
        <li>{$content.title}</li>
    </ul>
</div>

<div class="blank"></div>

<div class="row">

	<div class="content">
		<h1 class="content-title none">
			{$content.title}
		</h1>
		<div class="content-body">
			{$content.content}
		</div>
	</div><!-- content -->


</div><!-- row -->

{template 'footer.php'}