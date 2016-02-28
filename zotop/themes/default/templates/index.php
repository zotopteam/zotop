{template 'header.php'}

<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-7 col-lg-7">
                {if $c = m('content.category.get',2)}
                <div class="panel panel-default">
                    <div class="panel-heading">         
                        <div class="panel-action"><a class="more" href="{$c.url}">{t('更多')} <i class="fa fa-angle-right"></i></a></div>
                        <div class="panel-title">{$c.name}</div>
                    </div>

                    {content cid="$c.id" size="1" image="true"}
                    <div class="media media-sm">
                        <a href="{$r.url}" title="{$r.title}">
                            <div class="media-left"><img src="{thumb($r.image,400,300)}" alt="{$r.title}" class="media-object"/></div>
                            <div class="media-body">
                                <div class="media-heading">
                                    <span class="pull-right text-muted hidden">{format::date($r.createtime,'date')}</span>
                                    <h4 {$r.style}>{str::cut($r.title,26)} {$r.new}</h4>
                                </div>
                                <div class="media-summary hidden-xs">{str::cut($r.summary,50)}</div>
                            </div>
                        </a>
                    </div>
                    {/content}

                    <ul class="list-group">
                        {content cid="$c.id" size="5" ignore="$r.id"}
                        <li class="list-group-item">
                            <span class="pull-right text-muted hidden-xs">{format::date($r.createtime,'date')}</span>
                            <a href="{$r.url}" title="{$r.title}" {$r.style}>{str::cut($r.title,24)}</a> {$r.new}
                        </li>
                        {else}
                        <li class="list-group-item"><div class="nodata">{t('暂无数据')}</div></li>
                        {/content}
                    </ul>
                </div><!-- panel -->
                {/if}
            </div>
            <div class="col-sm-6 col-md-5 col-lg-5">
                {block id="4" type="html" name="t('网站简介')" template="block/panel-about.php"}
            </div>                
        </div>
    </div>
</section>
{template 'footer.php'}