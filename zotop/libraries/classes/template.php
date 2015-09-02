<?php
defined('ZOTOP') or die('No direct access allowed.');
/**
 * 模版类
 *
 * @copyright  (c)2009 zotop team
 * @package    zotop
 * @author     zotop team
 * @license    http://zotop.com/license.html
 */
class template
{
    /**
     *
     * @var array 模板变量
     */
    public $vars = array();


    /**
     *
     * @var string 模板主题标识
     */
    public $theme = null;

    /**
     *
     * @var array 受保护的内容
     */
    protected static $tags = array();


    /**
     * 
     * @var array
     */
    private $literal = array();


    /**
     * 初始化控制器
     */
    public function __construct( $theme=null )
    {
        // 定义主题
        $this->theme = $theme ? $theme : $this->theme;
        $this->theme = defined('ZOTOP_THEME') ? ZOTOP_THEME : $this->theme;

        // 定义__THRME__常量
        define('__THEME__', ZOTOP_URL_THEMES.'/'.$this->theme);
    }

    /**
     * 自定义标签，可以覆盖已经存在的添加标签
     *
     * 使用方法：
     *
     * template::tag('content');
     * template::tag('content','content_list');
     *
     * 当callback为空的时候，如 template::tag('content') 时 回调函数为：tag_content
     *
     * @param string $tag 标签名称
     * @param string $callback 回调函数
     * @return string
     */
    public static function tag($tag = null, $callback = '')
    {
        if ( $tag and is_string($tag) )
        {
            template::$tags[$tag] = empty($callback) ? "tag_{$tag}" : $callback;
        }

        if ( $tag and is_array($tag) )
        {
            foreach ($tag as $t => $c)
            {
                template::tag($t, $c);
            }

        }

        return template::$tags;
    }

    /**
     * 取得真实的视图文件路径,默认搜索应用下面的templates目录,如果启用主题模式则先搜索当前主题下面的templates，搜索不到的时候再搜索应用下面的templates目录
     *
     * @param string $template 视图名称，如：$app/$controller_$action.php 或者 $app/test.php 必须包含.php后缀
     * @return string
     */
    public function find($template = '')
    {
        
        //空值返回 [ZOTOP_APP]/[ZOTOP_CONTROLLER]_[ZOTOP_ACTION].php
        if ( empty($template) )
        {
            $template = ZOTOP_APP . DS . ZOTOP_CONTROLLER . '_' . ZOTOP_ACTION . '.php';
        }

        //去除右端多余的斜线
        $template = str_replace('/', DS, rtrim($template,'/'));

        // 如果模版不是已经存在的绝对路径模版
        if ( strpos($template, ZOTOP_PATH) === false )
        { 
            // 如果定义了主题，优先返回主题下面的模板
            if ( $this->theme and file_exists(ZOTOP_PATH_THEMES . DS . $this->theme . DS . 'templates' . DS . $template))
            {
                $template = ZOTOP_PATH_THEMES . DS . $this->theme . DS . 'templates' . DS . $template;
            }
            else
            {
                // 应用自带的template，如果含有 ‘/’ ,则第一个斜线前的字符为应用ID，如果不含‘/’，默认应用为‘system’
                $app = 'system';

                if ( $i = strpos($template, DS) )
                {
                    $app        = substr($template, 0, $i);
                    $template   = substr($template, $i + 1);
                }

                $template = A("{$app}.path") . DS . 'templates' . DS . $template;
            }
        }


        return $template;
    }

    /**
     * 获取被解析过的并缓存后的模板文件
     *
     * @param string $template 模板文件
     * @return string
     */
    public function compile($template = '')
    {
        //获取原始模版的真实路径
        $template = $this->find($template);
        
        //预定义解析过后的模版路径
        $compile  = ZOTOP_PATH_RUNTIME . DS . 'templates' . DS . substr(str_replace(DS, '.', $template), strlen(ZOTOP_PATH) + 1);

        if (ZOTOP_DEBUG or !file_exists($compile) or @filemtime($template) > @filemtime($compile))
        {
            if ($content = @file_get_contents($template))
            {
                // 解析模版
                $content = $this->parse($content);

                //写入解析后的模版
                if ( false === @file_put_contents($compile, $content) )
                {
                    throw new zotop_exception(t("The dir [%s] is not exist or not writable", debug::path(dirname($compile))));
                }

                @chmod($compile, 0774);

                return $compile;
            }

            throw new zotop_exception(t('The file [ %s ] not exist', debug::path($template)), 404);
        }

        return $compile;
    }

    /**
     * 转义 // 为 /，并自动为数组变量增加单引号
     *
     * @param string $var 转义的字符
     * @return 转义后的字符
     */
    public function addquote($var)
    {
        return str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var));
    }


    /**
     * 模板解析函数
     *
     * @param string $template 模板内容
     * @return string 解析的结果
     */
    public function parse($str)
    {
        // 去除多余换行
        $str = preg_replace("/([\n\r]+)\t+/s", "\\1", $str);

		// 支持<!-{…}-> 模式标签
		$str = preg_replace("/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}", $str);

		// 去除注释
        $str = preg_replace("/\<\!\-\-#.+?#\-\-\>/s", "", $str);

        // 解析 {literal}……………{/literal} 标签，literal中的内容不会参与之后的解析，直接显示
        $str = preg_replace("/\{literal\}(.*?){\/literal}/es", "\$this->parse_literal('\\1')", $str);

		// 解析点符号数组 {……$r.id……} => {……$r['id']……}  {……$r.id.n……} => {……$r['id']['n']……}，最多支持三维数组
		$str = preg_replace("/\{(.+?)\}/es", "\$this->parse_tag('\\1')", $str);

		// 解析单行php {php $i=1}
		$str = preg_replace("/\{php\s+(.+)\}/i", "<?php \\1?>", $str);

		// 解析 {template 'header.php'} 标签，模板嵌套
        $str = preg_replace("/\{template\s+(.+)\}/i", '<?php $this->display(\\1); ?>', $str);

		// 解析 {include 'header.php'} 标签，模板嵌套，直接合并进当前模板,z最多支持三级嵌套
        for($i = 1; $i <= 3; $i++)
        {
        	if ( stripos($str, '{include') !== false )
        	{
        		$str = preg_replace("/\{include\s+(.+)\}/ie", "\$this->loadsubtemplate('\\1')", $str);
        	}
		}

		// 解析 {hook 'site.header'} 标签，hook接口
        $str = preg_replace("/\{hook\s+(.+)\}/i", '<?php zotop::run(\\1, $this); ?>', $str);

		// 解析 {if ……} {else} {elseif ……} {endif}标签
		$str = preg_replace("/\{if\s+(.+?)\}/i", "<?php if(\\1):?>", $str );
        $str = preg_replace("/\{else\}/i", "<?php else: ?>", $str);
		$str = preg_replace("/\{elseif\s+(.+?)\}/i", "<?php elseif(\\1):?>", $str );
        $str = preg_replace("/\{\/if\}/i", "<?php endif; ?>", $str);

		// 解析 {loop $data $r} {loop $data $k $v} {/loop}标签
        $str = preg_replace("/\{loop\s+(\S+)\s+(\S+)\}/i", "<?php \$n=0; if(is_array(\\1)) foreach(\\1 as \\2){ ?>", $str);
        $str = preg_replace("/\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}/i", "<?php \$n=0; if(is_array(\\1)) foreach(\\1 as \\2 => \\3){ ?>", $str);
        $str = preg_replace("/\{\/loop\}/i", "<?php \$n++;};unset(\$n); ?>", $str);

		// 解析函数标签 format::date($time);
		$str = preg_replace("/\{([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(.*?\))\}/", "<?php echo \\1;?>", $str);

		// 解析静态变量标签 {ZOTOP_TIME}
        $str = preg_replace("/\{([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)\}/s", "<?php echo \\1;?>", $str);

		// 解析{for ……} {/for}标签
        $str = preg_replace("/\{for\s+(.+?)\}/i", "<?php for( \\1 ): ?>", $str);
        $str = preg_replace("/\{\/for\}/i", "<?php endfor; ?>", $str);

		// 解析数组 {$r[id]} => $r['id']
        $str = preg_replace("/\{(\\$[a-zA-Z0-9_\[\]\'\"\$\x7f-\xff]+)\}/es", "\$this->addquote('<?php echo \\1;?>')", $str);

		// 解析对象 {$content->title}
        $str = preg_replace("/\{(\\\$[a-zA-Z0-9_\-\>\[\]\'\"\$\.\x7f-\xff]+)\}/s", "<?php echo \\1;?>", $str);

		// 解析自增、自减标签 {$i++} {$i--} {++$i} {--$i}
        $str = preg_replace("/\{\+\+\\$(.+?)\}/", "<?php ++$\\1; ?>", $str);
        $str = preg_replace("/\{\-\-\\$(.+?)\}/", "<?php ++$\\1; ?>", $str);
        $str = preg_replace("/\{\\$(.+?)\+\+\}/", "<?php $\\1++; ?>", $str);
        $str = preg_replace("/\{\\$(.+?)\-\-\}/", "<?php $\\1--; ?>", $str);

		// 解析运算
		$str = preg_replace("/\{(\(.*?\))\}/", "<?php echo \\1;?>", $str);

        //Hook，接入自定义解析规则
        $str = zotop::filter('template.parse', $str, $this);

        // 解析field标签
        $str = preg_replace('/\{field(\s+[^}]+?)(\/?)\}/ie', "\$this->parse_field('\\1')", $str);

        // 解析用户自定义标签 {content ……}……{/content} 或者 {content ……/}
        if ($tag = template::tag())
        {
            $str = preg_replace('/\{('.implode('|', array_keys($tag)).')(\s+[^}]+?)(\/?)\}/ie', "\$this->_beginTag('\\0','\\1', '\\2', '\\3')", $str);
            $str = preg_replace('/\{\/('.implode('|', array_keys($tag)).')\}/ie', "\$this->_endTag('\\1')", $str);
        }

        // 解析并还原 literal 标签
        foreach ($this->literal as $key => $val)
        {
             $str = str_replace('<!--template::literal_data_'.$key.'-->', $val, $str);
        }        

        return "<?php defined('ZOTOP') or exit('No permission resources.'); ?>\r\n" . $str;
    }

    /**
     * 解析并存储literal标签中的内容
     * 
     * @param string $str literal标签中的代码
     * @return string
     */
    private function parse_literal($str)
    {
        if ( trim($str) )
        {
            $i = count($this->literal);
            $this->literal[$i] = $str;

            return '<!--template::literal_data_'.$i.'-->';
        }

        return '';
    }

    /**
     * 内置的field标签解析 TODO 此方法无法解析镖旗中的变量和函数
     * 
     * @param  [type] $str [description]
     * @return [type]      [description]
     */
    public function parse_field($str)
    {
        $attrs = $this->parse_attrs($str);
        $array = $this->array_attrs($attrs);

        return '<?php echo form::field('.$array.')?>';
    }    


    /**
     * 解析标签中的点符号数组,最多支持四维数组
     *
     * @param string $str 匹配到的标签内容
     *
     * @return string 解析的结果
     */
	public function parse_tag($str)
	{
		$var = '[a-zA-Z0-9_]+'; //数组允许使用的变量类型

		$str = stripslashes($str);
		$str = preg_replace("/\\\$(".$var.")\.(".$var.")\.(".$var.")\.(".$var.")/", "$\\1['\\2']['\\3']['\\4']", $str);
        $str = preg_replace("/\\\$(".$var.")\.(".$var.")\.(".$var.")/", "$\\1['\\2']['\\3']", $str);
        $str = preg_replace("/\\\$(".$var.")\.(".$var.")/", "$\\1['\\2']", $str);       

		return '{'.$str.'}';
	}

    /**
     * 将标签字符串转化为数组
     * 
     * 
     * @param  string $str 标签字符串，所有参数都必须以半角（英文）双引号括起来，如： id="1" size="10" name="$name" placeholder="t('dddd')"
     * @return array
     */
    public function parse_attrs($str)
    {
        $attrs = array();

        preg_match_all("/\s+([a-z0-9_-]+)\s*\=\s*\"(.*?)\"/i", stripslashes($str), $matches, PREG_SET_ORDER);

        foreach ($matches as $v)
        {
            $attrs[$v[1]] = $v[2];
        }

        return $attrs;        
    }

    /**
     * 将标签数组转化为数组字符串，支持 $变量 、function()函数 和 A::method() 静态方法 
     *
     * @param array $attrs 数组
     * @return code
     */
    public function array_attrs($attrs)
    {
        if (is_array($attrs))
        {
            $str = 'array(';

            foreach ($attrs as $key => $val)
            {
                if (is_array($val))
                {
                    $str .= "'$key'=>" . $this->array_attrs($val) . ",";
                }
                else
                {
                    if ( strpos($val, '$') === 0 or preg_match('/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(.*?\)/', $val) )
                    {
                        $str .= "'$key'=>$val,";
                    }
                    else
                    {
                        $str .= "'$key'=>'" . addslashes($val) . "',";
                    }
                }
            }

            return trim($str, ',') . ')';
        }

        return false;
    }

    /**
     * 自定义模板标签解析
     *
     * @param string $html 匹配到的HTML代码
     * @param string $tag 标签名称
     * @param string $array_attrs 标签属性
     * @param string $end 标签是否自动结束
     *
     * @return string 解析的结果
     */
    public function _beginTag($html, $tag, $str, $end)
    {
        //标签的所有参数都必须以半角（英文）双引号括起来
        preg_match_all('/\s+([a-z_]+)\s*\=\s*\"(.*?)\"/i', stripslashes($str), $matches, PREG_SET_ORDER);

        $callback = template::$tags[$tag]; //回调函数
		$newline = "\r\n";

        if ( function_exists($callback) )
        {
            // 处理标签组中系统标签的 return 和 cache
			foreach ($matches as $k => $v)
            {
                if (in_array($v[1], array('return', 'cache')))
                {
                    $$v[1] = $v[2];
                    continue;
                }

                $attrs[$v[1]] = $v[2];
            }

			// true 使用系统设置缓存时间 0 永久缓存，大于1的数字：缓存有效期
            $cache  = isset($cache) && intval($cache) ? intval($cache) : $cache;
            $return = isset($return) && trim($return) ? trim($return) : 'r';

            if ( $cache )
            {
                $code .= $newline.'if ( null === $' . $callback . ' = zotop::cache(\'' .$tag . md5(stripslashes($html)). '\') ):';
				$code .= $newline.'	if ( $' . $callback . ' = ' . $callback . '(' . $this->array_attrs($attrs) . ') ) :';
                $code .= $newline.'		zotop::cache(\'' .$tag . md5(stripslashes($html)). '\', $' . $callback . ', ' . $cache . ');';
				$code .= $newline.'	endif;';
                $code .= $newline.'endif;';
            }
			else
			{
				$code .= $newline.'$' . $callback . ' = ' . $callback . '(' . $this->array_attrs($attrs) . ');';
			}

			if ( $end )
			{
				$code .= ($return=='r') ? '' : $newline.'$' . $return . ' = $'.$callback.';';
			}
			else
			{
				$code .= $newline.'if ( is_array($' . $callback . ') ):';
				$code .= $newline.'	if ( isset($' . $callback . '[\'total\']) ){ extract($' . $callback . '); $' . $callback . ' = $data; $pagination = pagination::instance($total,$pagesize,$page); }'; // 分页
				$code .= $newline.'	$n=0;'; //编号
				$code .= $newline.'	foreach( $' . $callback . ' as $key => $'.$return.' ):'; //循环
			}
        }

        return '<?php'. $code . $newline . '?>';
    }

    /**
     * 模板标签解析：解析闭合标签
     *
     * @param string $tag 标签名称
     * @param string $array_attrs 标签属性
     * @param string $end 标签是否自动结束
     * @return string 解析的结果
     */
    public function _endTag($tag)
    {
		$newline = "\r\n";
		$code .= $newline.'	$n++;';
		$code .= $newline.'	endforeach;';
		$code .= $newline.'endif;';
        return '<?php' . $code . $newline .'?>';
    }

    /**
     * 将子模板写入当前模板， TODO 子模板自动更新
     * 
     * @param  string $file 子模板路径
     * @return string
     */
    private function loadsubtemplate($file)
    {
    	$file = trim(trim($file,'"'),"'");


    	return $this->render($file);
    }

    /**
     * 视图变量赋值，函数方式
     *
     * @param mixed $name 要显示的视图变量
     * @param mixed $value 变量的值
     * @return void
     */
    public function assign($name = '', $value = '')
    {
        if ($name === '')
        {
            // 返回全部
            return $this->vars;
        }
        elseif ($name === null)
        {
            // 清空
            $this->vars = array();
        }
        elseif (is_array($name) or is_object($name))
        {
            // 多项赋值
            foreach ($name as $key => $val)
            {
                $this->vars[$key] = $val;
            }
        }
        elseif ($value === '')
        {
            // 返回某项数据,不存在返回null
            return isset($this->vars[$name]) ? $this->vars[$name] : null;
        }
        elseif ($value === null)
        {
            // 删除
            unset($this->vars[$name]);
        }
        else
        {
            // 单项赋值
            $this->vars[$name] = $value;
        }

        return $this;
    }

    /**
     * 解析和获取视图内容 用于输出
     *
     * @param string $template 视图文件
     * @param string $content 视图输出内容
     * @return string
     */
    public function render($_template='')
    {
        if ( $_template = $this->compile($_template) )
        {
            // 缓存视图页面
            ob_start();
            ob_implicit_flush(0);

            // 阵列变量分解成为独立变量
            extract($this->vars, EXTR_OVERWRITE);            

            //加载视图文件
            include $_template;

            // 获取并清空缓存
            $content = ob_get_clean();

            // 输出视图文件
            return $content;
        }

        return null;
    }

    /**
     * 加载模板并输出解析后的页面
     *
     * @param string $template 视图文件
     * @param string $content_type 输出类型
     * @param string $charset 输出编码
     * @return mixed
     */
    public function display($template = '', $content_type = 'text/html', $charset = ZOTOP_CHARSET)
    {
        // 网页字符编码
        header('Content-Type:' . $content_type . '; charset=' . $charset);
        // 页面缓存控制
        header('Cache-control: no-cache');
        // Powered
        header('X-Powered-By:zotop');

        // 解析并获取模板内容
        echo $this->render($template);
    }
}
?>