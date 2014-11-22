<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<?php $this->display('header.php'); ?>
<div class="side">
<?php $this->display('block/admin_side.php'); ?>
</div>
<div class="main side-main">
<div class="main-header">
<div class="title"><?php echo $title;?></div>
<div class="action">
<a class="btn btn-icon-text btn-highlight dialog-open"  data-width="600px" data-height="200px" href="<?php echo u('block/category/add');?>">
<i class="icon icon-add"></i><b><?php echo t('添加分类');?></b>
</a>
</div>
</div><!-- main-header -->
<div class="main-body scrollable">
<?php echo form::header();?>
<table class="table zebra list sortable" cellspacing="0" cellpadding="0">
<thead>
<tr>
<td class="drag">&nbsp;</td>
<td class="w40 center none"><?php echo t('编号');?></td>
<td class="w400"><?php echo t('名称');?></td>
<td><?php echo t('说明');?></td>
<td class="w60 none"><?php echo t('数据');?></td>
</tr>
</thead>
<tbody>
<?php if(empty($data)):?>
<tr class="nodata"><td colspan="4"><div class="nodata"><?php echo t('暂时没有任何数据');?></div></td></tr>
<?php else: ?>
<?php $n=1; foreach($data as $r): ?>
<tr>
<td class="drag">&nbsp;<input type="hidden" name="id[]" value="<?php echo $r['id'];?>"></td>
<td class="center none"><?php echo $r['id'];?></td>
<td>
<div class="title"><?php echo $r['name'];?></div>
<div class="manage">
<a href="<?php echo u('block/admin/index/'.$r['id']);?>"><?php echo t('区块管理');?></a>
<s></s>
<a class="dialog-open"  data-width="600px" data-height="200px" href="<?php echo u('block/category/edit/'.$r['id']);?>"><?php echo t('编辑');?></a>
<s></s>
<a class="dialog-confirm" href="<?php echo u('block/category/delete/'.$r['id']);?>"><?php echo t('删除');?></a>
</div>
</td>
<td><?php echo $r['description'];?></td>
<td class="none"><?php echo intval($r['posts']);?></td>
</tr>
<?php $n++;endforeach;unset($n); ?>
<?php endif; ?>
</tbody>
</table>
<?php echo form::footer();?>
</div><!-- main-body -->
<div class="main-footer">
<div class="tips"><?php echo t('拖动列表项可以调整顺序');?></div>
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
$.msg(msg);
},'json');
}
});
});
</script>
<?php $this->display('footer.php'); ?>