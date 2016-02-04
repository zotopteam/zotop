{template 'header.php'}

<div class="container">

{if $c = m('content.category.get',2)}
<div class="panel panel-default panel-media clearfix">
  <div class="panel-heading"><h3 class="panel-title">{$c.name}</h3></div>
  <div class="panel-body">
      <div class="row">
        {content action="category" cid="$c.id"}
        <div class="col-xs-4"><a href="{$r.url}">{$r.name}</a></div>
        {/content}
      </div>  
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