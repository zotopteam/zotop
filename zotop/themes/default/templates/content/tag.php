{template 'header.php'}
<div class="position">
    <ul>
        <li><a href="{u()}">{t('首页')}</a></li>
		<li>{t('标签')}</li>
        <li>{$tag}</li>
    </ul>
</div>

<h1>{t('标签')} : {$tag}</h1>

<ul class="image-text pagelist">
	{content keywords="$tag" page="true" size="20"}

	{if $n>0 and $n%5==0}<hr/>{/if}
	
	<li>
		{if $r.image}<div class="image fr"><a href="{$r.url}"><img src="{$r.image}" alt="{$r.title}"/></a></div>{/if}
		<div class="text">
			<b><a href="{$r.url}" title="{$r.title}" {$r.style}>{$r.title}{$r.new}</a></b>
			<p>{str::cut($r.summary,200)}</p>
			<p>{loop $r.tags $t} <a href="{u('content/tag/'.$t)}">{$t}</a> {/loop}</p>
		</div>
	</li>
	
	{/content}
</ul>

<div class="pagination">{$pagination}</div>

{template 'footer.php'}