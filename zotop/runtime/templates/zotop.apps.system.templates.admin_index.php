<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<?php $this->display('header.php'); ?>
<div class="side main-side scrollable">

<dl class="list">
<dt><?php echo t('我的');?></dt>
<dd>
<table class="table">
<tr><td colspan="2">
<a href="<?php echo u('system/mine');?>"><?php echo t('编辑我的资料');?></a>
&nbsp;&nbsp;
<a href="<?php echo u('system/mine/password');?>"><?php echo t('修改我的密码');?></a>
</td></tr>
<tr><td class="w60"><?php echo t('登陆时间');?></td><td><?php echo format::date($user['logintime']);?></td></tr>
<tr><td class="w60"><?php echo t('登陆次数');?></td><td><?php echo $user['logintimes'];?></td></tr>
<tr><td class="w60"><?php echo t('登陆IP');?></td><td><?php echo $user['loginip'];?></td></tr>
</table>
</dd>
<dt><?php echo t('站点');?></dt>
<dd>
<table class="table list">
<tr><td class="w60"><?php echo t('名称');?></td><td><div class="textflow"><?php echo c('site.name');?></div></td></tr>
<tr><td class="w60"><?php echo t('网址');?></td><td><div class="textflow"><?php echo c('site.url');?></div></td></tr>
<tr><td class="w60"><?php echo t('主题');?></td><td><div class="textflow"><?php echo c('site.theme');?></div></td></tr>
</table>
</dd>
<dt><?php echo t('关于');?></dt>
<dd>
<table class="table">
<tr><td class="w60"><?php echo t('版本');?></td><td>v <?php echo c('zotop.version');?></td></tr>
<tr><td class="w60"><?php echo t('开发');?></td><td>zotop team</td></tr>
<tr><td class="w60"><?php echo t('官网');?></td><td><a href="http://www.zotop.com">zotop.com</a></td></tr>
</table>
</dd>
</dl>

</div>
<div class="main main-side">
<div class="main-header">
<div class="title"><?php echo $title;?></div>
</div>
<div class="main-body scrollable">
<ul class="navlist">
<?php $n=1; foreach($start as $s): ?>
<li>
<a href="<?php echo $s['href'];?>" class="nav clearfix">
<img src="<?php echo $s['icon'];?>">
<h2><?php echo $s['text'];?></h2>
<p><?php echo $s['description'];?></p>
</a>

<?php if(isset($s['msg'])):?>
<b class="msg"><?php echo $s['msg'];?></b>
<?php endif; ?>
</li>
<?php $n++;endforeach;unset($n); ?>
</ul>
</div><!-- main-body -->
<div class="main-footer">
<div class="fr"><?php echo zotop::powered();?></div>
<?php echo t('感谢您使用逐涛网站管理系统');?>
</div>
</div>
<?php $this->display('footer.php'); ?>