<ul class="sidenavlist">
	<li>
		<a href="{u('system/upload/'.$type, $_GET)}"{if substr_count(ZOTOP_URI,'system/upload/'.$type)} class="current"{/if}>
			<i class="icon icon-upload"></i>{t('本地上传')}
		</a>
	</li>
	<li>
		<a href="{u('system/upload/library/'.$type, $_GET)}"{if substr_count(ZOTOP_URI,'system/upload/library')} class="current"{/if}>
			<i class="icon icon-library"></i>{t('%s库',$typename)}
		</a>
	</li>
	<li>
		<a href="{u('system/upload/dirview/'.$type, $_GET)}"{if substr_count(ZOTOP_URI,'system/upload/dirview')} class="current"{/if}>
			<i class="icon icon-folder"></i>{t('目录浏览')}
		</a>
	</li>
	<li style="display:none;">
		<a href="{u('system/upload/remoteurl/'.$type, $_GET)}"{if substr_count(ZOTOP_URI,'system/upload/remoteurl')} class="current"{/if}>
			<i class="icon icon-url"></i>{t('网络文件')}
		</a>
	</li>
	{zotop::run('system.upload.navbar')}
</ul>
