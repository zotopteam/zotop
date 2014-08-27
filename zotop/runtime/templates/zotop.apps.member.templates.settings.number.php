<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<table class="field">
<tr>
<td class="label"><?php echo form::label(t('数值范围'),'length',false);?></td>
<td class="input">
<?php echo form::field(array('type'=>'number','name'=>'settings[min]','value'=>$data['settings']['min'],'title'=>t('最小值')));?>
 -
<?php echo form::field(array('type'=>'number','name'=>'settings[max]','value'=>$data['settings']['max'],'title'=>t('最大值')));?>
</td>
</tr>
</table>
<script type="text/javascript">

</script>