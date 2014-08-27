<?php
defined('ZOTOP') or die('No direct script access.');
/**
 * zotop core
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009-2012 zotop team
 * @license		http://zotop.com/license
 */

class runtime
{
    /**
     * 自动创建运行时, 打包核心类库，全局文件及配置文件
     *
     * @return void
     */
    public static function build()
    {
        //注册核心类库
        zotop::register(null);
        zotop::register(include(ZOTOP_PATH_CMS . DS . 'preload.php'));

        // 注册全局文件
        $global     = array();
        $global[]   = ZOTOP_PATH_CMS . DS . 'global.php';

        // 加载已经安装的 app下的预加载和全局文件
        $app        = @include(ZOTOP_PATH_CONFIG . DS . 'app.php');

        foreach ($app as $a)
        {

            if ($a['status'] <= 0) continue;

            //注册核心文件
            if ( file_exists(ZOTOP_PATH_APPS . DS . $a['dir'] . DS . 'preload.php') )
            {
                zotop::register(include (ZOTOP_PATH_APPS . DS . $a['dir'] . DS . 'preload.php'));
            }

            //注册全局文件
            if ( file_exists(ZOTOP_PATH_APPS . DS . $a['dir'] . DS . 'global.php') )
            {
                $global[] = ZOTOP_PATH_APPS . DS . $a['dir'] . DS . 'global.php';
            }
        }

        //加载系统配置
        zotop::config('zotop',    @include(ZOTOP_PATH_CONFIG.DS.'zotop.php'));
        zotop::config('database', @include(ZOTOP_PATH_CONFIG.DS.'database.php'));
        zotop::config('app',      $app);
        zotop::config('router',   @include(ZOTOP_PATH_CONFIG.DS."router.php"));
        zotop::config('cookie',   @include(ZOTOP_PATH_CONFIG.DS."cookie.php"));
        zotop::config('session',  @include(ZOTOP_PATH_CONFIG.DS."session.php"));

        zotop::config('system',   @include(ZOTOP_PATH_CONFIG.DS."system.php"));
        zotop::config('site',     @include(ZOTOP_PATH_CONFIG.DS."site.php"));

        //打包全局文件、类库文件及配置
        @file_put_contents(ZOTOP_PATH_RUNTIME . DS . 'preload.php', runtime::compile(zotop::register()));
        @file_put_contents(ZOTOP_PATH_RUNTIME . DS . 'global.php', runtime::compile($global));
        @file_put_contents(ZOTOP_PATH_RUNTIME . DS . 'config.php', "<?php\nreturn ".var_export(zotop::config(), true).";\n?>");

        //加载全局文件
        zotop::load(ZOTOP_PATH_RUNTIME . DS . 'global.php');
    }

    /**
     * 编译文件，将多个文件打包在一个文件中，并去除注释
     *
     * @param array|string $files 一个或者多个文件路径
     * @return string
     */
    public static function compile($files)
    {
        $string = '';

        foreach ((array )$files as $file)
        {
            if (file_exists($file))
            {
                $content = file_get_contents($file);

                //strip <?php
                $content = substr(trim($content), 5);

                if (strtolower(substr($content, -2)) == '?>')
                {
                    $content = substr($content, 0, -2);
                }

                $string .= $content;
            }
        }

        unset($content);

        return runtime::strip_whitespace("<?php\n" . $string . "\n?>");
    }

    /**
     * 去除代码中的空白和注释
     *
     * @param string $str 代码
     * @return string
     */
    public static function strip_whitespace($str)
    {
        $stripStr = '';

        //分析php源码
        $tokens = token_get_all($str);

        //末尾空格
        $last_space = false;

        for ($i = 0, $j = count($tokens); $i < $j; $i++)
        {
            if (is_string($tokens[$i]))
            {
                $last_space = false;
                $stripStr .= $tokens[$i];
            }
            else
            {
                switch ($tokens[$i][0])
                {
                        //过滤各种PHP注释
                    case T_COMMENT:
                    case T_DOC_COMMENT:
                        break;
                        //过滤空格
                    case T_WHITESPACE:
                        if (! $last_space)
                        {
                            $stripStr .= ' ';
                            $last_space = true;
                        }
                        break;
                    default:
                        $last_space = false;
                        $stripStr .= $tokens[$i][1];
                        break;
                }
            }
        }

        unset($tokens);

        return $stripStr;
    }
}
