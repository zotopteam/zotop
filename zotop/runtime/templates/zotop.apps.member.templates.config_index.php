<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<?php $this->display('header.php'); ?>
<?php echo form::header(u('member/config/save'));?>
<div class="side">
<?php $this->display('member/admin_side.php'); ?>
</div>
<div class="main side-main">
<div class="main-header">
<div class="title"><?php echo t('设置');?></div>
<div class="action">

</div>
</div><!-- main-header -->
<div class="main-body scrollable">
<table class="field">
<caption><?php echo t('会员注册');?></caption>
<tbody>

<tr>
<td class="label"><?php echo form::label(t('禁用用户名'),'register_point',false);?></td>
<td class="input">
<?php echo form::field(array('type'=>'textarea','name'=>'register_banned','value'=>c('member.register_banned')));?>
<?php echo form::tips(t('禁止使用的用户名和昵称，每行一个，可以使用通配符*和?'));?>
</td>
</tr>

<tr>
<td class="label"><?php echo form::label(t('邮件验证'),'register_validmail',false);?></td>
<td class="input">
<?php echo form::field(array('type'=>'bool','name'=>'register_validmail','value'=>c('member.register_validmail')));?>

<?php echo form::tips(t('系统设置中的“邮件发送”发送功能设置正确才能正常使用该功能'));?>
</td>
</tr>
<tr>
<td class="label"><?php echo form::label(t('验证邮件'),'register_validmail_content',false);?></td>
<td class="input">
<?php echo form::field(array('type'=>'text','name'=>'register_validmail_title','value'=>c('member.register_validmail_title')));?>
<div class="blank"></div>
<?php echo form::field(array('type'=>'editor','name'=>'register_validmail_content','value'=>c('member.register_validmail_content')));?>
<div class="blank"></div>
<div class="input-group">
<span class="input-group-addon"><?php echo t('有效期');?></span>
<?php echo form::field(array('type'=>'number','name'=>'register_validmail_expire','value'=>c('member.register_validmail_expire'),'min'=>1));?>
<span class="input-group-addon"><?php echo t('小时');?></span>
</div>
</td>
</tr>

</tbody>
</table>

<table class="field">
<caption><?php echo t('登录设置');?></caption>
<tbody>
<tr>
<td class="label"><?php echo form::label(t('开启验证码'),'login_captcha',false);?></td>
<td class="input">
<?php echo form::field(array('type'=>'bool','name'=>'login_captcha','value'=>c('member.login_captcha')));?>
</td>
</tr>
<tr>
<td class="label"><?php echo form::label(t('奖励积分'),'login_point',false);?></td>
<td class="input">
<?php echo form::field(array('type'=>'number','name'=>'login_point','value'=>c('member.login_point')));?>
<?php echo form::tips(t('每次登录奖励积分'));?>
</td>
</tr>
</tbody>
</table>

<table class="field">
<caption><?php echo t('找回密码');?></caption>
<tbody>
<tr>
<td class="label"><?php echo form::label(t('找回密码邮件'),'getpassword_mailcontent',false);?></td>
<td class="input">
<?php echo form::field(array('type'=>'text','name'=>'getpasswordmail_title','value'=>c('member.getpasswordmail_title')));?>
<div class="blank"></div>
<?php echo form::field(array('type'=>'editor','name'=>'getpasswordmail_content','value'=>c('member.getpasswordmail_content')));?>
<div class="blank"></div>
<div class="input-group">
<span class="input-group-addon"><?php echo t('有效期');?></span>
<?php echo form::field(array('type'=>'number','name'=>'getpasswordmail_expire','value'=>c('member.getpasswordmail_expire'),'min'=>0.1));?>
<span class="input-group-addon"><?php echo t('小时');?></span>
</div>
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
</script>
<?php $this->display('footer.php'); ?>