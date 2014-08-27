{template 'header.php'}

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
	{block 'mainslider'}
</div>


	
<div class="channel clearfix">
	<h1>{t('解决方案')}</h1>
	<ul>
		{content action="category" cid="3" return="c"}
			<li class="item-{$n} {if $category.id == $c.id}current{/if}"><s></s><a href="{$c.url}">{$c.name}</a></li>
		{/content}
	</ul>
</div>

<div class="row row-img-text">
	<div class="main">
		<div class="main-inner">
			<div class="top-solution">{block 'top-solution'}</div>

			<div class="hot-solution">{block 'hot-solution'}</div>
		</div>
	</div>
	<div class="side">
		<div class="image-solution">{block 'image-solution'}</div>
		<div class="ads-solution">
			<img src="{ZOTOP_URL_UPLOADS}/common/banner.jpg" width="420px"/>
		</div>		
	</div>
</div>


<div class="blank"></div>


<div class="channel clearfix">
	<h1>{t('天天商城')}</h1>
	<ul>
		{shop action="category" return="c"}
			<li class="item-{$n} {if $category.id == $c.id}current{/if}"><s></s><a href="{$c.url}">{$c.name}</a></li>
		{/shop}
	</ul>
</div>

<div class="blank"></div>

<div class="row row-shop">
	<div class="main">
	<div class="main-inner">	
		
		<div class="image-list-3 clearfix">{block 'best-goods'}</div>		
		
	</div>
	</div><!--main-->
	<div class="side">

		<div class="image-list-1 clearfix">{block 'sale-goods'}</div>
		
	</div><!--side-->
</div><!--row-->

{template 'footer.php'}