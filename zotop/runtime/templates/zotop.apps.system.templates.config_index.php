<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<?php $this->display('header.php'); ?>

<div class="side">
<?php $this->display('system/system_side.php'); ?>
</div>

<?php echo form::header();?>
<div class="main side-main">
<div class="main-header">
<div class="title"><?php echo t('全局设置');?></div>
<ul class="navbar">
<?php $n=1; foreach($navbar as $k => $n): ?>
<li<?php if(ZOTOP_ACTION == $k):?> class="current"<?php endif; ?>>
<a href="<?php echo $n['href'];?>"><?php if($n['icon']):?><i class="icon <?php echo $n['icon'];?>"></i><?php endif; ?> <?php echo $n['text'];?></a>
</li>
<?php $n++;endforeach;unset($n); ?>
</ul>
</div><!-- main-header -->
<div class="main-body scrollable">
<table class="field">
<caption><?php echo t('基本设置');?></caption>
<tbody>
<tr>
<td class="label"><?php echo form::label(t('网站名称'),'name',true);?></td>
<td class="input">
<?php echo form::field(array('type'=>'text','name'=>'name','value'=>c('site.name'),'maxlength'=>20,'required'=>'required'));?>
<?php echo form::tips(t('网站名称为该网站的标识'));?>
</td>
</tr>
<tr>
<td class="label"><?php echo form::label(t('网站网址'),'url',true);?></td>
<td class="input">
<?php echo form::field(array('type'=>'text','name'=>'url','value'=>c('site.url'),'required'=>'required'));?>
<?php echo form::tips(t('格式为：<b>http://www.zotop.com</b>'));?>
</td>
</tr>
<tr>
<td class="label"><?php echo form::label(t('网站主题'),'theme',true);?></td>
<td class="input">
<ul class="themelist clearfix">
<?php $n=1; foreach($themes as $id => $theme): ?>
<li <?php if(c('site.theme')== $id):?>class="selected"<?php endif; ?> title="<?php echo $theme['description'];?>">
<label>
<i class="icon icon-selected true"></i>
<div class="image"><img src="<?php echo $theme['image'];?>"/></div>
<div class="title textflow">
<input type="radio" name="theme" value="<?php echo $id;?>" <?php if(c('site.theme')== $id):?>checked="checked"<?php endif; ?>/>
&nbsp;<?php echo $theme['name'];?>
</div>
</label>
</li>
<?php $n++;endforeach;unset($n); ?>
</ul>
<?php echo form::tips(t('选择主题后，网站将以该主题和模板显示'));?>
</td>
</tr>
</tbody>
</table>



<table class="field">
<caption><?php echo t('搜索优化');?></caption>
<tbody>
<tr>
<td class="label"><?php echo form::label(t('网站标题'),'title',true);?></td>
<td class="input">
<?php echo form::field(array('type'=>'text','name'=>'title','value'=>c('site.title'),'required'=>'required'));?>
<?php echo form::tips(t('网站标题，用于搜索优化，不易过长'));?>
</td>
</tr>
<tr>
<td class="label"><?php echo form::label(t('网站关键词'),'keywords',true);?></td>
<td class="input">
<?php echo form::field(array('type'=>'text','name'=>'keywords','value'=>c('site.keywords'),'required'=>'required'));?>
<?php echo form::tips(t('请分析并填写网站关键词(Meta Keywords)，多个关键词使用空格隔开'));?>
</td>
</tr>
<tr>
<td class="label"><?php echo form::label(t('网站描述'),'description',true);?></td>
<td class="input">
<?php echo form::field(array('type'=>'text','name'=>'description','value'=>c('site.description'),'required'=>'required'));?>
<?php echo form::tips(t('请填写网站描述信息(Meta Description)'));?>
</td>
</tr>
</tbody>
</table>

<table class="field">
<caption><?php echo t('网站状态');?></caption>
<tbody>
<tr>
<td class="label"><?php echo form::label(t('关闭网站'),'closed',false);?></td>
<td class="input">
<?php echo form::field(array('type'=>'bool','name'=>'closed','value'=>c('site.closed')));?>
<?php echo form::tips(t('网站关闭时管理员在登陆系统之后仍然可以访问网站'));?>
</td>
</tr>
<tr>
<td class="label"><?php echo form::label(t('关闭原因'),'closedreason',false);?></td>
<td class="input">
<?php echo form::field(array('type'=>'textarea','name'=>'closedreason','value'=>c('site.closedreason')));?>
</td>
</tr>
</tbody>
</table>
</div><!-- main-body -->
<div class="main-footer">
<?php echo form::field(array('type'=>'submit','value'=>t('保存')));?>
</div><!-- main-footer -->
</div><!-- main -->
<?php echo form::footer();?>

<style type="text/css">
.themelist{margin:0 0 -40px -20px;zoom:1;}
.themelist li{position:relative;float:left;width:280px;overflow:hidden;margin: 0 0 20px 20px;background-color: #fff;padding:3px 3px 0 3px;box-shadow: 0 1px 1px #eee;border:3px solid #ebebeb;}
.themelist li:hover{border:3px solid #d5d5d5;}
.themelist li .image{width:100%;height:200px;line-height:200px;overflow:hidden;cursor:pointer;}
.themelist li .image img{width:100%;}
.themelist li .title{padding:5px 0;height:30px;line-height:30px;overflow:hidden;font-size:16px;cursor:pointer;}
.themelist li .icon{position:absolute;bottom:0px;right:5px;font-size:28px;height:36px;width:36px;display:none;z-index:200;}
.themelist li input{display:none;}
.themelist li.selected {border:3px solid #66bb00;}
.themelist li.selected .icon{display:block;}
</style>

<script type="text/javascript" src="<?php echo zotop::app('system.url');?>/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">
$(function(){
$('form.form').validate({submitHandler:function(form){
var action = $(form).attr('action');
var data = $(form).serialize();
$(form).find('.submit').disable(true);
$.loading();
$.post(action, data, function(msg){
$.msg(msg);
$(form).find('.submit').disable(false);
},'json');
}});
});

$(function(){
$('.themelist li').on('click',function(){
$(this).addClass('selected').siblings("li").removeClass('selected'); //单选
})
})	
</script>
<?php $this->display('footer.php'); ?>