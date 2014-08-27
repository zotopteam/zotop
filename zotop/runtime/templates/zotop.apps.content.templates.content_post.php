<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<?php $this->display('header.php'); ?>
<div class="side">
<?php $this->display('content/side.php'); ?>
</div>
<?php echo form::header();?>
<div class="main side-main">
<div class="main-header">
<div class="title"><?php echo $title;?></div>
<div class="position">
<a href="<?php echo u('content/content');?>"><?php echo t('内容管理');?></a>
<?php $n=1; foreach($parents as $p): ?>
<s class="arrow">></s>
<a href="<?php echo u('content/content/index/'.$p['id']);?>"><?php echo $p['name'];?></a>
<?php $n++;endforeach;unset($n); ?>
<s class="arrow">></s>
<?php if($data['id']):?><?php echo t('编辑%s',$model['name']);?><?php else: ?><?php echo t('添加%s',$model['name']);?><?php endif; ?>
</div>
<div class="action">
</div>
</div><!-- main-header -->
<div class="main-body scrollable">

<input type="hidden" name="id" value="<?php echo $data['id'];?>">
<input type="hidden" name="app" value="<?php echo $data['app'];?>">
<input type="hidden" name="modelid" value="<?php echo $data['modelid'];?>">
<input type="hidden" name="categoryid" value="<?php echo $data['categoryid'];?>">
<input type="hidden" name="status" value="<?php echo $data['status'];?>">

<?php $this->display($data['app'].'/content_post_'.$data['modelid'].'.php'); ?>

</div><!-- main-body -->
<div class="main-footer">

<?php echo form::field(array('type'=>'button','value'=>t('保存并发布'),'class'=>'submit btn-highlight','rel'=>'publish'));?>

<?php if($data['id']):?>
<?php echo form::field(array('type'=>'button','value'=>t('保存'),'class'=>'submit btn-primary','rel'=>'save'));?>
<?php else: ?>
<?php echo form::field(array('type'=>'button','value'=>t('保存草稿'),'class'=>'submit btn-primary','rel'=>'draft'));?>
<?php endif; ?>

<?php echo form::field(array('type'=>'button','value'=>t('返回列表'), 'onclick'=>'history.go(-1)'));?>

</div><!-- main-footer -->
</div><!-- main -->
<?php echo form::footer();?>

<script type="text/javascript" src="<?php echo zotop::app('system.url');?>/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">

var form = $('form.form');
var operate;

// 保存
$(function(){
form.on('click','button.submit',function(){
// 获取操作
operate = $(this).attr('rel');

// 同步参数
if ( operate == 'publish' || operate == 'draft' ){
$('input[name=status]').val(operate);
}
// 提交表单
form.submit();
})
});



// 表单验证并提交
$(function(){
form.validate({submitHandler:function(){
var action = form.attr('action');
var data = form.serialize();

form.find('.submit').disable(true);

$.loading();
$.post(action, data, function(msg){

if( operate == 'save' || operate == 'draft' ){

// 当保存草稿时候url返回值为内容编号
if ( operate == 'draft' && msg.url ) $('input[name=id]').val(msg.url);

form.find('.submit').disable(false);
msg.url = null;
}

if( !msg.state ){
form.find('.submit').disable(false);	
}

$.msg(msg);

},'json');
}});
});

$(function(){
$('span.options a').on('click',function(){

if ( $(this).hasClass('current') ){

$(this).removeClass('current');
$('tr.options').hide();

}else{
var option = $(this).attr('class').split(' ');

$(this).addClass('current').siblings("a").removeClass('current');

$.each(option, function(i, cls){
$('tr.options').hide();
$('tr.'+cls).show();
});
}
})
})
</script>
<?php $this->display('footer.php'); ?>