<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<?php $this->display('header.php'); ?>
<div class="side">
<?php $this->display('system/system_side.php'); ?>
</div>
<div class="main side-main">
<div class="main-header">
<div class="title"><?php echo $title;?></div>
<ul class="navbar">
<?php $n=1; foreach($navbar as $k => $n): ?>
<li<?php if(ZOTOP_ACTION == $k):?> class="current"<?php endif; ?>><a href="<?php echo $n['href'];?>"><?php echo $n['text'];?></a></li>
<?php $n++;endforeach;unset($n); ?>
</ul>
</div><!-- main-header -->
<div class="main-body scrollable">
<?php if(empty($apps)):?>
<div class="nodata"><?php echo t('没有未安装的应用，你可以 在线安装 或者 上传 一个新的应用');?></div>
<?php else: ?>
<table class="table list" cellspacing="0" cellpadding="0">
<tbody>
<?php $n=1; foreach($apps as $dir => $m): ?>
<tr>
<td class="w50 vt center">
<img src="<?php echo ZOTOP_URL_APPS;?>/<?php echo $dir;?>/app.png" style="width:48px;height:48px;margin-top:4px;">
</td>
<td class="w260 vt">
<div class="title"><?php echo $m['name'];?> <span class="green f12"><?php echo $m['id'];?></span></div>
<div class="manage">
<a class="dialog-open" data-width="800" data-height="400" title="<?php echo t('安装该应用');?>" href="<?php echo U('system/app/install',array('dir'=>rawurlencode($dir)));?>"><?php echo t('安装');?></a>
<s></s>
<a class="dialog-confirm"  title="<?php echo t('删除该应用');?>" href="<?php echo U('system/app/delete',array('dir'=>rawurlencode($dir)));?>"><?php echo t('删除');?></a>
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
<?php $n++;endforeach;unset($n); ?>
</tbody>
</table>
<?php endif; ?>
</div><!-- main-body -->
<div class="main-footer textflow">
<div class="tips">
<?php echo t('应用必须位于 %s 目录下才会显示，安装时点击该应用的<span class="red">安装</span>按钮，根据系统提示完成应用安装', str_replace(ZOTOP_PATH.DS,'',ZOTOP_PATH_APPS));?>
</div>
</div><!-- main-footer -->
</div>
<?php $this->display('footer.php'); ?>