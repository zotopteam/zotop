</div> <!-- body -->
<div class="global-footer">
	{if ZOTOP_DEBUG}
		{debug::vars(get_included_files())}
		{debug::vars(zotop::db()->sql())}
	{/if}
</div> <!-- footer -->

{hook 'admin.footer'}

</body>
</html>