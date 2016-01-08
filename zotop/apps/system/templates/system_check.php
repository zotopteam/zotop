{template 'header.php'}

{template 'system/system_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
		</div>
	</div><!-- main-header -->
	
	<div class="main-body scrollable">

		<div class="container-fluid container-primary">
			<div class="jumbotron text-center">
				<img src="{A('system.url')}/icons/zotop.png" class="hidden">
				<h1>{$title}</h1>
				<p>{$description}</p>
			</div>
		</div>

		<div class="container-fluid container-default">

			<div class="panel">
				<div class="panel-heading">
					<h2>{t('服务器环境')}</h2>
					<small>{t('当前服务器环境信息')}</small>
				</div>
				<div class="panel-body">		
					<div class="row">
						<div class="col-sm-6">	
							<table class="table">
								<tbody>
									<tr>
										<td width="30%">{t('操作系统')}</td>
										<td>{PHP_OS}</td>
									</tr>
									<tr>
										<td width="30%">{t('WEB服务器')}</td>
										<td>{trim(preg_replace(array('#PHP\/[\d\.]+#', '#\([\w]+\)#'), '', $_SERVER['SERVER_SOFTWARE']))}</td>
									</tr>
									<tr>
										<td width="30%">{t('PHP版本')}</td>
										<td>{phpversion()}</td>
									</tr>
									<tr>				
										<td width="30%">{t('服务器时间')}</td>
										<td>{date("Y-m-d G:i T",time())}</td>
									</tr>
									<tr>
										<td width="30%">{t('服务器IP')}</td>
										<td>{$_SERVER['SERVER_ADDR']}</td>
									</tr>
									<tr>				
										<td width="30%">{t('物理路径')}</td>
										<td>{ZOTOP_PATH}</td>
									</tr>
									<tr>				
										<td width="30%">{t('安全模式')}</td>
										<td>{if @ini_get('safe_mode')}<font class="true">{t('开启')}</font>{else}<font class="false">{t('关闭')}</font>{/if}</td>
									</tr>
									<tr>				
										<td width="30%">{t('socket')}</td>
										<td>{if function_exists('fsockopen')}<font class="true">{t('支持')}</font>{else}<font class="false">{t('不支持')}</font>{/if}</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-sm-6">
							<table class="table">
								<tbody>
									<tr>
										<td width="30%">{t('语言')}</td>
										<td>{$_SERVER[HTTP_ACCEPT_LANGUAGE]}</td>
									</tr>
									<tr>				
										<td width="30%">{t('gzip压缩')}</td>
										<td>{$_SERVER[HTTP_ACCEPT_ENCODING]}</td>
									</tr>
									<tr>
										<td width="30%">{t('URL重写')}</td>
										<td>{if $rewrite}<font class="true">{t('支持')}</font>{else}<font class="false">{t('不支持')}</font>{/if}</td>
									</tr>
									<tr>				
										<td width="30%">{t('上传限制')}</td><td>{ini_get('upload_max_filesize')}</td>

									</tr>
									<tr>
										<td width="30%">{t('POST提交限制')}</td>
										<td>{get_cfg_var("post_max_size")}</td>
									</tr>
									<tr>				
										<td width="30%">{t('脚本运行内存')}</td>
										<td>{if get_cfg_var("memory_limit")}{get_cfg_var("memory_limit")}{else}{t('无')}{/if}</td>
									</tr>
									<tr>
										<td width="30%">{t('脚本超时时间')}</td>
										<td>{get_cfg_var("max_execution_time")}</td>
									</tr>
									<tr>				
										<td width="30%">{t('被屏蔽的函数')}</td>
										<td>{if get_cfg_var("disable_functions")}{get_cfg_var("disable_functions")}{else}{t('无')}{/if}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="panel-footer text-center p0">
					<a href="{U('system/system/phpinfo')}" class="btn btn-block">{t('查看更多信息')}</a>
				</div>
			</div>
			
			<div class="blank"></div>

			<div class="panel">
				<div class="panel-heading">
					<h2>{t('文件和目录权限')}</h2>
					<small>{t('777属性通过：文件属性正常，没有设置777属性，需要管理员使用FTP工具手动设置777属性，否则程序可能无法正常运行')}</small>
				</div>
				<div class="panel-body">
				 <table class="table list" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<th><?php echo t('名称')?></th>
							<th class="col1"><?php echo t('目录')?></th>
							<th class="hidden-xs"><?php echo t('所需状态')?></th>
							<th class="hidden-xs"><?php echo t('当前状态')?></th>
						</tr>
					</thead>
					<tbody>
						{loop $check_io $f $l}
						<tr>
							<td>{$l['name']}</td>
							<td class="hidden-xs">{$l['position']}</td>
							<td class="hidden-xs">{if $l['writable']} {t('必须可写(0777)')} {else} {t('建议只读')} {/if}</td>
							<td>{if $l['is_writable']}<font class="true">{t('可写')}</font>{else}<font color="false">{t('不可写')}</font>{/if}</td>
						</tr>
						{/loop}
					</tbody>
				</table>			
				</div>
			</div>
		</div>
	</div><!-- main-body -->
	<div class="main-footer">
		<div class="copyright clearfix">
			<div class="copyright-thanks">{t('感谢您使用逐涛网站管理系统1')}</div>
			<div class="copyright-powered">{zotop::powered()}</div>
		</div>
	</div><!-- main-footer -->
</div><!-- main -->

{template 'footer.php'}