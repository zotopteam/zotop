<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<?php $this->display('header.php'); ?>
<div class="main">
<div class="main-header">
<div class="title"><?php echo $title;?></div>
<div class="action">
<a class="btn btn-icon-text btn-highlight dialog-open" href="<?php echo U('developer/project/add');?>" data-width="800" data-height="400">
<i class="icon icon-add"></i><b><?php echo t('新建应用');?></b>
</a>
</div>
</div>
<div class="main-body scrollable">
<?php if(empty($projects)):?>
<div class="nodata"><?php echo t('没有找到任何在建应用');?></div>
<?php else: ?>
<?php $n=1; foreach($projects as $r): ?>
<div class="projects">
<div class="icon">
<img src="<?php echo ZOTOP_URL_APPS;?>/<?php echo $r['dir'];?>/app.png">
</div>
<div class="info">
<div class="title"><?php echo $r['name'];?> <span>( <?php echo $r['id'];?> )</span></div>

<table class="attr">
<tr>
<td class="w100"><?php echo t('标识');?></td>
<td><?php echo $r['id'];?></td>
<td class="w100"><?php echo t('目录');?></td>
<td><?php echo $r['dir'];?></td>
<td class="w100"><?php echo t('版本');?></td>
<td><?php echo $r['version'];?></td>
</tr>
<tr>
<td class="w100"><?php echo t('类型');?></td>
<td><?php echo $r['type'];?></td>

<td class="w100"><?php echo t('依赖');?></td>
<td><?php echo $r['dependencies'];?></td>
<td class="w100"><?php echo t('数据表');?></td>
<td><?php echo $r['tables'];?></td>
</tr>

<tr>
<td class="w100"><?php echo t('作者');?></td>
<td><?php echo $r['author'];?></td>
<td class="w100"><?php echo t('邮箱');?></td>
<td><?php echo $r['email'];?></td>
<td class="w100"><?php echo t('网站');?></td>
<td colspan="3"><?php echo $r['homepage'];?></td>
</tr>
<tr>
<td class="w100"><?php echo t('描述');?></td>
<td colspan="5"><?php echo $r['description'];?></td>
</tr>
</table>
<div class="manage">
<a class="btn btn-icon-text btn-highlight" href="<?php echo u('developer/project/index','project='.$r['dir']);?>">
<i class="icon icon-edit"></i><b><?php echo t('管理应用');?></b>
</a>
</div>
</div>
</div>
<?php $n++;endforeach;unset($n); ?>
<?php endif; ?>
</div>
<div class="main-footer">
<div class="tips"><?php echo t('管理 apps 目录下在建的应用');?></div>
</div>
</div>
<style type="text/css">
.projects{border:solid 2px #ebebeb;margin:20px 0;background:#F3F3F3;}
.projects div.icon {float:left;padding:20px 0;width:120px;text-align:center;overflow:hidden;}
.projects div.icon img{width:80px;}
.projects div.info {margin-left:121px;font-size:15px;line-height:30px;}
.projects div.title{line-height:50px;font-size:24px;}
.projects div.title span{font-size:20px;color:#666;}
.projects div.manage{padding:10px 0;}
.projects table{border-top:solid 1px #ebebeb;}
.projects table td{padding:5px 0;border-bottom:solid 1px #ebebeb;}
</style>
<?php $this->display('footer.php'); ?>