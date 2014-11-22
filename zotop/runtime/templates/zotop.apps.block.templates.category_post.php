<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<?php $this->display('dialog.header.php'); ?>

<?php echo form::header();?>
<table class="field">
<tbody>
<tr>
<td class="label"><?php echo form::label(t('名称'),'name',true);?></td>
<td class="input">
<?php echo form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'required'=>'required'));?>
</td>
</tr>
<tr>
<td class="label"><?php echo form::label(t('说明'),'description',false);?></td>
<td class="input">
<?php echo form::field(array('type'=>'textarea','name'=>'description','value'=>$data['description']));?>
</td>
</tr>
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
$dialog['close']();
}

$.msg(msg);

},'json');
}});
});
</script>
<?php $this->display('dialog.footer.php'); ?>