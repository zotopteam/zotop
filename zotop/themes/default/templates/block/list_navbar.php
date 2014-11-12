<ul>
  {loop $data $r}
  <li><a href="{$r.style}" {if $r.style}style="{$r.style}"{/if}>{$r.title}</a></li>
  {/loop}
</ul>