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
            $output[] = debug::dump($var, false);
        }

        return '<pre class="debug">' . implode("\n", $output) . '</pre>';
    }

    /**
     * 返回变量的内容
     *
     * @param   mixed    变量
     * @param   echo  返回
     * @return  string
     */
    public static function dump($var, $echo = null)
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

		if ( $echo === null or $echo === true )
		{
			echo '<pre class="debug">' . $str . '</pre>';

			if ( $echo ) exit();
		}

		return $str;
    }

    /**
     * Removes root from a filename, replacing them with the plain text equivalents. Useful for debugging
     *
     *
     * @param   string  path to debug
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
     * Returns an HTML string, highlighting a specific line of a file, with some
     * number of lines padded above and below.
     *
     *     // Highlights the current line of the current file
     *     echo Debug::source(__FILE__, __LINE__);
     *
     * @param   string   file to open
     * @param   integer  line number to highlight
     * @param   integer  number of padding lines
     * @return  string   source of file
     * @return  FALSE    file is unreadable
     */
    public static function source($file, $line_number, $padding = 5)
    {
        if (! $file or ! is_readable($file))
        {
            // Continuing will cause errors
            return false;
        }

        // Open the file and set the line position
        $file = fopen($file, 'r');
        $line = 0;

        // Set the reading range
        $range = array('start' => $line_number - $padding, 'end' => $line_number + $padding);

        // Set the zero-padding amount for line numbers
        $format = '% ' . strlen($range['end']) . 'd';

        $source = '';
        while (($row = fgets($file)) !== false)
        {
            // Increment the line number
            if (++$line > $range['end']) break;

            if ($line >= $range['start'])
            {
                // Make the row safe for output
                $row = htmlspecialchars($row, ENT_NOQUOTES, ZOTOP_CHARSET);

                // Trim whitespace and sanitize the row
                $row = '<span class="number">' . sprintf($format, $line) . '</span> ' . $row;

                if ($line === $line_number)
                {
                    // Apply highlighting to this row
                    $row = '<span class="line highlight">' . $row . '</span>';
                }
                else
                {
                    $row = '<span class="line">' . $row . '</span>';
                }

                // Add to the captured source
                $source .= $row;
            }
        }

        // Close the file
        fclose($file);

        return '<pre class="source"><code>' . $source . '</code></pre>';
    }

    /**
     * Returns an array of HTML strings that represent each step in the backtrace.
     *
     *     // Displays the entire current backtrace
     *     echo implode('<br/>', Debug::trace());
     *
     * @param   string  path to debug
     * @return  string
     */
    public static function trace(array $trace = null)
    {
        // Start a new trace
        if ($trace === null)
        {
            $trace = debug_backtrace();
        }

        // Non-standard function calls
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
                // Invalid trace step
                continue;
            }

            if (isset($step['file']) and isset($step['line']))
            {
                // Include the source of this step
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

            // function()
            $function = $step['function'];

            if (in_array($step['function'], $statements))
            {
                if (empty($step['args']))
                {
                    // No arguments
                    $args = array();
                }
                else
                {
                    // Sanitize the file path
                    $args = array($step['args'][0]);
                }
            }
            elseif (isset($step['args']))
            {
                if (! function_exists($step['function']) or strpos($step['function'], '{closure}') !== false)
                {
                    // Introspection on closures or language constructs in a stack trace is impossible
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

                    // Get the function parameters
                    $params = $reflection->getParameters();
                }

                $args = array();

                foreach ($step['args'] as $i => $arg)
                {
                    if (isset($params[$i]))
                    {
                        // Assign the argument by the parameter name
                        $args[$params[$i]->name] = $arg;
                    }
                    else
                    {
                        // Assign the argument by number
                        $args[$i] = $arg;
                    }
                }
            }

            if (isset($step['class']))
            {
                // Class->method() or Class::method()
                $function = $step['class'] . $step['type'] . $step['function'];
            }

            $output[] = array(
                'function' => $function,
                'args' => isset($args) ? $args : null,
                'file' => isset($file) ? $file : null,
                'line' => isset($line) ? $line : null,
                'source' => isset($source) ? $source : null,
                );

            unset($function, $args, $file, $line, $source);
        }

        return $output;
    }

    /**
     * 运行速度和占用内存分析
     *
     * 使用方法:
     * <code>
     * debug::profiler('begin'); // 记录开始标记位
     * // ...
     * debug::profiler('end'); // 记录结束标签位
     *
     * echo debug::profiler('begin','end'); // 统计区间运行时间及内存占用
     *
     * </code>
     * @param string $start 开始标签
     * @param string $end 结束标签，如果没有定义，则会自动以当前作为标记位
     * @return mixed
     */
    function profiler($start, $end = '')
    {
        static $states = array();

        if (empty($end))
        {
            $states['t'][$start] = microtime(true);
            $states['m'][$start] = memory_get_usage();
        }
        else
        {
            if (! isset($states['t'][$end])) $states['t'][$end] = microtime(true);
            if (! isset($states['m'][$end])) $states['m'][$end] = memory_get_usage();

            return number_format($states['t'][$end] - $states['t'][$start], 6) . 'S ' . number_format(($states['m'][$end] - $states['m'][$start]) / 1024) . 'KB';
        }
    }
}
?>