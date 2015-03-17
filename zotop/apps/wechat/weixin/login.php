<?php
require dirname(__file__) . DIRECTORY_SEPARATOR . 'wechat.php';

// 配置
$options = array(
	'appid'          =>'wx42bcfbbc186fe17e',
	'appsecret'      =>'3e35d85e27ed996450903184b552e4b0', //填写高级调用功能的密钥
	'token'          =>'zotop', //填写你设定的key
	'encodingaeskey' =>'encodingaeskey' //填写加密用的EncodingAESKey，如接口为明文模式可忽略
);



if ( isset($_POST['uuid']) )
{
	if ( file_exists(dirname(__file__) . DIRECTORY_SEPARATOR . $_POST['uuid'].'.php') )
	{
		$userinfo = include(dirname(__file__) . DIRECTORY_SEPARATOR . $_POST['uuid'].'.php');

		$json = array('status'=>1,'user'=>$userinfo);

		unlink(dirname(__file__) . DIRECTORY_SEPARATOR .$_POST['uuid'].'.php');

		//写入用户登录信息
		//……
	}
	else
	{
		$json = array('status'=>0,'user'=>array());
	}

	exit(json_encode($json));
}

$uuid 	= mt_rand(1,2147483647);

$wechat = new wechat($options);

$qrcode = $wechat->getQRCode($uuid);

if ( $qrcode )
{
	$qrcodeimage = $wechat->getQRUrl($qrcode['ticket']);

	$path = dirname(__file__) . DIRECTORY_SEPARATOR . $uuid.'.php';

	include(dirname(__file__) . DIRECTORY_SEPARATOR . 'login.tpl.php');
}
else
{
	echo($wechat->errMsg);
}
?>