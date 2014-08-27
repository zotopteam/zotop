<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<?php $this->display('header.php'); ?>
<div class="side">
<?php $this->display('content/side.php'); ?>
</div>
<div class="main side-main">
<div class="main-header">
<div class="title"><?php echo $title;?></div>
<div class="position">
<a href="<?php echo u('content/category');?>"><?php echo t('根栏目');?></a>
<?php $n=1; foreach($parents as $p): ?>
<s class="arrow">></s>
<a href="<?php echo u('content/category/index/'.$p['id']);?>"><?php echo $p['name'];?></a>
<?php $n++;endforeach;unset($n); ?>
</div>
<div class="action">
<a class="btn btn-highlight btn-icon-text" href="<?php echo u('content/category/add/'.$parentid);?>"><i class="icon icon-add"></i><b><?php echo t('添加栏目');?></b></a>
<a class="btn btn-icon-text dialog-confirm" href="<?php echo u('content/category/repair');?>"><i class="icon icon-refresh"></i><b><?php echo t('修复栏目');?></b></a>
</div>
</div><!-- main-header -->
<div class="main-body scrollable">
<?php echo form::header();?>
<table class="table list sortable" cellspacing="0" cellpadding="0">
<thead>
<tr>
<td class="drag">&nbsp;</td>
<td class="w40 center"><?php echo t('状态');?></td>
<td><?php echo t('名称');?></td>
<td class="w80"><?php echo t('编号');?></td>
<td class="w120"><?php echo t('别名');?></td>
<td class="w60"><?php echo t('数据');?></td>
</tr>
</thead>
<tbody>
<?php if(empty($data)):?>
<tr class="nodata"><td colspan="4"><div class="nodata"><?php echo t('暂时没有任何数据');?></div></td></tr>
<?php else: ?>
<?php $n=1; foreach($data as $r): ?>
<tr>
<td class="drag">&nbsp;<input type="hidden" name="id[]" value="<?php echo $r['id'];?>"></td>
<td class="center"><?php if($r['disabled']):?><i class="icon icon-false false"></i><?php else: ?><i class="icon icon-true true"></i><?php endif; ?></td>
<td>
<div class="title">
<?php echo $r['name'];?>

<?php if($r['childid']):?>	<i class="icon icon-folder" title="<?php echo t('有子栏目');?>"></i> <?php else: ?>	<i class="icon icon-item" title="<?php echo t('无子栏目');?>"></i>	<?php endif; ?>
</div>

<div class="manage">
<?php if($r['disabled']):?>
<a class="dialog-confirm" href="<?php echo u('content/category/status/'.$r['id']);?>"><?php echo t('启用');?></a>
<?php else: ?>

<a href="<?php echo $r['url'];?>" target="_blank"><?php echo t('访问');?></a>
<s></s>
<a href="<?php echo u('content/category/index/'.$r['id']);?>"><?php echo t('子栏目管理');?> [ <?php if($r['childid']):?><?php echo count(explode(',',$r['childid']));?><?php else: ?>0<?php endif; ?> ]</a>
<s></s>
<a href="<?php echo u('content/category/edit/'.$r['id']);?>"><?php echo t('编辑');?></a>
<s></s>
<a class="dialog-open" data-width="400" data-height="300" href="<?php echo u('content/category/move/'.$r['id']);?>"><?php echo t('移动');?></a>
<s></s>
<a class="dialog-confirm" href="<?php echo u('content/category/status/'.$r['id']);?>"><?php echo t('禁用');?></a>
<s></s>
<a class="dialog-confirm" href="<?php echo u('content/category/delete/'.$r['id']);?>"><?php echo t('删除');?></a>
<?php endif; ?>
</div>
</td>
<td><?php echo $r['id'];?></td>
<td><?php echo $r['alias'];?></td>
<td><?php echo intval($r['datacount']);?></td>
</tr>
<?php $n++;endforeach;unset($n); ?>
<?php endif; ?>
</tbody>
</table>
<?php echo form::footer();?>
</div><!-- main-body -->
<div class="main-footer">
<div class="tips">
<i class="icon icon-info"></i> <?php echo t('拖动列表项可以调整顺序');?>&nbsp;
<i class="icon icon-folder"></i> <?php echo t('有子栏目');?>&nbsp;
<i class="icon icon-item"></i> <?php echo t('无子栏目');?>&nbsp;
</div>
</div><!-- main-footer -->
</div><!-- main -->
<script type="text/javascript">
//sortable
$(function(){
$("table.sortable").sortable({
items: "tbody > tr",
axis: "y",
placeholder:"ui-sortable-placeholder",
helper: function(e,tr){
tr.children().each(function(){
$(this).width($(this).width());
});
return tr;
},
update:function(){
var action = $(this).parents('form').attr('action');
var data = $(this).parents('form').serialize();
$.post(action, data, function(msg){
$.msg(msg);location.reload();
},'json');
}
});
});
</script>
<?php $this->display('footer.php'); ?>