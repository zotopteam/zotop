<?php include ZOTOP_PATH_INSTALL.DS.'template'.DS.'header.php';?>

<input type="hidden" name="app" id="app" value="<?php echo implode(',',$app)?>"/>


<div class="main main-installing scrollable">
<div class="main-inner">	
	<ul id="installing">
		<li><?php echo t('正在准备安装……');?></li>
	</ul>
</div>
</div>

<div id="progressbar"><div id="progress"></div>

<div class="buttons">
	<a id="prev" class="button" href="index.php?action=check"><?php echo t('上一步')?></a>
	<a id="next" class="button disabled" href="javascript:void(0);"><?php echo t('安装中……')?></div></a>
</div>


<script type="text/javascript">

var n = 0;
var scrollable;

setTimeout(function (){
	scrollable = $('.scrollable').data('jsp');
}, 500);

function install(){
	var app = $('#app').val();
	var apps = app.split(',');
	var data = {app : apps[n], listorder:n};


	$.post("index.php?action=installing&rand="+Math.random()*5, data, function(result){

		if( result.code == 1 ){
			$('#installing').append('<li class="error"><span>'+ result.message +'</span></li>'); //指定的数据库不存在，系统也无法创建，请先通过其他方式建立好数据库！
			$('#prev').show();
		}else if( result.code == 2 ){
			$('#installing').append('<li class="error"><span>'+ result.message +'</span></li>');
			$('#prev').show();
		}else{
			$('#installing').append('<li class="success"><span>'+ result.message +'</span></li>');
			$('#progress').css('width',Math.ceil( (n+1) * 100 / apps.length) + '%');
			$('#prev').hide();

			scrollable.scrollToY(10000);

			n++;//安装下一个

			if( n < apps.length ){
				install();
			}else{
				$('#installing').append('<li><span class="success"><?php echo t('正在完成安装……')?></span></li>');

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
