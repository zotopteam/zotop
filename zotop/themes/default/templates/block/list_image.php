<div class="image-list">
{loop $data $i $r}
	<a href="{U($r.url)}" title="{$r.title}">
		<div class="image"><img src="{$r.image}" alt="{$r.title}"></div>
		<div class="title" {$r.style}>{$r.title}</div>
	</a>
{/loop}
</div>
