{template 'header.php'}
<style>
  .panel-app .image-list a{position:relative;display:block;float:left;font-size:12px;text-align:center;}
  .panel-app .image-list a .image{margin:10px auto 6px auto;height: 45px;overflow: hidden;}
  .panel-app .image-list a .title{padding:0}
  .panel-category a{
    display: block;
    padding: 8px 0;
    margin-bottom: 10px;
    font-size: 14px;
    text-align: center;
    background-color: #eee;    
  }
  .panel-image a{display: block;background: #f9f9f9;padding:1px;margin-bottom: 10px;}
  .panel-image .image{width: 100%;overflow: hidden;}
  .panel-image .image img{width: 100%;}
  .panel-image .title{
    width: 95%;
    margin:10px auto 5px auto;
    height: 36px;
    line-height: 18px;
    font-size: 12px;
    text-align: center;
  }

  .panel-media .media{border:0 none;border-top:solid 1px #ebebeb;padding: 10px 0;panel-shadow:0 0 0 #fff;border-radius:0px;}
</style>

{if $b = m('block.block.get',4)}

<div id="mainslider" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
	{loop $b.data $i $r}
	<li data-target="#mainslider" data-slide-to="{$i}" {if $i==0}class="active"{/if}></li>
	{/loop}
  </ol>
  <div class="carousel-inner" role="listbox">
  	{loop $b.data $i $r}
    <div class="item {if $i==0}active{/if}">
      <a href="{$r.url}"></a><img src="{thumb($r.image,1920,700)}" alt="{$r.title}"></a>
    </div>
    {/loop}
  </div>
  <a class="left carousel-control" href="#mainslider" role="button" data-slide="prev">
    <span class="icon-prev fa fa-angle-left" aria-hidden="true"></span>
    <span class="sr-only">{t('上一个')}</span>
  </a>
  <a class="right carousel-control" href="#mainslider" role="button" data-slide="next">
    <span class="icon-next fa fa-angle-right" aria-hidden="true"></span>
    <span class="sr-only">{t('下一个')}</span>
  </a>
</div>
<div class="blank"></div>
{/if}

<div class="container">
{if $c = m('content.category.get',3)}
<div class="panel panel-default panel-category clearfix">
  <div class="panel-heading"><h3 class="panel-title">{$c.name}</h3></div>
  <div class="panel-body">
      <div class="row">
        {content action="category" cid="$c.id"}
        <div class="col-xs-4"><a href="{$r.url}">{$r.name}</a></div>
        {/content}
      </div>
  </div>
</div> 
{/if}

{if $c = m('content.category.get',5)}
<div class="panel panel-default panel-image clearfix">
  <div class="panel-heading"><h3 class="panel-title">{$c.name}</h3></div>
  <div class="panel-body">
    <div class="row">
      {content modelid="case" cid="$c.id" size="4"}
      <div class="col-xs-6">
        <a href="{$r.url}">
          <div class="image"><img src="{thumb($r.image,400,300)}" alt="{$r.title}"></div>
          <div class="title" {$r.style}>{$r.title} {$r.new}</div>          
        </a>
      </div> 
      {/content}
    </div>
  </div>
  <div class="panel-footer">
    <a href="{$c.url}">更多 <i class="fa fa-arrow-right"></i></a>
  </div>
</div>
{/if}

{if $c = m('content.category.get',2)}
<div class="panel panel-default panel-media clearfix">
  <div class="panel-heading"><h3 class="panel-title">{$c.name}</h3></div>
  <div class="panel-body">
      {content cid="$c.id" size="4"}
      <div class="media">
        <a href="{$r.url}">
        <div class="media-left"><img src="{thumb($r.image,200,150)}" alt="{$r.title}"></div>
        <div class="media-body">
          <div class="media-heading" {$r.style}>{$r.title} {$r.new}</div>
          <div class="media-info hidden-sm">
            <i class="fa fa-folder"></i> {m('content.category.get',$r.categoryid, 'name')}
            &nbsp;&nbsp;&nbsp;
            <i class="fa fa-calendar"></i> {format::date($r.createtime,'u|date')}
            &nbsp;&nbsp;&nbsp;
            <i class="fa fa-user"></i> {$r.hits}
          </div>        
          <div class="media-description hidden">{str::cut($r.summary,140)}</div>
        </div>
        </a>
      </div>
      {/content}
  </div>
  <div class="panel-footer">
    <a href="{$c.url}">更多 <i class="fa fa-arrow-right"></i></a>
  </div>
</div>
{/if}

{if $c = m('content.category.get',6)}
<div class="panel panel-default panel-media clearfix">
  <div class="panel-heading"><h3 class="panel-title">{$c.name}</h3></div>
  <div class="panel-body">
      {content cid="$c.id" size="4"}
      <div class="media">
        <a href="{$r.url}">
        <div class="media-left"><img src="{thumb($r.image,200,150)}" alt="{$r.title}"></div>
        <div class="media-body">
          <div class="media-heading" {$r.style}>{$r.title} {$r.new}</div>
          <div class="media-info hidden-sm">
            <i class="fa fa-folder"></i> {m('content.category.get',$r.categoryid, 'name')}
            &nbsp;&nbsp;&nbsp;
            <i class="fa fa-calendar"></i> {format::date($r.createtime,'u|date')}
            &nbsp;&nbsp;&nbsp;
            <i class="fa fa-user"></i> {$r.hits}
          </div>        
          <div class="media-description hidden">{str::cut($r.summary,140)}</div>
        </div>
        </a>
      </div>
      {/content}
  </div>
  <div class="panel-footer">
    <a href="{$c.url}">更多最新知识 <i class="fa fa-arrow-right"></i></a>
  </div>
</div>
{/if}
</div>
{template 'footer.php'}