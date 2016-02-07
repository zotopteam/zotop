{template 'header.php'}

{template 'developer/project_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="goback"><a href="{U('developer/project/table')}"><i class="fa fa-angle-left"></i><span>{t('返回')}</span></a></div>	
		<div class="title">{$table}</div>
		
		<div class="btn-group">
			{loop developer_schema_navbar($table) $k $n}
				<a href="{$n.href}" class="btn {if $k=='show'}btn-primary{else}btn-default{/if} {$n.class}">{$n.text}</a>
			{/loop}
		</div>
	</div>
	<div class="main-body scrollable">
		<div class="container-fluid">

			<textarea style="width:3000px;border:0 none;margin:10px;padding:0;line-height:20px;">
			// [{$table}] 创建
			$this->db->dropTable('{$table}');
			$this->db->createTable('{$table}', {$schemastr});

			{if !empty($data)}
			// [{$table}] 插入数据
			$data = {arr::export($data)};
			foreach($data as $d){$this->db->table('{$table}')->data($d)->insert();}
			{/if}
			</textarea>

		</div>
	</div><!-- main-body -->
	<div class="main-footer">
		<div class="footer-text">{t('数据表结构代码')}</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		$('textarea').height(function(){
			return this.scrollHeight;
		}).css('overflow','hidden');
	})
</script>

{template 'footer.php'}