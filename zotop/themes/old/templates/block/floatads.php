<div id="floatads" style="position:absolute;z-index:9999;overflow:hidden;" >
{loop $data $i $r}
		<a href="{U($r.url)}" title="{$r.title} --- {t('点击查看详情')}"><img src="{thumb($r.image,300,200)}" alt="{$r.title}" style="max-width:300px;max-height:200px;border:solid 3px #fff;"/></a>		
{/loop}
</div>
<script type="text/javascript" src="{__THEME__}/js/floatads.js"></script>

