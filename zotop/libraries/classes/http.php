<?php defined('ZOTOP') or die('No direct script access.');
/**
 * http
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009-2012 zotop team 
 * @license		http://zotop.com/license
 */
class http
{
	public $header;
	public $data;
	public $status = 0; // 正确返回200
	public $state; // 正确返回OK

	private $method;
	private $cookie;
	private $post;
	private $ContentType;
	
	public function __construct(){}
	
	// post 请求
	public function post( $url, $data = array( ), $referer = "", $limit = 0, $timeout = 30, $block = true )
	{
		$this->method = "POST";
		$this->ContentType = "Content-Type: application/x-www-form-urlencoded\r\n";
		
		if ( $data )
		{
			$post = "";
			foreach ( $data as $k => $v )
			{
				$post .= $k."=".rawurlencode( $v )."&";
			}
			$this->post .= substr( $post, 0, -1 );
		}

		return $this->request( $url, $referer, $limit, $timeout, $block );
	}

	// get 请求
	public function get( $url, $referer = "", $limit = 0, $timeout = 30, $block = true )
	{
		$this->method = "GET";

		return $this->request( $url, $referer, $limit, $timeout, $block );
	}
	
	// 私有请求
	private function request( $url, $referer = "", $limit = 0, $timeout = 30, $block = true )
	{
			$matches = parse_url( $url );
			$host = $matches['host'];
			$path = $matches['path'] ? $matches['path'].( $matches['query'] ? "?".$matches['query'] : "" ) : "/";
			$port = $matches['port'] ? $matches['port'] : 80;

			$referer = $referer == "" ? request::url() : $referer;

			$out = "{$this->method} {$path} HTTP/1.1\r\n";
			$out .= "Accept: */*\r\n";
			$out .= "Referer: {$referer}\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "User-Agent: ".$_SERVER['HTTP_USER_AGENT']."\r\n";
			$out .= "Host: {$host}\r\n";

			if ( $this->cookie )
			{
				$out .= "Cookie: {$this->cookie}\r\n";
			}

			if ( $this->method == "POST" )
			{
				$out .= $this->ContentType;
				$out .= "Content-Length: ".strlen( $this->post )."\r\n";
				$out .= "Cache-Control: no-cache\r\n";
				$out .= "Connection: Close\r\n\r\n";
				$out .= $this->post;
			}
			else
			{
				$out .= "Connection: Close\r\n\r\n";
			}

			if ( ini_get( "max_execution_time" ) < $timeout )
			{
				@set_time_limit( $timeout );
			}

			$fp = @fsockopen( $host, $port, $errno, $error, $timeout );

			$this->post = '';

			if ( $fp )
			{
				stream_set_blocking( $fp, $block );
				stream_set_timeout( $fp, $timeout );
				fwrite( $fp, $out );
				
				$this->data = "";
				
				$status = stream_get_meta_data( $fp );

				if ( !$status['timed_out'] )
				{
					$maxsize = min( $limit, 1024000 );
					$maxsize = $maxsize ==0 ? 1024000 : $maxsize;

					$start = false;
					
					while ( !feof( $fp ) )
					{
						if ( $start )
						{
							$line = fread( $fp, $maxsize );

							if ( $maxsize < strlen( $this->data ) )
							{
								break;
							}

							$this->data .= $line;
						}
						else
						{
							$line = fgets( $fp );
							$this->header .= $line;

							if ( $line == "\r\n" || $line == "\n" )
							{
								$start = true;
							}
						}
					}
				}

				fclose( $fp );

				// 解析数据
				if ( strpos($this->header,'chunk') )
				{
					$data = explode(chr(13), $this->data);
					$this->data = $data[1];
				}
				
				// 解析header获得status
				preg_match( "|^HTTP/1.1 ([0-9]{3}) (.*)|", $this->header, $s);

				$this->status = intval($s[1]);
				$this->state = $s[2];

				return $this->status == 200 ? true : false;
			}

			$this->status = $errno;
			$this->state = $error;
			
			return false;
	}

	// 保存文件
	public function save( $file )
	{
		$dir = dirname( $file );

		if ( !is_dir( $dir ) )
		{
			folder::create( $dir );
		}

		return file_put_contents( $file, $this->data );
	}
	
	// 设置cookie
	public function setCookie( $name, $value='')
	{
		if ( is_array($name) )
		{
			foreach( $name as $k=>$v )
			{
				$this->cookie .= "{$k}={$v};";
			}
		}
		else
		{
			$this->cookie .= "{$name}={$value};";
		}
	}

	// 获取cookie
	public function getCookie()
	{
		$cookies = array();

		if ( preg_match_all( "|Set-Cookie: ([^;]*);|", $this->header, $m ) )
		{
			foreach ( $m[1] as $c )
			{
				list( $k, $v ) = explode( "=", $c );
				$cookies[$k] = $v;
			}
		}

		return $cookies;
	}
}
?>