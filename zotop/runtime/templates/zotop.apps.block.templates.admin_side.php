<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<div class="side-header">
<?php echo A('block.name');?>
</div><!-- side-header -->
<div class="side-body no-footer scrollable">

<ul class="sidenavlist">
<?php $n=1; foreach(m('block.category.getall') as $c): ?>
<li>
<a href="<?php echo u('block/admin/index/'.$c['id']);?>"<?php if(substr_count(ZOTOP_URI,'block/admin') and $c['id'] == $categoryid):?> class="current"<?php endif; ?>>
<i class="icon icon-folder"></i><?php echo $c['name'];?>
</a>
</li>
<?php $n++;endforeach;unset($n); ?>
<li class="blank"></li>
<li>
<a href="<?php echo u('block/category');?>"<?php if(substr_count(ZOTOP_URI,'block/category')):?> class="current"<?php endif; ?>>
<i class="icon icon-category"></i><?php echo t('分类管理');?>
</a>
</li>
</ul>

</div>