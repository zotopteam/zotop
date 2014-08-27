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
<li<?php if($modelid == $i):?> class="current"<?php endif; ?>><a href="<?php echo u('member/field/index/'.$m['id']);?>"><?php echo $m['name'];?></a></li>
<?php $n++;endforeach;unset($n); ?>
</ul>
<div class="action">
<a class="btn btn-icon-text btn-highlight" href="<?php echo U('member/field/add/'.$modelid);?>">
<i class="icon icon-add"></i><b><?php echo t('添加字段');?></b>
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
<td class="center w50"><?php echo t('状态');?></td>
<td><?php echo t('标签名');?> ( <?php echo t('字段名');?> )</td>
<td class="w100"><?php echo t('控件');?></td>
<td class="w100 center"><?php echo t('注册时显示');?></td>
<td class="w100 center"><?php echo t('必填');?></td>
<td class="w100 center"><?php echo t('值唯一');?></td>
</tr>
</thead>
<tbody>
<?php $n=1; foreach($data as $r): ?>
<tr>
<td class="drag"><input type="hidden" name="id[]" value="<?php echo $r['id'];?>"/></td>
<td class="center"><?php if(!$r['disabled']):?><i class="icon icon-true true"></i><?php else: ?><i class="icon icon-false"></i><?php endif; ?></td>
<td>
<div class="title textflow"><?php echo $r['label'];?> ( <?php echo $r['name'];?> )</div>
<div class="manage">
<a href="<?php echo u('member/field/edit/'.$r['id']);?>"><?php echo t('编辑');?></a>
<s></s>
<?php if($r['issystem']):?>
<a href="javascript:void(0);" class="disabled"><?php echo t('删除');?></a>
<?php else: ?>
<a href="<?php echo u('member/field/delete/'.$r['id']);?>" class="dialog-confirm" data-confirm="<b><?php echo t('您确定要删除吗？删除后将删除全部相关数据并且无法恢复！');?></b>"><?php echo t('删除');?></a>
<?php endif; ?>
</div>
</td>
<td><?php echo $controls[$r['control']]['name'];?></td>
<td class="center"><?php if($r['base']):?><i class="icon icon-true true"></i><?php else: ?><i class="icon icon-false"></i><?php endif; ?></td>
<td class="center"><?php if($r['notnull']):?><i class="icon icon-true true"></i><?php else: ?><i class="icon icon-false"></i><?php endif; ?></td>
<td class="center"><?php if($r['unique']):?><i class="icon icon-true true"></i><?php else: ?><i class="icon icon-false"></i><?php endif; ?></td>
</tr>
<?php $n++;endforeach;unset($n); ?>
<tbody>
</table>
<?php echo form::footer();?>
<?php endif; ?>
</div>
<div class="main-footer">
<?php echo t('管理会员扩展信息字段，拖动行可以进行排序');?>
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