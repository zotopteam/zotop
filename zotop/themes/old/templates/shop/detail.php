{template 'header.php'}

<div class="channel clearfix">
	<h1>{t('天天商城')}</h1>
	<ul>
		{shop action="category" return="c"}
			<li class="item-{$n} {if $category.id == $c.id}current{/if}"><s></s><a href="{$c.url}">{$c.name}</a></li>
		{/shop}
	</ul>


</div>

<div class="position">
    <ul>
        <li><a href="{u()}">{t('首页')}</a></li>
		{shop action="position" cid="$category.id"}
		<li><a href="{$r.url}">{$r.name}</a></li>
		{/shop}
        <li>{$goods.name}</li>
    </ul>
</div>


<div class="row">

	<div class="goods-intro">

		<div class="goods-image">

			{if $goods.gallery}
				<ul id="etalage">
				{loop $goods.gallery $r}
				<li>
					<img class="etalage_thumb_image" src="{thumb($r.image,400,300)}" alt="{$r.description}"/>
					<img class="etalage_source_image" src="{thumb($r.image,1200,900)}" title="{$r.description}"/>
				</li>
				{/loop}
				</ul>
			{else}
				<div class="image">
					<img src="{thumb($goods.thumb,400,300)}"/>
				</div>
			{/if}

		</div>

		<h1 class="goods-title">{$goods.name}</h1>

		<div class="goods-description">{$goods.description}</div>

		<div class="goods-attrs clearfix">
			<div class="item"><b>{t('编号')}：</b><span>{$goods.sn}</span></div>
			<div class="item"><b>{t('品牌')}：</b><span><a href="{m('shop.brand.get', $goods.brandid, 'url')}" target="_blank">{m('shop.brand.get', $goods.brandid, 'name')}</a></span></div>
			<div class="item none"><b>{t('上架时间')}：</b><span>{format::date($goods.updatetime)}</span></div>

			{loop $goods.attrs $r}
				<div class="item"><b>{$r.name}：</b><span>{$r.value}</span></div>
			{/loop}
		</div>

		<div class="goods-buy clearfix">

			<div class="fl">
				<a href="http://wpa.qq.com/msgrd?v=3&uin={c('shop.qq')}&site={c('site.name')}&menu=yes" target="_blank" class="btn btn-large btn-important">{t('购买咨询')}</a>
			</div>

			<div class="fr share">
				<div class="bdsharebuttonbox"><a href="#" class="bds_more" data-cmd="more"></a><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#" class="bds_renren" data-cmd="renren" title="分享到人人网"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a><a href="#" class="bds_bdysc" data-cmd="bdysc" title="分享到百度云收藏"></a></div>
				<script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"1","bdSize":"16"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
			</div>
		</div>
	</div>

	<div class="content">

		<div class="content-body">
			{$goods.content}
		</div>

		{if $goods.tags}
		<div class="content-tags">
			<b>{t('标签')}</b> ：{loop $goods.tags $t} <a href="{u('content/tag/'.$t)}">{$t}</a> {/loop}
		</div>
		{/if}

		<div class="content-prev">
			<b>{t('上篇')}</b> ：
			{shop cid="$category.id" prev="$goods.id" size="1"}
			<a href="{$r.url}" title="{$r.title}" {$r.style}>{$r.name}{$r.new}</a>
			{/shop}
			{if empty($tag_shop)} {t('暂无内容')} {/if}
		</div>

		<div class="content-next">
			<b>{t('下篇')}</b> ：
			{shop cid="$category.id" next="$goods.id" size="1"}
			<a href="{$r.url}" title="{$r.title}" {$r.style}>{$r.name}{$r.new}</a>
			{/shop}
			{if empty($tag_shop)} {t('暂无内容')} {/if}
		</div>

		{shop keywords="$goods.keywords" ignore="$goods.id" size="5"/}
		{if $tag_shop}
		<div class="content-related">
			<h5>{t('相关内容')}</h5>
			<ul class="list">
			{loop $tag_shop $r}
			<li><a href="{$r.url}" title="{$r.title}" {$r.style}>{$r.name}{$r.new}</a></li>
			{/loop}
			</ul>
		</div>
		{/if}

	</div><!-- content -->

</div><!-- row -->

{html::import(__THEME__.'/js/etalage/jquery.etalage.min.js')}
{html::import(__THEME__.'/js/etalage/jquery.etalage.css')}
<script type="text/javascript">
$(function(){
	$('#etalage').etalage({
		thumb_image_width: 400,
		thumb_image_height: 300,
		source_image_width: 1200,
		source_image_height: 900,
		zoom_area_width: 500,
		zoom_area_height: 380,
		zoom_area_distance: 5,
		small_thumbs: 5,
		smallthumb_inactive_opacity: 0.8,
		show_descriptions:false,
		show_hint: true,
		show_icon: false,
		autoplay: true,
		keyboard: true,
		zoom_easing: false
	});
})
</script>

<style type="text/css">
	.goods-intro{position:relative;z-index: 1;padding-left:450px;min-height: 400px;height: auto!important;height: 400px;border-bottom: solid 2px #008AFF;margin: 20px 0;overflow: hidden;}
	.goods-image{position:absolute;top: 0;left: 0;width: 420px;}
	.goods-image .image{width:400px;height:360px;overflow: hidden;border: 1px solid #ddd;padding:6px;}
	.goods-image .image img{width: 100%;}
	.goods-title{font-size: 24px;font-weight: normal;padding: 0;margin:-2px 0 20px 0;}
	.goods-description{font-size: 14px;line-height: 22px;margin-bottom: 10px;max-height: 90px;overflow: hidden;}
	.goods-attrs{background: #EAF5FE;border: solid 1px #DFDFDF;padding: 10px;margin:20px 0;}
	.goods-attrs .item{float:left;width:49%;font-size: 15px;padding:5px 0;}
	.goods-attrs b{font-weight: normal;color:#666;margin-right: 10px;}
</style>


{template 'footer.php'}