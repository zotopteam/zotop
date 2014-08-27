<?php defined('ZOTOP') or exit('No permission resources.'); ?>
</div> <!-- body -->
<div class="global-footer">
<?php if(ZOTOP_DEBUG):?>
<?php echo debug::vars(get_included_files());?>
<?php echo debug::vars(zotop::db()->sql());?>
<?php endif; ?>
</div> <!-- footer -->

<?php zotop::run('admin.footer', $this); ?>

</body>
</html>