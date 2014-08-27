<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<div class="side-header">
<?php echo A('member.name');?>
</div><!-- side-header -->
<div class="side-body no-footer scrollable">

<ul class="sidenavlist">

<li>
<a href="<?php echo u('member/admin');?>"<?php if(substr_count(ZOTOP_URI,'member/admin')):?> class="current"<?php endif; ?>>
<i class="icon icon-admin"></i><?php echo t('会员管理');?>
</a>
</li>

<li>
<a href="<?php echo u('member/model');?>"<?php if(substr_count(ZOTOP_URI,'member/model')):?> class="current"<?php endif; ?>>
<i class="icon icon-model"></i><?php echo t('会员模型');?>
</a>
</li>

<li>
<a href="<?php echo u('member/group');?>"<?php if(substr_count(ZOTOP_URI,'member/group')):?> class="current"<?php endif; ?>>
<i class="icon icon-category"></i><?php echo t('会员组');?>
</a>
</li>

<li>
<a href="<?php echo u('member/field');?>"<?php if(substr_count(ZOTOP_URI,'member/field')):?> class="current"<?php endif; ?>>
<i class="icon icon-flag"></i><?php echo t('字段管理');?>
</a>
</li>

<li>
<a href="<?php echo u('member/config');?>"<?php if(substr_count(ZOTOP_URI,'member/config')):?> class="current"<?php endif; ?>>
<i class="icon icon-config"></i><?php echo t('设置');?>
</a>
</li>
</ul>

</div><!-- side-body -->