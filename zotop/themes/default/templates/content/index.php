{template 'header.php'}
<div class="position">
    <ul>
        <li><a href="{u()}">{t('首页')}</a></li>
		{content action="position" cid="$category.id"}
		<li><a href="{$r.url}">{$r.name}</a></li>
		{/content}
    </ul>
</div>

{content action="category" cid="$category.id" return="c"}
	<div class="box">
	<div class="box-head">
		<div class="box-title">{$c.name}</div>
		<div class="box-action"><a class="more" href="{$c.url}">{t('更多')}</a></div>
	</div>
	<div class="box-body">
		<ul class="image-text">
			{content cid="$c.id" size="1" thumb="true"}
			<li>
				<div class="image"><a href="{$r.url}"><img src="{$r.thumb}" alt="{$r.title}"/></a></div>
				<div class="text">
					<b><a href="{$r.url}" title="{$r.title}" {$r.style}>{$r.title}{$r.new}</a></b>
					<p>{str::cut($r.summary,200)}</p>
				</div>
			</li>
			{/content}
		</ul>
		<ul class="list">
			{content cid="$c.id" size="8"}
			<li><a href="{$r.url}" title="{$r.title}" {$r.style}>{$r.title}{$r.new}</a></li>
			{/content}
		</ul>
	</div><!-- box-body -->
	</div><!-- box -->
{/content}


{template 'footer.php'}