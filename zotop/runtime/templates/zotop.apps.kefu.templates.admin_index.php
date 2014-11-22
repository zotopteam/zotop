<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<?php $this->display('header.php'); ?>

<div class="side">
<?php $this->display('kefu/admin_side.php'); ?>
</div>

<div class="main side-main">
<div class="main-header">
<div class="title"><?php echo $title;?></div>
<div class="action">
<a class="btn btn-icon-text btn-highlight dialog-open" href="<?php echo U('kefu/admin/add');?>" data-width="800px" data-height="400px">
<i class="icon icon-add"></i><b><?php echo t('添加');?></b>
</a>
</div>
</div>
<div class="main-body scrollable">
<?php if(empty($data)):?>
<div class="nodata"><?php echo t('没有找到任何数据');?></div>
<?php else: ?>
<?php echo form::header();?>
<table class="table list sortable">
<thead>
<tr>
<td class="drag"></td>
<td class="w40 center"><?php echo t('状态');?></td>
<td><?php echo t('名称');?></td>
<td class="w100"><?php echo t('类型');?></td>					
<td class="w300"><?php echo t('预览');?></td>
</tr>
</thead>
<tbody>
<?php $n=1; foreach($data as $r): ?>
<tr>
<td class="drag">&nbsp;<input type="hidden" name="id[]" value="<?php echo $r['id'];?>"></td>
<td class="w40 center"><?php if($r['disabled']):?><i class="icon icon-false false"></i><?php else: ?><i class="icon icon-true true"></i><?php endif; ?></td>
<td>
<div class="title textflow">
<?php if($r['account']):?>
<?php echo $r['account'];?><span><?php echo $r['text'];?></span>
<?php else: ?>
<?php echo $r['text'];?>
<?php endif; ?>						
</div>
<div class="manage">
<a href="<?php echo u('kefu/admin/edit/'.$r['id']);?>" class="dialog-open" data-width="800px" data-height="400px"><?php echo t('编辑');?></a>
<s></s>
<a href="<?php echo u('kefu/admin/delete/'.$r['id']);?>" class="dialog-confirm"><?php echo t('删除');?></a>
</div>
</td>
<td><?php echo m('kefu.kefu.types',$r['type']);?></td>
<td><div class="title textflow"><?php echo $r['show'];?></div></td>
</tr>
<?php $n++;endforeach;unset($n); ?>
<tbody>
</table>
<?php echo form::footer();?>
<?php endif; ?>
</div>
<div class="main-footer">

</div>
</div>

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