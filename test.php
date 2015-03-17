<?php


    $url = 'https://login.weixin.qq.com/jslogin?appid=wx782c26e4c19acffb&redirect_uri=http%3A%2F%2Fwx.qq.com%2Fcgi-bin%2Fmmwebwx-bin%2Fwebwxnewloginpage&fun=new&lang=zh_CN';
    $ch = curl_init();
    //CURLOPT_HTTPHEADER  用来设置http头字段的数组，相当于html的<head></head>中的内容设置
    curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type: application/x-www-form-urlencoded','Connection: close' ,'Cache-Control: no-cache' ,'Accept-Language: zh-cn'));
    //CURLOPT_TIMEOUT  响应时间设置
    curl_setopt ($ch, CURLOPT_TIMEOUT, 20);
    //CURLOPT_USERAGENT  在HTTP请求中包含一个“User-Agent: “头的字符串(用来设置用户浏览器)
    curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)");
    //CURLOPT_HEADER  启用时会将头文件的信息作为数据流输出(true,false)
    curl_setopt ($ch, CURLOPT_HEADER,0);
    //CURLOPT_FOLLOWLOCATION  启用时会将服务器服务器返回的“Location: “放在header中递归的返回给服务器，使用CURLOPT_MAXREDIRS可以限定递归返回的数量。
    curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 0);
    //CURLOPT_RETURNTRANSFER  (这个很重要)将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    //CURLOPT_POST 启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
    curl_setopt($ch, CURLOPT_POST, 0);
    //CURLOPT_URL  需要获取的URL地址，也可以在curl_init()函数中设置
    curl_setopt ($ch, CURLOPT_URL,$url);
    // CURLOPT_SSL_VERIFYPEER  禁用后cURL将终止从服务端进行验证。使用CURLOPT_CAINFO选项设置证书
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //CURLOPT_SSL_VERIFYHOST    1 检查服务器SSL证书中是否存在一个公用名(common name)。译者注：公用名(Common Name)一般来讲就是填写你将要申请SSL证书的域名 (domain)或子域名(sub domain)。2 检查公用名是否存在，并且是否与提供的主机名匹配。
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  false);
    //CURLOPT_HTTPGET   用get方式获取参数
    curl_setopt($ch, CURLOPT_HTTPGET, 1);
    $res  = curl_exec($ch);
    curl_close($ch);

    //var_dump($res);


    $param = array(
        'appid'        => 'wx782c26e4c19acffb',
        'redirect_uri' => 'http%3A%2F%2Fwx.qq.com%2Fcgi-bin%2Fmmwebwx-bin%2Fwebwxnewloginpage',
        'fun'          => 'new',
        'lang'         => 'zh_CN',
    );

    $rr = test::http('https://login.weixin.qq.com/jslogin',$param);

    var_dump($rr);

    class test{



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
