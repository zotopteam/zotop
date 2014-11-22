<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<?php $this->display('dialog.header.php'); ?>
<?php echo form::header();?>

<?php echo form::field(array('type'=>'hidden','name'=>'blockid','value'=>$block['id'],'required'=>'required'));?>

<table class="field">
<tbody>
<?php $n=1; foreach(m('block.block.fieldlist',$block['fields']) as $k => $f): ?>
<?php if($f['show']):?>
<tr>
<td class="label"><?php echo form::label($f['label'], $k, $f['required']=='required');?></td>
<td class="input">
<?php if($f['type'] == 'title'):?>
<?php echo form::field(array_merge($f,array('value'=>$data[$k],'style'=>$data['style'])));?>
<?php elseif(in_array($f['type'],array('image','file','images','files','editor'))):?>
<?php echo form::field(array_merge($f,array('value'=>$data[$k],'dataid'=>'block-'.$block['id'])));?>
<?php else: ?>
<?php echo form::field(array_merge($f,array('value'=>$data[$k])));?>
<?php endif; ?>
</td>
</tr>
<?php endif; ?>
<?php $n++;endforeach;unset($n); ?>
</tbody>
</table>

<?php echo form::footer();?>

<script type="text/javascript" src="<?php echo zotop::app('system.url');?>/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">

// 对话框设置
$dialog.callbacks['ok'] = function(){
$('form.form').submit();
return false;
};

$(function(){
$('form.form').validate({submitHandler:function(form){
var action = $(form).attr('action');
var data = $(form).serialize();
$.loading();
$.post(action, data, function(msg){

if( msg.state ){
parent.location.reload();
$dialog['close']();
}

$.msg(msg);

},'json');
}});
});

</script>
<?php $this->display('dialog.footer.php'); ?>