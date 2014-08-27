<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<?php $this->display('header.php'); ?>
<div class="side">
<?php $this->display('member/admin_side.php'); ?>
</div><!-- side -->
<div class="main side-main">
<div class="main-header">
<div class="title"><?php echo $title;?></div>
<ul class="navbar">
<?php $n=1; foreach($models as $i => $m): ?>
<li<?php if($modelid == $i):?> class="current"<?php endif; ?>><a href="<?php echo u('member/group/index/'.$m['id']);?>"><?php echo $m['name'];?></a></li>
<?php $n++;endforeach;unset($n); ?>
</ul>
<div class="action">
<a class="btn btn-icon-text btn-highlight" href="<?php echo U('member/group/add/'.$modelid);?>">
<i class="icon icon-add"></i><b><?php echo t('添加');?></b>
</a>
</div>
</div>
<div class="main-body scrollable">
<?php if(empty($data)):?>
<div class="nodata"><?php echo t('没有找到任何数据');?></div>
<?php else: ?>
<?php echo form::header();?>
<table class="table zebra list sortable">
<thead>
<tr>
<td class="drag"></td>
<td class="center w60"><?php echo t('状态');?></td>
<td class="w200" ><?php echo t('名称');?></td>
<td><?php echo t('说明');?></td>
<td class="w100"><?php echo t('积分下限');?></td>
</tr>
</thead>
<tbody>
<?php $n=1; foreach($data as $r): ?>
<tr>
<td class="drag"><input type="hidden" name="id[]" value="<?php echo $r['id'];?>"/></td>
<td class="center"><?php if(!$r['disabled']):?><i class="icon icon-true true"></i><?php else: ?><i class="icon icon-false"></i><?php endif; ?></td>
<td>
<div class="title textflow"><?php echo $r['name'];?></div>
<div class="manage">
<a href="<?php echo u('member/group/edit/'.$r['id']);?>"><?php echo t('编辑');?></a>
<s></s>
<?php if($r['issystem']):?>
<a href="javascript:void(0);" class="disabled"><?php echo t('删除');?></a>
<?php else: ?>
<a href="<?php echo u('member/group/delete/'.$r['id']);?>" class="dialog-confirm"><?php echo t('删除');?></a>
<?php endif; ?>
</div>
</td>
<td><?php echo $r['description'];?></td>
<td><?php echo $r['point'];?></td>
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