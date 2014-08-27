<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<div class="side-header">
<?php echo t('系统');?>
</div><!-- side-header -->
<div class="side-body scrollable">
<ul class="sidenavlist">
<li><a href="<?php echo u('system/app');?>"<?php if(substr_count(ZOTOP_URI,'system/app')):?> class="current"<?php endif; ?>><i class="icon icon-app"></i><?php echo t('应用管理');?></a></li>
<li><a href="<?php echo u('system/config');?>"<?php if(substr_count(ZOTOP_URI,'system/config')):?> class="current"<?php endif; ?>><i class="icon icon-config"></i><?php echo t('全局设置');?></a></li>		
<li><a href="<?php echo u('system/theme');?>"<?php if(substr_count(ZOTOP_URI,'system/theme')):?> class="current"<?php endif; ?>><i class="icon icon-skin"></i><?php echo t('主题和模板');?></a></li>
<li><a href="<?php echo u('system/ipbanned');?>"<?php if(substr_count(ZOTOP_URI,'system/ipbanned')):?> class="current"<?php endif; ?>><i class="icon icon-disabled"></i><?php echo t('IP禁止');?></a></li>
<li><a href="<?php echo u('system/badword');?>"<?php if(substr_count(ZOTOP_URI,'system/badword')):?> class="current"<?php endif; ?>><i class="icon icon-warning"></i><?php echo t('敏感词管理');?></a></li>		
<li><a href="<?php echo u('system/administrator');?>"<?php if(substr_count(ZOTOP_URI,'system/administrator')):?> class="current"<?php endif; ?>><i class="icon icon-users"></i><?php echo t('管理员管理');?></a></li>
<li><a href="<?php echo u('system/role');?>"<?php if(substr_count(ZOTOP_URI,'system/role')):?> class="current"<?php endif; ?>><i class="icon icon-user"></i><?php echo t('角色管理');?></a></li>
<li><a href="<?php echo u('system/priv');?>"<?php if(substr_count(ZOTOP_URI,'system/priv')):?> class="current"<?php endif; ?>><i class="icon icon-priv"></i><?php echo t('权限管理');?></a></li>
<li><a href="<?php echo u('system/log');?>"<?php if(substr_count(ZOTOP_URI,'system/log')):?> class="current"<?php endif; ?>><i class="icon icon-flag"></i><?php echo t('系统操作日志');?></a></li>
<li><a href="<?php echo u('system/info');?>"<?php if(substr_count(ZOTOP_URI,'system/info')):?> class="current"<?php endif; ?>><i class="icon icon-info"></i><?php echo t('系统信息');?></a></li>
</ul>
</div><!-- side-body -->