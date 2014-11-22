<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<ul>
  <?php $n=1; foreach($data as $r): ?>
  <li><a href="<?php echo $r['style'];?>" <?php if($r['style']):?>style="<?php echo $r['style'];?>"<?php endif; ?>><?php echo $r['title'];?></a></li>
  <?php $n++;endforeach;unset($n); ?>
</ul>