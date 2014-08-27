<!--焦点图 开始-->
<div id="block-{$id}" class="slider clearfix pngfix">
	<div class="loading"></div>
	<div class="pic">
		<ul>
		{loop $data $i $r}
		<li><a href="{U($r.url)}" title="{$r.title}"><img src="{$r.image}" alt="{$r.title}" text="{$r.description}"/></a></li>
		{/loop}
		</ul>
	</div>
</div>

{html::import(__THEME__.'/js/myfocus/myfocus-2.0.4.min.js')}
{html::import(__THEME__.'/js/myfocus/mf-pattern/mF_expo2010.js')}
{html::import(__THEME__.'/js/myfocus/mf-pattern/mF_expo2010.css')}


<script type="text/javascript">
myFocus.set({
	id:'block-{$id}',
	pattern:'mF_expo2010',//风格样式
	trigger:'mouseover',//触发切换模式['click'(鼠标点击)|'mouseover'(鼠标悬停)]
	loadingShow:true,
	time:5,
	auto:true,
	wrap:false//是否保留边框(有的话)[true|false]
})
</script>
<!--焦点图 结束-->