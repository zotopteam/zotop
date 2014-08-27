<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<div class="side-header">
<?php echo A('shop.name');?>
</div><!-- side-header -->
<div class="side-body no-footer scrollable">

<ul class="sidenavlist">
<li class="none">
<a href="<?php echo u('shop/admin');?>"<?php if(substr_count(ZOTOP_URI,'shop/admin')):?> class="current"<?php endif; ?>>
<i class="icon icon-home"></i><?php echo t('商城首页');?>
</a>
</li>
<li>
<a href="<?php echo u('shop/goods');?>"<?php if(substr_count(ZOTOP_URI,'shop/goods') and !$categoryid):?> class="current"<?php endif; ?>>
<i class="icon icon-admin"></i><?php echo t('商品管理');?>
</a>
</li>
<li>
<table id="tree" class="table list navtree none" cellspacing="0" cellpadding="0">
<tbody>
<?php $n=1; foreach(m('shop.category.active') as $c): ?>
<tr data-tt-id="a<?php echo $c['id'];?>" <?php if($c['parentid'] !=0 ):?>data-tt-parent-id="a<?php echo $c['parentid'];?>"<?php endif; ?> <?php if($categoryid==$c['id']):?>class="current"<?php endif; ?>>
<td class="name">
<a data-href="<?php echo u('shop/goods/index/'.$c['id']);?>"><i class="icon <?php if($c['childid']):?>icon-folder<?php else: ?>icon-item<?php endif; ?>"></i><?php echo $c['name'];?></a>
</td>
</tr>
<?php $n++;endforeach;unset($n); ?>
</tbody>
</table>
</li>
<li>
<a href="<?php echo u('shop/category');?>"<?php if(substr_count(ZOTOP_URI,'shop/category')):?> class="current"<?php endif; ?>>
<i class="icon icon-category"></i><?php echo t('商品分类');?>
</a>
</li>

<li>
<a href="<?php echo u('shop/type');?>"<?php if(substr_count(ZOTOP_URI,'shop/type')):?> class="current"<?php endif; ?>>
<i class="icon icon-flag"></i><?php echo t('商品类型');?>
</a>
</li>

<?php if(c('shop.spec')):?>
<li>
<a href="<?php echo u('shop/spec');?>"<?php if(substr_count(ZOTOP_URI,'shop/spec')):?> class="current"<?php endif; ?>>
<i class="icon icon-flag"></i><?php echo t('商品规格');?>
</a>
</li>
<?php endif; ?>

<?php if(c('shop.brand')):?>
<li>
<a href="<?php echo u('shop/brand');?>"<?php if(substr_count(ZOTOP_URI,'shop/brand')):?> class="current"<?php endif; ?>>
<i class="icon icon-flag"></i><?php echo t('商品品牌');?>
</a>
</li>
<?php endif; ?>

<li>
<a href="<?php echo u('shop/config');?>"<?php if(substr_count(ZOTOP_URI,'shop/config')):?> class="current"<?php endif; ?>>
<i class="icon icon-config"></i><?php echo t('设置');?>
</a>
</li>
</ul>

</div><!-- side-body -->


<link rel="stylesheet" type="text/css" href="<?php echo A('system.url');?>/common/css/jquery.treetable.css"/>
<script type="text/javascript" src="<?php echo A('system.url');?>/common/js/jquery.treetable.js"></script>
<script type="text/javascript">
$(function(){
$("#tree").treetable({
column : 0,
indent : 18,
expandable : true,
persist: true,
initialState : 'collapsed', //"expanded" or "collapsed".
clickableNodeNames : true,
stringExpand: "<?php echo t('展开');?>",
stringCollapse: "<?php echo t('关闭');?>"
}).removeClass('none');

<?php if($categoryid):?>
$("#tree").treetable("reveal", "a<?php echo $categoryid;?>");
<?php endif; ?>


$("#tree").find('a[data-href]').click(function(){
location.href = $(this).attr('data-href');
});
})
</script>