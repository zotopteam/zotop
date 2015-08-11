<ul class="image-list">
{loop $data $i $r}
	<li>
		<div class="image"><a href="{U($r.url)}" title="{$r.title}"><img src="{$r.image}" alt="{$r.title}"></a></div>
		<div class="title"><a href="{U($r.url)}" title="{$r.title}" {$r.style}>{$r.title}</a></div>
	</li>
{/loop}
</ul>
