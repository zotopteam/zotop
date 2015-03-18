<ul>
  {loop $data $r}
  <li><a href="{$r.url}" {if $r.style}style="{$r.style}"{/if}><span>{$r.title}</span></a></li>
  {/loop}
</ul>