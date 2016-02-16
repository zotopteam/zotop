<?php
defined('ZOTOP') or die('No direct access allowed.');
/**
 * 验证类
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009 zotop team
 * @license		http://zotop.com/license.html
 */
class validate
{
    
    //  /**
    //  * 待验证的数据
    //  * 
    //  * @var array
    //  */
    //  protected $data   = array();
     
    //  /**
    //  * 验证规则
    //  * @var array
    //  */
    //  protected $rules  = array();


    //  /**
    //   * 验证消息
    //   * 
    //   * @var array
    //   */
    //  protected $messages = array();

    /**
     * 返回的错误
     * 
     * @var array
     */
    protected static $error    = array();     


    /**
     * 默认错误
     * 
     * @var array
     */
    protected static $messages = array(
        'required'          => 'This is a required field',
        'date'              => 'Please enter a valid date.',
        'digits'            => 'Please enter only digits.',
        'email'             => 'Please enter a valid email address.',
        'equalTo'           => 'Please enter the same value again.',
        'maxlength'         => 'Please enter less characters.',
        'max'               => 'Please enter a more little value.',
        'minlength'         => 'Please enter more characters.',
        'min'               => 'Please enter a greater value.',
        'number'            => 'Please enter a valid number.',
        'rangelength'       => 'Please enter a value between with a number of characters in the range.',
        'range'             => 'Please enter a value btween {0} and {1}.',
        'required'          => 'This field is required.',
        'url'               => 'Please enter a valid URL.',
    );

    /**
     * 验证方法
     * 
     * @var array
     */
    protected static $callback = array();


    /**
     * 添加一个新的验证方法
     * 
     * @param String $name 验证方法名称
     * @param String $callback  回调函数
     * @param String $message 默认的错误消息
     */
    public static function extend($rule, $callback, $message = null)
    {
        self::callback[$rule] = $callback;
        self::messages[$rule] = $message;
    }     
        
    
    // public function __construct($data, $rules=array(), $messages=array())
    // {
    //     $this->data     = $data;        
    //     $this->messages = array_merge(self::default_messages, $messages);
    //     $this->rules    = $this->parse_rules($rules);
    // }

    /**
     * 验证一个数组数据
     * 
     * @param  [type] $data     [description]
     * @param  array  $rules    [description]
     * @param  array  $messages [description]
     * @return [type]           [description]
     */
    public static function valid($data, $rules=array(), $messages=array())
    {
        self::error    = array();
        self::data     = $data;
        self::messages = array_merge(self::messages, $messages);
        self::rules    = self::parse_rules($rules);

        foreach (self::rules as $index => $rules)
        {
            self::check(arr::get($data,$index), $rules);
        }

        return count(self::error) === 0;
    }

    /**
     * 验证单项数据
     * 
     * @param  [type] $data  [description]
     * @param  [type] $rules [description]
     * @return [type]        [description]
     */
    public static function check($data, $rules)
    {
        foreach ($rules as $rule => $param)
        {
            if ( $callback = self::callback[$rule] )
            {
                call_user_func_array($callback, $param);
            }

            // 调用默认规则
            // if ( $rule = "rule_{$type}" and method_exists('form',$type) )
            // {
            //     return form::$type($field);
            // }            
        }
    }

    /**
     * 解析验证规则
     *
     * 
     * @param  [type] $rules [description]
     * @return [type]        [description]
     */
    public function parse_rules($rules)
    {   
        return $rules;
    }


    // 添加规则
    // public function rules()
    // {

    // }


    /**
     * 添加消息
     * 
     * @param  array  $messages [description]
     * @return [type]           [description]
     */
    // public function messages($messages=array())
    // {}

}
?>