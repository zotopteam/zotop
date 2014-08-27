</div> <!-- body -->
<div class="footer">

	<div class="footer-service clearfix">
		<ul>
			<li><a href="#"><i class="icon icon-good"></i><b>{t('解决方案定制')}</b></a></li>
			<li><a href="#"><i class="icon icon-reject"></i><b>{t('安防产品采购')}</b></a></li>
			<li><a href="#"><i class="icon icon-refresh"></i><b>{t('全程施工监管')}</b></a></li>
			<li><a href="#"><i class="icon icon-user"></i><b>{t('一年免费维护')}</b></a></li>
			<li><a href="#"><i class="icon icon-gift"></i><b>{t('三年质量保障')}</b></a></li>
		</ul>
	</div>

	<div class="footer-links clearfix">
		<dl class="col-links col-links-first">
			<dt>{m('content.category.get',5,'name')}</dt>
			{content cid="5" size="5" cache="true"}
			<dd><a href="{$r.url}" title="{$r.title}" {$r.style}>{str::cut($r.title,14)}</a></dd>
			{/content}
		</dl>
		<dl class="col-links">
			<dt>{m('content.category.get',6,'name')}</dt>
			{content cid="6" size="5" cache="true"}
			<dd><a href="{$r.url}" title="{$r.title}" {$r.style}>{str::cut($r.title,14)}</a></dd>
			{/content}
		</dl>
		<dl class="col-links">
			<dt>{m('content.category.get',7,'name')}</dt>
			{content cid="7" size="5" cache="true"}
			<dd><a href="{$r.url}" title="{$r.title}" {$r.style}>{str::cut($r.title,14)}</a></dd>
			{/content}
		</dl>
		<dl class="col-links">
			<dt>{m('content.category.get',1,'name')}</dt>
			{content cid="1" size="5" cache="true"}
			<dd><a href="{$r.url}" title="{$r.title}" {$r.style}>{str::cut($r.title,14)}</a></dd>
			{/content}
		</dl>
		<div class="col-contact">
			<p class="phone">400-007-2223</p>
			<p>
				<img src="{ZOTOP_URL_UPLOADS}/common/qcode.jpg" width="140px"/>
			</p>
		</div>						
	</div>

	<div class="footer-navbar">{block 'navbar'}</div>

	<div class="footer-info">{block 'footer'}</div>
	{block 'counter'}
</div> <!-- footer -->

{hook 'site.footer'}

<div class="floatbar">
	<a class="item none" id="gotop" href="javascript:;" title="{t('回到顶部')}"><div class="icon icon-up"></div></a>
	<a class="item" href="{u('guestbook#guestbook-add')}" title="{t('留言')}"><div class="icon icon-msg"></div></a>	
</div>

</div><!-- wrapper -->
<div class="powered none">{zotop::powered()}</div>
</body>
</html>