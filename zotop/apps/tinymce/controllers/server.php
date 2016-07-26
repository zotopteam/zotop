<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 编辑器服务类
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class tinymce_controller_server extends admin_controller
{

	public function action_index()
    {

        $result = array(
        	'state'=> t('暂时不支持编辑器直接上传文件')
        );

        exit(json_encode($result));
	}

	/**
	 * 加载远程图片
	 * 
	 * @return [type] [description]
	 */
	public function action_proxy()
	{
		$validMimeTypes = array("image/gif", "image/jpeg", "image/png");

		if (!isset($_GET["url"]) || !trim($_GET["url"])) {
		    header("HTTP/1.0 500 Url parameter missing or empty.");
		    return;
		}

		$scheme = parse_url($_GET["url"], PHP_URL_SCHEME);
		if ($scheme === false || in_array($scheme, array("http", "https")) === false) {
		    header("HTTP/1.0 500 Invalid protocol.");
		    return;
		}

		if ( !function_exists('getimagesizefromstring') )
		{
		      function getimagesizefromstring($string_data)
		      {
		         $uri = 'data://application/octet-stream;base64,' . base64_encode($string_data);
		         return getimagesize($uri);
		      }
		}		

		$content = file_get_contents($_GET["url"]);
		
		$info    = getimagesizefromstring($content);

		if ($info === false || in_array($info["mime"], $validMimeTypes) === false) {
		    header("HTTP/1.0 500 Url doesn't seem to be a valid image.");
		    return;
		}

		header('Content-Type:' . $info["mime"]);
		echo $content;	
	}

	/**
	 * 图片上传
	 * 
	 * @return [type] [description]
	 */
	public function action_uploadimage()
	{
		reset($_FILES);

		$temp             = current($_FILES);
		$accepted_origins = array("http://localhost", request::host());		

		if (is_uploaded_file($temp['tmp_name']))
		{
			if (isset($_SERVER['HTTP_ORIGIN']))
            {
				if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins))
                {
					header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
				}
                else
                {
					header("HTTP/1.0 403 Origin Denied");
					return;
				}
			}

			// 如果需要cookies 设置：images_upload_credentials : true
			header('Access-Control-Allow-Credentials: true');
			header('P3P: CP="There is no P3P policy."');

			// 审查文件名
			if ( preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name']) )
			{
			    header("HTTP/1.0 500 Invalid file name.");
			    return;
			}

			// 检查格式
			if ( !in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png")) )
			{
			    header("HTTP/1.0 500 Invalid extension.");
			    return;
			}

			$filename = date('Ymdhis').rand(1000, 9999).'.'.file::ext($temp['name']);
			$filepath = m('system.attachment.savepath').DS.$filename;

			// 移动文件
			move_uploaded_file($temp['tmp_name'], $filepath);

			// 将文件保存到数据库
			$file = m('system.attachment.savefile', $filepath, $_GET);


			exit(json_encode(array('location' => $file['url'])));
		}

		header("HTTP/1.0 500 Server Error");	
	}
}
?>