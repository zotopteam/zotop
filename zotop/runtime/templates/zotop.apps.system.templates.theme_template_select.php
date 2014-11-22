<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<?php $this->display('dialog.header.php'); ?>

<div class="main no-footer">
<div class="main-header">
<div class="position">
<a href="<?php echo u('system/theme/template/select');?>"><?php echo t('模版目录');?></a>
<?php $n=1; foreach($position as $p): ?>
<s class="arrow">></s> <a href="<?php echo u('system/theme/template/select?dir='.$p[1]);?>"><?php echo $p['0'];?></a>
<?php $n++;endforeach;unset($n); ?>
</div>
<div class="action">
<a class="btn btn-icon-text dialog-open"  data-width="600px" data-height="200px" href="<?php echo u('system/theme/template_newfolder?dir='.$dir);?>">
<i class="icon icon-folder"></i><b><?php echo t('新建目录');?></b>
</a>
<a class="btn btn-icon-text dialog-open"  data-width="600px" data-height="200px" href="<?php echo u('system/theme/template_newfile?dir='.$dir);?>">
<i class="icon icon-file"></i><b><?php echo t('新建模板');?></b>
</a>
<a class="btn btn-icon-text" href="javascript:location.reload();">
<i class="icon icon-refresh"></i><b><?php echo t('刷新');?></b>
</a>
</div>
</div><!-- main-header -->
<div class="main-body scrollable">

<table class="table zebra list" cellspacing="0" cellpadding="0">
<thead>
<tr>
<td class="w30 center"></td>
<td><?php echo t('名称');?></td>
<td class="w120"></td>
<td class="w140"><?php echo t('修改时间');?></td>
</tr>
</thead>
<tbody>
<?php $n=1; foreach($folders as $f): ?>
<tr>
<td class="center"><b class="icon icon-folder"></b></td>
<td>
<div class="textflow"><a href="<?php echo u('system/theme/template/select?dir='.$f['path']);?>"><?php echo $f['name'];?></a></div>
<div class="description"><?php echo $f['note'];?></div>
</td>
<td>
<div class="hidden right manage">
<a class="dialog-open" href="<?php echo u('system/theme/template_renamefolder?dir='.$f['path']);?>" data-width="600px" data-height="200px" title="<?php echo t('重命名');?> & <?php echo t('注释');?>">
<i class="icon icon-config"></i>
</a>
<s></s>
<a class="dialog-confirm" href="<?php echo u('system/theme/template_deletefolder?dir='.$f['path']);?>" title="<?php echo t('删除');?>">
<i class="icon icon-delete"></i>
</a>
</div>
</td>
<td><?php echo format::date($f['time']);?></td>
</tr>
<?php $n++;endforeach;unset($n); ?>
<?php $n=1; foreach($files as $f): ?>
<tr class="template <?php if($f['file'] == $_GET['file']):?>selected<?php endif; ?>" data-file="<?php echo $f['file'];?>">
<td class="center"><b class="icon icon-file"></b></td>
<td>
<div class="textflow"><?php echo $f['name'];?></div>
<div class="description"><?php echo $f['note'];?></div>
</td>
<td>
<div class="hidden right manage">
<a class="dialog-open" href="<?php echo u('system/theme/template_edit?file='.$f['path']);?>" data-width="1000px" data-height="500px" title="<?php echo t('编辑');?>">
<i class="icon icon-edit"></i>
</a>
<s></s>
<a class="dialog-open" href="<?php echo u('system/theme/template_copyfile?file='.$f['path']);?>" data-width="600px" data-height="200px" title="<?php echo t('复制');?>">
<i class="icon icon-copy"></i>
</a>
<s></s>
<a class="dialog-open" href="<?php echo u('system/theme/template_renamefile?file='.$f['path']);?>" data-width="600px" data-height="200px" title="<?php echo t('重命名');?> & <?php echo t('注释');?>">
<i class="icon icon-config"></i>
</a>

<s></s>
<a class="dialog-confirm" href="<?php echo u('system/theme/template_deletefile?file='.$f['path']);?>" title="<?php echo t('删除');?>">
<i class="icon icon-delete"></i>
</a>
</div>
</td>
<td><?php echo format::date($f['time']);?></td>
</tr>
<?php $n++;endforeach;unset($n); ?>
</tbody>
</table>

</div><!-- main-body -->
<div class="main-footer">
<?php echo t('网站主题和模版决定了网站的外观，你可以修改网站当前主题或者安装一个新的主题');?>
</div><!-- main-footer -->
</div><!-- main -->

<script type="text/javascript">

// 对话框设置
$dialog.callbacks['ok'] = function(){

var $selected = $('table.list').find('tr.selected');

if ( $selected['length'] == 0 ){
$.error('<?php echo t('请选择模版');?>');
return false;
}

$dialog.ok($selected.attr('data-file'));
return true;

};

$dialog.title('<?php echo t('选择模版');?>');


// 选择模版
$(function(){
$('table.list').on('click','tr.template',function(e){

//当点击为按钮时，禁止选择
if( $(e.target).parent().attr('tagName') == 'A' ) return false;

if ( $(this).hasClass('selected') ) {
$(this).removeClass("selected");
}else{
$(this).addClass('selected').siblings("tr.template").removeClass('selected'); //单选
}

return false;
});
});
</script>

<?php $this->display('dialog.footer.php'); ?>