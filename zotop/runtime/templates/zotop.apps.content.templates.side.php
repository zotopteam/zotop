<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<div class="side-header">
<?php echo A('content.name');?>
</div><!-- side-header -->
<div class="side-body scrollable">
<ul class="sidenavlist">
<li class="title">
<a href="<?php echo u('content/content/index');?>" <?php if(ZOTOP_CONTROLLER == 'content' and !$categoryid):?>class="current"<?php endif; ?>><i class="icon icon-admin"></i><?php echo t('内容管理');?></a>
</li>
<li>
<table id="tree" class="table list navtree none" cellspacing="0" cellpadding="0">
<tbody>
<?php $n=1; foreach(m('content.category.active') as $c): ?>
<tr data-tt-id="a<?php echo $c['id'];?>" <?php if($c['parentid'] !=0 ):?>data-tt-parent-id="a<?php echo $c['parentid'];?>"<?php endif; ?> <?php if($categoryid==$c['id']):?>class="current"<?php endif; ?>>
<td class="name">
<a data-href="<?php echo u('content/content/index/'.$c['id']);?>"><i class="icon <?php if($c['childid']):?>icon-folder<?php else: ?>icon-item<?php endif; ?>"></i><?php echo $c['name'];?></a>
</td>
</tr>
<?php $n++;endforeach;unset($n); ?>
</tbody>
</table>
</li>
<li><a href="<?php echo u('content/config');?>" <?php if(substr_count(ZOTOP_URI,'content/config')):?>class="current"<?php endif; ?>><i class="icon icon-config"></i><?php echo t('内容设置');?></a></li>
<li><a href="<?php echo u('content/category');?>" <?php if(substr_count(ZOTOP_URI,'content/category')):?>class="current"<?php endif; ?>><i class="icon icon-category"></i><?php echo t('栏目管理');?></a></li>
<li><a href="<?php echo u('content/model');?>" <?php if(substr_count(ZOTOP_URI,'content/model')):?>class="current"<?php endif; ?>><i class="icon icon-model"></i><?php echo t('模型管理');?></a></li>
<li class="none"><a href="<?php echo u('content/admintags');?>" <?php if(substr_count(ZOTOP_URI,'content/admintags')):?>class="current"<?php endif; ?>><i class="icon icon-tag"></i><?php echo t('Tag管理');?></a></li>
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