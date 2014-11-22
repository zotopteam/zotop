<?php defined('ZOTOP') or exit('No permission resources.'); ?>

<table class="field">
<tbody>
<tr>
<td class="label"><?php echo form::label(t('行数'),'rows',false);?></td>
<td class="input">
<div class="input-group">
<?php echo form::field(array('type'=>'number','name'=>'rows','value'=>intval($data['rows']),'min'=>0));?>
<span class="input-group-addon"><?php echo t('行');?></span>
</div>
<?php echo form::tips(t('0表示无固定行数'));?>
</td>
</tr>

<tr>
<td class="label"><?php echo form::label(t('字段'),'fields',false);?></td>
<td class="input">
<table class="controls table border">
<thead>
<tr>
<td class="w50 center"><?php echo t('显示');?></td>
<td class="w100"><?php echo t('字段名称');?></td>
<td class="w100">
<?php echo t('字段标识');?> &nbsp;	<i class="icon icon-help" title="<?php echo t('可在模板中调用');?>"></i>
</td>
<td class="w50 center"><?php echo t('必填');?></td>
<td><?php echo t('设置');?></td>
</tr>
</thead>
<tbody>
<?php $n=1; foreach(m('block.block.fieldlist',$data['fields']) as $k => $v): ?>
<tr>
<td class="center">
<?php if($v['name']=='title'):?>
<input type="checkbox" class="checkbox disabled" checked disabled>
<input type="hidden" name="fields[<?php echo $k;?>][show]" class="checkbox" value="1" checked>
<?php else: ?>
<input type="checkbox" name="fields[<?php echo $k;?>][show]" class="checkbox" value="1" <?php if($v['show']):?>checked="checked"<?php endif; ?>>
<?php endif; ?>
</td>
<td>
<input type="text" name="fields[<?php echo $k;?>][label]" class="text tiny" value="<?php echo $v['label'];?>">
</td>
<td>
<input type="hidden" name="fields[<?php echo $k;?>][name]" class="text" value="<?php echo $v['name'];?>">
<input type="text" name="showname" class="text tiny" value="<?php echo $v['name'];?>" disabled>
</td>
<td class="center">
<?php if($v['name']=='title'):?>
<input type="checkbox" class="checkbox disabled" checked disabled>
<input type="hidden" name="fields[<?php echo $k;?>][required]" class="checkbox" value="required" checked>
<?php else: ?>
<input type="checkbox" name="fields[<?php echo $k;?>][required]" class="checkbox" value="required" <?php if($v['required']):?>checked="checked"<?php endif; ?>>
<?php endif; ?>						
</td>
<td>
<?php if(in_array($v['name'], array('title','description'))):?>
<div class="input-group">
<span class="input-group-addon"><?php echo t('长度');?></span>
<?php echo form::field(array('type'=>'number','name'=>'fields['.$k.'][minlength]','value'=>$v['minlength']));?>
<span class="input-group-addon">-</span>
<?php echo form::field(array('type'=>'number','name'=>'fields['.$k.'][maxlength]','value'=>$v['maxlength']));?>
<span class="input-group-addon"><?php echo t('字');?></span>
</div>
<?php endif; ?>

<?php if(in_array($v['name'], array('image'))):?>
<div class="input-group">
<span class="input-group-addon"><?php echo t('宽高');?></span>
<?php echo form::field(array('type'=>'number','name'=>'fields['.$k.'][image_width]','value'=>$v['image_width']));?>
<span class="input-group-addon">×</span>
<?php echo form::field(array('type'=>'number','name'=>'fields['.$k.'][image_height]','value'=>$v['image_height']));?>
<span class="input-group-addon">px</span>								
</div>						

<?php echo form::field(array('type'=>'radio','options'=>array(0=>t('原图'),1=>t('缩放'),2=>t('裁剪')),'name'=>'fields['.$k.'][image_resize]','value'=>$v['image_resize']));?>

<?php echo form::field(array('type'=>'radio','options'=>array(1=>t('水印'),0=>t('无')),'name'=>'fields['.$k.'][watermark]','value'=>$v['watermark']));?>

<?php endif; ?>

<?php if(in_array($v['name'], array('c1','c2','c3','c4','c5'))):?>
<?php echo form::field(array('type'=>'select','options'=>m('block.block.fieldtypes'),'name'=>'fields['.$k.'][type]','value'=>$v['type']));?>
<?php else: ?>
<?php echo form::field(array('type'=>'hidden','name'=>'fields['.$k.'][type]','value'=>$v['type']));?>
<?php endif; ?>

</td>
</tr>
<?php $n++;endforeach;unset($n); ?>
</tbody>
</table>
</td>
</tr>
<tr>
<td class="label"><?php echo form::label(t('推送'),'commend',false);?></td>
<td class="input">
<?php echo form::field(array('type'=>'radio','options'=>array(0=>t('禁止推送'),1=>t('允许: 无需审核'),2=>t('允许: 需要审核')),'name'=>'commend','value'=>$data['commend']));?>
</td>
</tr>

</tbody>
</table>