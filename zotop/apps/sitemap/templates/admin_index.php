{template 'header.php'}

<div class="main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">

		</div>
	</div>
	<div class="main-body scrollable">
		
		<table class="field">
			<caption>{t('sitemap.xml')}</caption>
			<tr>
				<td class="label">{t('文件地址')}</td>
				<td class="input">
					<a href="{$url}" target="_blank"><b>{$url}</b></a> 
				</td>
			</tr>
			<tr>
				<td class="label">{t('生成时间')}</td>
				<td class="input">
					<span>{format::date($time)}</span>
				</td>
			</tr>
			<tr>
				<td class="label">{t('文件大小')}</td>
				<td class="input">
					<span>{format::size($size)}</span>
				</td>
			</tr>			
			<tr>
				<td class="label"></td>
				<td class="input">
					<div><a href="{U('sitemap/admin/create')}" class="btn btn-highlight ajax-post">{t('立即生成')}</a></div>
				</td>
			</tr>						
		</table>
		<table class="field">
			<caption>{t('搜索引擎提交')}</caption>			
			<tr class="none">
				<td class="label">{t('谷歌提交地址')}</td>
				<td class="input">
					<a href="http://www.google.com/webmasters" target="_blank">http://www.google.com/webmasters</a>
				</td>
			</tr>
			<tr>
				<td class="label">{t('百度提交地址')}</td>
				<td class="input">
					<a href="http://zhanzhang.baidu.com/sitemap" target="_blank">http://zhanzhang.baidu.com/sitemap</a>
				</td>
			</tr>
			<tr>
				<td class="label">{t('360搜索提交地址')}</td>
				<td class="input">
					<a href="http://zhanzhang.so.com/" target="_blank">http://zhanzhang.so.com/</a>
				</td>
			</tr>
			<tr>
				<td class="label">{t('搜狗搜索提交地址')}</td>
				<td class="input">
					<a href="http://zhanzhang.sogou.com/" target="_blank">http://zhanzhang.sogou.com/</a>
				</td>
			</tr>														
		</table>

	</div>
	<div class="main-footer">
		{a('sitemap.description')}
	</div>
</div>
{template 'footer.php'}