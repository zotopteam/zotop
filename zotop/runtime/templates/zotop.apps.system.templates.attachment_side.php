<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<ul class="sidenavlist">
<li>
<a href="<?php echo u('system/attachment/upload/'.$type, $_GET);?>"<?php if(substr_count(ZOTOP_URI,'system/attachment/upload')):?> class="current"<?php endif; ?>>
<i class="icon icon-upload"></i><?php echo t('本地上传');?>
</a>
</li>
<li>
<a href="<?php echo u('system/attachment/library/'.$type, $_GET);?>"<?php if(substr_count(ZOTOP_URI,'system/attachment/library')):?> class="current"<?php endif; ?>>
<i class="icon icon-library"></i><?php echo t('%s库',$typename);?>
</a>
</li>
<li>
<a href="<?php echo u('system/attachment/dirview/'.$type, $_GET);?>"<?php if(substr_count(ZOTOP_URI,'system/attachment/dirview')):?> class="current"<?php endif; ?>>
<i class="icon icon-folder"></i><?php echo t('目录浏览');?>
</a>
</li>
<li style="display:none;">
<a href="<?php echo u('system/attachment/remoteurl/'.$type, $_GET);?>"<?php if(substr_count(ZOTOP_URI,'system/attachment/remoteurl')):?> class="current"<?php endif; ?>>
<i class="icon icon-url"></i><?php echo t('网络文件');?>
</a>
</li>
<?php echo zotop::run('system.attachment.navbar');?>
</ul>
