<?php
defined('ZOTOP') or die('No direct access allowed.');
/**
 * 控制器基类
 *
 * @copyright  (c)2009 zotop team
 * @package    core
 * @author     zotop team
 * @license    http://zotop.com/license.html
 */
class controller
{
    /**
     *
     * @var object 模板实例对象
     */
    protected $template = null;

    /**
     *
     * @var array 模板变量
     */
    protected $template_vars = array();

    /**
     *
     * @var string 定义默认的方法
     */
    public $default_action = 'index';

    /**
     * 取得模板对象实例，并初始化
     *
     */
    public function __construct()
    {
    }

    /**
     * 初始化动作，动作执行之前调用
     *
     */
    public function __init()
    {
        //
    }


    /**
     * 空方法 当操作不存在的时候执行
     *
     * @param string $method 方法名
     * @param array $args 参数
     * @return mixed
     */
    public function __empty($action = '', $arguments = array())
    {
        throw new zotop_exception(t('未能找到相应的动作 %s，请检查控制器中动作是否存在？', $action), 404);
    }

    /**
     * 视图实例
     */
    protected function template()
    {
        // 实例化视图类
        if (!$this->template)
        {
            $this->template = zotop::instance('template');
        }

        // 模板变量传值
        if ($this->template_vars and is_array($this->template_vars))
        {
            $this->template->assign($this->template_vars);
        }

        return $this->template;
    }

    /**
     * 模板变量赋值，函数方式
     *
     * @param mixed $name 要显示的模板变量
     * @param mixed $value 变量的值
     * @return void
     */
    protected function assign($name = '', $value = '')
    {
        if ($name === '')
        {
            // 返回全部
            return $this->template_vars;
        }
        elseif ($name === null)
        {
            // 清空
            $this->template_vars = array();
        }
        elseif (is_array($name) or is_object($name))
        {
            // 多项赋值
            foreach ($name as $key => $val)
            {
                $this->template_vars[$key] = $val;
            }
        }
        elseif ($value === '')
        {
            // 返回某项数据,不存在返回null
            return isset($this->template_vars[$name]) ? $this->template_vars[$name] : null;
        }
        elseif ($value === null)
        {
            // 删除
            unset($this->template_vars[$name]);
        }
        else
        {
            // 单项赋值
            $this->template_vars[$name] = $value;
        }

        return $this;
    }

    /**
     * 模板变量赋值，魔术方法
     *
     * @param mixed $name 要显示的模板变量
     * @param mixed $value 变量的值
     * @return void
     */
    public function __set($name, $value)
    {
        $this->assign($name, $value);
    }

    /**
     * 取得模板显示变量的值
     * @access protected
     * @param string $name 模板显示变量
     * @return mixed
     */
    public function __get($name)
    {
        return $this->assign($name);
    }


    /**
     * 模板显示 调用内置的模板引擎显示方法
     *
     * @param string $templateFile 指定要调用的模板文件,默认为空 由系统自动定位模板文件
     * @param string $charset 输出编码
     * @param string $content_type 输出类型
     * @return void
     */
    protected function display($template = '', $content_type = '', $charset = '')
    {
        //输出页面内容
        $this->template()->display($template, $content_type, $charset);
    }


    /**
     * 提交验证，正确提交则返回post数据，否则返回false
     *
     */
    public function post()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if ((empty($_SERVER['HTTP_REFERER']) || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])))
            {
                return empty($_POST) ? true : $_POST;
            }

            throw new zotop_exception(t('invalid submit'));
        }
        return false;
    }


    /*
    * redirect to uri ,详细参见 zotop::url 的参数设定
    *
    * @param string $url, 如：U('system/index')
    * @return null
    */
    public function redirect($url)
    {
        if ( $url and !headers_sent() )
        {
            header("location:{$url}");
            exit();
        }
    }

    /*
    * 错误消息提示
    *
    * @param string $content 错误消息
    * @return null
    */
    public function error($content, $time = 3)
    {
        $this->message(array(
            'state'   => false,
            'content' => $content,
            'time'    => $time
		));
    }

    /*
    * 正确消息提示
    *
    * @param string $msg
    * @return null
    */
    public function success($content, $url = null, $time = 2)
    {
        $this->message(array(
            'state'   => true,
            'content' => $content,
            'url'     => $url,
            'time'    => $time
		));
    }

    /*
    * 消息提示
    *
    * @param array $msg
    * @return null
    */
    public function message(array $msg)
    {
        //清理已经输出内容
        ob_clean();

        //如果请求为ajax，则输出json数据
        if ( ZOTOP_ISAJAX )
        {
            exit(json_encode($msg));
        }

        $this->assign($msg);
        $this->display("system/message.php");

        //exit后 无法进入 shoutdown render
        //exit(1);
    }

    /*
    * 404 error
    *
    * @param string $content 错误消息
    * @return null
    */
    public function _404($content)
    {
        @header('HTTP/1.1 404 Not Found');
        @header('Status: 404 Not Found');

        $this->assign('content', $content);
        $this->display("system/404.php");
    }    
}
