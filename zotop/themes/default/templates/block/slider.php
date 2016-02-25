<div id="slider-block-{$id}" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
	{loop $data $i $r}
	<li data-target="#slider-block-{$id}" data-slide-to="{$i}" {if $i==0}class="active"{/if}></li>
	{/loop}
  </ol>
  <div class="carousel-inner" role="listbox">
  	{loop $data $i $r}
    <div class="item {if $i==0}active{/if}">
      <img src="{$r.image}" alt="{$r.title}">    
    </div>
    {/loop}
  </div>
  <a class="left carousel-control" href="#slider-block-{$id}" role="button" data-slide="prev">
    <span class="icon-prev fa fa-angle-left" aria-hidden="true"></span>
    <span class="sr-only">{t('前')}</span>
  </a>
  <a class="right carousel-control" href="#slider-block-{$id}" role="button" data-slide="next">
    <span class="icon-next fa fa-angle-right" aria-hidden="true"></span>
    <span class="sr-only">{t('后')}</span>
  </a>
</div>