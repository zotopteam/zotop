<?php
/**
 * 系统全局文件，包含自动加载类以及全局函数
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009-2012 zotop team
 * @license		http://zotop.com/license
 */

/*
* 注册类，用于自动加载
*/
zotop::register(array(
    'runtime'               => ZOTOP_PATH_LIBRARIES . DS . 'classes' . DS . 'runtime.php',
    'debug'                 => ZOTOP_PATH_LIBRARIES . DS . 'classes' . DS . 'debug.php',
    'zotop_exception'       => ZOTOP_PATH_LIBRARIES . DS . 'classes' . DS . 'exception.php',
    'arr'                   => ZOTOP_PATH_LIBRARIES . DS . 'classes' . DS . 'array.php',
    'pinyin'                => ZOTOP_PATH_LIBRARIES . DS . 'classes' . DS . 'pinyin.php',
    'html'                  => ZOTOP_PATH_LIBRARIES . DS . 'classes' . DS . 'html.php',
    'tree'                  => ZOTOP_PATH_LIBRARIES . DS . 'classes' . DS . 'tree.php',
    'pagination'            => ZOTOP_PATH_LIBRARIES . DS . 'classes' . DS . 'pagination.php',
    'http'                  => ZOTOP_PATH_LIBRARIES . DS . 'classes' . DS . 'http.php',
    'mail'                  => ZOTOP_PATH_LIBRARIES . DS . 'classes' . DS . 'mail.php',
    'captcha'               => ZOTOP_PATH_LIBRARIES . DS . 'classes' . DS . 'captcha.php',

    'db'       => ZOTOP_PATH_LIBRARIES . DS . 'classes' . DS . 'db.php', 
    'db_mysql' => ZOTOP_PATH_LIBRARIES . DS . 'classes' . DS . 'db' . DS . 'mysql.php',
    
    'session'               => ZOTOP_PATH_LIBRARIES . DS . 'classes' . DS . 'session.php',
    'session_native'        => ZOTOP_PATH_LIBRARIES . DS . 'classes' . DS . 'session' . DS . 'native.php',
    'session_file'          => ZOTOP_PATH_LIBRARIES . DS . 'classes' . DS . 'session' . DS . 'file.php',
    'image'                 => ZOTOP_PATH_LIBRARIES . DS . 'classes' . DS . 'image.php',
    'image_gd'              => ZOTOP_PATH_LIBRARIES . DS . 'classes' . DS . 'image' . DS . 'gd.php',
    'upload'                => ZOTOP_PATH_LIBRARIES . DS . 'classes' . DS . 'upload.php',
    'pclzip'                => ZOTOP_PATH_LIBRARIES . DS . 'extends' . DS . 'pclzip.lib.php',
    ));

/**
 *
 * 函数zotop::app()的简写方式，详细参见zotop::app()函数
 *
 */
function a($key = null)
{
    return zotop::app($key);
}

/**
  * 函数zotop::config()的简写方式，详细参见zotop::config()函数
 *
 * @param string|array $name 配置变量，数组或字符串
 * @param mixed $value 配置值
 * @param mixed $default 默认值
 * @return mixed
 */
function c($name=null, $value = null, $default = null)
{
    return zotop::config($name, $value, $default);
}



/*
* 实例化一个模型 或者 实例化一个模型并调用模型方法，第一个参数格式为：[应用.模型] 或者 [应用.模型.方法]，其余参数均为函数的参数
*
* @code
*
* m('system.user'); //实例化 system 应用的 user 模型
* m('content.category'); //实例化 content 应用的 category 模型
*
* m('system.user.get', $userid); // 实例化 system.user 模型并调用get方法，传入一个参数 $userid
* m('system.user.get', $userid, 'point'); // 实例化 system.user 模型并调用get方法，传入两个参数

* m('content.category.active'); //实例化 content.category 模型并调用active方法
*
* @endcode
*
* @param string $ns 一般由[应用.模型] 或者 [应用.模型.方法] 组成
* @param string $p1 参数1
* @param string $p2 参数2
* @return mix
*/
function m($ns)
{
    static $models = array();

    list($app, $model, $method) = explode('.', $ns);

    $class = $app . '_model_' . $model;

    if (empty($models[$class]))
    {
        $path = a("{$app}.path") . DS . 'models' . DS . $model . '.php';

        if (class_exists($class) == false) zotop::load($path);

        if (class_exists($class) == false)
        {
            throw new zotop_exception(t('模型实例化失败，模型文件 {1} 或者 模型类 {2} 不存在', $path, $class));
        }

        $models[$class] = new $class();
    }

    if (empty($method))
    {
        return $models[$class];
    }

    if (method_exists($models[$class], $method))
    {
        $args = func_get_args();

        return call_user_func_array(array(&$models[$class], $method), array_slice($args, 1));
    }

    throw new zotop_exception(t('模型 {1} 中找不到方法 {2} 方法', $class, $method));
}


/**
 * 语言获取和设置函数，TODO从应用语言包中获取数据
 *
 * @param string|array $key string:设置的键名获取或者设置该键值，array：配置数组赋值数组，空返回整个设置数组
 * @param string $value 键值，用于赋值
 * @return mix
 */
function l($key = null, $val = null)
{
    static $langs = array();

    if (empty($key)) return $langs;

    if (is_array($key))
    {
        $langs = array_merge($langs, $key);
        return $langs;
    }

    if (is_null($val))
    {
        return isset($langs[$key]) ? $langs[$key] : $key;
    }

    $langs[$key] = $val;

    return $val;
}


/**
 *
 * 翻译并替换字符串中的变量
 *
 * @param string $str 待转换字符串
 * @param mix $params 参数
 * @return string
 */
function t($str, $params = '')
{
    // 翻译
    $str = l($str);

    $params = is_array($params) ? $params : func_get_args();

    foreach ($params as $key => $val)
    {
        $str = str_replace('{' . $key . '}', $val, $str);
    }
    
    return count($params) < 2 ? $str : vsprintf($str, array_slice($params, 1));
}


/**
 * 函数zotop::url()的简写方式，详细参见zotop::url()函数
 *
 * @param string $uri
 * @param mixed $params
 * @param bool $host
 * @return
 */
function u($uri = '', $params = array(), $host = true)
{
    return zotop::url($uri, $params, $host);
}


/**
 * 输出缩略图
 *
 * TODO 缩略图生成单独的文件夹存放
 * 
 * @param sting $img 图片地址
 * @param int $width 缩略图宽度
 * @param int $height 缩略图高度
 * @param string $default 默认图片
 * @return string
 */
function thumb($image, $width, $height, $default = null)
{
    $default = $default ? $default : ZOTOP_URL_PUBLIC . '/common/noimage.png';

    if (empty($image)) return $default;

    // 获取图片相对于上传目录的路径，如：/uploads/2010/11/1.jpg => /2010/11/1.jpg
    if (preg_match("/(" . preg_quote(ZOTOP_PATH_UPLOADS, "/") . "|" . preg_quote(ZOTOP_URL_UPLOADS, "/") . ")(.*)\$/", $image, $matches))
    {
        $img = $matches[2]; //不含upload目录
    }

    // 如果是站外图片或者不存在的图片直接返回
    if (strpos($img, '://') or !file_exists(ZOTOP_PATH_UPLOADS . $img)) return $image;

    // 缩略图地址
    $newimg = dirname($img) . '/thumb_' . $width . '_' . $height . '_' . basename($img);

    // 生成缩略图
    if (!file_exists(ZOTOP_PATH_UPLOADS . $newimg) || filemtime(ZOTOP_PATH_UPLOADS . $newimg) < filemtime(ZOTOP_PATH_UPLOADS . $img))
    {
        try
        {
            $image = new image(ZOTOP_PATH_UPLOADS . $img);
            $image->thumb($width, $height)->save(ZOTOP_PATH_UPLOADS . $newimg);
        }
        catch (exception $e)
        {
            return $image;
        }
    }

    return ZOTOP_URL_UPLOADS . $newimg;
}
?>