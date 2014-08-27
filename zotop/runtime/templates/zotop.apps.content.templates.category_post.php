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
<a href="<?php echo u('content/category');?>"><?php echo t('根栏目');?></a>
<?php $n=1; foreach($parents as $p): ?>
<s class="arrow">></s>
<a href="<?php echo u('content/category/index/'.$p['id']);?>"><?php echo $p['name'];?></a>
<?php $n++;endforeach;unset($n); ?>
<s class="arrow">></s>
<?php if($data['id']):?><?php echo t('编辑');?><?php else: ?><?php echo t('添加');?><?php endif; ?>
</div>
<div class="action">

</div>
</div><!-- main-header -->
<div class="main-body scrollable">
<input type="hidden" name="parentid" value="<?php echo $data['parentid'];?>">

<table class="field">
<caption><?php echo t('基本设置');?></caption>
<tbody>
<tr>
<td class="label"><?php echo form::label(t('名称'),'name',true);?></td>
<td class="input">
<?php echo form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'required'=>'required'));?>
</td>
</tr>
<tr>
<td class="label"><?php echo form::label(t('别名'),'alias',false);?></td>
<td class="input">
<?php echo form::field(array('type'=>'alias','name'=>'alias','value'=>$data['alias'],'data-source'=>'name'));?>
</td>
</tr>
<tr>
<td class="label"><?php echo form::label(t('首页模版'),'settings[template_index]',true);?></td>
<td class="input">
<?php echo form::field(array('type'=>'template','name'=>'settings[template_index]','value'=>$data['settings']['template_index'],'required'=>'required'));?>
</td>
</tr>
<tr>
<td class="label"><?php echo form::label(t('列表页模版'),'settings[template_list]',true);?></td>
<td class="input">
<?php echo form::field(array('type'=>'template','name'=>'settings[template_list]','value'=>$data['settings']['template_list'],'required'=>'required'));?>
</td>
</tr>
<tr>
<td class="label"><?php echo form::label(t('内容模型'),'models',false);?></td>
<td class="input">
<table class="controls">
<thead>
<tr>
<td class="w100"><?php echo t('使用模型');?></td>
<td><?php echo t('模型内容页模版');?></td>
</tr>
</thead>
<tbody>
<?php $n=1; foreach($models as $i => $m): ?>
<tr>
<td>
<label>
<input type="checkbox" name="settings[models][<?php echo $i;?>][enabled]" value="1" class="vm" <?php if($m['enabled']):?>checked="checked"<?php endif; ?>/>
<?php echo $m['name'];?>
</label>
</td>
<td>
<?php if($m['tablename']):?>
<?php echo form::field(array('type'=>'template','name'=>'settings[models]['.$i.'][template]','value'=>$m['template'],'style'=>'width:340px;'));?>
<?php endif; ?>
<td>
</tr>
<?php $n++;endforeach;unset($n); ?>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table class="field">
<caption><?php echo t('高级设置');?></caption>
<tbody>
<tr>
<td class="label"><?php echo form::label(t('标题'),'title',false);?></td>
<td class="input">
<?php echo form::field(array('type'=>'text','name'=>'title','value'=>$data['title']));?>
</td>
</tr>
<tr>
<td class="label"><?php echo form::label(t('图片'),'image',false);?></td>
<td class="input">
<?php echo form::field(array(
'type'			=> 'image',
'name'			=> 'image',
'value'			=> $data['image'],
'dataid'		=> $data['dataid'],
'image_resize'	=> c('content.category_image_resize'),
'image_width'	=> c('content.category_image_width'),
'image_height'	=> c('content.category_image_height'),
'image_quality'	=> c('content.category_image_quality'),
))?>
</td>
</tr>
<tr>
<td class="label"><?php echo form::label(t('简介'),'content',false);?></td>
<td class="input">
<?php echo form::field(array('type'=>'editor','name'=>'content','value'=>$data['content'],'tools'=>true,'theme'=>'full','dataid'=>$data['dataid']));?>
</td>
</tr>
<tr>
<td class="label"><?php echo form::label(t('关键词'),'keywords',false);?></td>
<td class="input">
<?php echo form::field(array('type'=>'keywords','name'=>'keywords','value'=>$data['keywords'],'data-source'=>'content'));?>
</td>
</tr>
<tr>
<td class="label"><?php echo form::label(t('描述'),'description',false);?></td>
<td class="input">
<?php echo form::field(array('type'=>'textarea','name'=>'description','value'=>$data['description'],'placeholder'=>t('合理填写有助于搜索引擎排名优化')));?>
</td>
</tr>	
<?php if(a('member')):?>
<tr>
<td class="label"><?php echo form::label(t('会员投稿'),'settings[contribute]',false);?></td>
<td class="input">

<?php echo form::field(array('type'=>'radio','options'=>array(0=>t('禁止'), 1=>t('允许')),'name'=>'settings[contribute]','value'=>(int)$data['settings']['contribute']));?>

<div class="input-group" id="contribute_point">
<span class="input-group-addon">
<?php echo t('投稿积分');?>
</span>
<?php echo form::field(array('type'=>'number','name'=>'settings[contribute_point]','value'=>(int)$data['settings']['contribute_point']));?>
<span class="input-group-addon">
<?php echo t('正数为增加积分，负数为扣除积分');?>
</span>						
</div>

<script type="text/javascript">
$(function(){

$contribute = $('input[name="settings[contribute]"]');

$contribute['filter'](':checked').val() == 1 ? $('#contribute_point').show() : $('#contribute_point').hide();

$contribute['on']('click',function(){
$contribute['filter'](':checked').val() == 1 ? $('#contribute_point').show() : $('#contribute_point').hide();
});
});
</script>
</td>
</tr>
<?php endif; ?>
</tbody>
</table>
<?php if($data['childid']):?>
<table class="field">
<tbody>
<tr>
<td class="label"><?php echo form::label(t('设置复制'),'apply-setting-childs',false);?></td>
<td class="input">
<span class="field"><label><input type="checkbox" name="apply-setting-childs" value="1"> <?php echo t('复制设置到全部子栏目');?></label></span>
</td>
</tr>
</tbody>
</table>
<?php endif; ?>
</div><!-- main-body -->
<div class="main-footer">
<?php echo form::field(array('type'=>'submit','value'=>t('保存')));?>

<?php echo form::field(array('type'=>'button','value'=>t('取消'), 'onclick'=>'history.go(-1)'));?>
</div><!-- main-footer -->
</div><!-- main -->
<?php echo form::footer();?>

<script type="text/javascript" src="<?php echo zotop::app('system.url');?>/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">
$(function(){
$('form.form').validate({submitHandler:function(form){
var action = $(form).attr('action');
var data = $(form).serialize();
$.loading();
$(form).find('.submit').disable(true);
$.post(action, data, function(msg){

if( !msg.state ){
$(form).find('.submit').disable(false);
}

$.msg(msg);

},'json');
}});
});

$(function(){
$('[name=name]').change(function(){
var name = $(this).val();

$('[name=title]').val(name);
$('[name=keywords]').val(name);
$('[name=description]').val(name);
});
})
</script>
<?php $this->display('footer.php'); ?>