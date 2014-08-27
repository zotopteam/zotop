<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<table class="field">
<tr>
<td class="label"><?php echo form::label(t('长度范围'),'length',false);?></td>
<td class="input">
<?php echo form::field(array('type'=>'number','name'=>'settings[minlength]','value'=>intval($data['settings']['minlength']),'min'=>0,'title'=>t('最小长度')));?>
 -
<?php echo form::field(array('type'=>'number','name'=>'settings[maxlength]','value'=>$data['settings']['maxlength'],'title'=>t('最大长度')));?>
</td>
</tr>
</table>
