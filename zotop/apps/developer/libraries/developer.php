<?php
defined('ZOTOP') or die('No direct script access.');
/**
 * 开发助手类
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009-2012 zotop team
 * @license		http://zotop.com/license
 */

class developer
{
     /**
     * 读取程序模板文件，替换相应标签后写入项目文件夹中
     *
     *
     * @param mixed $source 源文件
     * @param mixed $target 目标文件
     * @param mixed $data 替换数据
     * @return void
     */
    public static function make_file($source, $target, $app, $data)
    {
        if ( file::exists($target) ) return false;

        if ( file::exists($source) )
        {
            $str = file::get($source);

            // 替换app参数
            foreach ($app as $key => $val)
            {
                $str = str_replace('[app.' . $key . ']', $val, $str);
            }            

            // 替换其它参数
            foreach ($data as $key => $val)
            {
                $str = str_replace('[' . $key . ']', $val, $str);
            }

            // HOOK
            $str = zotop::filter('developer.make_file', $str, $app, $data);

            return file::put($target, $str);
        }

        return false;
    }

    /**
     * 默认的控制器模板
     * 
     * @param  string $table 表名称
     * @return array
     */
    public static function controllers()
    {
        return zotop::filter('developer.controllers',array(
            A('developer.path').DS.'source'.DS.'controllers'.DS.'site.php'        => t('前台控制器-基础'),
            A('developer.path').DS.'source'.DS.'controllers'.DS.'admin.php'       => t('后台控制器-基础'),     
            A('developer.path').DS.'source'.DS.'controllers'.DS.'admin_model.php' => t('后台模型控制器-[列表-添加-编辑-删除]'),
        ));
    }     
}
?>