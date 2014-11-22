<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $title;?> <?php echo t('逐涛网站管理系统');?></title>
<meta content="none" name="robots" />
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo A('system.url');?>/common/css/global.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo A('system.url');?>/common/icon/style.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo A('system.url');?>/common/css/jquery.dialog.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo A('system.url');?>/common/css/login.css"/>
<script type="text/javascript" src="<?php echo A('system.url');?>/common/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo A('system.url');?>/common/js/zotop.js"></script>
<script type="text/javascript" src="<?php echo A('system.url');?>/common/js/jquery.plugins.js"></script>
<script type="text/javascript" src="<?php echo A('system.url');?>/common/js/jquery.dialog.js"></script>
<script type="text/javascript" src="<?php echo A('system.url');?>/common/js/global.js"></script>
<link rel="shortcut icon" type="image/x-icon" href="<?php echo A('system.url');?>/zotop.ico" />
<link rel="icon" type="image/x-icon" href="<?php echo A('system.url');?>/zotop.ico" />
<link rel="bookmark" type="image/x-icon" href="<?php echo A('system.url');?>/zotop.ico" />
<?php zotop::run('admin.head', $this); ?>
</head>
<body>

<?php zotop::run('admin.header', $this); ?>

<div class="topbar">
<a href="<?php echo u();?>"><i class="icon icon-home"></i> <?php echo t('网站首页');?></a>
<b>│</b>
<a href="javascript:void(0);" class="add-favorite"><i class="icon icon-star2"></i> <?php echo t('加入收藏夹');?></a>
<b>│</b>
<a href="<?php echo u('system/login/shortcut');?>"><i class="icon icon-heart"></i> <?php echo t('设为桌面图标');?></a>
</div>

<div class="box hidden" id="loginbox">
<div class="box-body">
<?php echo form::header();?>
<div class="form-header"></div>
<div class="form-status"><?php echo t('请输入您的用户名和密码登录');?></div>
<div class="form-body">
<table class="field">
<tbody>
<tr>
<td class="label"><?php echo form::label(t('用户名'),'username');?></td>
<td class="input">
<?php echo form::field(array('type'=>'text','name'=>'username','value'=>($remember_username ? $remember_username : ''),'required'=>'required'));?>
</td>
</tr>
<tr>
<td class="label"><?php echo form::label(t('密&nbsp;&nbsp;&nbsp;码'),'password');?></td>
<td class="input">
<?php echo form::field(array('type'=>'password','name'=>'password','required'=>'required'));?>
</td>
</tr>
<?php if(c('system.login_captcha')):?>
<tr>
<td class="label"><?php echo form::label(t('验证码'),'captcha');?></td>
<td class="input">
<?php echo form::field(array('type'=>'captcha','name'=>'captcha','required'=>'required'));?>
</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div><!-- form-body -->
<div class="form-footer">
<span class="field remember">
<label>
<input type="checkbox" class="checkbox" id="remember" name="remember" value="30" <?php if($remember_username):?>checked="checked"<?php endif; ?>/>
<?php echo t('记住账号');?>
</label>
</span>
<?php echo form::field(array('type'=>'submit','value'=>t('登 录')));?>
</div>
<?php echo form::footer();?>
</div><!-- box-body -->
</div><!-- box -->
<div class="bottombar">
<?php echo t('感谢您使用逐涛内容管理系统');?>
<div class="fr"><?php echo zotop::powered();?></div>
</div>
<script type="text/javascript" src="<?php echo A('system.url');?>/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">
// 禁止被包含
if(top!= self){top.location = self.location;}

// 居中显示登录窗口
$(function(){
$('#loginbox').position({of: $( "body" ),my: 'center center',at: 'center center'}).removeClass('hidden').draggable({handle:'.form-header',containment: "parent"});
$(window).bind('resize',function(){
$('#loginbox').position({of: $( "body" ),my: 'center center',at: 'center center'});
});
})

//加入收藏夹
$(function(){
$("a.add-favorite").on('click',function(){
var title = window.document.title;
var url = window.location.href;
try{
if ( window.sidebar && 'object' == typeof( window.sidebar ) && 'function' == typeof( window.sidebar.addPanel ) ){
window.sidebar.addPanel(title, url , '');
}else if ( document.all && 'object' == typeof( window.external ) ){
window.external.addFavorite(url , title);
}else {
$.error('<?php echo t('您使用的浏览器不支持此功能，请按“Ctrl + D”键手工加入收藏');?>',5);
}
}catch(e){
$.error('<?php echo t('您使用的浏览器不支持此功能，请按“Ctrl + D”键手工加入收藏');?>',5);
}
return false;
});
})

// 登录
$(function(){
$('form.form').validate({
rules: {
username: 'required',
password: 'required'
},
messages: {
username: "<?php echo t('请输入您的用户名');?>",
password: "<?php echo t('请输入您的密码');?>",
captcha: {required : "<?php echo t('请输入验证码');?>"}
},
showErrors:function(errorMap,errorList){
if (errorList[0]) $('.form-status').html('<span class="error">'+ errorList[0].message +'</span>');
},
submitHandler:function(form){
var action = $(form).attr('action');
var data = $(form).serialize();
$(form).find('.submit').disable(true);
$(form).find('.form-status').html('<?php echo t('正在登录中, 请稍后……');?>');
$.post(action, data, function(msg){
if( msg ) $(form).find('.form-status').html('<span class="'+msg.state+'">'+ msg.content +'</span>');
if( msg.url ){
location.href = msg.url;
return true;
}
$(form).find('.submit').disable(false);
return false;
},'json');
}
});
});
</script>
<!--[if lte IE 7]>
<div class="notsupport">
<h1><?php echo t('您的浏览器版本过低，ZOTOP暂不支持您的浏览器，请升级到IE8或者更高版本浏览器');?> <a href="http://windows.microsoft.com/zh-CN/windows/upgrade-your-browser"><?php echo t('立即升级');?></a></h1>
</div>
<![endif]-->

<?php zotop::run('admin.footer', $this); ?>

</body>
</html>