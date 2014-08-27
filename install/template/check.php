<?php include ZOTOP_PATH_INSTALL.DS.'template'.DS.'header.php';?>
<div class="main scrollable clearfix">
<div class="main-inner">
	 <h1><?php echo t('服务器环境')?></h1>

	 <table class="list">
		<thead>
			<tr>
				<td class="col1"><?php echo t('检测项目')?></td>
				<td class="col2"><?php echo t('当前配置')?></td>
				<td class="col3"><?php echo t('推荐配置')?></td>
				<td class="col4"></td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>WEB 服务器</td>
				<td><?php echo trim(preg_replace(array('#PHP\/[\d\.]+#', '#\([\w]+\)#'), '', $_SERVER['SERVER_SOFTWARE']));?> - <?php echo PHP_OS;?></td>
				<td>Apache/2.2.x - Linux/Freebsd</td>
				<td><span><span class="success">&#x221A;</span></span></td>
			</tr>
			<tr>
				<td>PHP 版本</td>
				<td>PHP <?php echo phpversion();?></td>
				<td>PHP 5.2.0 及以上</td>
				<td>
					<?php if(phpversion() >= '5.2.0'){ ?>
						<span><span class="success">&#x221A;</span></span>
					<?php }else{ ?>
					<font class="red"><span class="error">&#x00D7;</span>&nbsp;无法安装</font><?php }?>
					</font>
				</td>
			</tr>
			<tr>
				<td>PDO 扩展</td>
				<td><?php echo  extension_loaded('PDO') ? '<span class="success">&#x221A;</span>' : '<span class="error">&#x00D7;</span>' ?></td>
				<td>必须开启</td>
				<td><?php echo  extension_loaded('PDO') ? '<span class="success">&#x221A;</span>' : '<span class="error">&#x00D7;</span>' ?></td>
			</tr>
			<tr>
				<td>PDO_SQLITE 扩展</td>
				<td><?php echo  extension_loaded('PDO_SQLITE') ? '<span class="success">&#x221A;</span>' : '<span class="error">&#x00D7;</span>' ?></td>
				<td>必须开启</td>
				<td><?php echo  extension_loaded('PDO_SQLITE') ? '<span class="success">&#x221A;</span>' : '<span class="error">&#x00D7;</span>' ?></td>
			</tr>
			<tr>
				<td>MYSQL 扩展</td>
				<td><?php echo  extension_loaded('mysql') ? '<span class="success">&#x221A;</span>' : '<span class="error">&#x00D7;</span>' ?></td>
				<td>必须开启</td>
				<td><?php echo  extension_loaded('mysql') ? '<span class="success">&#x221A;</span>' : '<span class="error">&#x00D7;</span>' ?></td>
			</tr>

			<tr>
			<td>ICONV/MB_STRING 扩展</td>
			<td>
				<?php echo (extension_loaded('iconv') || extension_loaded('mbstring')) ? '<span class="success">&#x221A;</span>' : '<span class="error">&#x00D7;</span>' ?>
			</td>
			<td>必须开启</td>
			<td>
				<?php echo (extension_loaded('iconv') || extension_loaded('mbstring')) ? '<span class="success">&#x221A;</span>' : '<span class="error">&#x00D7; 字符集转换效率低</span>' ?>
			</td>
			</tr>

			<tr>
				<td>JSON扩展</td>
				<td><?php echo $PHP_JSON ? '<span class="success">&#x221A;</span>' : '<span class="error">&#x00D7;</span>' ?></td>
				<td>必须开启</td>
				<td>
					<?php echo $PHP_JSON ? '<span class="success">&#x221A;</span>' : '<span class="error">&#x00D7;&nbsp;不只持json,<a href="http://pecl.php.net/package/json" target="_blank">安装 PECL扩展</a></span>' ?>
				</td>
			</tr>
			<tr>
				<td>GD 扩展</td>
				<td><?php echo $PHP_GD ? '<span class="success">&#x221A;</span> ( '.$PHP_GD.' )' : '<span class="error">&#x00D7;</span>' ?></td>
				<td>建议开启</td>
				<td>
				<?php echo $PHP_GD ? '<span class="success">&#x221A;</span>' : '<span class="error">&#x00D7;&nbsp;不支持缩略图和水印</span>' ?>
				</td>
			</tr>
			<tr>
				<td>ZLIB 扩展</td>
				<td><?php echo extension_loaded('zlib') ? '<span class="success">&#x221A;</span>' : '<span class="error">&#x00D7;</span>' ?></td>
				<td>建议开启</td>
				<td>
				<?php echo extension_loaded('zlib') ? '<span class="success">&#x221A;</span>' : '<span class="error">&#x00D7;&nbsp;不支持Gzip功能</span>' ?>
				</td>
			</tr>
			<tr>
				<td>FTP 扩展</td>
				<td><?php echo extension_loaded('ftp') ? '<span class="success">&#x221A;</span>' : '<span class="error">&#x00D7;</span>' ?></td>
				<td>建议开启</td>
				<td>
				<?php echo extension_loaded('ftp') ? '<span class="success">&#x221A;</span>' : '<span class="error">&#x00D7;&nbsp;不支持FTP形式文件传送</span>' ?>
				</td>
			</tr>

			<tr>
				<td>allow_url_fopen</td>
				<td><?php echo ini_get('allow_url_fopen') ? '<span class="success">&#x221A;</span>' : '<span class="error">&#x00D7;</span>' ?></td>
				<td>建议打开</td>
				<td>
				<?php echo ini_get('allow_url_fopen') ? '<span class="success">&#x221A;</span>' : '<span class="error">&#x00D7;&nbsp;不支持保存远程图片</span>' ?>
				</td>
			</tr>
			<tr>
				<td>DNS解析</td>
				<td><?php echo $PHP_DNS ? '<span class="success">&#x221A;</span>' : '<span class="error">&#x00D7;</span>' ?></td>
				<td>建议设置正确</td>
				<td>
				<?php echo $PHP_DNS ? '<span class="success">&#x221A;</span>' : '<span class="error">&#x00D7;&nbsp;不支持采集和保存远程图片</span>' ?>
				</td>
			</tr>
		</tbody>
	</table>

	 <h1><?php echo t('目录和文件权限')?></h1>

	 <table class="list">
		<thead>
			<tr>
				<td class="col2"><?php echo t('名称')?></td>
				<td class="col1"><?php echo t('目录')?></td>
				<td class="col2"><?php echo t('所需状态')?></td>
				<td class="col2"><?php echo t('当前状态')?></td>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $f=>$l): ?>
			<tr>
				<td><?php echo $l['name'];?></td>
				<td><?php echo $l['position'];?></td>				
				<td><?php echo $l['writable'] ? t('必须可写(0777)') : t('建议只读');?></td>
				<td><?php if($l['is_writable']): ?><font class="success"><?php echo t('可写');?></font><?php else:?><font color="error"><?php echo t('不可写');?></font><?php endif;?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>	
</div>
</div>
<div class="buttons">
<a id="prev" class="button" href="index.php?action=start"><?php echo t('上一步')?></a>
<?php if ( $success ) : ?>
<a id="next" class="button" href="index.php?action=data"><?php echo t('下一步')?></a>
<?php else :?>
<a id="next" class="button disabled">未通过检测，无法继续安装</a>
<?php endif;?>
</div>
<?php include ZOTOP_PATH_INSTALL.DS.'template'.DS.'footer.php';?>
