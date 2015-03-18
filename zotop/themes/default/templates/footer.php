</div> <!-- body -->
<div class="footer">
	<div class="footer-info">{block id="2" name="t('网站底部')" type="html" template="block/html.php"}</div>
	
	{block id="3" name="t('统计代码')" type="text" template="block/text.php"}
</div> <!-- footer -->

<div class="powered">{zotop::powered()} {c('theme.name')}</div>


{hook 'site.footer'}

<div class="floatbar">
	<a class="item none" id="gotop" href="javascript:;" title="{t('回到顶部')}"><div class="icon icon-up"></div></a>
	<a class="item" href="{u('guestbook#guestbook-add')}" title="{t('留言')}"><div class="icon icon-msg"></div></a>	
</div>

</div><!-- wrapper -->
</body>
</html>