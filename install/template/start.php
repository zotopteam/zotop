<?php include ZOTOP_PATH_INSTALL.DS.'template'.DS.'header.php';?>

<?php if ( $installed ) : ?>

<section class="global-body">
    
    <div class="masthead text-center">
        <div class="container-fluid">
            <div class="masthead-body">
                <h1><i class="fa fa-warning"></i> <?php echo t('已经安装过了')?></h1>
                <h2><?php echo t('如果要重新安装请删除安装锁文件：{1}', debug::path(ZOTOP_PATH_DATA.DS.'install.lock'))?></h2>
                <p></p>
            </div>
        </div>
    </div>

</section>

<footer class="global-footer navbar-fixed-bottom clearfix" role="navigation">  
        <a class="next btn btn-success pull-right disabled" href="javascript:void(0);"><?php echo t('下一步')?> <i class="fa fa-angle-right"></i></a>
    </nav>
</footer>


<?php else :?>

<section class="global-body">
     
      <div class="masthead text-center">
        <div class="container-fluid">
            <div class="masthead-body">
                <h1><?php echo t('欢迎您使用逐涛')?></h1>
                <h2><?php echo t('免费、开源、快速、简洁的网站管理系统')?></h2>
                <p></p>
            </div>
        </div>
    </div>  

</section>

<footer class="global-footer navbar-fixed-bottom clearfix" role="navigation">  
        
        <div class="text pull-left">        	
        	<label for="agree">
                <input type="checkbox" name="agree" id="agree" checked="checked" value="1">
                <?php echo t('阅读并同意');?>
            </label>                       
        	<a href="http://www.zotop.com/license.html" target="_blank" class="va-m"><?php echo t('许可协议');?></a>
		</div>

        <a class="next btn btn-success pull-right" href="javascript:void(0);" onclick="submit_start();"><?php echo t('下一步')?> <i class="fa fa-angle-right"></i></a>
    </nav>
</footer>


<script type="text/javascript">
function submit_start(){
    if ( $('#agree').is(':checked') ){
        location.href='index.php?action=check';
    }else{
        alert("<?php echo t('如果您不同意协议内容无法进行安装');?>");
    }
    return false;
}
</script>

<?php endif;?>

<?php include ZOTOP_PATH_INSTALL.DS.'template'.DS.'footer.php';?>