{template 'dialog.header.php'}

	<textarea style="width:3000px;border:0 none;;margin:10px;padding:0;line-height:20px;">
	// [{$tablename}] 创建
	$this->db->dropTable('{$tablename}');
	$this->db->createTable('{$tablename}', {$schemastr});

	{if !empty($data)}
	// [{$tablename}] 插入数据
	$data = {arr::export($data)};
	foreach($data as $d){$this->db->table('{$tablename}')->data($d)->insert();}
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