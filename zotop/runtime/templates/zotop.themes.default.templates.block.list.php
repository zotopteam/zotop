<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<div class="box">
<div class="box-head">
<h1 class="box-title"><?php echo $name;?></h1>
</div>
<div class="box-body">
<ul class="list">
<?php $n=1; foreach($data as $i => $r): ?>
<li><a href="<?php echo $r['url'];?>" target="_blank"><?php echo $r['title'];?></a></li>
<?php $n++;endforeach;unset($n); ?>
</ul>
</div>
</div><!-- box -->