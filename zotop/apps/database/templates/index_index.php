{template 'header.php'}
<div class="main{if empty($databases)} no-footer{/if}">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div>
	<div class="main-body scrollable">
		{if empty($databases)}
			<div class="nodata">{t('没有找到任何数据表')}</div>
		{else}
		<form>
		<table class="table list zebra">	
			<thead>
				<tr>
					<td class="w200">{t('名称')}</td>
					<td>{t('位置')}</td>
					<td class="w160">{t('数据库')}</td>					
					<td class="w120">{t('数据表前缀')}</td>
					<td class="w80">{t('编码')}</td>
					<td class="w60 center">{t('持久连接')}</td>
				</tr>			
			</thead>
			<tbody>
			{loop $databases $id $config}
				<tr class="{if $i%2==0}even{else}odd{/if}">
					<td>
						<div><b>{$id}</b></div>
						<div class="manage">
							<a href="{zotop::url('database/index/table',array('databaseconfig'=>$id))}">{t('数据表管理')}</a>
						</div>
					</td>					
					<td>
						{if $config['username']}{$config['username']} : {$config['password']}@{/if}
						{$config[hostname]}
						{if $config['hostport']}:{$config['hostport']}{/if}</td>
					<td>{$config[database]}</td>

					<td>{$config[prefix]}</td>
					<td>{$config[charset]}</td>
					<td class="center">{if $config['pconnect']}<span class="green">√</span>{else}<span class="gray">×</span>{/if}</td>
				</tr>
				{$i++}
			{/loop}
			<tbody>
		</table>
		</form>
		{/if}
	</div>
	<div class="main-footer">
		<div class="tips">{t('管理 data/database.php 中配置的数据库')}</div>
	</div>
</div>
{template 'footer.php'}