{template 'header.php'}



<div class="channel clearfix">
	<h1>{m('content.category.get',$category.rootid, 'name')}</h1>
	<ul>
		{content action="category" cid="$category.rootid" return="c"}			
			<li class="item-{$n} {if $category.id == $c.id}current{/if}"><s></s><a href="{$c.url}">{$c.name}</a></li>
		{/content}
	</ul>
</div>

<div class="position">
    <ul>
        <li><a href="{u()}">{t('首页')}</a></li>
		{content action="position" cid="$category.id"}
		<li><a href="{$r.url}">{$r.name}</a></li>
		{/content}
    </ul>
</div>

<div class="blank"></div>

<ul class="image-text pagelist">
	{content cid="$category.id" page="true" size="20"}

	{if $n>0 and $n%5==0}<hr/>{/if}

	<li>
		{if $r.thumb}<div class="image"><a href="{$r.url}"><img src="{thumb($r.thumb,180,120)}" alt="{$r.title}"/></a></div>{/if}
		<div class="text">
			<b><a href="{$r.url}" title="{$r.title}" {$r.style}>{$r.title}</a>{$r.new}</b>
			<p>{str::cut($r.summary,200)}</p>
		</div>
	</li>
	{/content}
</ul>

<div class="pagination">{$pagination}</div>
{template 'footer.php'}