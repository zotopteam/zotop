<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <title>Document</title>
  <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
 </head>
 <body>
	
	<div style="text-align:center;">
		
		<div><img src="<?php echo $qrcodeimage?>" alt="微信"></div>
 		<div><b>扫描二维码登录 <?php echo $uuid;?> <?php echo strlen('qrscene_');?> </b></div>	

 	</div>

 	<script>
	var ajaxlock = false;
	var ajaxhandle;
	function synclogin(){

		if (!ajaxlock) {
			ajaxlock = true;
			$.post(location.href,{uuid:'<?php echo $uuid;?>'},function(json){
				console.log(json);
				
				if (json.status && json.status==1 ) {

					// 跳转url
					alert('login success, welcome '+json.user.nickname);
					//location.href = "http://www.163.com";
					clearInterval(ajaxhandle);
				}

				ajaxlock = false;
			},'json');
		}

	}
	$(function(){
		ajaxhandle = setInterval("synclogin()",1000);
	}); 		
 	</script>
  
 </body>
</html>
