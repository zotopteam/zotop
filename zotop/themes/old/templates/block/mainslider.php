<!--焦点图 开始-->
<div id="block-{$id}" class="slidebox">
    <div class="hd">
            <ul></ul>
    </div>
    <div class="bd">
		<ul>
		{loop $data $i $r}
		<li><a href="{U($r.url)}" title="{$r.title}"><img src="{$r.image}" alt="{$r.title}"/></a></li>
		{/loop}
		</ul>
    </div>
    <a class="prev" href="javascript:void(0)"></a>
	<a class="next" href="javascript:void(0)"></a>
</div>

{html::import(__THEME__.'/js/slider/jquery.superslide.js')}
{html::import(__THEME__.'/js/slider/jquery.superslide.css')}

<script type="text/javascript">
$(function(){
	$("#block-{$id}").slide({titCell:".hd ul",mainCell:".bd ul",effect:"fold",autoPlay:true,autoPage:"<li><a></a></li>"});
});
</script>
<!--焦点图 结束-->