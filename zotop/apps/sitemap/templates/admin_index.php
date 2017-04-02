{template 'header.php'}
<div class="main scrollable">
	<div class="container-fluid container-primary">
		<div class="jumbotron text-center">
			<h1>{a('sitemap.name')}</h1>
			<p>{a('sitemap.description')}</p>
			<p>
				<a href="{U('sitemap/admin/create')}" class="btn btn-success btn-lg js-ajax-post">
					<i class="fa fa-refresh fa-fw"></i> {t('立即生成')}
				</a>
			</p>
		</div>
	</div>

	<div class="container-fluid">
		<table class="table">
			<caption>{t('sitemap.xml')}</caption>
			<tr>
				<td width="20%">{t('文件地址')}</td>
				<td>
					<a href="{$url}" target="_blank"><b>{$url}</b></a> 
				</td>
			</tr>
			<tr>
				<td>{t('生成时间')}</td>
				<td>
					<span>{format::date($time)}</span>
				</td>
			</tr>
			<tr>
				<td>{t('文件大小')}</td>
				<td>
					<span>{format::size($size)}</span>
				</td>
			</tr>					
		</table>

		<table class="table">
			<caption>{t('搜索引擎提交')}</caption>			

			<tr>
				<td width="20%">{t('百度提交地址')}</td>
				<td>
					<a href="http://zhanzhang.baidu.com/sitemap" target="_blank">http://zhanzhang.baidu.com/sitemap</a>
				</td>
			</tr>
			<tr>
				<td>{t('360搜索提交地址')}</td>
				<td>
					<a href="http://zhanzhang.so.com/" target="_blank">http://zhanzhang.so.com/</a>
				</td>
			</tr>
			<tr>
				<td>{t('搜狗搜索提交地址')}</td>
				<td>
					<a href="http://zhanzhang.sogou.com/" target="_blank">http://zhanzhang.sogou.com/</a>
				</td>
			</tr>
			<tr class="hidden">
				<td>{t('谷歌提交地址')}</td>
				<td>
					<a href="http://www.google.com/webmasters" target="_blank">http://www.google.com/webmasters</a>
				</td>
			</tr>																	
		</table>
	</div>
</div>
{template 'footer.php'}