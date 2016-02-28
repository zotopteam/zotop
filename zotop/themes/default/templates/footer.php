<footer class="footer">
    <div class="container">
        <nav class="text-center">
          {block id="2" type="list" name="t('底部导航')" template="block/nav_inline.php" fields="title,url"}
        </nav>

        <div class="footer-text text-center">
            <div>{C('site.copyright')} {if C('site.beian')} <a href="http://www.miitbeian.gov.cn/" target="_blank">{C('site.beian')}</a> {/if}</div>
            <div>{C('site.address')} {C('site.phone')} {C('site.fax')} {C('site.email')}</div>            
            <div class="custom">{block id="3" type="html" name="t('网站底部自定义内容')" template="block/html.php"}</div>
            <div class="powered">{zotop::powered()} {C('theme.name')}</div>
        </div>      
    </div>
</footer>

<ul class="scroll-btn">
    {if A('guestbook')}
    <li>
        <a href="{U('guestbook')}"><i class="fa fa-comment"></i></a>
        <div class="popover fade left">
            <div class="arrow"></div>
            <div class="popover-content">{C('guestbook.title')}</div>
        </div>        
    </li>
    {/if}    
    {if C('site.wechat_qrcode')}
    <li>
        <a href="javascript:;"><i class="fa fa-qrcode"></i></a>
        <div class="popover fade left qrcode">
            <div class="arrow"></div>
            <div class="popover-content">
                <img src="{C('site.wechat_qrcode')}" alt="{t('微信二维码')}">
                {t('微信公众号')} <br> {if C('site.wechat')} {C('site.wechat')} {else} &nbsp; {/if}
            </div>
        </div>
    </li>
    {/if}
    <li>
        <a href="javascript:;"><i class="fa fa-phone"></i></a>
        <div class="popover fade left">
            <div class="arrow"></div>
            <div class="popover-content"><strong class="text-danger">{C('site.phone')}</strong></div>
        </div>
    </li> 
    <li id="gotop" style="visibility:hidden;">
        <a href="javascript:;"<i class="fa fa-chevron-up"></i></a>
        <div class="popover fade left">
            <div class="arrow"></div>
            <div class="popover-content">{t('回到顶部')}</div>
        </div>          
    </li>
</ul>

{block id="5" type="text" name="t('统计代码')" template="block/text.php"}

{hook 'site.footer'}
</body>
</html>