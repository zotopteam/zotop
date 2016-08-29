{template 'header.php'}

<section class="section bg-default">
    <div class="container">
        {block id="4" type="html" name="t('网站简介')" template="block/panel-about.php"}
    </div>
</section>

{if $c = m('content.category.get',1)}
<section class="section">
    <div class="container">

        <div class="panel panel-default">
            <div class="panel-heading">         
                <div class="panel-action"><a class="more" href="{$c.url}">{t('更多')} <i class="fa fa-angle-right"></i></a></div>
                <div class="panel-title">{$c.name}</div>
            </div>

            {content cid="$c.id" size="1" image="true"}
            <div class="media media-sm">
                <a href="{$r.url}" title="{$r.title}">
                    <div class="media-left"><img src="{$r.image width="400" height="300"}" alt="{$r.title}" class="media-object"/></div>
                    <div class="media-body">
                        <div class="media-heading">
                            <span class="pull-right text-muted hidden">{$r.createtime date="date"}</span>
                            <h4 {$r.style}>{$r.title length="26"} {$r.new}</h4>
                        </div>
                        <div class="media-summary hidden-xs">{$r.summary length="26"}</div>
                    </div>
                </a>
            </div>
            {/content}

            <ul class="list-group">
                {content cid="$c.id" size="5" ignore="$r.id"}
                <li class="list-group-item">
                    <span class="pull-right text-muted hidden-xs">{$r.createtime date="date"}</span>
                    <a href="{$r.url}" title="{$r.title}" {$r.style}>{$r.title length="26"}</a> {$r.new}
                </li>
                {empty}
                <li class="list-group-item"><div class="nodata">{t('暂无数据')}</div></li>
                {/content}
            </ul>
        </div><!-- panel -->

    </div>
</section>
{/if}


{if $c = m('content.category.get',5)}
<section class="section bg-primary">
    <div class="container">
            
        <div class="page-header clearfix">
            <h1 class="pull-left"><a href="{$c.url}">{$c.name}</a></h1>
            <h5 class="hidden">{$c.description}</h5>
            <nav>
                <a class="more " href="{$c.url}">{t('更多')} <i class="fa fa-angle-right"></i></a>
            </nav>
        </div>

        <div class="row">
            {content cid="$c.id" size="8"}
            <div class="col-xs-6 col-md-3 dataitem">
 
                <div class="thumbnail product">
                    <a href="{$r.url}">
                    <div class="image"><img src="{$r.image width="400" height="300"}" alt="{$r.title}"></div>
                    <div class="caption">
                        <div class="title text-overflow" {$r.style}>{$r.title} {$r.new}</div>           
                        <div class="description hidden">{$r.summary length="26"}</div>                         
                    </div>

                    </a>
                </div>

            </div>
            {empty}
            <div class="nodata">{t('暂时没有数据，请稍后访问')}</div>                   
            {/content}
        </div>
               
    </div>
</section>
{/if}

{template 'footer.php'}