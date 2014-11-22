<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<?php $this->display('header.php'); ?>
<div class="side">
<?php $this->display('block/admin_side.php'); ?>
</div>

<?php echo form::header(U('block/datalist/order/'.$block['id']));?>
<div class="main side-main">
<div class="main-header">
<div class="title"><?php echo $title;?></div>
<div class="position">
<a href="<?php echo u('block/admin/index/'.$categoryid);?>"><?php echo $category['name'];?></a>
<s class="arrow">></s>
<?php echo t('内容维护');?>			
<s class="arrow">></s>
<?php echo $block['name'];?>			
</div>	
<div class="action">
<a class="btn btn-icon-text btn-highlight dialog-open" href="<?php echo u('block/datalist/add/'.$block['id']);?>" data-width="800px" data-height="400px"><i class="icon icon-add"></i><b><?php echo t('添加');?></b></a>
<a class="btn btn-icon-text dialog-open" href="<?php echo u('block/index/preview/'.$block['id']);?>" data-width="800px" data-height="400px"><i class="icon icon-view"></i><b><?php echo t('预览');?></b></a>
<a class="btn btn-icon-text" href="<?php echo u('block/admin/edit/'.$block['id']);?>"><i class="icon icon-setting"></i><b><?php echo t('设置');?></b></a>
</div>
</div><!-- main-header -->
<div class="main-body scrollable">
<?php if($block['data'] and is_array($block['data'])):?>		
<table class="table zebra list sortable" id="datalist" cellspacing="0" cellpadding="0">
<thead>
<tr>
<td class="drag">&nbsp;</td>
<td class="w40 center"><?php echo t('行号');?></td>
<td><?php echo t('标题');?></td>
<td class="w300"><?php echo t('操作');?></td>
<td class="w140"><?php echo t('发布时间');?></td>
</tr>
</thead>
<tbody>
<?php $n=1; foreach(m('block.datalist.getlist',$block['id']) as $i => $r): ?>
<tr>
<td class="drag" title="<?php echo t('拖动排序');?>" data-placement="left">&nbsp;<input type="hidden" name="id[]" value="<?php echo $r['id'];?>"></td>
<td class="w40 center"></td>
<td>
<div class="title overflow">
<?php if($r['url']):?>
<a href="<?php echo U($r['url']);?>" target="_blank"><?php echo $r['title'];?></a>
<?php else: ?>
<?php echo $r['title'];?>
<?php endif; ?>
</div> 
</td>
<td>
<div class="manage">
<?php if($r['stick']):?>
<a href="<?php echo U('block/datalist/stick/'.$r['id']);?>" class="ajax-post"><i class="icon icon-down"></i> <?php echo t('取消置顶');?></a>
<?php else: ?>
<a href="<?php echo U('block/datalist/stick/'.$r['id']);?>" class="ajax-post"><i class="icon icon-up"></i> <?php echo t('设为置顶');?></a>
<?php endif; ?>
<s>|</s>
<a href="<?php echo U('block/datalist/edit/'.$r['id']);?>" data-width="800px" data-height="400px" class="dialog-open"><i class="icon icon-edit"></i> <?php echo t('编辑');?></a>
<s>|</s>
<a href="<?php echo U('block/datalist/delete/'.$r['id']);?>" class="dialog-confirm"><i class="icon icon-delete"></i> <?php echo t('删除');?></a>
</div>
</td>
<td class="w140"><?php echo format::date($r['createtime'],'datetime');?></td>
</tr>					
<?php $n++;endforeach;unset($n); ?>				
</tbody>
</table>
<?php else: ?>
<div class="nodata"><?php echo t('暂时没有任何数据');?></div>
<?php endif; ?>

<?php if($block['description']):?>
<div class="description">
<div class="tips"><i class="icon icon-info alert"></i> <?php echo $block['description'];?> </div>
</div>
<?php endif; ?>

</div><!-- main-body -->
<div class="main-footer">

</div><!-- main-footer -->
</div><!-- main -->
<?php echo form::footer();?>

<style type="text/css">
div.description{line-height:22px;font-size:14px;clear: both; border: solid 1px #F2E6D1; background: #FCF7E4; color: #B25900; border-radius: 5px;margin: 10px 0; padding: 10px;}
</style>
<script type="text/javascript">

// 重排行号
function linenumber(){
    $("table.sortable tbody tr").each(function(d, a) {
        $(a).find("td:eq(1)").text(d + 1);
    });	
}

//sortable
$(function(){
linenumber();

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
var action 	= $(this).parents('form').attr('action');
var data 	= $(this).parents('form').serialize();

$.post(action, data, function(msg){
linenumber();
$.msg(msg);
},'json');
}
});
});
</script>

<?php $this->display('footer.php'); ?>