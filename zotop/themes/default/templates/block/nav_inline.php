<ul class="nav navbar-nav">
	{loop $data $i $r}
    <li><a href="{$r.url}" {$r.style}>{$r[title]}</a></li>
    {/loop}
</ul>
