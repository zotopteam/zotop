<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<?php $this->display('header.php'); ?>
<div class="side">
<?php $this->display('system/system_side.php'); ?>
</div>
<div class="main side-main">
<div class="main-header">
<div class="title"><?php echo t('应用管理');?></div>
<ul class="navbar">
<?php $n=1; foreach($navbar as $k => $n): ?>
<li<?php if(ZOTOP_ACTION == $k):?> class="current"<?php endif; ?>><a href="<?php echo $n['href'];?>"><?php echo $n['text'];?></a></li>
<?php $n++;endforeach;unset($n); ?>
</ul>
</div><!-- main-header -->
<div class="main-body scrollable">

<?php echo form::header();?>
<table class="table list sortable" cellspacing="0" cellpadding="0">
<thead>
<tr>
<td class="drag"></td>
<td class="w50 center"><?php echo t('图标');?></td>
<td class="w260"><?php echo t('名称');?></td>
<td class="w60"><?php echo t('版本');?></td>
<td><?php echo t('说明');?></td>
</tr>
</thead>
<tbody>
<?php $n=1; foreach($apps as $id => $m): ?>

<?php if(!in_array($id, $cores)):?>
<tr<?php if($m['status']==0):?> class="disabled"<?php endif; ?>>
<td class="drag">&nbsp;<input type="hidden" name="id[]" value="<?php echo $m['id'];?>"></td>
<td class="w50 vt center">
<img src="<?php echo ZOTOP_URL_APPS;?>/<?php echo $m['dir'];?>/app.png" style="width:48px;height:48px;margin-top:4px;<?php if($m['status']==0):?>opacity: 0.2;<?php endif; ?>">
</td>
<td class="w260 vt">
<div class="title"><?php echo $m['name'];?> <span class="green f12"><?php echo $m['id'];?></span></div>

<div class="manage">

<?php if(file::exists(ZOTOP_PATH_APPS.DS.$m['dir'].DS.'controllers'.DS.'config.php')):?>
<a href="<?php echo U($id.'/config');?>"><?php echo t('设置');?></a>
<s></s>
<?php endif; ?>

<?php if($m['status']==0):?>
<a class="dialog-confirm" title="<?php echo t('启用该应用');?>" href="<?php echo U('system/app/status/'.$id);?>"><?php echo t('启用');?></a>
<?php else: ?>
<a class="dialog-confirm" title="<?php echo t('禁用该应用');?>" href="<?php echo U('system/app/status/'.$id);?>"><?php echo t('禁用');?></a>
<?php endif; ?>

<s></s>
<a class="dialog-open" data-width="800" data-height="400" title="<?php echo t('卸载该应用');?>" href="<?php echo U('system/app/uninstall/'.$id);?>"><?php echo t('卸载');?></a>

</div>

</td>
<td class="w60 vt">
v<?php echo $m['version'];?>
</td>
<td class="vt">
<div><?php echo $m['description'];?></div>
<div class="manage gray f12">
<?php if($m['author']):?> <?php echo t('作者');?>: <?php echo $m['author'];?> <?php endif; ?>
<?php if($m['homepage']):?> <s></s> <a target="_blank" href="<?php echo $m['homepage'];?>"><?php echo t('网站');?></a> <?php endif; ?>
</div>
</td>
</tr>
<?php endif; ?>
<?php $n++;endforeach;unset($n); ?>
</tbody>
</table>
<?php echo form::footer();?>
</div><!-- main-body -->
<div class="main-footer textflow">
<div class="tips">
<?php echo t('小贴士：应用是对系统现有功能的扩展。如：内容发布使用内容管理应用(content)，会员管理使用会员系统(member)等，获取最新应用请登陆<a target="_blank"href="%s">官方网站</a>','http://www.zotop.com');?>
</div>
</div><!-- main-footer -->
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
$.msg(msg);location.reload();
},'json');
}
});
});
</script>
<?php $this->display('footer.php'); ?>