<div class="box">
<div class="box-head">
	<h1 class="box-title">{$name}</h1>
</div>
<div class="box-body">
	<ul class="list">
	{loop $data $i $r}
		<li><span class="extra">{format::date($r.time)}</span><a href="{$r.url}" target="_blank">{$r.title}</a></li>
	{/loop}
	</ul>
</div>
</div><!-- box -->