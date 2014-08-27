<?php
defined('ZOTOP') or die('No direct access allowed.');
/**
 * TREE操作类，用于生成树状结构
 *
 * @package		zotop
 * @class		url_base
 * @author		zotop team
 * @copyright	(c)2009 zotop team
 * @license		http://zotop.com/license.html
 */
class tree
{
    public $nodes = array(); //树形的元数据2维数组
    public $icon = array(
        '│',
        '├',
        '└');
    public $string = '';
    public $data = array();
    public $childs = array();

    /**
     * 构造函数，初始化类
     * @param array 2维数组，例如：
     * array(
     *      array('id'=>'1','parentid'=>'0','title'=>'一级栏目一'),
     *      array('id'=>'2','parentid'=>'0','title'=>'一级栏目二'),
     *      array('id'=>'3','parentid'=>'1','title'=>'二级栏目一'),
     *      array('id'=>'4','parentid'=>'1','title'=>'二级栏目二'),
     *      array('id'=>'5','parentid'=>'2','title'=>'二级栏目三'),
     *      array('id'=>'6','parentid'=>'3','title'=>'三级栏目一'),
     *      array('id'=>'7','parentid'=>'3','title'=>'三级栏目二')
     * )
     */
    public function __construct($nodes = array())
    {
        if (is_array($nodes))
        {
            foreach ($nodes as $node)
            {
                $this->nodes[$node['id']] = $node;
            }
        }

        $this->string = '';
    }

    /**
     * 树形工厂模式
     *
     *     $tree = tree::instance($nodes);
     *
     * @param   string   image file path
     * @param   string   driver type: GD, ImageMagick, etc
     * @return  Image
     */
    public static function instance($nodes = array())
    {
        return new tree($nodes);
    }


    /**
     * 添加节点
     *
     * @param array
     * @return array
     */
    public function add($node)
    {
        if (is_array($node))
        {
            $this->nodes[$node['id']] = array_merge($this->nodes, $node);
            return true;
        }

        return false;
    }

    /**
     * 获取下级子级节点数组
     *
     * @param int|string
     * @return array
     */
    public function getChild($parentid, $nodes = array())
    {
        $child = array();

        $nodes = empty($nodes) ? $this->nodes : $nodes;

        foreach ($nodes as $node)
        {
            if ($node['parentid'] == $parentid)
            {
                $child[$node['id']] = $node;
            }
        }

        return $child;
    }

    /**
     * 获取下级子级节点ID数组
     *
     * @param int|string
     * @return array
     */
    public function getChildID($parentid, $nodes = array())
    {
        return array_keys($this->getChild($parentid, $nodes));
    }

    /**
     * 获取全部子级节点数组
     * @param int|string
     * @return array
     */
    public function getAllChild($parentid, $nodes = array(), &$childs = array())
    {
        $child = $this->getChild($parentid, $nodes);

        if (is_array($child) && ! empty($child))
        {
            $childs = $childs + $child;

            foreach ($child as $c)
            {
                $this->getAllChild($c['id'], $nodes, $childs);
            }
        }

        return $childs;
    }

    /**
     * 获取全部子级节点ID数组
     *
     * @param int|string
     * @return array
     */
    public function getAllChildID($parentid, $nodes = array())
    {
        return array_keys($this->getAllChild($parentid, $nodes));
    }

    /**
     * 得到当前位置的节点数组
     * @param int|string
     * @return array
     */
    public function getParents($id, &$p = array())
    {
        $parents = array();

        if (isset($this->nodes[$id]))
        {
            $p[] = $this->nodes[$id];

            $parentid = $this->nodes[$id]['parentid'];

            if (isset($this->nodes[$parentid]))
            {
                $this->getParents($parentid, $p);
            }

            if (is_array($p))
            {
                krsort($p); //逆向排序

                foreach ($p as $node)
                {
                    $parents[$node['id']] = $node;
                }
            }

        }
        return $parents;
    }

    /**
     * 获取格式化后的数组
     * @param int|string
     * @return array
     */
    public function getTree($id = 0, $level = 0, &$nodes = array())
    {
        $number = 1;

        $nodes = empty($nodes) ? $this->nodes : $nodes;

        $childs = $this->getChild($id, $nodes);

        if (is_array($childs) && ! empty($childs))
        {
            if (isset($this->data[$id]))
            {
                $this->data[$id]['_child'] = count($childs);
            }

            $level = $level + 1;

            foreach ($childs as $child)
            {
                unset($nodes[$child['id']]);

                $this->data[$child['id']] = $child;
                $this->data[$child['id']]['_level'] = $level;
                $this->data[$child['id']]['_last'] = ($number == count($childs)) ? 1 : 0;

                $this->getTree($child['id'], $level, $nodes);
                $number++;
            }
        }

        return $this->data;
    }

}
?>