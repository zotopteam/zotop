<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<?php $this->display('dialog.header.php'); ?>

<textarea style="width:1200px;border:0 none;;margin:10px;padding:0;line-height:20px;">
// [<?php echo $table;?>] 创建
$this->db->table('<?php echo $table;?>')->drop();
$this->db->table('<?php echo $table;?>')->create(<?php echo $schemastr;?>);
<?php if(!empty($data)):?>
// [<?php echo $table;?>] 插入数据
<?php $n=1; foreach($data as $r): ?>
$this->db->insert('<?php echo $table;?>',<?php echo var_export($r,true);?>);
<?php $n++;endforeach;unset($n); ?>
<?php endif; ?>
</textarea>
<script type="text/javascript">

$(function(){
$('textarea').height(function(){
return this.scrollHeight;
}).css('overflow','hidden');
})
</script>

<?php $this->display('dialog.footer.php'); ?>