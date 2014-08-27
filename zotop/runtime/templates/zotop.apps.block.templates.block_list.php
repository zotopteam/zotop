<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<?php $this->display('header.php'); ?>
<div class="side">
<?php $this->display('block/side.php'); ?>
</div>

<div class="main side-main">
<div class="main-header">
<div class="title"><?php echo $category['name'];?> </div>

<form class="searchbar" method="post" action="<?php echo u('block/block');?>">
<input type="text" name="keywords" value="<?php echo $keywords;?>" placeholder="<?php echo t('请输入关键词');?>"  x-webkit-speech/>
<button type="submit"><i class="icon icon-search"></i></button>
</form>

<div class="action">
<div class="menu btn-menu">
<a class="btn btn-highlight btn-icon-text" href="javascript:void(0);"><i class="icon icon-add"></i><b><?php echo t('新建');?></b><b class="arrow"></b></a>
<div class="dropmenu">
<div class="dropmenulist">
<?php $n=1; foreach($types as $k => $v): ?>
<a href="<?php echo u('block/block/add/'.$k.'/'.$category['id']);?>"><i class="icon icon-item icon-<?php echo $k;?>"></i><?php echo $v;?></a>
<?php $n++;endforeach;unset($n); ?>
</div>
</div>
</div>
</div>
</div><!-- main-header -->

<div class="main-body scrollable">
<?php echo form::header();?>
<table class="table zebra list sortable" id="datalist" cellspacing="0" cellpadding="0">
<thead>
<tr>
<td class="drag">&nbsp;</td>
<td><?php echo t('标题');?></td>
<td class="w140"><?php echo t('标识');?></td>
<td class="w180 none"><?php echo t('模板标签');?></td>
<td class="w80"><?php echo t('类型');?></td>
<td class="w160"><?php echo t('更新时间');?></td>
</tr>
</thead>
<tbody>
<?php if(empty($data)):?>
<tr class="nodata"><td colspan="4"><div class="nodata"><?php echo t('暂时没有任何数据');?></div></td></tr>
<?php else: ?>
<?php $n=1; foreach($data as $r): ?>
<tr>
<td class="drag" title="<?php echo t('拖动排序');?>" data-placement="left">&nbsp;<input type="hidden" name="id[]" value="<?php echo $r['id'];?>"></td>
<td>
<div class="title textflow" title="<?php echo $r['title'];?>"<?php if($r['style']):?>style="<?php echo $r['style'];?>"<?php endif; ?>>
<?php echo $r['name'];?><span><?php echo $r['description'];?></span>
</div>
<div class="manage">
<a href="<?php echo u('block/block/data/'.$r['id']);?>"><?php echo t('数据管理');?></a>
<s></s>
<a class="ajax-post" href="<?php echo u('block/block/publish/'.$r['id']);?>"><?php echo t('发布');?></a>
<s></s>
<a href="<?php echo u('block/block/edit/'.$r['id']);?>"><?php echo t('设置');?></a>
<s></s>
<a class="dialog-confirm" href="<?php echo u('block/block/delete/'.$r['id']);?>"><?php echo t('删除');?></a>
</div>
</td>
<td><?php echo $r['uid'];?></td>
<td class="none"><input value="<?php echo $r['tag'];?>" class="text" style="width:80%"/></td>
<td><div class="textflow"><?php echo $types[$r['type']];?></div></td>
<td>
<div><?php echo m('system.user.get', $r['userid'], 'username');?></div>
<div class="f12 time"><?php echo format::date($r['updatetime']);?></div>
</td>
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