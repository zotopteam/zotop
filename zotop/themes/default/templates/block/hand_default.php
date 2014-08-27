<ul class="list">
{loop $data $i $row}
	<li>
		{loop $row $j $r}
		{if $j>0}&nbsp;&nbsp;{/if}
		<a href="{U($r.url)}" title="{$r.title}"{if $r.style} style="{$r.style}"{/if}>{$r.title}</a>
		{/loop}
	</li>
{/loop}
</ul>
