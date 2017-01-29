<?php
defined('ZOTOP') or die('No direct access allowed.');
/**
 * content_category
 *
 * @package     system
 * @author      zotop team
 * @copyright   (c)2009-2011 zotop team
 * @license     http://zotop.com/license.html
 */
class content_model_category extends model
{
    protected $pk = 'id';
    protected $table = 'content_category';
    protected $category = array();
    public $types = array();

    /**
     * 初始化函数
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->types = array(
            0 => t('栏目'),
            1 => t('单页面'),
            2 => t('链接')
        );

        // 获取全部数据
        $this->category = $this->cache();
    }

    /**
     * 栏目数据获取，可以获取整个栏目数据或者获取栏目的某个键值的数据
     *
     * @param mixed $id 栏目编号
     * @param string $field 字段名称
     * @param string $key 如果字段是数组，数组键名
     * @return
     */
    public function get($id= null, $field = '', $key = '')
    {
        if (isset($id))
        {
            $id = intval($id);

            if ( $key )
            {
                return isset($this->category[$id][$field][$key]) ? $this->category[$id][$field][$key] : null;
            }

            if ( $field )
            {
                return isset($this->category[$id][$field]) ? $this->category[$id][$field] : null;
            }            

            return isset($this->category[$id]) ? $this->category[$id] : array();
        }

        return $this->category;
    }

    /**
     * 插入
     *
     */
    public function add($data)
    {

        // 添加前验证
        if ( empty($data['name']) ) return $this->error(t('名称不能为空'));

        // 检查别名是否存在
        if ( $data['alias'] and alias($data['alias']) ) return $this->error(t('别名已经存在'));

        $data['id']        = $this->max('id') + 1;
        $data['parentid']  = is_numeric($data['parentid']) ? max(0, $data['parentid']) : 0;
        $data['rootid']    = is_numeric($data['rootid']) ? $data['rootid'] : $data['id'];
        $data['listorder'] = $this->where('parentid', '=', $data['parentid'])->max('listorder') + 1;


        if ($id = $this->insert($data))
        {
            // 插入别名
            alias($data['alias'], "content/index/{$id}");

            // 保存关联附件
            m('system.attachment')->setRelated("content-category-{$id}");

            // 修复栏目数据
            $this->category[$id] = $data;
            $this->repair();

            return $id;
        }

        return false;
    }

    /**
     * 修改
     *
     */
    public function edit($data, $id)
    {
        if ( empty($data['name']) ) return $this->error(t('名称不能为空'));

        if ( $data['alias'] and $alias = alias($data['alias']) )
        {
            if ( $alias != "content/index/{$id}" ) return $this->error(t('别名已经存在').$alias);
        }

        if ( $this->update($data, $id) )
        {
            // 别名
            alias($data['alias'], "content/index/{$id}");

            // 复制设置到子栏目
            if ( $data['apply-setting-childs'] )
            {
                $childids = $this->get($id,'childids');
                $applay   = $this->where('id','in', explode(',', $childids))->data('settings', $data['settings'])->update();
            }

            // 数据关系
            $this->cache(true);

            return $id;
        }

        return false;
    }

    /**
     * 删除
     *
     */
    public function delete($id)
    {
        // 如果有子节点，禁止删除
        if ( $this->where('parentid', '=', $id)->count() )
        {
            return $this->error(t('该栏目下尚有子栏目，无法删除'));
        }

        // 如果有内容数据，禁止删除
        if ($this->datacount($id))
        {
            return $this->error(t('该栏目下尚有数据，无法删除'));
        }

        if ( parent::delete($id) )
        {
            // 删除别名
            alias(null, "content/index/{$id}");

            // 修复栏目数据
            $this->category[$id] = null;
            $this->repair();

            return true;
        }

        return false;
    }

    /*
    * 重新统计栏目数据
    *
    * @param string $id 栏目编号，多个编号之间使用“,”隔开
    * @return string 缓存数据
    */
    public function datacount($id)
    {
        $count = m('content.content')->where('categoryid', 'in', explode(',', $id))->count();

        return $count;
    }

    /**
     * 获取排序过的全部数据
     *
     */
    public function select()
    {
        static $result = array();

        if (empty($result))
        {
            $data = $this->db()->orderby('listorder', 'asc')->select();

            foreach ($data as &$d)
            {
                $d['settings']  = unserialize($d['settings']);
                $d['url']       = empty($d['alias']) ? U("content/index/{$d['id']}") : U($d['alias']);

                $result[$d['id']] = $d;
            }
        }

        return $result;
    }

    /**
     * 获取有效的栏目
     *
    * @param int $id 栏目编号，默认为获取全部有效栏目
    * @return array 缓存数据
     */
    public function active($id=0)
    {
        static $data = array();

        if ( empty($data) )
        {
            foreach ($this->category as $i => $c)
            {
                if ($c['disabled']) continue;

                $data[$i] = $c;
            }

            $data = tree::instance($data)->getTree($id);
        }

        return $data;
    }

    /**
     * 获取可投稿栏目
     *
     */
    public function contribute()
    {
        static $data = array();

        if (empty($data))
        {
            foreach ($this->active() as $i => $c)
            {
                if ( $c['disabled'] or $c['settings']['contribute'] == 0 ) continue;

                $data[$i] = $c;
            }
        }

        return $data;
    }

    /**
     * 获取全部父分类节点数据
     *
     * @param int $id
     * @return array
     */
    public function getParents($id)
    {
        $parents = array();
        $id      = intval($id);

        if ( $id and isset($this->category[$id]) )
        {
            $parentids = $this->category[$id]['parentids'];
            $parentids = explode(',', $parentids);

            foreach ($parentids as $parentid)
            {
                if ( $c = $this->category[$parentid] )
                {
                    $parents[$parentid] = $c;
                }
            }
        }
        
        return $parents;
    }


    /**
     * 获取子节点，只获取下一级的节点，不递归
     * 
     * @param  int $id     栏目编号
     * @param  bool $enable 是否只获取启用的栏目
     * @return array 栏目数组
     */
    public function getChild($id, $enable=null)
    {
        $child = array();

        foreach ($this->category as $i => $c)
        {
            if ( intval($id) == $c['parentid'] )
            {
                if ( $enable and $c['disabled'])
                {
                    continue;
                }

                $child[$i] = $c;
            }
        }

        return $child;
    }


    /**
     * 修复栏目数据
     */
    public function repair()
    {
        @set_time_limit(600);


        foreach ($this->category as $id => $c)
        {
            $parentids = $this->getParentIDs($id); // 获取父栏目串
            $childids = $this->getChildIDs($id); // 获取全部子栏目
            $childid = $this->getChildID($id); // 获取下级子栏目
            $rootid = reset(explode(',', $parentids)); //获取父编号串的第一个作为根编号

            // 如果有更新则更新数据
            if ($this->category[$id]['parentids'] != $parentids or $this->category[$id]['childids'] != $childids or $this->category[$id]['childid'] != $childid)
            {
                $this->update(array(
                    'parentids' => $parentids,
                    'childids' => $childids,
                    'childid' => $childid,
                    'rootid' => $rootid), $id);
            }
        }

        $this->cache(true);

        return true;
    }

    /**
     * 获取全部父分类的编号字符串(包含自身编号)，如：1,2,3,4,6,7
     *
     * @param int $id
     * @return string
     */
    private function getParentIDs($id, &$parentids = array())
    {
        if ( $id and isset($this->category[$id]))
        {
            // 将自身加入输入
            $parentids[] = $id;

            // 节点父编号
            if ($parentid = $this->category[$id]['parentid'])
            {
                $parentids[] = $parentid;
                $this->getParentIDs($this->category[$parentid]['parentid'], $parentids);
            }
        }

        return implode(',', array_reverse($parentids, true));
    }


    /*
    * 获取子级栏目编号串，不包含第三级类别的编号
    *
    * @param int $id
    * @return string
    */
    private function getChildID($id)
    {
        $childid = array();

        foreach ( $this->category as $c )
        {
            if ($c['parentid'] == $id)
            {
                $childid[] = $c['id'];
            }
        }

        return implode(',', $childid);
    }


    /*
    * 获取全部的子分类的编号字符串（包含自身编号），如：1,2,3,4
    *
    * @param array $id
    * @return string
    */
    private function getChildIDs($id)
    {
        $childids = $id;


        if (isset($this->category[$id]))
        {
            foreach ($this->category as $c)
            {
                if ($c['parentid'] and $c['id'] != $id and $c['parentid'] == $id)
                {
                    $childids .= ',' . $this->getChildIDs($c['id']);
                }
            }
        }


        return $childids;
    }

    /**
     * 移动栏目
     *
     * @param $int $id category for move
     * @param $int $parentid new category parentid
     * @return bool
     */
    public function move($id, $parentid)
    {

        // 无法将栏目移动到自己下面
        if ($id == $parentid)
        {
            return $this->error(t('无法将栏目移动到自己下面'));
        }

        // 无法将栏目移动到自己的子栏目下面
        $categoty = $this->get($id);

        if (in_array($parentid, explode(',', $categoty['childids'])))
        {
            return $this->error(t('无法将栏目移动到自己的子栏目下面'));
        }

        // 未移动栏目
        if ($parentid == $categoty['parentid'])
        {
            return $this->error(t('已经在该栏目下面了，请重新选择'));
        }

        if ( $this->update(array('parentid' => $parentid), $id) )
        {
            // 修复之前设置缓存中的父节点
            $this->category[$id]['parentid'] = $parentid;
            $this->repair();
            return true;
        }

        return false;
    }

    /**
     * 根据传入的 id 数组顺序进行排序
     *
     * @param array $ids
     * @return bool
     */
    public function order($ids)
    {
        foreach ((array )$ids as $i => $id)
        {
            $this->update(array('listorder' => $i + 1), $id);
        }

        $this->cache(true);
        return true;
    }

    /**
     * 根据ID自动判断并设置状态
     *
     * @param string $id ID
     * @return bool
     */
    public function status($id)
    {
        if ( $data = $this->get($id) )
        {
            $disabled = $data['disabled'] ? 0 : 1;

            $childids = explode(',', $data['childids']);

            // 操作当前节点及当前节点子节点
            $this->where('id', 'in', $childids)->update(array('disabled' => $disabled));

            // 如果启用，必须启用父节点
            if (!$disabled)
            {
                $parentids = explode(',', $data['parentids']);

                $this->where('id', 'in', $parentids)->update(array('disabled' => $disabled));
            }

            $this->cache(true);
            return true;
        }

        return false;
    }

    /*
     * 缓存数据和设置缓存
     *
     * @param string $refresh 强制刷新
     * @return string 缓存数据
     */
    public function cache($refresh=false)
    {
        $cache = zotop::cache('content.category');

        if ( $refresh or empty($cache) or !is_array($cache) )
        {
            $cache = $this->select();

            zotop::cache('content.category', $cache, false);
        }

        return $cache;
    }
}
?>