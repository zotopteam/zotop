<?php include ZOTOP_PATH_INSTALL.DS.'template'.DS.'header.php';?>

<div class="main scrollable">
<div class="main-inner">
		
		<form id="form" method="post" action="javascirpt:;">
		
			<h1><?php echo t('网站设置');?></h1>
			<table class="form-field">
					<tr>
						<td class="label"><?php echo form::label(t('网站名称'),'site_name')?></td>
						<td class="input">
							<?php echo form::field(array(
								'type'=>'text',
								'name'=>'site_name',
								'value'=>$data['site_name'],
								'required'=>'required',
								'placeholder'=>t('请输入网站名称，如 “逐涛网”')
							))?>
						</td>
					</tr>
					<tr>
						<td class="label"><?php echo form::label(t('创始人账号'),'admin_username')?></td>
						<td class="input">
							<?php echo form::field(array(
								'type'=>'text',
								'name'=>'admin_username',
								'value'=>$data['admin_username'],
								'required'=>'required'
							))?>
						</td>
					</tr>					
					<tr>
						<td class="label"><?php echo form::label(t('创始人密码'),'admin_password')?></td>
						<td class="input">
							<?php echo form::field(array(
								'type'=>'text',
								'id'=>'admin_password',
								'name'=>'admin_password',
								'value'=>$data['admin_password'],
								'required'=>'required'
							))?>							
						</td>
					</tr>
					<tr>
						<td class="label"><?php echo form::label(t('创始人邮箱'),'admin_email')?></td>
						<td class="input">
							<?php echo form::field(array(
								'type'=>'text',
								'name'=>'admin_email',
								'value'=>$data['admin_email'],
								'required'=>'required'
							))?>
						</td>
					</tr>											
				</tbody>
			</table>	

			<h1><?php echo t('数据库设置');?></h1>

			<input type="hidden" name="charset" value="<?php echo $charset?>"/>
			<input type="hidden" name="driver" value="<?php echo $driver?>"/>
			<table class="form-field">
				<tbody>
					<tr>
						<td class="label"><?php echo form::label(t('类型'),'driver')?></td>
						<td class="input">
							<?php echo form::field(array(
								'type'=>'radio',
								'options'=>array(
									'mysql'=>t('Mysql : 速度更快，效率更高，适用于数据较多的网站'),
									'sqlite'=>t('Sqlite : 简单高效，适用于数据较少的企业网站')
								),
								'id'=>'driver',
								'name'=>'driver',
								'value'=>$data['driver'],
								'required'=>'required',
								'column'=>1
							))?>
						</td>
					</tr>
			</table>
			<table class="form-field mysql-ui">
					<tr>
						<td class="label"><?php echo form::label(t('主机'),'mysql_hostname')?></td>
						<td class="input">
							<?php echo form::field(array(
								'type'=>'text',
								'id'=>'mysql_hostname',
								'name'=>'mysql_hostname',
								'value'=>$data['mysql_hostname'],
								'required'=>'required',
							))?>
							<?php echo form::tips(t('如果数据库和程序不在同一服务器，请填写数据库服务器IP地址'))?>
						</td>
					</tr>
					<tr>
						<td class="label"><?php echo form::label(t('端口'),'mysql_hostport')?></td>
						<td class="input">
							<?php echo form::field(array(
								'type'=>'number',
								'id'=>'mysql_hostport',
								'name'=>'mysql_hostport',
								'value'=>$data['mysql_hostport'],
								'required'=>'required',
								'title'=>t('请填写数据库端口，默认情况下一般为3306')
							))?>
						</td>
					</tr>
					<tr>
						<td class="label"><?php echo form::label(t('用户名'),'mysql_username')?></td>
						<td class="input">
							<?php echo form::field(array(
								'type'=>'text',
								'id'=>'mysql_username',
								'name'=>'mysql_username',
								'value'=>$data['mysql_username'],
								'required'=>'required',
								'title'=>t('请填写数据库用户名，默认情况下用户名为root')
							))?>
						</td>
					</tr>
					<tr>
						<td class="label"><?php echo form::label(t('密码'),'mysql_password')?></td>
						<td class="input">
							<?php echo form::field(array(
								'type'=>'text',
								'id'=>'mysql_password',
								'name'=>'mysql_password',
								'value'=>$data['mysql_password'],
							))?>
						</td>
					</tr>
					<tr>
						<td class="label"><?php echo form::label(t('数据库名称'),'mysql_database')?></td>
						<td class="input">
							<?php echo form::field(array(
								'type'=>'text',
								'id'=>'mysql_database',
								'name'=>'mysql_database',
								'value'=>$data['mysql_database'],
								'required'=>'required',
							))?>
						</td>
					</tr>
			</table>
			<table class="form-field sqlite-ui" id="sqlite" style="display:none;">
					<tr>
						<td class="label"><?php echo form::label(t('数据库名称'),'sqlite_database')?></td>
						<td class="input">
							<?php echo form::field(array(
								'type'=>'text',
								'id'=>'sqlite_database',
								'name'=>'sqlite_database',
								'value'=>$data['sqlite_database'],
								'required'=>'required',
							))?>
							<?php echo form::tips(t('请更改数据库名称，默认情况下数据库文件保存在/data目录下'))?>
						</td>
					</tr>
			</table>
			<table class="form-field">
					<tr>
						<td class="label"><?php echo form::label(t('数据表前缀'),'prefix')?></td>
						<td class="input">
							<?php echo form::field(array(
								'type'=>'text',
								'id'=>'prefix',
								'name'=>'prefix',
								'value'=>$data['prefix'],
								'required'=>'required',
							))?>
							<?php echo form::tips(t('如果一个数据库安装多个zotop，请更改前缀'))?>
						</td>
					</tr>
					<tr>
						<td class="label"><?php echo form::label(t('持久连接'),'mysql_pconnect')?></td>
						<td class="input">
							<?php echo form::field(array(
								'type'=>'bool',
								'id'=>'pconnect',
								'name'=>'pconnect',
								'value'=>$data['pconnect']
							))?>
							<?php echo form::tips(t('持久连接，则数据库连接上后不释放，保存一直连接状态，多占用资源，但提升效率'))?>
						</td>
					</tr>
				</tbody>
			</table>

		


    	</form>
</div>
</div>
<div class="buttons">
	<a id="prev" class="button" href="index.php?action=check"><?php echo t('上一步')?></a>
	<a id="next" class="button" href="javascript:void(0);" onclick="submit();"><?php echo t('下一步')?></a>
</div>
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
