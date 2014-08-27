<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<?php $this->display('header.php'); ?>
<div class="side">
<?php $this->display('member/admin_side.php'); ?>
</div><!-- side -->
<div class="main side-main">
<div class="main-header">
<div class="title"><?php echo $title;?></div>
<div class="action">
<a class="btn btn-icon-text btn-highlight" href="<?php echo U('member/model/add');?>">
<i class="icon icon-add"></i><b><?php echo t('添加模型');?></b>
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
<td class="w400" ><?php echo t('名称');?></td>
<td><?php echo t('说明');?></td>
<td class="w160"><?php echo t('数据表名');?></td>
<td class="center w100"><?php echo t('允许注册');?></td>
<td class="center w100"><?php echo t('会员数目');?></td>
</tr>
</thead>
<tbody>
<?php $n=1; foreach($data as $r): ?>
<tr>
<td class="drag"><input type="hidden" name="id[]" value="<?php echo $r['id'];?>"/></td>
<td class="center"><?php if($r['disabled']):?><i class="icon icon-false false"></i><?php else: ?><i class="icon icon-true true"></i><?php endif; ?></td>
<td>
<div class="title textflow"><?php echo $r['name'];?> <span><?php echo $r['id'];?></span></div>
<div class="manage">
<a href="<?php echo u('member/model/edit/'.$r['id']);?>"><?php echo t('设置');?></a>
<s></s>
<a href="<?php echo u('member/group/index/'.$r['id']);?>"><?php echo t('会员组');?></a>
<s></s>
<a href="<?php echo u('member/field/index/'.$r['id']);?>"><?php echo t('字段管理');?></a>
<s></s>
<a href="<?php echo u('member/model/delete/'.$r['id']);?>" class="dialog-confirm"><?php echo t('删除');?></a>
</div>
</td>
<td><?php echo $r['description'];?></td>
<td><?php echo $r['tablename'];?></td>
<td class="center"><?php if($r['settings']['register']):?><i class="icon icon-true true"></i><?php else: ?><i class="icon icon-false false"></i><?php endif; ?></td>
<td class="center">0</td>
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