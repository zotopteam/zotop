{template 'dialog.header.php'}

	<textarea style="width:1600px;border:0 none;;margin:10px;padding:0;line-height:20px;">
	// [{$table}] 创建	
	$this->db->schema('{$table}')->drop();
	$this->db->schema('{$table}')->create({$schemastr});

	{if !empty($data)}
	// [{$table}] 插入数据
	{loop $data $r}
	$this->db->insert('{$table}',{var_export($r,true)});
	{/loop}
	{/if}
	</textarea>
	<script type="text/javascript">

		$(function(){
			$('textarea').height(function(){
				return this.scrollHeight;
			}).css('overflow','hidden');
		})
	</script>

{template 'dialog.footer.php'}