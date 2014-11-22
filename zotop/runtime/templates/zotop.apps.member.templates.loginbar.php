<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<style type="text/css">
.loginbar{position:absolute;top:0px;right:0px;font-size:14px;color:#666;}
.loginbar b{font-weight:normal;margin:0 5px;}
</style>
<ul>
<?php if($_USER['username']):?>	
<li>
<b><?php echo $_USER['username'];?></b>
<a class="loginout" href="<?php echo U('member/login/loginout');?>"><?php echo t('退出');?></a>
</li>
<li>
<s></s>
<a href="<?php echo U('member/index');?>"><?php echo t('会员中心');?></a>
</li>
<?php else: ?>
<li>
<a class="login" href="<?php echo U('member/login');?>"><i class="icon icon-user"></i><?php echo t('登录');?></a>
</li>
<li>
<s></s>
<a class="register" href="<?php echo U('member/register');?>"><?php echo t('免费注册');?></a>
</li>
<?php endif; ?>

<?php $n=1; foreach(zotop::filter('member.loginbar',array()) as $r): ?>
<li><s></s><a href="<?php echo $r['url'];?>"><?php echo $r['text'];?></a></li>
<?php $n++;endforeach;unset($n); ?>

</ul>