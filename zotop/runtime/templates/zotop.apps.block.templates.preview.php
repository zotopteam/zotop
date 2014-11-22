<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<?php $this->display('dialog.header.php'); ?>

<div class="preview clearfix">	

<?php $this->display($template); ?>

</div>

<style type="text/css">
.preview{margin:20px;}
</style>

<?php $this->display('dialog.footer.php'); ?>