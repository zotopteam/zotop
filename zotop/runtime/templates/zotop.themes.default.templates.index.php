<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<?php $this->display('header.php'); ?>

<style type="text/css">
.mainslider{}
.mainslider div.slider{height:400px;}
.mainslider div.slider img{width:1150px;height:400px;}


.row-shop{border: 1px solid #DFDFDF;height: 621px;overflow: hidden;}
.row-shop .main-inner{margin-right:348px;}
.row-shop .side{margin-left:-352px;width:352px;}

.image-list-3{}
.image-list-3 ul.image-list li{width:264px;height:310px;border-right:1px solid #DFDFDF;border-bottom: 1px solid #DFDFDF;}
.image-list-3 ul.image-list li div.title{display: none;}

.image-list-1{}
.image-list-1 ul.image-list li{width:100%;height:103px;border-right:1px solid #DFDFDF;border-bottom: 1px solid #DFDFDF;}
.image-list-1 ul.image-list li div.title{display: none;}

.row-img-text {margin:15px 0;}
.row-img-text .main-inner{margin:0;margin-left:450px;}
.row-img-text .side{margin:0;margin-left:-100%;width:450px;}

.image-solution div.slider{width:420px;height:345px;overflow: hidden;}
.image-solution div.slider img{width:420px;height:345px;}

.top-solution{height:204px;overflow: hidden;}
.top-solution ul.image-text {margin-top: -5px;margin-bottom: 5px;}
.top-solution ul.image-text .text b{margin-bottom: 10px;}
.top-solution ul.image-text .text b a{font-size: 24px;}
.top-solution ul.image-text .text p{height:48px;}

.hot-solution{height: 260px; overflow: hidden;}

.ads-solution{margin-top:8px;}

</style>

<div class="mainslider">
<div class="box">
<div class="box-head">
<h1 class="box-title">自动创建</h1>
</div>
<div class="box-body">
<ul class="list">
</ul>
</div>
</div><!-- box -->
</div>



<div class="channel clearfix">
<h1><?php echo t('解决方案');?></h1>
<ul>
<?php
$tag_content = tag_content(array('action'=>'category','cid'=>'3'));
if ( is_array($tag_content) ):
	if ( isset($tag_content['total']) ){extract($tag_content);$tag_content = $data; $pagination = pagination::instance($total,$pagesize,$page);}
	$n=0;
	foreach( $tag_content as $key=>$c ):
?>
<li class="item-<?php echo $n;?> <?php if($category['id'] == $c['id']):?>current<?php endif; ?>"><s></s><a href="<?php echo $c['url'];?>"><?php echo $c['name'];?></a></li>
<?php
	$n++;
	endforeach;
endif;
?>
</ul>
</div>

<div class="row row-img-text">
<div class="main">
<div class="main-inner">
<div class="top-solution"><div class="error block-error">区别编号错误</div></div>

<div class="hot-solution"><div class="error block-error">区别编号错误</div></div>
</div>
</div>
<div class="side">
<div class="image-solution"><div class="error block-error">区别编号错误</div></div>
<div class="ads-solution">
<img src="<?php echo ZOTOP_URL_UPLOADS;?>/common/banner.jpg" width="420px"/>
</div>		
</div>
</div>


<div class="blank"></div>


<div class="channel clearfix">
<h1><?php echo t('天天商城');?></h1>
<ul>
{shop action="category" return="c"}
<li class="item-<?php echo $n;?> <?php if($category['id'] == $c['id']):?>current<?php endif; ?>"><s></s><a href="<?php echo $c['url'];?>"><?php echo $c['name'];?></a></li>
{/shop}
</ul>
</div>

<div class="blank"></div>

<div class="row row-shop">
<div class="main">
<div class="main-inner">	

<div class="image-list-3 clearfix"><div class="error block-error">区别编号错误</div></div>		

</div>
</div><!--main-->
<div class="side">

<div class="image-list-1 clearfix"><div class="error block-error">区别编号错误</div></div>

</div><!--side-->
</div><!--row-->

<?php $this->display('footer.php'); ?>