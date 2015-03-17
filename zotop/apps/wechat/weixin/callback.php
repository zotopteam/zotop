<?php
require dirname(__file__) . DIRECTORY_SEPARATOR . 'wechat.php';

// 配置
$options = array(
	'appid' => 'wx42bcfbbc186fe17e',
	'appsecret' => '3e35d85e27ed996450903184b552e4b0', //填写高级调用功能的密钥
	'token' => 'zotop', //填写你设定的key
	'encodingaeskey' => 'encodingaeskey' //填写加密用的EncodingAESKey，如接口为明文模式可忽略
);

$wechat = new wechat($options);

$wechat -> valid();

$type = $wechat -> getRev() -> getRevType();

switch($type)
{
	case Wechat::MSGTYPE_TEXT :
		$wechat -> text("hello, I'm wechat") -> reply();
		exit ;
		break;
	case Wechat::MSGTYPE_EVENT :
		$revdata = $wechat -> getRevData();
		$event = $wechat -> getRevEvent();

		// 如果传递了事件参数 场景值ID
		if ($key = $event['key'])
		{
			$openid = $wechat -> getRevFrom();
			$userinfo = $wechat -> getUserInfo($openid);

			//写入临时文件，是否可用session替代？

			// 用户未关注时，进行关注后的事件推送
			if (substr($key, 0, 8) == 'qrscene_')
			{
				$key = substr($key, 8);
			}

			file_put_contents(dirname(__file__) . DIRECTORY_SEPARATOR . $key . '.php', '<?php return ' . var_export($userinfo, true) . '?>');

			$wechat ->

			//返回
			//$wechat->text(var_export($event,true))->reply();
			$wechat -> text('登录成功') -> reply();
		}
		else
		{
			$wechat -> text(var_export($revdata, true)) -> reply();
		}
		break;
	case Wechat::MSGTYPE_IMAGE :
		break;
	default :
		$wechat -> text("help info") -> reply();
		break;
}
?>