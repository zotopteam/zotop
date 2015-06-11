</div> <!-- body -->
<div class="global-footer">
	{loop zotop::trace('sql') $t}
	{$t}<br>
	{/loop}
</div> <!-- footer -->

{hook 'admin.footer'}

</body>
</html>