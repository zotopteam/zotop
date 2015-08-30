<?php include ZOTOP_PATH_INSTALL.DS.'template'.DS.'header.php';?>
    
<?php if ( $success ) : ?>
<section class="global-body">
    <div class="masthead text-center">
        <div class="masthead-body">
            <div class="container-fluid">
                <h1><i class="fa fa-check-circle"></i> <?php echo t('检测通过')?></h1>
                <h2><?php echo t('恭喜！您的服务器环境可以安装 ZOTOP')?></h2>
                <p></p>
            </div>
        </div>
    </div>
</section>
<?php else :?>
<section class="global-body scrollable">

    <div class="jumbotron text-center">
        <div class="container-fluid">
            <h1><i class="fa fa-times-circle"></i> <?php echo t('未通过检测')?></h1>
            <p><?php echo t('您的服务器环境无法正常运行ZOTOP，请检查您的服务器配置')?></p>
            <p></p>
        </div>
    </div>

    <div class="container-fluid">
         
        <div class="page-header">
            <h1><?php echo t('服务器环境')?></h1>
        </div>

        <div class="page-body">

             <table class="table table-striped list">
                <thead>
                    <tr>
                        <th width="40%"><?php echo t('检测项目')?></th>
                        <th width="30%"><?php echo t('最低需求')?></th>
                        <th width="30%"><?php echo t('检测结果')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($check_env as $key => $value): ?>
                    <tr>
                        <td><?php echo $value['item']?></td>
                        <td><?php echo $value['need']?></td>
                        <td>
                        <?php if ( $value['checked'] ) : ?>
                            <span class="text-success"><i class="fa fa-check-circle"></i> <?php echo $value['message']?></span> 
                        <?php else:?>
                            <span class="text-error"><i class="fa fa-times-circle text-error"></i> <?php echo $value['message']?></span>
                        <?php endif;?>
                        </td>
                    </tr>                    
                    <?php endforeach;?>
                </tbody>
            </table>

        </div>

        <div class="page-header">
            <h1><?php echo t('目录和文件权限')?></h1>
        </div>
        
        <div class="page-body">

            <table class="table table-striped list">
                <thead>
                    <tr>
                        <th width="40%"><?php echo t('检测项目')?></th>
                        <th width="30%"><?php echo t('最低需求')?></th>
                        <th width="30%"><?php echo t('检测结果')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($check_dir as $f=>$l): ?>
                    <tr>
                        <td><?php echo $l['name'];?>[ <?php echo $l['position'];?> ]</td>               
                        <td><?php echo $l['writable'] ? t('必须可写(0777)') : t('建议只读');?></td>
                        <td>
                            <?php if($l['is_writable']): ?>
                            <span class="text-success"><i class="fa fa-check-circle"></i> <?php echo t('可写');?></span>
                            <?php else:?>
                            <span class="text-error"><i class="fa fa-times-circle"></i> <?php echo t('不可写');?></span>
                            <?php endif;?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</section>
<?php endif;?>

<footer class="global-footer navbar-fixed-bottom clearfix" role="navigation">
    <a id="prev" class="btn btn-default" href="index.php?action=start"><i class="fa fa-angle-left"> <?php echo t('上一步')?></i></a>

    <?php if ( $success ) : ?>
    <a id="next" class="btn btn-success pull-right" href="index.php?action=data"><?php echo t('下一步')?> <i class="fa fa-angle-right"></i></a>
    <?php else :?>
    <a id="next" class="btn btn-success pull-right disabled" disabled><?php echo t('下一步')?> <i class="fa fa-angle-right"></i></a>
    <?php endif;?>      
</footer>

<?php include ZOTOP_PATH_INSTALL.DS.'template'.DS.'footer.php';?>
