<?php defined('ZOTOP') or exit('No permission resources.'); ?>
</div> <!-- body -->
<div class="footer">

<div class="footer-service clearfix">
<ul>
<li><a href="#"><i class="icon icon-good"></i><b><?php echo t('解决方案定制');?></b></a></li>
<li><a href="#"><i class="icon icon-reject"></i><b><?php echo t('安防产品采购');?></b></a></li>
<li><a href="#"><i class="icon icon-refresh"></i><b><?php echo t('全程施工监管');?></b></a></li>
<li><a href="#"><i class="icon icon-user"></i><b><?php echo t('一年免费维护');?></b></a></li>
<li><a href="#"><i class="icon icon-gift"></i><b><?php echo t('三年质量保障');?></b></a></li>
</ul>
</div>

<div class="footer-links clearfix">
<dl class="col-links col-links-first">
<dt><?php echo m('content.category.get',5,'name');?></dt>
<?php
if ( null === $tag_content = zotop::cache('content182e17166e82d9fb5dde6e5b955bf8f4') ):
	if ( $tag_content = tag_content(array('cid'=>'5','size'=>'5')) ) :
		zotop::cache('content182e17166e82d9fb5dde6e5b955bf8f4', $tag_content, true);
	endif;
endif;
if ( is_array($tag_content) ):
	if ( isset($tag_content['total']) ){extract($tag_content);$tag_content = $data; $pagination = pagination::instance($total,$pagesize,$page);}
	$n=0;
	foreach( $tag_content as $key=>$r ):
?>
<dd><a href="<?php echo $r['url'];?>" title="<?php echo $r['title'];?>" <?php echo $r['style'];?>><?php echo str::cut($r['title'],14);?></a></dd>
<?php
	$n++;
	endforeach;
endif;
?>
</dl>
<dl class="col-links">
<dt><?php echo m('content.category.get',6,'name');?></dt>
<?php
if ( null === $tag_content = zotop::cache('contentcca97adb9172b16fe7f054873e077c19') ):
	if ( $tag_content = tag_content(array('cid'=>'6','size'=>'5')) ) :
		zotop::cache('contentcca97adb9172b16fe7f054873e077c19', $tag_content, true);
	endif;
endif;
if ( is_array($tag_content) ):
	if ( isset($tag_content['total']) ){extract($tag_content);$tag_content = $data; $pagination = pagination::instance($total,$pagesize,$page);}
	$n=0;
	foreach( $tag_content as $key=>$r ):
?>
<dd><a href="<?php echo $r['url'];?>" title="<?php echo $r['title'];?>" <?php echo $r['style'];?>><?php echo str::cut($r['title'],14);?></a></dd>
<?php
	$n++;
	endforeach;
endif;
?>
</dl>
<dl class="col-links">
<dt><?php echo m('content.category.get',7,'name');?></dt>
<?php
if ( null === $tag_content = zotop::cache('content9be928fab6c8b57049a0366b535b2b67') ):
	if ( $tag_content = tag_content(array('cid'=>'7','size'=>'5')) ) :
		zotop::cache('content9be928fab6c8b57049a0366b535b2b67', $tag_content, true);
	endif;
endif;
if ( is_array($tag_content) ):
	if ( isset($tag_content['total']) ){extract($tag_content);$tag_content = $data; $pagination = pagination::instance($total,$pagesize,$page);}
	$n=0;
	foreach( $tag_content as $key=>$r ):
?>
<dd><a href="<?php echo $r['url'];?>" title="<?php echo $r['title'];?>" <?php echo $r['style'];?>><?php echo str::cut($r['title'],14);?></a></dd>
<?php
	$n++;
	endforeach;
endif;
?>
</dl>
<dl class="col-links">
<dt><?php echo m('content.category.get',1,'name');?></dt>
<?php
if ( null === $tag_content = zotop::cache('contentac9bae94b18ad0ac956cd0770d1cc0b0') ):
	if ( $tag_content = tag_content(array('cid'=>'1','size'=>'5')) ) :
		zotop::cache('contentac9bae94b18ad0ac956cd0770d1cc0b0', $tag_content, true);
	endif;
endif;
if ( is_array($tag_content) ):
	if ( isset($tag_content['total']) ){extract($tag_content);$tag_content = $data; $pagination = pagination::instance($total,$pagesize,$page);}
	$n=0;
	foreach( $tag_content as $key=>$r ):
?>
<dd><a href="<?php echo $r['url'];?>" title="<?php echo $r['title'];?>" <?php echo $r['style'];?>><?php echo str::cut($r['title'],14);?></a></dd>
<?php
	$n++;
	endforeach;
endif;
?>
</dl>
<div class="col-contact">
<p class="phone">400-007-2223</p>
<p>
<img src="<?php echo ZOTOP_URL_UPLOADS;?>/common/qcode.jpg" width="140px"/>
</p>
</div>						
</div>

<div class="footer-navbar"><div class="error block-error">区别编号错误</div></div>

<div class="footer-info"><div class="error block-error">区别编号错误</div></div>
<div class="error block-error">区别编号错误</div>
</div> <!-- footer -->

<?php zotop::run('site.footer', $this); ?>

<div class="floatbar">
<a class="item none" id="gotop" href="javascript:;" title="<?php echo t('回到顶部');?>"><div class="icon icon-up"></div></a>
<a class="item" href="<?php echo u('guestbook#guestbook-add');?>" title="<?php echo t('留言');?>"><div class="icon icon-msg"></div></a>	
</div>

</div><!-- wrapper -->
<div class="powered none"><?php echo zotop::powered();?></div>
</body>
</html>