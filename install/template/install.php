<?php include ZOTOP_PATH_INSTALL.DS.'template'.DS.'header.php';?>

<input type="hidden" name="app" id="app" value="<?php echo implode(',',$app)?>"/>

<div class="global-body">

	<div class="masthead text-center">
	    <div class="masthead-body">
	       	<div class="container">
	            <h1><?php echo t('安装进行中');?></h1>
	            <div class="progress">
				  <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
				    0%
				  </div>
				</div>
				<h2 class="progress-text"><?php echo t('正在准备安装……')?></h2>
	        </div>
	    </div>
	</div>

	<div class="container-fluid hidden">
		<div class="page-header">
			<h1><?php echo t('详细进程');?></h1>
		</div>
		<div class="page-body">
			<ul class="installing">
				<li><?php echo t('正在准备安装……');?></li>
			</ul>
		</div>
	</div>


<footer class="global-footer navbar-fixed-bottom clearfix" role="navigation">
	<a class="prev btn btn-default" href="index.php?action=check"><?php echo t('上一步')?></a>
	<a class="next btn btn-success pull-right disabled" href="javascript:void(0);"><?php echo t('安装中……')?></div></a>
</footer>


<script type="text/javascript">

var n = 0;

function install(){
	var app = $('#app').val();
	var apps = app.split(',');
	var data = {app : apps[n], listorder:n};

	$.post("index.php?action=installing&rand="+Math.random()*5, data, function(result){
		console.log(result);
		if( result.code == 1 ){
			$('.progress-text').html('<div class="text-error">'+result.message+'<h3>'+result.detail+'</h3></div>');
			$('.prev').show();
		}else if( result.code == 2 ){
			$('.progress-text').html('<div class="text-error">'+result.message+'<h3>'+result.detail+'</h3></div>');
			$('.prev').show();
		}else{
			var progress = Math.ceil( (n+1) * 100 / apps.length);
			
			$('.progress-bar').css('width', progress + '%').html(progress + '%');
			$('.progress-text').html(result.message);
			$('.prev').hide();

			n++;//安装下一个

			if( n < apps.length ){
				install();
			}else{
				
				$('.progress-text').html('<?php echo t('正在完成安装……')?>');

				//重新启动系统
				setTimeout(function(){
					location.href = 'index.php?action=success';
				},1000);
			}
		}
	},'json');
}

$(function(){
	install();
});

</script>
<?php include ZOTOP_PATH_INSTALL.DS.'template'.DS.'footer.php';?>
