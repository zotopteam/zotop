<ul>
  {loop $data $r}
  <li><a href="{U($r['url'])}">{$r['title']}</a></li>
  {/loop}
</ul>