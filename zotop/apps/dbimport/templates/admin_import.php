{template 'header.php'}



<div class="main">
	<div class="main-header">
		<div class="title">{t('数据导入')}</div>

		<div class="position">
			<a href="{u('dbimport/admin/index')}">{t('规则管理')}</a>
			<s class="arrow">></s>
			{$data.name}			
		</div>

	</div><!-- main-header -->
	<div class="main-body scrollable">
		
		{if $error}
			<div class="nodata">
					<h2>{t('导入数据发生了一个错误')} ：<span class="error">{$error} </span></h2>


					<h3>{t('请尝试')}：<a href="{u('dbimport/admin/edit/'.$data.id)}">{t('编辑规则')}</a></h3>
			</div>

		{elseif $count==0}

			<div class="nodata">
				<h2>{t('没有可以导入的数据')}</h2>
				<h3>{t('请尝试')}：<a href="{u('dbimport/admin/edit/'.$data.id)}">{t('编辑规则')}</a></h3>
			</div>
		{else}

			<table class="field">
				<caption>{t('数据源信息')}</caption>
				<tbody>	
				<tr>
					<td class="label"><label>{t('数据源')}</label></td>
					<td class="input"><b>{$data.source.hostname}:{$data.source.hostport} / {$data.source.database} </b></td>
				</tr>
				<tr>
					<td class="label"><label>{t('待提取数据条件')}</label></td>
					<td class="input"><b>{$data.source.table} {$data.source.condition}</b></td>
				</tr>					
				<tr>
					<td class="label"><label>{t('待导入数据条数')}</label></td>
					<td class="input"><b>{$count}</b></td>
				</tr>
						
				</tbody>
			</table>
		

			<div class="blank"></div>

			<a href="javascript:void(0);" class="btn btn-highlight">{t('开始导入')}</a>

		{/if}



	</div><!-- main-body -->
	<div class="main-footer">

	</div><!-- main-footer -->
</div><!-- main -->


<script type="text/javascript">

</script>

{template 'footer.php'}