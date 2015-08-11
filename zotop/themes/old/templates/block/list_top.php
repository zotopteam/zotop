<ul class="image-text">
{loop $data $i $r}
	<li>
		{if $r.image}
		<div class="image"><a href="{U($r.url)}"><img src="{$r.image}" alt="{$r.title}"/></a></div>
		{/if}
		<div class="text">
			<b><a href="{U($r.url)}" title="{$r.title}">{$r.title}</a></b>
			<p>
				{$r.description}
			</p>
		</div>		
	</li>
{/loop}
</ul>
