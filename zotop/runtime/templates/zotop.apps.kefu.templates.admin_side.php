<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<div class="side-header">
<?php echo A('kefu.name');?>
</div><!-- side-header -->
<div class="side-body no-footer scrollable">

<ul class="sidenavlist">
<li>
<a href="<?php echo u('kefu/admin');?>"<?php if(substr_count(ZOTOP_URI,'kefu/admin')):?> class="current"<?php endif; ?>>
<i class="icon icon-admin"></i><?php echo t('客服管理');?>
</a>
</li>
<li>
<a href="<?php echo u('kefu/config');?>"<?php if(substr_count(ZOTOP_URI,'kefu/config')):?> class="current"<?php endif; ?>>
<i class="icon icon-config"></i><?php echo t('设置');?>
</a>
</li>
</ul>

</div><!-- side-body -->