<?php include ZOTOP_PATH_INSTALL.DS.'template'.DS.'header.php';?>
<div class="global-body scrollable">
    
    <?php if ( $success ) : ?>
    <div class="masthead text-center">
        <div class="masthead-body">
            <div class="container-fluid">
                <h1><i class="fa fa-check-circle"></i> <?php echo t('检测通过')?></h1>
                <h2><?php echo t('您的服务器环境可以顺利安装ZOTOP')?></h2>
                <p></p>
            </div>
        </div>
    </div>
    <?php else :?>

    <div class="container-fluid">
         
        <div class="page-header">
            <h1><?php echo t('服务器环境')?></h1>
        </div>

         <table class="table table-bordered list">
            <thead>
                <tr>
                    <th class="col1"><?php echo t('检测项目')?></th>
                    <th class="col2"><?php echo t('当前配置')?></th>
                    <th class="col3"><?php echo t('推荐配置')?></th>
                    <th class="col4"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>WEB 服务器</td>
                    <td><?php echo trim(preg_replace(array('#PHP\/[\d\.]+#', '#\([\w]+\)#'), '', $_SERVER['SERVER_SOFTWARE']));?> - <?php echo PHP_OS;?></td>
                    <td>Apache/2.2.x - Linux/Freebsd</td>
                    <td><span><span class="text-success">&#x221A;</span></span></td>
                </tr>
                <tr>
                    <td>PHP 版本</td>
                    <td>PHP <?php echo phpversion();?></td>
                    <td>PHP 5.2.0 及以上</td>
                    <td>
                        <?php if(phpversion() >= '5.2.0'){ ?>
                            <span><span class="text-success">&#x221A;</span></span>
                        <?php }else{ ?>
                        <font class="red"><span class="text-error">&#x00D7;</span>&nbsp;无法安装</font><?php }?>
                        </font>
                    </td>
                </tr>
                <tr>
                    <td>PDO 扩展</td>
                    <td><?php echo  extension_loaded('PDO') ? '<span class="text-success">&#x221A;</span>' : '<span class="text-error">&#x00D7;</span>' ?></td>
                    <td>必须开启</td>
                    <td><?php echo  extension_loaded('PDO') ? '<span class="text-success">&#x221A;</span>' : '<span class="text-error">&#x00D7;</span>' ?></td>
                </tr>
                <tr>
                    <td>PDO_SQLITE 扩展</td>
                    <td><?php echo  extension_loaded('PDO_SQLITE') ? '<span class="text-success">&#x221A;</span>' : '<span class="text-error">&#x00D7;</span>' ?></td>
                    <td>必须开启</td>
                    <td><?php echo  extension_loaded('PDO_SQLITE') ? '<span class="text-success">&#x221A;</span>' : '<span class="text-error">&#x00D7;</span>' ?></td>
                </tr>
                <tr>
                    <td>MYSQL 扩展</td>
                    <td><?php echo  extension_loaded('mysql') ? '<span class="text-success">&#x221A;</span>' : '<span class="text-error">&#x00D7;</span>' ?></td>
                    <td>必须开启</td>
                    <td><?php echo  extension_loaded('mysql') ? '<span class="text-success">&#x221A;</span>' : '<span class="text-error">&#x00D7;</span>' ?></td>
                </tr>

                <tr>
                <td>ICONV/MB_STRING 扩展</td>
                <td>
                    <?php echo (extension_loaded('iconv') || extension_loaded('mbstring')) ? '<span class="text-success">&#x221A;</span>' : '<span class="text-error">&#x00D7;</span>' ?>
                </td>
                <td>必须开启</td>
                <td>
                    <?php echo (extension_loaded('iconv') || extension_loaded('mbstring')) ? '<span class="text-success">&#x221A;</span>' : '<span class="text-error">&#x00D7; 字符集转换效率低</span>' ?>
                </td>
                </tr>

                <tr>
                    <td>JSON扩展</td>
                    <td><?php echo $PHP_JSON ? '<span class="text-success">&#x221A;</span>' : '<span class="text-error">&#x00D7;</span>' ?></td>
                    <td>必须开启</td>
                    <td>
                        <?php echo $PHP_JSON ? '<span class="text-success">&#x221A;</span>' : '<span class="text-error">&#x00D7;&nbsp;不只持json,<a href="http://pecl.php.net/package/json" target="_blank">安装 PECL扩展</a></span>' ?>
                    </td>
                </tr>
                <tr>
                    <td>GD 扩展</td>
                    <td><?php echo $PHP_GD ? '<span class="text-success">&#x221A;</span> ( '.$PHP_GD.' )' : '<span class="text-error">&#x00D7;</span>' ?></td>
                    <td>建议开启</td>
                    <td>
                    <?php echo $PHP_GD ? '<span class="text-success">&#x221A;</span>' : '<span class="text-error">&#x00D7;&nbsp;不支持缩略图和水印</span>' ?>
                    </td>
                </tr>
                <tr>
                    <td>ZLIB 扩展</td>
                    <td><?php echo extension_loaded('zlib') ? '<span class="text-success">&#x221A;</span>' : '<span class="text-error">&#x00D7;</span>' ?></td>
                    <td>建议开启</td>
                    <td>
                    <?php echo extension_loaded('zlib') ? '<span class="text-success">&#x221A;</span>' : '<span class="text-error">&#x00D7;&nbsp;不支持Gzip功能</span>' ?>
                    </td>
                </tr>
                <tr>
                    <td>FTP 扩展</td>
                    <td><?php echo extension_loaded('ftp') ? '<span class="text-success">&#x221A;</span>' : '<span class="text-error">&#x00D7;</span>' ?></td>
                    <td>建议开启</td>
                    <td>
                    <?php echo extension_loaded('ftp') ? '<span class="text-success">&#x221A;</span>' : '<span class="text-error">&#x00D7;&nbsp;不支持FTP形式文件传送</span>' ?>
                    </td>
                </tr>

                <tr>
                    <td>allow_url_fopen</td>
                    <td><?php echo ini_get('allow_url_fopen') ? '<span class="text-success">&#x221A;</span>' : '<span class="text-error">&#x00D7;</span>' ?></td>
                    <td>建议打开</td>
                    <td>
                    <?php echo ini_get('allow_url_fopen') ? '<span class="text-success">&#x221A;</span>' : '<span class="text-error">&#x00D7;&nbsp;不支持保存远程图片</span>' ?>
                    </td>
                </tr>
                <tr>
                    <td>DNS解析</td>
                    <td><?php echo $PHP_DNS ? '<span class="text-success">&#x221A;</span>' : '<span class="text-error">&#x00D7;</span>' ?></td>
                    <td>建议设置正确</td>
                    <td>
                    <?php echo $PHP_DNS ? '<span class="text-success">&#x221A;</span>' : '<span class="text-error">&#x00D7;&nbsp;不支持采集和保存远程图片</span>' ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="page-header">
            <h1><?php echo t('目录和文件权限')?></h1>
        </div>
        
        <table class="table table-bordered list">
        <thead>
            <tr>
                <th class="col2"><?php echo t('名称')?></th>
                <th class="col1"><?php echo t('目录')?></th>
                <th class="col2"><?php echo t('所需状态')?></th>
                <th class="col2"><?php echo t('当前状态')?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($list as $f=>$l): ?>
            <tr>
                <td><?php echo $l['name'];?></td>
                <td><?php echo $l['position'];?></td>               
                <td><?php echo $l['writable'] ? t('必须可写(0777)') : t('建议只读');?></td>
                <td><?php if($l['is_writable']): ?><font class="text-success"><?php echo t('可写');?></font><?php else:?><font color="text-error"><?php echo t('不可写');?></font><?php endif;?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
    </div>
    <?php endif;?>
</div>

<footer class="global-footer navbar-fixed-bottom clearfix" role="navigation">
    <a id="prev" class="btn btn-default" href="index.php?action=start"><i class="fa fa-angle-left"> <?php echo t('上一步')?></i></a>

    <?php if ( $success ) : ?>
    <a id="next" class="btn btn-success pull-right" href="index.php?action=data"><?php echo t('下一步')?> <i class="fa fa-angle-right"></i></a>
    <?php else :?>
    <a id="next" class="btn btn-success pull-right disabled"><?php echo t('未通过检测，无法继续安装')?></a>
    <?php endif;?>      
</footer>

<?php include ZOTOP_PATH_INSTALL.DS.'template'.DS.'footer.php';?>
