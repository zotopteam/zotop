<?php
defined('ZOTOP') or die('No direct access allowed.');
/**
 * 验证类
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009 zotop team
 * @license		http://zotop.com/license.html
 *
 *
 *
 *       $data = array(
 *         'title' => '测试一下',
 *         'price' => 0
 *        );
 *
 *        validator::valid($data, array(
 *         'title' => 'required|minlength:10|msg-label:标题',
 *         'price' => 'min:1|msg-label:价格|msg-min:最小价格为$param',
 *        ));
 *
 *        dd(validator::error());
 * 
 */
class validator
{
    

    /**
     * 验证中产生错误
     * 
     * @var array
     */
    protected static $error    = array();     

    
    /**
     * 验证规则
     * 
     * @var array
     */
    protected static $rules    = array();

    /**
     * 验证方法
     * 
     * @var array
     */
    protected static $callback = array(); 

    /**
     * 验证提示
     * 
     * @var array
     */
    protected static $messages = array(); 

    /**
     * 默认错误
     * 
     * @var array
     */
    protected static $defult_messages = array(
        'required'          => '$label不能为空',
        'date'              => '$label必须是日期格式',
        'digits'            => 'Please enter only digits.',
        'email'             => 'Please enter a valid email address.',
        'equalto'           => 'Please enter the same value again.',
        'maxlength'         => 'Please enter less characters.',
        'max'               => 'Please enter a more little value.',
        'minlength'         => 'Please enter more characters.',
        'min'               => '$label不能小于$param',
        'number'            => 'Please enter a valid number.',
        'rangelength'       => 'Please enter a value between with a number of characters in the range.',
        'range'             => 'Please enter a value btween {0} and {1}.',
        'url'               => 'Please enter a valid URL.',
    );

    /**
     * 扩展一个验证规则
     * 
     * @param  string $name     验证规则名称
     * @param  function $callback 回调验证函数
     * @param  string $message  默认错误信息
     * @return bool
     */
    public function extend($name, $callback, $message)
    {
        $name = strtolower($name);

        if ( in_array($name, array('msg','label')) OR substr($name, 0, 4)=='msg-' )
        {
            throw new Exception(t('$1系统已经占用扩展名',$name));            
        }

        self::$callback[$name]        = $callback;
        self::$defult_messages[$name] = $message;

        return true;      
    }       
    

    /**
     * 解析验证规则
     *
     * 
     * @param  array $rules 规则列表
     * @return array
     */
    public function parse_rules($rules)
    {   
        $result = array();

        foreach ($rules as $key => $rule)
        {
            $key = strtolower($key);

            if ( is_string($rule) )
            {
                foreach ( explode('|', $rule) as $rulestring )
                {
                    list($k, $v) = explode(':', $rulestring, 2);

                    $k = strtolower($k);
                    
                    if ( $key == 'msg' or $key == 'label' )
                    {
                        // msg和label为特殊标记
                        self::$messages[$key][$k] = $v;
                    }
                    elseif ( substr($k,4) == 'msg-' )
                    {
                        // msg-开头为对应规则的消息 如：msg-min, msg-required
                        self::$messages[$key][substr($k,4)] = $v;
                    }
                    else
                    {
                        // 规则和参数，如果参数中含有逗号，自动分割为数组
                        if ( $k != 'regex' and is_string($v) and strpos($v, ',') !== false )
                        {
                            $v = explode(',', $v);
                        }

                        $result[$key][$k] = $v;   
                    }
                                      
                }
            }
        }

        return $result;
    }

    /**
     * 解析消息
     * 
     * @param  [type] $messages [description]
     * @return [type]           [description]
     */
    public function parse_messages($messages)
    {
        return $messages;
    }

    /**
     * 验证一个数组数据
     * 
     * @param  mixed  $data     待验证的数据数组
     * @param  array  $rules    验证规则
     * @param  array  $messages [description]
     * @return bool
     */
    public static function valid($data, $rules=array(), $messages=array())
    {
        self::$error    = array();
        self::$messages = $messages;
        self::$rules    = self::parse_rules($rules);

        foreach (self::$rules as $index => $rules)
        {
            $value = arr::get($data, $index);

            foreach ($rules as $rule => $param)
            {
                $callback = self::$callback[$rule] ? self::$callback[$rule] : "validator::is_{$rule}";

                if ( is_callable($callback) && !$callback($value, $param) )
                {
                    self::error($index,$rule,$param,$value);
                }
            }
        }

        return count(self::$error) === 0;
    }


    public static function error($index=null, $rule=null, $param=null, $value=null)
    {
        if ( $index && $rule )
        {
            $replace = array(
                'index' => $index,
                'label' => self::$messages[$index]['label'] ? self::$messages[$index]['label'] : $index,
                'param' => $param,
                'value' => $value,
            );

            if ( self::$messages[$index]['msg'] )
            {
                $message = self::$messages[$index]['msg'];
            }
            elseif (self::$messages[$index][$rule])
            {
                $message = self::$messages[$index][$rule];
            }
            else
            {
                $message = self::$defult_messages[$rule];
            }          

            self::$error[] = t($message,$replace);

            return false;
        }

        return self::$error;
    }

    /**
     * 验证规则 是否必填
     * 
     * @param  mixed $value 待验证的值
     * @param  mixed $param 验证参数
     * @return bool 验证结果
     */
    public static function is_required($value, $param=null)
    {
        if ( is_null($value) )
        {
            return false;
        }
        elseif (is_string($value) && trim($value) === '')
        {
            return false;
        }
        elseif ( is_array($value) && count($value) < 1)
        {
            return false;
        }

        return true;
    }

    /**
     * 验证规则 最小值
     * 
     * @param  mixed $value 待验证的值
     * @param  mixed $param 验证参数
     * @return bool 验证结果
     */
    public static function is_min($value, $param)
    {
        return is_numeric($value) && $value >= $param;
    }

    /**
     * 验证规则 最大值
     * 
     * @param  mixed $value 待验证的值
     * @param  mixed $param 验证参数
     * @return bool 验证结果
     */
    public static function is_max($value, $param)
    {
        return is_numeric($value) && $value <= $param;
    }


    /**
     * 验证规则 最大长度
     * 
     * @param  mixed $value 待验证的值
     * @param  mixed $param 验证参数
     * @return bool 验证结果
     */
    public static function is_maxlength($value, $param)
    {
        return isset($value{$param}) === false;
    }

    /**
     * 验证规则 最小长度
     * 
     * @param  mixed $value 待验证的值
     * @param  mixed $param 验证参数
     * @return bool 验证结果
     */
    public static function is_minlength($value, $param)
    {
        return isset($value{--$param});
    }    

    /**
     * 验证规则 是否为数字
     * 
     * @param  mixed $value 待验证的值
     * @param  mixed $param 验证参数
     * @return bool 验证结果
     */
    public static function is_number($value)
    {
        return is_null($value) || is_numeric($value);
    }

    /**
     * 验证规则 是否为字符串
     * 
     * @param  mixed $value 待验证的值
     * @param  mixed $param 验证参数
     * @return bool 验证结果
     */
    public static function is_string($value)
    {
        return is_null($value) || is_string($value);
    }    

    /**
     * 验证规则  正则验证
     * 
     * @param  mixed $value 待验证的值
     * @param  mixed $param 验证参数
     * @return bool 验证结果
     */    
    public static function is_regex($value, $param)
    {
        return preg_match($param, $value) == 1;
    } 

    /**
     * 验证规则 日期
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public static function is_date($value)
    {
        if ( strtotime($value) === false )
        {
            return false;
        }

        $date = date_parse($value);

        return checkdate($date['month'], $date['day'], $date['year']);
    }

    /**
     * 验证规则 IP
     *
     * @param  mixed   $value
     * @return bool
     */
    public static function is_ip($value)
    {
        return filter_var($value, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * 验证规则 邮件
     *
     * @param  mixed   $value
     * @return bool
     */
    public static function is_email($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }    
}
?>