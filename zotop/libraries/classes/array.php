<?php
defined('ZOTOP') or die('No direct access allowed.');
/**
 * array数组操作类
 *
 * @copyright  (c)2009 zotop team
 * @package    zotop
 * @author     zotop team
 * @license    http://zotop.com/license.html
 */
class arr
{
    public static $tree = array();

    /**
     * Retrieve a single key from an array. If the key does not exist in the
     * array, the default value will be returned instead.
     *
     *     // Get the value "username" from $_POST, if it exists
     *     $username = arr::get($_POST, 'username');
     *
     *     // Get the value "sorting" from $_GET, if it exists
     *     $sorting = arr::get($_GET, 'sorting');
     *
     * @param   array   array to extract from
     * @param   string  key name
     * @param   mixed   default value
     * @return  mixed
     */
    public static function get($array, $key, $default = null)
    {
        return isset($array[$key]) ? $array[$key] : $default;
    }

    /**
     * 从数组中弹出键，返回该键的值并从数组中删除该键，如果弹出多个键，则返回一个由弹出键组成的数组
     *
     * <code>
     *	$array = array('value'=>'1','description'=>'2','label'=>'3');
     *	$a = arr::take($array,'label');
     *	$b = arr::take($array,array('label','description'));
     *	$c = arr::take($array,'label','description');
     *  
     *	$a = 3
     *	$b = $c = array('label'=>'3','description'=>'2')
     *
     *</code>
     *
     * @param array $array 目标数组
     * @param string $key 弹出的键名
     * @param string …… 键名
     *
     * @return $mix	被弹出 的数据
     */
    public static function take(&$array, $key)
    {
        $array = (array )$array;
        $return = array();

        //如果弹出多个，则返回数组，否则返回该键的值
        $result = (is_array($key) or func_num_args() > 2) ? true : false;

        //获取键
        if (!is_array($key))
        {
            $key = array_slice(func_get_args(), 1);
        }

        foreach ($key as $k)
        {
            if (array_key_exists($k, $array))
            {
                $return[$k] = $array[$k];
                unset($array[$k]);
            }
        }

        if (empty($return)) return null;

        if ($result) return $return;

        return reset($return);
    }

    /**
     * 合并多维数组
     *
     *     $john = array('name' => 'john', 'children' => array('fred', 'paul', 'sally', 'jane'));
     *     $mary = array('name' => 'mary', 'children' => array('jane'));
     *
     *     // 合并数组
     *     $john = arr::merge($john, $mary);
     *
     *     // 结果
     *     array('name' => 'mary', 'children' => array('fred', 'paul', 'sally', 'jane'))
     *
     * @param   array  initial array
     * @param   array  array to merge
     * @param   array  ...
     * @return  array
     */
    public static function merge(array $a1, array $a2)
    {
        $result = array();

        for ($i = 0, $total = func_num_args(); $i < $total; $i++)
        {
            // Get the next array
            $arr = func_get_arg($i);

            // Is the array associative?
            $assoc = arr::is_assoc($arr);

            foreach ($arr as $key => $val)
            {
                if (isset($result[$key]))
                {
                    if (is_array($val) and is_array($result[$key]))
                    {
                        if (arr::is_assoc($val))
                        {
                            // Associative arrays are merged recursively
                            $result[$key] = arr::merge($result[$key], $val);
                        }
                        else
                        {
                            // Find the values that are not already present
                            $diff = array_diff($val, $result[$key]);

                            // Indexed arrays are merged to prevent duplicates
                            $result[$key] = array_merge($result[$key], $diff);
                        }
                    }
                    else
                    {
                        if ($assoc)
                        {
                            // Associative values are replaced
                            $result[$key] = $val;
                        }
                        elseif (!in_array($val, $result, true))
                        {
                            // Indexed values are added only if they do not yet exist
                            $result[] = $val;
                        }
                    }
                }
                else
                {
                    // New values are added
                    $result[$key] = $val;
                }
            }
        }

        return $result;
    }

    /**
     * 从数组中删除键，并返回删除该键后的数组
     *
     * @param array $array 目标数组
     * @param string $key 待删除的键名
     * ……
     * @return $mix	被弹出 的数据
     */
    public static function remove($array, $key)
    {
        $array = (array )$array;

        if (!is_array($key))
        {
            $key = array_slice(func_get_args(), 1);
        }

        foreach ($key as $k)
        {
            unset($array[$k]);
        }

        return $array;
    }

    /*
    * 根据键值多维排序
    */
    public static function multisort(&$array, $key, $sort = SORT_ASC)
    {
        if (!is_array($array)) return false;

        foreach ($array as $row)
        {
            if (is_array($row))
            {
                $keys[] = $row[$key];
            }
            else
            {
                return false;
            }
        }

        array_multisort($keys, $sort, $array);

        return $array;
    }

    


    /**
     * trim 数组
     *
     * @param array $array 目标数组
     * @return array
     */
    public static function trim($input)
    {
        if (!is_array($input))
        {
            return trim($input);
        }
        return array_map(array('arr', 'trim'), $input);
    }

    /**
     * 从数组中删除空白的元素（包括只有空白字符的元素）
     *
     * 用法：
     * @code php
     * $arr = array('', 'test', '   ');
     * arr::clear($arr);
     *
     * dump($arr);
     *   // 输出结果中将只有 'test'
     * @endcode
     *
     * @param array $arr 要处理的数组
     * @param boolean $trim 是否对数组元素调用 trim 函数
     */
    public static function clear(&$arr, $trim = true)
    {
        foreach ($arr as $key => $value)
        {
            if (is_array($value))
            {
                arr::clear($arr[$key]);
            }
            else
            {
                $value = trim($value);

                if ($value == '')
                {
                    unset($arr[$key]);
                }
                elseif ($trim)
                {
                    $arr[$key] = $value;
                }
            }
        }
        return $arr;
    }

    /**
     * 从一个二维数组中返回指定键的所有值
     *
     * 用法：
     * @code php
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1'),
     *     array('id' => 2, 'value' => '2-1'),
     * );
     * $values = arr::column($rows, 'value');
     *
     * dump($values);
     *   // 输出结果为
     *   // array(
     *   //   '1-1',
     *   //   '2-1',
     *   // )
     * @endcode
     *
     * @param array $arr 数据源
     * @param string $col 要查询的键
     *
     * @return array 包含指定键所有值的数组
     */
    public static function column($arr, $col)
    {
        $ret = array();

        foreach ($arr as $row)
        {
            if (isset($row[$col]))
            {
                $ret[] = $row[$col];
            }
        }
        return $ret;
    }

    /**
     * 将一个二维数组转换为 HashMap，并返回结果
     *
     * 用法1：
     * @code php
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1'),
     *     array('id' => 2, 'value' => '2-1'),
     * );
     * $hashmap = arr::hashmap($rows, 'id', 'value');
     *
     * dump($hashmap);
     *   // 输出结果为
     *   // array(
     *   //   1 => '1-1',
     *   //   2 => '2-1',
     *   // )
     * @endcode
     *
     * 如果省略 $value_field 参数，则转换结果每一项为包含该项所有数据的数组。
     *
     * 用法2：
     * @code php
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1'),
     *     array('id' => 2, 'value' => '2-1'),
     * );
     * $hashmap = arr::hashmap($rows, 'id');
     *
     * dump($hashmap);
     *   // 输出结果为
     *   // array(
     *   //   1 => array('id' => 1, 'value' => '1-1'),
     *   //   2 => array('id' => 2, 'value' => '2-1'),
     *   // )
     * @endcode
     *
     * @param array $arr 数据源
     * @param string $key_field 按照什么键的值进行转换
     * @param string $value_field 对应的键值
     *
     * @return array 转换后的 HashMap 样式数组
     */
    public static function hashmap(array $arr, $key_field, $value_field = null)
    {
        $ret = array();
        if ($value_field)
        {
            foreach ($arr as $row)
            {
                $ret[$row[$key_field]] = $row[$value_field];
            }
        }
        else
        {
            foreach ($arr as $row)
            {
                $ret[$row[$key_field]] = $row;
            }
        }
        return $ret;
    }

    /**
     * 将一个二维数组按照指定字段的值分组
     *
     * 用法：
     * @code php
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1', 'parent' => 1),
     *     array('id' => 2, 'value' => '2-1', 'parent' => 1),
     *     array('id' => 3, 'value' => '3-1', 'parent' => 1),
     *     array('id' => 4, 'value' => '4-1', 'parent' => 2),
     *     array('id' => 5, 'value' => '5-1', 'parent' => 2),
     *     array('id' => 6, 'value' => '6-1', 'parent' => 3),
     * );
     * $values = arr::group($rows, 'parent');
     *
     * dump($values);
     *   // 按照 parent 分组的输出结果为
     *   // array(
     *   //   1 => array(
     *   //        array('id' => 1, 'value' => '1-1', 'parent' => 1),
     *   //        array('id' => 2, 'value' => '2-1', 'parent' => 1),
     *   //        array('id' => 3, 'value' => '3-1', 'parent' => 1),
     *   //   ),
     *   //   2 => array(
     *   //        array('id' => 4, 'value' => '4-1', 'parent' => 2),
     *   //        array('id' => 5, 'value' => '5-1', 'parent' => 2),
     *   //   ),
     *   //   3 => array(
     *   //        array('id' => 6, 'value' => '6-1', 'parent' => 3),
     *   //   ),
     *   // )
     * @endcode
     *
     * @param array $arr 数据源
     * @param string $key_field 作为分组依据的键名
     *
     * @return array 分组后的结果
     */
    public static function group($arr, $key_field)
    {
        $ret = array();
        foreach ($arr as $row)
        {
            $key = $row[$key_field];
            $ret[$key][] = $row;
        }
        return $ret;
    }

    /**
     * 将无限分类数组转化为树
     *
     * 用法：
     * @code php
     * $data = array(
     *     array('id' => 1, 'parentid' => 0, 'name' => '1-0'),
     *     array('id' => 5, 'parentid' => 0, 'name' => '5-0'),
     *     array('id' => 2, 'parentid' => 1, 'name' => '2-1'),
     *     array('id' => 3, 'parentid' => 1, 'name' => '2-1'),
     *     array('id' => 4, 'parentid' => 3, 'name' => '4-3'),	 
     *     array('id' => 6, 'parentid' => 5, 'name' => '6-5'),
     * );
     *
     * $values = arr::tree($data, 0);
     *
     * @endcode
     *
     * @param array $data   //无限分类数组(传入前需要对无限分类数组的 parentid 进行 ASC 排序，数据库读取数据排序或者使用arr::multisort($data,'parentid'))
     * @param int $rootid	//初始的父编号，一般为0             
     * @param int $id		//编号键名
     * @param int $parentid //父编号键名
     * @param int $level    //分类级别
     * @return array $tree 
     */
    public static function tree(&$data, $rootid = 0, $id = "id", $parentid = "parentid", $level = 0)
    {
        foreach ($data as $k => $v)
        {
            if ($v[$parentid] == $rootid)
            {
                $v['_level'] = $level;
                self::$tree[] = $v;
                unset($data[$k]);
                self::tree($data, $v[$id], $id, $parentid, $level + 1);
            }
        }

        return self::$tree;
    }

    /**
     * 在数组的开头插入数组
     *
     *     arr::unshift($array, 'key', 'value');
     *
     * @param   array   array to modify
     * @param   string  array key name
     * @param   mixed   array value
     * @return  array
     */
    public static function unshift(array & $array, $key, $val = '')
    {

        // 插入的数组
        $insert = is_array($key) ? $key : array($key => $val);

        $array = array_reverse($array, true);
        $array = array_merge($array, $insert);
        $array = array_reverse($array, true);

        return $array;
    }

    /**
     * 在数组组的特定位置插入数组
     *
     * @param array $array 原始数组
     * @param int $i 插入位置
     * @param string||array $key 插入的数组键名 或者数组
     * @param mixed  $val 插入的数组值
     * @return array 插入后的数组
     */
    public static function insert(array & $array, $i, $key, $val = '')
    {
        // 如果数组已经存在，则删除原数组中的数据
        if (in_array($key, $array, true))
        {
            unset($array[$key]);
        }

        // 插入的数组
        $insert = is_array($key) ? $key : array($key => $val);

        // 将数组插入到新的位置
        $array = array_merge(array_slice($array, 0, $i), $insert, array_slice($array, $i));

        return $array;
    }

    /**
     * 将数组插入到特定的键前面,如果比对的键名不存在，则直接插入到末尾
     *
     * @param array $array 原始数组
     * @param string $existing 比对键名
     * @param string $key 插入的数组键名
     * @param mixed  $val 插入的数组值
     * @return array 插入后的数组
     */
    public static function before(array & $array, $existing, $key, $val = '')
    {
        if (false === $i = array_search($existing, array_keys($array)))
        {
            $array[$key] = $val;
            return $array;
        }

        return self::insert($array, $i, $key, $val);
    }

    /**
     * 将数组插入到特定的键后面,如果比对的键名不存在，则直接插入到末尾
     *
     * @param array $array 原始数组
     * @param string $existing 比对键名
     * @param string $key 插入的数组键名
     * @param mixed  $val 插入的数组值
     * @return array 插入后的数组
     */
    public static function after(array & $array, $existing, $key, $val = '')
    {
        if (false === $i = array_search($existing, array_keys($array)))
        {
            $array[$key] = $val;
            return $array;
        }

        return self::insert($array, $i + 1, $key, $val);
    }


}
?>