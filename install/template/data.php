<?php include ZOTOP_PATH_INSTALL.DS.'template'.DS.'header.php';?>

<div class="global-body scrollable">

    <div class="jumbotron text-center">
        <div class="container-fluid">
            <h1><?php echo t('搭建网站数据存储平台')?></h1>
            <p><?php echo t('请填写网站管理员信息和数据库连接参数，创建网站和数据库设置')?></p>
            <p></p>
        </div>
    </div>

    <div class="container-fluid">
    
    <form id="form" class="form-horizontal" method="post" action="index.php?action=dbcreate">

        <div class="page-header">
            <h1><?php echo t('网站设置');?></h1>
        </div>
        
        <div class="page-body">

            <div class="form-group">
                <div class="col-sm-2 control-label">
                    <?php echo form::label(t('网站名称'), 'site_name', true)?>
                </div>
                <div class="col-sm-8">
                    <?php echo form::field(array(
                        'type'        => 'text',
                        'name'        => 'site_name',
                        'value'       => $data['site_name'],
                        'required'    => 'required',
                        'placeholder' => t('请输入网站名称，如 “逐涛网”')
                    ))?>            
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 control-label">
                    <?php echo form::label(t('创始人账号'),'admin_username', true)?>
                </div>
                <div class="col-sm-8">
                    <?php echo form::field(array(
                        'type'     => 'text',
                        'name'     => 'admin_username',
                        'value'    => $data['admin_username'],
                        'required' => 'required'
                    ))?>            
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 control-label">
                    <?php echo form::label(t('创始人密码'),'admin_password', true)?>
                </div>
                <div class="col-sm-8">
                    <?php echo form::field(array(
                        'type'     => 'text',
                        'id'       => 'admin_password',
                        'name'     => 'admin_password',
                        'value'    => $data['admin_password'],
                        'required' => 'required'
                    ))?>                
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 control-label">
                    <?php echo form::label(t('创始人邮箱'),'admin_email', true)?>
                </div>
                <div class="col-sm-8">
                    <?php echo form::field(array(
                        'type'     => 'email',
                        'name'     => 'admin_email',
                        'value'    => $data['admin_email'],
                        'required' => 'required'
                    ))?>            
                </div>
            </div>

        </div> <!-- page-body -->


        <div class="page-header">
            <h1><?php echo t('数据库设置');?></h1>
        </div>      
        <div class="page-body">
            
            <input type="hidden" name="charset" value="<?php echo $charset?>"/>
            <input type="hidden" name="driver" value="<?php echo $driver?>"/>

            <div class="form-group hidden">
                    
                        <div class="col-sm-2 control-label"><?php echo form::label(t('类型'),'driver')?></div>
                        <div class="col-sm-8">
                            <?php echo form::field(array(
                                'type'     => 'radio',
                                'options'  => array(
                                    'mysql'  => t('Mysql : 速度更快，效率更高，适用于数据较多的网站'),
                                    'sqlite' => t('Sqlite : 简单高效，适用于数据较少的企业网站')
                                ),
                                'id'       => 'driver',
                                'name'     => 'driver',
                                'value'    => $data['driver'],
                                'required' => 'required',
                                'column'   => 1
                            ))?>
                        </div>
            </div>

            <div class="form-group mysql-ui">                   
                        <div class="col-sm-2 control-label"><?php echo form::label(t('Mysql主机'),'mysql_hostname', true)?></div>
                        <div class="col-sm-6">
                            <?php echo form::field(array(
                                'type'     => 'text',
                                'id'       => 'mysql_hostname',
                                'name'     => 'mysql_hostname',
                                'value'    => $data['mysql_hostname'],
                                'required' => 'required',
                            ))?>
                            <?php echo form::tips(t('如果数据库和程序不在同一服务器，请填写数据库服务器IP地址'))?>
                        </div>
                        

            </div>

            <div class="form-group mysql-ui">
                        <div class="col-sm-2 control-label"><?php echo form::label(t('Mysql端口'),'mysql_hostport', true)?></div>
                        <div class="col-sm-2">
                            <?php echo form::field(array(
                                'type'     => 'number',
                                'id'       => 'mysql_hostport',
                                'name'     => 'mysql_hostport',
                                'value'    => $data['mysql_hostport'],
                                'required' => 'required',
                                'title'    => t('请填写数据库端口，默认情况下一般为3306')
                            ))?>
                        </div>
                    
            </div>
            
            <div class="form-group mysql-ui">       
                        <div class="col-sm-2 control-label"><?php echo form::label(t('Mysql用户名'),'mysql_username', true)?></div>
                        <div class="col-sm-8">
                            <?php echo form::field(array(
                                'type'     => 'text',
                                'id'       => 'mysql_username',
                                'name'     => 'mysql_username',
                                'value'    => $data['mysql_username'],
                                'required' => 'required',
                                'title'    => t('请填写数据库用户名，默认情况下用户名为root')
                            ))?>
                        </div>
                    
            </div>
            
            <div class="form-group mysql-ui">       
                        <div class="col-sm-2 control-label"><?php echo form::label(t('Mysql密码'),'mysql_password')?></div>
                        <div class="col-sm-8">
                            <?php echo form::field(array(
                                'type'  => 'text',
                                'id'    => 'mysql_password',
                                'name'  => 'mysql_password',
                                'value' => $data['mysql_password'],
                            ))?>
                        </div>
                    
            </div>
            
            <div class="form-group mysql-ui">       
                        <div class="col-sm-2 control-label"><?php echo form::label(t('数据库名称'),'mysql_database', true)?></div>
                        <div class="col-sm-8">
                            <?php echo form::field(array(
                                'type'     => 'text',
                                'id'       => 'mysql_database',
                                'name'     => 'mysql_database',
                                'value'    => $data['mysql_database'],
                                'required' => 'required',
                            ))?>
                        </div>
                    
            </div>

            <div class="form-group sqlite-ui" id="sqlite" style="display:none;">
                    
                        <div class="col-sm-2 control-label"><?php echo form::label(t('数据库名称'),'sqlite_database', true)?></div>
                        <div class="col-sm-8">
                            <?php echo form::field(array(
                                'type'     => 'text',
                                'id'       => 'sqlite_database',
                                'name'     => 'sqlite_database',
                                'value'    => $data['sqlite_database'],
                                'required' => 'required',
                            ))?>
                            <?php echo form::tips(t('请更改数据库名称，默认情况下数据库文件保存在/data目录下'))?>
                        </div>
                    
            </div>

            <div class="form-group">
                    
                        <div class="col-sm-2 control-label"><?php echo form::label(t('数据表前缀'),'prefix', true)?></div>
                        <div class="col-sm-8">
                            <?php echo form::field(array(
                                'type'     => 'text',
                                'id'       => 'prefix',
                                'name'     => 'prefix',
                                'value'    => $data['prefix'],
                                'required' => 'required',
                            ))?>
                            <?php echo form::tips(t('如果一个数据库安装多个zotop，请更改前缀'))?>
                        </div>
            </div>      
                
            <div class="form-group">
        
                        <div class="col-sm-2 control-label"><?php echo form::label(t('持久连接'),'mysql_pconnect')?></div>
                        <div class="col-sm-8">
                            <?php echo form::field(array(
                                'type'  => 'bool',
                                'id'    => 'pconnect',
                                'name'  => 'pconnect',
                                'value' => $data['pconnect']
                            ))?>
                            <?php echo form::tips(t('持久连接，则数据库连接上后不释放，保存一直连接状态，多占用资源，但提升效率'))?>
                        </div>
                    
            </div>
        
        </div> <!-- page-body -->
    </form>
    </div>

</div>

<footer class="global-footer navbar-fixed-bottom clearfix" role="navigation">
    <a id="prev" class="btn btn-default" href="index.php?action=check"><i class="fa fa-angle-left"></i> <?php echo t('上一步')?></a>
    <a id="next" class="btn btn-success pull-right" href="javascript:void(0);" onclick="submit();"><?php echo t('下一步')?> <i class="fa fa-angle-right"></i></a>
</footer>

<script type="text/javascript">
show_driver();

$(function(){
    $('input[name=driver]').change(function(){
        show_driver($(this).val())
    });
})

function show_driver(driver){

    driver = driver || $('input[name=driver]:checked').val();

    if ( driver == 'mysql' ){
        $('.sqlite-ui').hide();
        $('.mysql-ui').show();
    }else{
        $('.sqlite-ui').show();
        $('.mysql-ui').hide();
    }
}

function submit(){
    $('#form').submit();
}

//mysql submit
$(function(){
    $('#form').validate({
        submitHandler: function(form) {
            
            var data = $(form).serialize();

            $.post("index.php?action=dbcreate", data, function(result){
                if( result.code > 1){
                    alert(result.message);
                    return false;
                }else if( result.code==1 || ( result.code==0 && confirm(result.message) ) ){
                    location.href = 'index.php?action=app';
                }
            },'json');

            return false;
        }
    });
})
</script>
<?php include ZOTOP_PATH_INSTALL.DS.'template'.DS.'footer.php';?>
