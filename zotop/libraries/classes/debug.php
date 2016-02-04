<?php
defined('ZOTOP') or die('No direct script access.');
/**
 * debug类，
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009-2012 zotop team
 * @license		http://zotop.com/license
 */

class debug
{
    /**
     * 返回多个变量的类型和值，并放在pre标签内
     *
     *     echo debug::vars($a, $b, $c);
     *
     * @param   mixed  待debug的变量
     * @param   ...
     * @return  string
     */
    public static function vars()
    {
        if (func_num_args() === 0) return;

        foreach (func_get_args() as $var)
        {
            $output[] = debug::dump($var, true);
        }

        return '<pre class="debug">' . implode("\n", $output) . '</pre>';
    }

    /**
     * 打印给定变量并结束脚本执行
     *
     * @param   mixed  变量
     * @param   echo  是否返回。默认为直接输出并exit
     * @return  string
     */
    public static function dump($var, $return = false)
    {
        $str = '';

        if ($var === null)
        {
            $str = '<small>NULL</small>';
        }
        elseif (is_bool($var))
        {
            $str = '<small>bool</small> ' . ($var ? 'TRUE' : 'FALSE');
        }
        elseif (is_float($var))
        {
            $str = '<small>float</small> ' . $var;
        }
        else
        {
            $str = '<small>' . gettype($var) . '</small> ' . htmlspecialchars(print_r($var, true), ENT_NOQUOTES, ZOTOP_CHARSET);
        }

		if ( !$return )
		{
			echo '<pre class="debug">' . $str . '</pre>';
			exit();
		}

		return $str;
    }

    /**
     * 格式化输出安全的地址，防止泄露真实地址
     *
     *
     * @param   string  文件路径
     * @return  string
     */
    public static function path($file)
    {
		if (strpos($file, ZOTOP_PATH_CMS) === 0)
        {
            $file = 'zotop:' . DIRECTORY_SEPARATOR . substr($file, strlen(ZOTOP_PATH_CMS));
        }

		if (strpos($file, ZOTOP_PATH) === 0)
        {
            $file = 'zotop:' . DIRECTORY_SEPARATOR . substr($file, strlen(ZOTOP_PATH));
        }

        return $file;
    }

   
    /**
     * 显示高亮的源码片段
     * 
     * @param  string  $file        文件地址
     * @param  integer $line_number 高亮的行
     * @param  integer $padding     前后填充的行数
     * @return string
     */
    public static function source($file, $line_number, $padding = 5)
    {
        if (! $file or ! is_readable($file))
        {
            return false;
        }

        $file = fopen($file, 'r');
        $line = 0;

        $range = array('start' => $line_number - $padding, 'end' => $line_number + $padding);

        $format = '% ' . strlen($range['end']) . 'd';

        $source = '';

        while (($row = fgets($file)) !== false)
        {
            if (++$line > $range['end']) break;

            if ($line >= $range['start'])
            {
                $row = htmlspecialchars($row, ENT_NOQUOTES, ZOTOP_CHARSET);

                $row = '<span class="number">' . sprintf($format, $line) . '</span> ' . $row;

                if ($line === $line_number)
                {
                    $row = '<span class="line highlight">' . $row . '</span>';
                }
                else
                {
                    $row = '<span class="line">' . $row . '</span>';
                }

                $source .= $row;
            }
        }

        fclose($file);

        return '<pre class="source"><code>' . $source . '</code></pre>';
    }

    /**
     * 跟踪
     * 
     * @param   array  追踪
     * @return  array
     */
    public static function trace(array $trace = null)
    {
        if ($trace === null)
        {
            $trace = debug_backtrace();
        }

        $statements = array(
            'include',
            'include_once',
            'require',
            'require_once');

        $output = array();

        foreach ($trace as $step)
        {
            if (! isset($step['function']))
            {
                continue;
            }

            if (isset($step['file']) and isset($step['line']))
            {
                $source = debug::source($step['file'], $step['line']);
            }

            if (isset($step['file']))
            {
                $file = $step['file'];

                if (isset($step['line']))
                {
                    $line = $step['line'];
                }
            }

            $function = $step['function'];

            if (in_array($step['function'], $statements))
            {
                if (empty($step['args']))
                {
                    $args = array();
                }
                else
                {
                    $args = array($step['args'][0]);
                }
            }
            elseif (isset($step['args']))
            {
                if (! function_exists($step['function']) or strpos($step['function'], '{closure}') !== false)
                {
                    $params = null;
                }
                else
                {
                    if (isset($step['class']))
                    {
                        if (method_exists($step['class'], $step['function']))
                        {
                            $reflection = new ReflectionMethod($step['class'], $step['function']);
                        }
                        else
                        {
                            $reflection = new ReflectionMethod($step['class'], '__call');
                        }
                    }
                    else
                    {
                        $reflection = new ReflectionFunction($step['function']);
                    }

                    $params = $reflection->getParameters();
                }

                $args = array();

                foreach ($step['args'] as $i => $arg)
                {
                    if (isset($params[$i]))
                    {
                        $args[$params[$i]->name] = $arg;
                    }
                    else
                    {
                        $args[$i] = $arg;
                    }
                }
            }

            if (isset($step['class']))
            {
                $function = $step['class'] . $step['type'] . $step['function'];
            }

            $output[] = array(
                'function' => $function,
                'args'     => isset($args) ? $args : null,
                'file'     => isset($file) ? $file : null,
                'line'     => isset($line) ? $line : null,
                'source'   => isset($source) ? $source : null,
            );

            unset($function, $args, $file, $line, $source);
        }

        return $output;
    }
}
?>