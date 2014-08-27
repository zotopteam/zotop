<div class="box">
<div class="box-head">
	<h1 class="box-title">{$name}</h1>
</div>
<div class="box-body">
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
</div>
</div><!-- box -->