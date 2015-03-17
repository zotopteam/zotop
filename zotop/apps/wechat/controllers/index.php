<?php


$wechat = new wechat();

$taken  = $wechat->getAccessToken();

$ticket = $wechat->qrcodeCreate(1);

$qrcode = $wechat->showqrcode($ticket['ticket']);

echo '<img src="'.$qrcode.'"/>';

class wechat
{
    /**
     * 微信开发者申请的appid
     * @var string
     */
    private $appid = 'wx42bcfbbc186fe17e';

    /**
     * 微信开发者申请的appsecret
     * @var string
     */
    private $appsecret = '3e35d85e27ed996450903184b552e4b0';

    /**
     * 获取到的access_token
     * @var string
     */
    private $accesstoken = '';


    /**
     * 构造方法，调用微信高级接口时实例化SDK
     * 
     * @param string $appid  微信appid
     * @param string $secret 微信appsecret
     * @param string $token  获取到的access_token
     */
    public function __construct($appid=mull, $secret=null, $token = null)
    {
        if( $appid && $secret)
        {
			$this->appid     = $appid;
			$this->appsecret = $secret;

            if( $token )
            {
                $this->accesstoken = $token;
            }
        }
    }

    /**
     * 获取access_token，用于后续接口访问
     * 
     * @return array access_token信息，包含token和有效期
     */
    public function getAccessToken($type = 'client', $code = null)
    {
        $param = array('appid'  => $this->appid,'secret' => $this->appsecret);

        switch ($type) 
        {
            case 'client':
				$param['grant_type'] = 'client_credential';
				$url                 = 'https://api.weixin.qq.com/cgi-bin/token';
                break;

            case 'code':
				$param['code']       = $code;
				$param['grant_type'] = 'authorization_code';
				$url                 = 'https://api.weixin.qq.com/sns/oauth2/access_token';
                break;
            
            default:
                throw new Exception('不支持的grant_type类型！');
                break;
        }

        $token = self::http($url, $param);
        $token = json_decode($token, true);

        if( is_array($token) )
        {
            if(isset($token['errcode']))
            {
                throw new Exception($token['errmsg']);
            }
            else
            {
                $this->accesstoken = $token['access_token'];
                return $token;
            }
        }
        else
        {
            throw new Exception('获取微信access_token失败！');
        }
    }

    /**
     * 创建二维码，可创建指定有效期的二维码和永久二维码
     * 
     * @param  integer $scene_id       二维码参数
     * @param  integer $expire_seconds 二维码有效期，0-永久有效
     */
    public function qrcodeCreate($scene_id, $expire_seconds = 0)
    {
        $data = array();

        if(is_numeric($expire_seconds) && $expire_seconds > 0)
        {
            $data['expire_seconds'] = $expire_seconds;
            $data['action_name']    = 'QR_SCENE';
        }
        else
        {
            $data['action_name']    = 'QR_LIMIT_SCENE';
        }

        $data['action_info']['scene']['scene_id'] = $scene_id;

        return $this->api('qrcode/create', $data);
    }

    /**
     * 根据ticket获取二维码URL
     * @param  string $ticket 通过 qrcodeCreate接口获取到的ticket
     * @return string         二维码URL
     */
    public function showqrcode($ticket)
    {
        return "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket={$ticket}";
    }    

    /**
     * 获取授权用户信息
     * @param  string $token acess_token
     * @param  string $lang  指定的语言
     * @return array         用户信息数据，具体参见微信文档
     */
    public function getUserInfo($token, $lang = 'zh_CN')
    {
        $query = array(
            'access_token' => $token['access_token'],
            'openid'       => $token['openid'],
            'lang'         => $lang,
        );

        $info = self::http("https://api.weixin.qq.com/sns/userinfo", $query);
        return json_decode($info, true);
    }

	public function checkSignature()
	{
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		if( $tmpStr == $signature ){
		     return true;
		}else{
		     return false;
		}
	}    


    /**
     * 调用微信api获取响应数据
     * @param  string $name   API名称
     * @param  string $data   POST请求数据
     * @param  string $method 请求方式
     * @param  string $param  GET请求参数
     * @return array          api返回结果
     */
    protected function api($name, $data = '', $method = 'POST', $param = '')
    {
        $params = array('access_token' => $this->accesstoken);

        if(!empty($param) && is_array($param))
        {
            $params = array_merge($params, $param);
        }

        $url  = "https://api.weixin.qq.com/cgi-bin/{$name}";

        if(!empty($data))
        {
            //保护中文，微信api不支持中文转义的json结构
            array_walk_recursive($data, function(&$value){
                $value = urlencode($value);
            });

            $data = urldecode(json_encode($data));
        }

        $data = self::http($url, $params, $data, $method);

        return json_decode($data, true);
    }       

    /**
     * 发送HTTP请求方法，目前只支持CURL发送请求
     * 
     * @param  string $url    请求URL
     * @param  array  $param  GET参数数组
     * @param  array  $data   POST的数据，GET请求时该参数无效
     * @param  string $method 请求方法GET/POST
     * @return array          响应数据
     */
    public static function http($url, $param, $data = '', $method = 'GET')
    {
        $opts = array(
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        );

        /* 根据请求类型设置特定参数 */
        $opts[CURLOPT_URL] = $url . '?' . http_build_query($param);

        if(strtoupper($method) == 'POST')
        {
			$opts[CURLOPT_POST]       = 1;
			$opts[CURLOPT_POSTFIELDS] = $data;
            
             //发送JSON数据
            if(is_string($data))
            {
                $opts[CURLOPT_HTTPHEADER] = array(
                    'Content-Type: application/json; charset=utf-8',  
                    'Content-Length: ' . strlen($data),
                );
            }
        }

        /* 初始化并执行curl请求 */
		$ch    = curl_init();
		curl_setopt_array($ch, $opts);
		$data  = curl_exec($ch);
		$error = curl_error($ch);
		curl_close($ch);

        //发生错误，抛出异常
        if($error) throw new Exception('请求发生错误：' . $error);

        return  $data;
    }    

}
?>