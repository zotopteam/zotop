<div class="panel panel-default panel-about">
    <div class="panel-heading">
        <div class="panel-action">
        {if alias('about')} <a class="more" href="{U('about')}">{t('更多')} <i class="fa fa-angle-right"></i></a> {/if}
        </div>
        <div class="panel-title">{$name}</div>
    </div>
    <div class="panel-body">
        <div class="html">{$data}</div>
    </div>
</div>